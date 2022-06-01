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
			'kategori' => 'kategori.id_kategori = menu.id_kategori',
		);
		$select = "menu.*, kategori.kategori";
		$data['menu'] = $this->Gm->joinTable('menu', $tables,$select);
		$this->load->view('template/header');
		$this->load->view('home', $data);
		$this->load->view('template/footer');
	}
	public function product()
	{
		$tables = array(
			'kategori' => 'kategori.id_kategori = menu.id_kategori',
		);
		$select = "menu.*, kategori.kategori";
		$data['menu'] = $this->Gm->joinTable('menu', $tables,$select);
		$data['kategori'] = $this->Gm->getAll('kategori');
		$this->load->view('template/header');
		$this->load->view('product', $data);
		$this->load->view('template/footer');
	}
}
