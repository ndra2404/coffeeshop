<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {
	public function __construct()
    {
        parent::__construct();
        $this->load->model("General_model",'Gm');
    }
	public function index()
	{
		$tables = array(
			'product_type' => 'product_type.id = product.product_type_id',
		);
		$select = "product.*, product_type.product_type as product_type_name";
		$data['products'] = $this->Gm->joinTable('product', $tables,$select);
		$this->load->view('template/header');
		$this->load->view('home', $data);
		$this->load->view('template/footer');
	}
	public function product()
	{
		$data ="";
		$this->load->view('template/header');
		$this->load->view('product', $data);
		$this->load->view('template/footer');
	}
}
