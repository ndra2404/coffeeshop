<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaksi_model extends CI_Model {

	private $table = 'pesanan';

	public function create($data)
	{
		return $this->db->insert($this->table, $data);
	}
	public function update($data,$id)
	{
		$this->db->where('no_order', $id);
		return $this->db->update($this->table, $data);
	}

	public function read()
	{
		$this->db->select("*");
		$this->db->from($this->table);
		$this->db->join('pembayaran', 'pesanan.no_order = pembayaran.no_order');
		return $this->db->get();
	}

	public function delete($id)
	{
		$this->db->where('id', $id);
		return $this->db->delete($this->table);
	}

	public function getProduk($barcode)
	{
		// $qty="1,2";
		// $barcode=explode(',',"01,02");
		// $total = explode(',', $qty);
		$this->db->select('*');
		$this->db->from("detail_pesanan");
		$this->db->where('no_order', $barcode);
		$datadetail = $this->db->get()->result();
		foreach ($datadetail as $key => $value) {
			$this->db->select('nama_menu');
			$this->db->where('id_menu', $value->id_menu);
			$data[] = '<tr><td>'.$this->db->get('menu')->row()->nama_menu.' ('.$value->jumlah.')</td></tr>';
		}
		return join($data);
	}


	public function penjualanBulan($date)
	{
		$qty = $this->db->query("SELECT qty FROM transaksi WHERE DATE_FORMAT(tanggal, '%d %m %Y') = '$date'")->result();
		$d = [];
		$data = [];
		foreach ($qty as $key) {
			$d[] = explode(',', $key->qty);
		}
		foreach ($d as $key) {
			$data[] = array_sum($key);
		}
		return $data;
	}

	public function transaksiHari($hari)
	{
		return $this->db->query("SELECT COUNT(*) AS total FROM pesanan WHERE DATE_FORMAT(tgl_order, '%d %m %Y') = '$hari'")->row();
	}

	public function transaksiTerakhir($hari)
	{
		return $this->db->query("SELECT sum(grand_total)qty FROM pembayaran WHERE DATE_FORMAT(tgl_bayar, '%Y%m') = '$hari' LIMIT 1")->row();
	}

	public function getAll($id)
	{
		$this->db->select('*');
		$this->db->from('pesanan');
		$this->db->join('pembayaran', 'pesanan.no_order = pembayaran.no_order', 'left');
		$this->db->where('pesanan.no_order', $id);
		return $this->db->get()->row();
	}
	public function getDetail($id)
	{
		$this->db->select('*');
		$this->db->from('detail_pesanan');
		$this->db->join('menu', 'detail_pesanan.id_menu = menu.id_menu', 'left');
		$this->db->where('detail_pesanan.no_order', $id);
		return $this->db->get()->result();
	}

	public function getName($barcode)
	{
		foreach ($barcode as $b) {
			$this->db->select('nama_produk, harga');
			$this->db->where('id', $b);
			$data[] = $this->db->get('produk')->row();
		}
		return $data;
	}
	public function savedetail($tablesave,$data)
	{
		return $this->db->insert($tablesave, $data);
	}
	public function lastNumber()
	{
		$result = $this->db->select('max(RIGHT(no_order,3)) as max_id', false)->from($this->table)->get();; 
		if($result->num_rows() <> 0) {
			$data = $result->row();
			$kode = intval($data->max_id) + 1;
			return 'ORD'.date('d').str_pad($kode,3, 0,STR_PAD_LEFT);
		}else{
			return 'ORD'.date('d').'001';
		}
	}
	public function lastPembayaran()
	{
		$result = $this->db->select('max(RIGHT(no_pembayaran ,3)) as max_id', false)->from('pembayaran')->get();; 
		if($result->num_rows() <> 0) {
			$data = $result->row();
			$kode = intval($data->max_id) + 1;
			return 'BYR'.date('d').str_pad($kode,3, 0,STR_PAD_LEFT);
		}else{
			return 'BYR'.date('d').'001';
		}
	}

}

/* End of file Transaksi_model.php */
/* Location: ./application/models/Transaksi_model.php */
