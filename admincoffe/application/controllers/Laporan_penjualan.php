<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan_penjualan extends CI_Controller {

	public function index()
	{
		if ($this->session->userdata('status') !== 'login' ) {
			redirect('/');
		}
		$this->load->view('laporan_penjualan');
	}
	public function print(){
		if ($this->session->userdata('status') !== 'login' ) {
			redirect('/');
		}
		$this->load->view('printlaporan');
	}
	
}


/* End of file Laporan_penjualan.php */
/* Location: ./application/controllers/Laporan_penjualan.php */
