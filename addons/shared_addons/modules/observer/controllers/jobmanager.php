<?php
class Jobmanager extends Public_Controller
{
	private $config = array();

	public function __construct()
	{
		parent::__construct();
		$this->load->model('observer_data_m');
		$this->load_config();
	}

	private function get_jobs() 
	{
		return $this->config['jobs'];
	}

	private function load_config() 
	{
		$this->config = array( 
			'jobs' => array(
				'observer' => array(
					'method' => 'observer',
					'data' => array(
						'merchants' => array(
							'2' => array('title' => 'Kereskedő 1'),
							'3' => array('title' => 'Kereskedő 2'),
							'4' => array('title' => 'Kereskedő 3'),
							'5' => array('title' => 'Kereskedő 4'),
							'6' => array('title' => 'Kereskedő 5')
						), 
						'products' => array(
							'1' => array('title' => 'Termék 1'), 
							'2' => array('title' => 'Termék 2')
						)
					)
				)
			) 
		);		
	}

	public function observer($data)
	{
		$time = date('Y-m-d H:i:s');
		foreach ($data['merchants'] as $merchant_id => $merchant) {
			foreach ($data['products'] as $product_id => $product) {
				$price = ceil(rand());
				$this->insert_price($product_id, $merchant_id, $price, $time);
			}
		}
	}

	private function insert_price($product_id, $merchant_id, $price, $time) 
	{
		$result = $this->db->insert('observer_data', array(
			'observer_products_id'  => $product_id, 
			'observer_merchants_id' => $merchant_id, 
			'price'                 => $price, 
			'created'               => $time
		)); 

		echo 'új sor beszúrva... <br />';

		return $result;
	}

	/**
	 * @todo foreach cikllusban tesztelni, hogy a függvény létezik-e mielőtt futtatjuk
	 */
	public function run()
	{
		foreach ($this->get_jobs() as $job) {
			$this->$job['method']($job['data']);
		}
	}
}
