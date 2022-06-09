<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kategori_produk_model extends CI_Model {

	private $table = 'kategori';

	public function create($data)
	{
		return $this->db->insert($this->table, $data);
	}

	public function read()
	{
		return $this->db->get($this->table);
	}

	public function update($id, $data)
	{
		$this->db->where('id_kategori', $id);
		return $this->db->update($this->table, $data);
	}

	public function delete($id)
	{
		$this->db->db_debug = false;
		try {
			$this->db->where('id_kategori', $id);
			return $this->db->delete($this->table);
		} catch (Exception $e) {
			return false;
		}
	}

	public function getKategori($id)
	{
		$this->db->where('id_kategori', $id);
		return $this->db->get($this->table);
	}

	public function search($search="")
	{
		$this->db->like('kategori', $search);
		return $this->db->get($this->table)->result();
	}

}

/* End of file Kategori_produk_model.php */
/* Location: ./application/models/Kategori_produk_model.php */
