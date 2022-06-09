<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Belum_bayar extends CI_Controller {

	public function index()
	{
		if ($this->session->userdata('status') !== 'login' ) {
			redirect('/');
		}
		$data['belum_bayar'] = $this->db->query("SELECT * FROM pesanan WHERE status_order = 'Belum Bayar'")->result();
		$this->load->view('belum_bayar', $data);
	}

}

/* End of file Belum_bayar.php */
/* Location: ./application/controllers/Belum_bayar.php */
