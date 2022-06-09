<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Belum Bayar</title>
  <link rel="stylesheet" href="<?php echo base_url('assets/vendor/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
  <link rel="stylesheet" href="<?php echo base_url('assets/vendor/adminlte/plugins/sweetalert2/sweetalert2.min.css') ?>">
  <link rel="stylesheet" href="<?php echo base_url('assets/vendor/adminlte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') ?>">
  <?php $this->load->view('partials/head'); ?>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <?php $this->load->view('includes/nav'); ?>

  <?php $this->load->view('includes/aside'); ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col">
            <h1 class="m-0 text-dark">Belum Bayar</h1>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="card">
          <div class="card-body">
            <table class="table w-100 table-bordered table-hover" id="laporan_stok_keluar">
              <thead>
                <tr>
                  <th>No Order</th>
                  <th>No Meja</th> 
                  <th>Atas Nama</th> 
                  <th>Tanggal</th> 
									<th>Sub Menu</th> 
                  <th>Action</th>
                </tr>
              </thead>
							<tbody>
								<?php foreach ($belum_bayar as $key => $value): ?>
									<tr>
										<td><?php echo $value->no_order ?></td>
										<td><?php echo $value->no_meja ?></td>
										<td><?php echo $value->nama ?></td>
										<td><?php echo $value->tgl_order ?></td>
										<td><?php echo $value->sub_total ?></td>
										<td>
											<a href="<?php echo site_url('transaksi/bayar/'.$value->no_order) ?>" data-toggle="modal" data-target="#modal" class="btn btn-success btn-sm bayar">Bayar</a>
										</td>
									</tr>
								<?php endforeach ?>
            </table>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

</div>

<div class="modal fade" id="modal">
<div class="modal-dialog">
<div class="modal-content">
  <div class="modal-header">
    <h5 class="modal-title">Bayar</h5>
    <button class="close" data-dismiss="modal">
      <span>&times;</span>
    </button>
  </div>
  <div class="modal-body">
    <form id="formbayar">
			<div class="form-group">
        <label>No Order</label>
        <input type="text" class="form-control" name="no_order" id="no_order" required>
      </div>
      <div class="form-group">
        <label>Tanggal</label>
        <input type="text" class="form-control" name="tanggal" id="tanggal" required>
      </div>
      <div class="form-group">
        <label>Atas Nama</label>
				<input placeholder="Atas Nama" type="text" id='atasnama' class="form-control" name="pelanggan">
      </div>
			<div class="form-group">
        <label>Bayar</label>
				<select name="bayar" id="pelanggans" onChange="kembalian()" class="form-control select2" required>
					<option value="">---</option>
					<option value="Belum Bayar">Belum Bayar</option>
					<option value="Bayar">Bayar</option>
				</select>
      </div>
      <div class="form-group">
        <label>Jumlah Uang</label>
        <input placeholder="Jumlah Uang" type="number" class="form-control" name="jumlah_uang" onkeyup="kembalian()" required>
      </div>
      <div class="form-group">
        <label>Diskon</label>
        <input placeholder="Diskon" type="number" class="form-control" onkeyup="kembalian()" name="diskon">
      </div>
			
			<div class="form-group">
        <label>Nomor Meja</label>
				<select name="nomeja" id="nomeja" class="form-control select2">
					<option value="01">01</option>
					<option value="02">02</option>
				</select>
      </div>
			
      <div class="form-group">
        <b>Total Bayar:</b> <span class="total_bayar"></span>
				<span id="total" style="font-size: 80px; line-height: 1;display:none" class="text-danger">0</span>
      </div>
      <div class="form-group">
        <b>Kembalian:</b> <span class="kembalian"></span>
      </div>
      <button id="add" class="btn btn-success" type="submit" disabled>Bayar</button>
      <button id="cetak" class="btn btn-success" type="submit" onclick="bayarCetak()" disabled>Bayar Dan Cetak</button>
      <button class="btn btn-danger" data-dismiss="modal">Close</button>
    </form>
  </div>
</div>
</div>
</div>
<!-- ./wrapper -->
<?php $this->load->view('includes/footer'); ?>
<?php $this->load->view('partials/footer'); ?>
<script src="<?php echo base_url('assets/vendor/adminlte/plugins/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?php echo base_url('assets/vendor/adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>"></script>
<script src="<?php echo base_url('assets/vendor/adminlte/plugins/jquery-validation/jquery.validate.min.js') ?>"></script>
<script src="<?php echo base_url('assets/vendor/adminlte/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<script>
  var readUrl = '<?php echo site_url('stok_keluar/read') ?>';
  var deleteUrl = '<?php echo site_url('transaksi/delete') ?>';
</script>
<script src="<?php echo base_url('assets/js/laporan_stok_keluar.min.js') ?>"></script>
<script>
  var produkGetNamaUrl = '<?php echo site_url('produk/get_nama') ?>';
  var produkGetStokUrl = '<?php echo site_url('produk/get_stok') ?>';
  var addUrl = '<?php echo site_url('transaksi/update') ?>';
  var getMenuUrl = '<?php echo site_url('produk/get_menu') ?>';
  var pelangganSearchUrl = '<?php echo site_url('pelanggan/search') ?>';
  var cetakUrl = '<?php echo site_url('transaksi/cetak/') ?>';
</script>
<script src="<?php echo base_url('assets/js/unminify/transaksi2.js') ?>"></script>
</body>
</html>
