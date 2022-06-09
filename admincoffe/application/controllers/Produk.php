<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Produk extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if ($this->session->userdata('status') !== 'login' ) {
			redirect('/');
		}
		$this->load->model('produk_model');
		$this->load->model('kategori_produk_model');
	}

	public function index()
	{
		$search = $this->kategori_produk_model->search("");
		$data['kategori'] = $search;
		$this->load->view('produk',$data);
	}

	public function read()
	{
		header('Content-type: application/json');
		if ($this->produk_model->read()->num_rows() > 0) {
			foreach ($this->produk_model->read()->result() as $produk) {
				$data[] = array(
					'nama' => $produk->nama_menu,
					'kategori' => $produk->kategori,
					'harga' => $produk->harga,
					'status' => $produk->status_menu,
					'image' => '<img src="../assets/images/menu/'.$produk->foto.'" width="80px" height="80px">',
					'action' => '<button class="btn btn-sm btn-success" onclick="edit(\''.$produk->id_menu.'\')">Edit</button> <button class="btn btn-sm btn-danger" onclick="remove(\''.$produk->id_menu.'\')">Delete</button>'
				);
			}
		} else {
			$data = array();
		}
		$produk = array(
			'data' => $data
		);
		echo json_encode($produk);
	}

	public function add()
	{
		$config['upload_path']="../assets/images/menu";
		$config["allowed_types"] ="*";
        $config['encrypt_name'] = TRUE;
		$image="";
        $this->load->library('upload',$config);
        if($this->upload->do_upload("file")){
			$image=$this->upload->data('file_name');
			$lastcode = $this->produk_model->lastNumber();
			$data = array(
				'id_menu' => $lastcode,
				'nama_menu' => $this->input->post('nama'),
				'id_kategori' => $this->input->post('kategori'),
				'harga' => $this->input->post('harga'),
				'foto' => $image,
				'status_menu' => $this->input->post('status')
			);
			if ($this->produk_model->create($data)) {
				echo json_encode($data);
			}
		}else{
			$msg = $this->upload->display_errors();
		}
		
	}

	public function delete()
	{
		$id = $this->input->post('id');
		if ($this->produk_model->delete($id)) {
			echo json_encode('sukses');
		}
	}

	public function edit()
	{
		$id = $this->input->post('id');
		$config['upload_path']="../assets/images/menu";
		$config["allowed_types"] ="*";
        $config['encrypt_name'] = TRUE;
		$image="";
        $this->load->library('upload',$config);
        if($this->upload->do_upload("file")){
			$image=$this->upload->data('file_name');
		}
		if($image==""){
			$data = array(
				'nama_menu' => $this->input->post('nama'),
				'id_kategori' => $this->input->post('kategori'),
				'harga' => $this->input->post('harga'),
				'status_menu' => $this->input->post('status')
			);
		}else{
			$data = array(
				'nama_menu' => $this->input->post('nama'),
				'id_kategori' => $this->input->post('kategori'),
				'harga' => $this->input->post('harga'),
				'foto' => $image,
				'status_menu' => $this->input->post('status')
			);
		}
		if ($this->produk_model->update($id,$data)) {
			echo json_encode('sukses');
		}
	}

	public function get_produk()
	{
		header('Content-type: application/json');
		$id = $this->input->post('id');
		$kategori = $this->produk_model->getProduk($id);
		if ($kategori->row()) {
			echo json_encode($kategori->row());
		}
	}

	public function get_menu()
	{
		header('Content-type: application/json');
		$menu = $this->input->post('menu');
		$search = $this->produk_model->getMenu($menu);
		foreach ($search as $row) {
			$data[] = array(
				'id' => $row->id_menu,
				'text' => $row->nama_menu
			);
		}
		echo json_encode($data);
	}

	public function get_nama()
	{
		header('Content-type: application/json');
		$id = $this->input->post('id');
		echo json_encode($this->produk_model->getNama($id));
	}

	public function get_stok()
	{
		header('Content-type: application/json');
		$id = $this->input->post('id');
		echo json_encode($this->produk_model->getStok($id));
	}

	public function produk_terlaris()
	{
		header('Content-type: application/json');
		$produk = $this->produk_model->produkTerlaris();
		foreach ($produk as $key) {
			$label[] = $key->nama_produk;
			$data[] = $key->terjual;
		}
		$result = array(
			'label' => $label,
			'data' => $data,
		);
		echo json_encode($result);
	}

	public function data_stok()
	{
		header('Content-type: application/json');
		$produk = $this->produk_model->dataStok();
		echo json_encode($produk);
	}

}

/* End of file Produk.php */
/* Location: ./application/controllers/Produk.php */
