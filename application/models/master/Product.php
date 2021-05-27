<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Product extends MY_Model {

	public $tableName = 'product';
	public $datatable_columns = ['id', 'product_id', 'product_name','product_prodi_name','product_prodi_no'];
	public $datatable_search = ['id', 'product_id', 'product_name','product_prodi_name','product_prodi_no'];
    public $blameable = true;
    public $timestamps = true;
    public $soft_delete = false;


     public function getListProductByUnit($products = [], $dropdown = false)
	{
		if (empty($products)) {
			$list_product = ['' => '- Pilih Jurusan -'];
		}

		if ($dropdown) {
			$list_product = ['' => '- Pilih Jurusan -'];
		} else {
			$list_product = [];
		}

		foreach ($products as $key => $product) {
			$list_product[$product->product_id] = $product->product_name;
		}

		return $list_product;
	}



}