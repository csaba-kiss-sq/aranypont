<?php defined('BASEPATH') or exit('No direct script access allowed');

class Module_Observer extends Module
{
	public $version = '1.0.0a';

	public function info()
	{
		$info = array(
			'name' => array(
				'en' => 'Árfolyamok',
				'hu' => 'Árfolyamok',
			),
			'description' => array(
				'en' => 'Árofolyamok figyelése, beállítása.',
				'hu' => 'Árofolyamok figyelése, beállítása.',
			),
			'frontend' => false,
			'backend' => true,
			'menu' => 'obsever',

			'sections' => array(
				'observer' => array(
					'name' => 'observer:title',
					'uri' => 'admin/observer',
				),
				'merchants' => array(
					'name' => 'Kereskedők',
					'uri' => 'admin/observer/merchants',
					'shortcuts' => array(
						array(
							'name' => 'observer:create_title',
							'uri' => 'admin/observer/merchants/create',
							'class' => 'add',
						),
					),
				),
				'product' => array(
					'name' => 'Termékek',
					'uri' => 'admin/observer/product/',
					'shortcuts' => array(
						array(
							'name' => 'Létrehozás',
							'uri' => 'admin/observer/product/create'
						)
					)
				),
				'categories' => array(
					'name' => 'Kategóriák',
					'uri' => 'admin/observer/categories/',
					'shortcuts' => array(
						array(
							'name' => 'Létrehozás',
							'uri' => 'admin/observer/categories/create'
						)
					)
				),
				'selectros' => array(
					'name' => 'Selectros (devel)',
					'uri' => 'admin/observer/selectros/',
					'shortcuts' => array(
						array(
							'name' => 'Létrehozás',
							'uri' => 'admin/observer/selectros/create'
						)
					)
				),
				'collector' => array(
					'name' => 'Collector (devel)',
					'uri'  => 'admin/observer/collector' 
				),
			),
		);

		return $info;
	}

	public function install()
	{
		$this->dbforge->drop_table('observer_categories');
		$this->dbforge->drop_table('observer_data');
		$this->dbforge->drop_table('observer_merchants');
		$this->dbforge->drop_table('observer_products');
		$this->dbforge->drop_table('observer_selectors');

		$this->install_tables(array(
			'observer_categories' => array(
				'id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true, 'primary' => true), 
				'title' => array('type' => 'VARCHAR', 'constraint' => 255, 'null' => false, 'unique' => true),
			),
			'observer_data' => array(
				'id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true, 'primary' => true),
				'observer_products_id' => array('type' => 'INT', 'constraint' => 11, 'null' => false, 'key' => true),
				'observer_merchants_id' => array('type' => 'INT', 'constraint' => 11, 'null' => false, 'key' => true),
				'price' => array('type' => 'INT', 'constraint' => 11, 'null' => false, 'key' => true),
				'created' => array('type' => 'TIMESTAMP', 'null' => false)
			),
			'observer_merchants' => array(
				'id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true, 'primary' => true),
				'title' => array('type' => 'VARCHAR', 'constraint' => 255, 'null' => false, 'unique' => true),
				'website' => array('type' => 'VARCHAR', 'constraint' => 255, 'null' => false),
				'description' => array('type' => 'TEXT', 'null' => true),
				'map' => array('type' => 'VARCHAR', 'constraint' => 255, 'null' => false),
			),
			'observer_products' => array(
				'id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true, 'primary' => true),
				'title' => array('type' => 'VARCHAR', 'constraint' => 100, 'null' => false, 'unique' => true),
				'observer_categories_id' => array('type' => 'INT', 'constraint' => 11, 'null' => false, 'key' => true),
			),
			'observer_selectors' => array(
				'id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true, 'primary' => true),
				'url' => array('type' => 'VARCHAR', 'constraint' => 255, 'null' => false),
				'selector' => array('type' => 'TEXT', 'null' => true),
				'observer_products_id' => array('type' => 'INT', 'constraint' => 11, 'null' => false, 'key' => true),
				'observer_merchants_id' => array('type' => 'INT', 'constraint' => 11, 'null' => false, 'key' => true),
			),
		));
		$this->db->insert('observer_categories', array('id' => 1, 'title' => 'törtarany'));

		$this->db->insert('observer_merchants', array('id' => 1, 'title' => 'Aranypont0', 'website' => 'localhost0', 'description' => 'Saját árak és termékek', 'map' => '' ));
		$this->db->insert('observer_merchants', array('id' => 2, 'title' => 'Aranypont2', 'website' => 'localhost1', 'description' => 'Saját árak és termékek', 'map' => '' ));
		$this->db->insert('observer_merchants', array('id' => 3, 'title' => 'Aranypont3', 'website' => 'localhost2', 'description' => 'Saját árak és termékek', 'map' => '' ));
		$this->db->insert('observer_merchants', array('id' => 4, 'title' => 'Aranypont4', 'website' => 'localhost4', 'description' => 'Saját árak és termékek', 'map' => '' ));
		$this->db->insert('observer_merchants', array('id' => 5, 'title' => 'Aranypont5', 'website' => 'localhost5', 'description' => 'Saját árak és termékek', 'map' => '' ));
		
		$this->db->insert('observer_products', array('id' => 1, 'title' => '8kt törtarany', 'observer_categories_id' => 1));
		$this->db->insert('observer_products', array('id' => 2, 'title' => '9kt törtarany', 'observer_categories_id' => 1));
		$this->db->insert('observer_products', array('id' => 3, 'title' => '14kt törtarany', 'observer_categories_id' => 1));
		$this->db->insert('observer_products', array('id' => 4, 'title' => '18kt törtarany', 'observer_categories_id' => 1));
		$this->db->insert('observer_products', array('id' => 5, 'title' => '24kt törtarany', 'observer_categories_id' => 1));
		
		$this->db->insert('observer_data', array('id' => 1, 'observer_products_id' => 1, 'observer_merchants_id' => 1, 'price' => 6000, 'created' => 0)); 
		$this->db->insert('observer_data', array('id' => 2, 'observer_products_id' => 2, 'observer_merchants_id' => 1, 'price' => 6500, 'created' => 0)); 
		$this->db->insert('observer_data', array('id' => 3, 'observer_products_id' => 3, 'observer_merchants_id' => 1, 'price' => 7200, 'created' => 0)); 
		$this->db->insert('observer_data', array('id' => 4, 'observer_products_id' => 4, 'observer_merchants_id' => 1, 'price' => 7500, 'created' => 0)); 
		$this->db->insert('observer_data', array('id' => 5, 'observer_products_id' => 5, 'observer_merchants_id' => 1, 'price' => 8000, 'created' => 0)); 

		return true;
	}

	public function uninstall()
	{
		$this->dbforge->drop_table('observer_categories');
		$this->dbforge->drop_table('observer_data');
		$this->dbforge->drop_table('observer_merchants');
		$this->dbforge->drop_table('observer_products');
		$this->dbforge->drop_table('observer_selectors');

		return true;
	}

	public function upgrade($old_version)
	{
		return true;
	}
}
