<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaksi extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if ($this->session->userdata('status') !== 'login' ) {
			redirect('/');
		}
		$this->load->model('transaksi_model');
	}

	public function index()
	{
		$this->load->view('transaksi');
	}

	public function read()
	{
		// header('Content-type: application/json');
		if ($this->transaksi_model->read()->num_rows() > 0) {
			foreach ($this->transaksi_model->read()->result() as $transaksi) {
				//$barcode = explode(',', $transaksi->barcode);
				$tanggal = new DateTime($transaksi->tgl_order);
				$data[] = array(
					'tanggal' => $tanggal->format('d-m-Y H:i:s'),
					'nama_produk' => '<table>'.$this->transaksi_model->getProduk($transaksi->no_order).'</table>',
					'total_bayar' => $transaksi->grand_total,
					'jumlah_uang' =>  $transaksi->grand_total+ $transaksi->kembalian,
					'diskon' => $transaksi->discount,
					'pelanggan' => $transaksi->nama,
					'action' => '<a class="btn btn-sm btn-success" href="'.site_url('transaksi/cetak/').$transaksi->no_order.'">Print</a> <button class="btn btn-sm btn-danger" onclick="remove('.$transaksi->no_order.')">Delete</button>'
				);
			}
		} else {
			$data = array();
		}
		$transaksi = array(
			'data' => $data
		);
		echo json_encode($transaksi);
	}

	public function add()
	{
		$nopesanan = $this->transaksi_model->lastNumber();
		$nopembayaran = $this->transaksi_model->lastPembayaran();
		$produk = json_decode($this->input->post('produk'));
		$tanggal = new DateTime($this->input->post('tanggal'));
		$barcode = array();
		$data = array(
			'tgl_order' => $tanggal->format('Y-m-d H:i:s'),
			'no_order' =>$nopesanan,
			//'qty' => implode(',', $this->input->post('qty')),
			'sub_total' => $this->input->post('total_bayar'),
			'nama' => $this->input->post('pelanggan'),
			'nota' => $this->input->post('nota'),
			'status_order' =>  $this->input->post('status'),
			'no_meja'=> '01',
			'id_kasir' => $this->session->userdata('id')
		);
		
		if ($this->transaksi_model->create($data)) {
			
			foreach ($produk as $key=>$produk) {
				$datadetail = array(
					'no_order' => $nopesanan,
					'id_menu ' => $produk->id,
					'jumlah' => $this->input->post('qty')[$key],
					'price' => $produk->harga,
					'total' => $produk->harga*$this->input->post('qty')[$key],
					'status' =>1
				);
				$this->transaksi_model->savedetail("detail_pesanan",$datadetail);
			}

			if($this->input->post('status')=="Bayar"){
				$datapembayaran = array(
					'no_pembayaran' => $nopembayaran,
					'no_order' => $nopesanan,
					'tgl_bayar' => $tanggal->format('Y-m-d H:i:s'),
					'grand_total' => $this->input->post('total_bayar')-($this->input->post('diskon')?$this->input->post('diskon'):0),
					'discount' => $this->input->post('diskon'),
					'kembalian' => $this->input->post('jumlah_uang')-$this->input->post('total_bayar'),
				);
				$this->transaksi_model->savedetail("pembayaran",$datapembayaran);
			}
			
			echo json_encode($nopesanan);
		}
		$data = $this->input->post('form');
	}

	public function delete()
	{
		$id = $this->input->post('id');
		if ($this->transaksi_model->delete($id)) {
			echo json_encode('sukses');
		}
	}
	public function update($nopesanan)
	{
		$nopembayaran = $this->transaksi_model->lastPembayaran();
		$tanggal = new DateTime($this->input->post('tanggal'));
		$data = array(
			'status_order' => $this->input->post('status')
		);
		$this->transaksi_model->update($data,$nopesanan);
		$datapembayaran = array(
			'no_pembayaran' => $nopembayaran,
			'no_order' => $nopesanan,
			'tgl_bayar' => $tanggal->format('Y-m-d H:i:s'),
			'grand_total' => $this->input->post('total_bayar')-($this->input->post('diskon')?$this->input->post('diskon'):0),
			'discount' => $this->input->post('diskon'),
			'kembalian' => $this->input->post('jumlah_uang')-$this->input->post('total_bayar'),
		);
		$this->transaksi_model->savedetail("pembayaran",$datapembayaran);
		echo json_encode($nopesanan);
	}

	public function cetak($id)
	{
		$produk = $this->transaksi_model->getAll($id);

		$detail = $this->transaksi_model->getDetail($id);
		
		$tanggal = new DateTime($produk->tgl_order);
		//$barcode = explode(',', $produk->barcode);
		//$qty = explode(',', $produk->qty);

		//$produk->tanggal = $tanggal->format('d m Y H:i:s');

		// $dataProduk = $this->transaksi_model->getName($barcode);
		// foreach ($dataProduk as $key => $value) {
		// 	$value->total = $qty[$key];
		// 	$value->harga = $value->harga * $qty[$key];
		// }

		$data = array(
			'nota' => $produk->no_order,
			'no_bayar' => $produk->no_pembayaran,
			'tanggal' => $tanggal->format('d-m-Y H:i:s'),
			'produk' => $detail,
			'total' => $produk->sub_total,
			'bayar' => $produk->grand_total+$produk->kembalian,
			'kembalian' =>  $produk->kembalian,
			'kasir' => 0
		);
		$this->load->view('cetak', $data);
	}

	public function penjualan_bulan()
	{
		header('Content-type: application/json');
		$day = $this->input->post('day');
		foreach ($day as $key => $value) {
			$now = date($day[$value].' m Y');
			if ($qty = $this->transaksi_model->penjualanBulan($now) !== []) {
				$data[] = array_sum($this->transaksi_model->penjualanBulan($now));
			} else {
				$data[] = 0;
			}
		}
		echo json_encode($data);
	}

	public function transaksi_hari()
	{
		header('Content-type: application/json');
		$now = date('d m Y');
		$total = $this->transaksi_model->transaksiHari($now);
		echo json_encode($total);
	}

	public function transaksi_terakhir($value='')
	{
		header('Content-type: application/json');
		$now = date('Ym');
		foreach ($this->transaksi_model->transaksiTerakhir($now) as $key) {
			$total = explode(',', $key);
		}
		echo json_encode($total);
	}
}

/* End of file Transaksi.php */
/* Location: ./application/controllers/Transaksi.php */
