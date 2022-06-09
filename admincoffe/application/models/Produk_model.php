<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Produk_model extends CI_Model {

	private $table = 'menu';

	public function create($data)
	{
		return $this->db->insert($this->table, $data);
	}

	public function read()
	{
		$this->db->select('*');
		$this->db->from($this->table);
		$this->db->join('kategori', 'menu.id_kategori = kategori.id_kategori');
		return $this->db->get();
	}

	public function update($id, $data)
	{
		$this->db->where('id_menu', $id);
		return $this->db->update($this->table, $data);
	}

	public function delete($id)
	{
		$this->db->where('id_menu', $id);
		return $this->db->delete($this->table);
	}

	public function getProduk($id)
	{
		$this->db->select('*');
		$this->db->from($this->table);
		$this->db->join('kategori', 'menu.id_kategori = kategori.id_kategori');
		$this->db->where('menu.id_menu', $id);
		 return $this->db->get();
	}

	public function getMenu($search='')
	{
		$this->db->select('id_menu, nama_menu');
		$this->db->like('nama_menu', $search);
		return $this->db->get($this->table)->result();
	}

	public function getNama($id)
	{
		$this->db->select('nama_menu, status_menu');
		$this->db->where('id_menu', $id);
		return $this->db->get($this->table)->row();
	}

	public function getStok($id)
	{
		$this->db->select('10 as stok, nama_menu , harga, id_menu barcode');
		$this->db->where('id_menu', $id);
		return $this->db->get($this->table)->row();
	}

	public function produkTerlaris()
	{
		return $this->db->query('SELECT produk.nama_produk, produk.terjual FROM `produk` 
		ORDER BY CONVERT(terjual,decimal)  DESC LIMIT 5')->result();
	}

	public function lastNumber()
	{
		$result = $this->db->select('max(id_menu) as max_id', false)->from($this->table)->get();; 
		if($result->num_rows() <> 0) {
			$data = $result->row();
			$kode = intval($data->max_id) + 1;
			return str_pad($kode,2, 0,STR_PAD_LEFT);
		}else{
			return '01';
		}
		// $cc = $this->db->count_all('job_card');
		// $coun = str_pad($cc,4,STR_PAD_LEFT);
		// $id = "JI"."-";
		// $d = date('y') ;
		// $mnth = date("m");
		// $customid = $id.$d.$mnth.$coun;
		//return $this->db->query('SELECT produk.nama_produk, produk.stok FROM `produk` ORDER BY CONVERT(stok, decimal) DESC LIMIT 50')->result();
	}

}

/* End of file Produk_model.php */
/* Location: ./application/models/Produk_model.php */
