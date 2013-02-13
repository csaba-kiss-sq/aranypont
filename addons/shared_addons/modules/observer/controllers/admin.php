<?php defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends Admin_Controller
{
	protected $section = 'observer';

	public function __construct()
	{
		parent::__construct();

		$this->load->model(array('observer_data_m'));
		$this->load->model(array('observer_categories_m'));
		$this->load->model(array('observer_merchants_m'));
		$this->load->model(array('observer_products_m'));
		$this->lang->load(array('observer','merchants'));
	}

	public function charts()
	{
		/*
		$products = $this->observer_products_m->get_dropdown();

		$lines = array();

		foreach($products as $product_id => $product_title) 
		{
			$pricesArray = array();

			$data = $this->db->select()
				->where('observer_data.observer_products_id = ', $product_id)
				->where('observer_data.observer_merchants_id = ', 4)
				->order_by('created', 'DESC')
				->get('observer_data')->result_array();

			$data = array_reverse($data);

			foreach ($data as $key => $value) 
			{
				$pricesArray[] = $value['price'];
			}
			
			$prices = implode(', ', $pricesArray);
			$lines[] = '{name: \''.$product_title.'\', data: ['.$prices.']}';
		}
		*/

		$merchants = $this->observer_merchants_m->get_dropdown();

		$lines = array();

		foreach($merchants as $merchant_id => $merchant_title) 
		{
			$pricesArray = array();

			$data = $this->db->select()
				->where('observer_data.observer_products_id = ', 4)
				->where('observer_data.observer_merchants_id = ', $merchant_id)
				->order_by('created', 'DESC')
				->get('observer_data')->result_array();

			$data = array_reverse($data);

			foreach ($data as $key => $value) 
			{
				$pricesArray[] = $value['price'];
			}
			
			$prices = implode(', ', $pricesArray);
			$lines[] = '{name: \''.$merchant_title.'\', data: ['.$prices.']}';
		}

 
		$json = 'series = ['. implode(', ', $lines).'];';

		$series = array(
			'series' => json_encode(array(
				'name' => 'Termék 1',
				'data' => $pricesArray,
			)),
			'json' => $json,
			'categories' => $this->observer_categories_m->get_dropdown(),
			'merchants'  => $this->observer_merchants_m->get_dropdown(),
			'products'   => $this->observer_products_m->get_dropdown(),
		);

		$this->template
			->enable_parser(true)
			->title($this->module_details['name'])
			->append_js('module::highcharts.js')
			->build('admin/charts', $series);
	}

	public function grid($date) 
	{
		$grid = $this->get_data_grid($date);
		$this->template
			->title($this->module_details['name'])
			->build('admin/index', $grid);
	}

	public function index()
	{
		$grid = $this->get_data_grid(date("Y-m-d"));
		$this->template
			->title($this->module_details['name'])
			->build('admin/index', $grid);
	}

	private function get_data_grid($date)
	{
		$categories = $this->db->select()->order_by('id', 'ASC')->get('observer_categories')->result_array();

		foreach ($categories as $category) {
			$result[$category['id']] = array(
				'title' => $category['title']
			);
		}

		$products = $this->db->select()->order_by('id', 'ASC')->get('observer_products')->result_array();

		foreach ($products as $product) {
			$result[$product['observer_categories_id']]['products'][$product['id']] = $product;
			$data = $this->db->select()
				->where('observer_data.created < ', $date." 23:59:59" )
				->where('observer_data.observer_products_id =', $product['id'])
				->group_by(array('observer_merchants_id','observer_products_id'))
				->order_by('created', 'DESC')
				->get('observer_data')->result_array();
			foreach ($data as $key => $value) {
				$result[$product['observer_categories_id']]['data'][$value['observer_merchants_id']][$value['observer_products_id']]  = $value['price']; 
			}	
		}

		foreach ($categories as $category) {
			ksort($result[$category['id']]['data']);
		}

		$merchants = array();
		$tmp = $this->db->select()->order_by('id', 'ASC')->get('observer_merchants')->result_array();
		foreach ($tmp as $key => $value) {
			$merchants[$value['id']] = $value['title'];
		}
		unset($tmp);

		return array(
			'date'      => $date,
			'grid'      => $result,
			'merchants' => $merchants
		);
	}

	public function merchant() 
	{
		$this->input->is_ajax_request() and $this->template->set_layout(false);

		
		/*	foreach ($data as $key => $value) {
				$prices[$value['observer_products_id']] = $value['price'];
			}
		*/

		/*
		chartOptions.series = [{
            name: 'Termék 1',
            data: [2.0, 3.9, 9.5, 14.5, 18.2, 21.5, 25.2, 26.5, 23.3, 18.3, 13.9, 9.6]
        }, {
            name: 'Termék 2',
            data: [-0.2, 0.8, 5.7, 11.3, 17.0, 22.0, 24.8, 24.1, 20.1, 14.1, 8.6, 2.5]
        }, {
            name: 'Termék 3',
            data: [-0.9, 0.6, 3.5, 8.4, 13.5, 17.0, 18.6, 17.9, 14.3, 9.0, 3.9, 1.0]
        }, {
            name: 'Termék 4',
            data: [3.9, 4.2, 5.7, 8.5, 11.9, 15.2, 17.0, 16.6, 14.2, 10.3, 6.6, 4.8]
        }];
        */

		$json = "series = [{name: 'Termék 1', data: [".$prices."]}];";
    


		$series = array(
			'series' => json_encode(array(
				'name' => 'Termék 1',
				'data' => $pricesArray
			)),
			'json' => $json
		);

		$this->template
			->append_js('module::highcharts.js')
			->build('admin/merchant', $series);
	}

	public function product()
	{
		$this->template
			->title($this->module_details['name']);
	
		$this->template->build('admin/product');	
	}
}