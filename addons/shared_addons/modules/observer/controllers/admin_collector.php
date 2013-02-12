<?php defined('BASEPATH') or exit('No direct script access allowed');

class Admin_Collector extends Admin_Controller
{
	protected $section = 'collector';
 
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$this->load->helper('phpQuery');
		$selectors = $this->db->select()->get('observer_selectors')->result_array();
		$date =  date("Y-m-d H:i:s");

		foreach ($selectors as $selector) {
		
			$dataRow = $this->db->select()
				->where('observer_data.observer_products_id =', $selector['observer_products_id'])
				->where('observer_data.observer_merchants_id =', $selector['observer_merchants_id'])
				// ->where( 'observer_data.created < ', $date." 23:59:59" ) 
				->get('observer_data')->row();

			$content = file_get_contents($selector['url']);

			if (strpos($content, '</html>')) {
				$html = $content;
			} else {
				$html = '<html><head></head><body>'.$content.'</body></html>';
			}

			phpQuery::newDocumentHTML($html, $charset = 'utf-8'); 
			$text = pq($selector['selector'])->text();
			$price = preg_replace('[\D]', '', $text);

			if (is_null($dataRow) || $dataRow->price != $price) {
	 			$this->db->insert('observer_data', array(
	 				'observer_products_id' => $selector['observer_products_id'], 
	 				'observer_merchants_id' => $selector['observer_merchants_id'], 
	 				'price' => $price, 
	 				'created' => $date
 				)); 
			} 
		}
		 
		/*
		$url = 'http://golderado.hu/#tortarany-felvasarlas';
		$html = file_get_contents($url);

		phpQuery::newDocumentHTML($html, $charset = 'utf-8');
		// 14k tört arany 
		echo pq('.price_table:eq(1) tr:eq(2) td:eq(2)')->text();
		*/
	}
}