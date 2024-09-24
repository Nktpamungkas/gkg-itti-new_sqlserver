<?php 
ini_set("error_reporting", 1);
session_start();
include_once 'koneksi.php';
include_once 'utils/helper.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Stoppage Mesin</title>

</head>
<body>

<form class="form-horizontal" action="" method="post" enctype="multipart/form-data" name="form1">
	<div class="box box-info">
		<div class="box-header with-border">
			<h3 class="box-title">Input Data Stoppage Machine</h3>
			<div class="box-tools pull-right">
				<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
			</div>
		</div>
		<div class="box-body">
			<div class="col-md-6">
                <?php if ($_SESSION['lvl_idGkg'] == 'ADMIN' or $_SESSION['lvl_idGkg'] == 'LEADER') { ?>
					<div class="form-group">
						<!-- <label for="operator" class="col-sm-3 control-label">Operator</label>
						<div class="col-sm-5">
							<input type="text" name="operator" id="operator" required class="form-control" placeholder="-Nama Operator-" style="text-transform: uppercase;">
						</div> -->
						<label for="operator" class="col-sm-3 control-label">Operator</label>
						<div class="col-sm-5">
							<select name="operator" class="form-control" id="operator" required>
								<option value="">-Pilih-</option>
								<?php
								$sqlNO = sqlsrv_query($con,"SELECT DISTINCT nama FROM db_ikg.tbl_user WHERE [status]='Aktif' ORDER BY nama ASC");
								while ($rNO = sqlsrv_fetch_array($sqlNO)) {
								?>
									<option value="<?php echo $rNO['nama']; ?>"><?php echo $rNO['nama']; ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
				<?php } else { ?>
					<div class="form-group">
						<label for="operator" class="col-sm-3 control-label">Operator</label>
						<div class="col-sm-5">
							<input name="operator" id="operator" class="form-control" value="<?php echo $_SESSION['usridGkg'] ?>" readonly>
						</div>
					</div>
				<?php } ?>
                <div class="form-group">
					<label for="g_shift" class="col-sm-3 control-label">Group Shift</label>
					<div class="col-sm-2">
						<select name="g_shift" class="form-control" required>
							<option value="">-Pilih-</option>
							<option value="A">A</option>
							<option value="B">B</option>
							<option value="C">C</option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label for="shift" class="col-sm-3 control-label">Waktu Shift</label>
					<div class="col-sm-2">
						<select name="shift" class="form-control" required>
							<option value="">-Pilih-</option>
							<option value="1">1</option>
							<option value="2">2</option>
							<option value="3">3</option>
						</select>
					</div>
				</div>
                <div class="form-group">
					<label for="jenis_mesin" class="col-sm-3 control-label">Jenis Mesin</label>
					<div class="col-sm-3">
						<select name="jenis_mesin" class="form-control" id="jenis_mesin" required>
							<option value="">-Pilih-</option>
							<?php
							$sqlJM = sqlsrv_query($con,"SELECT DISTINCT jenis_mesin FROM db_ikg.tbl_mesin_gkg ORDER BY jenis_mesin ASC");
							while ($rJM = sqlsrv_fetch_array($sqlJM)) {
							?>
								<option value="<?php echo $rJM['jenis_mesin']; ?>"><?php echo $rJM['jenis_mesin']; ?></option>
							<?php } ?>
						</select>
					</div>
				</div>
                <div class="form-group">
					<label for="nama_mesin" class="col-sm-3 control-label">Nama Mesin</label>
					<div class="col-sm-3">
						<select name="nama_mesin" class="form-control" id="nama_mesin" required>
							<option value="">-Pilih-</option>
							<?php
							$sqlNM = sqlsrv_query($con,"SELECT nama_mesin FROM db_ikg.tbl_mesin_gkg ORDER BY nama_mesin ASC");
							while ($rNM = sqlsrv_fetch_array($sqlNM)) {
							?>
								<option value="<?php echo $rNM['nama_mesin']; ?>"><?php echo $rNM['nama_mesin']; ?></option>
							<?php } ?>
						</select>
					</div>
				</div>
                <div class="form-group">
                    <label for="tgl_mulai" class="col-sm-3 control-label">Tgl Mulai Stop Mesin</label>
                    <div class="col-sm-4">
                        <div class="input-group date">
                            <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
                            <input name="tgl_mulai" type="text" class="form-control pull-right" id="datepicker" placeholder="Tanggal Mulai"  autocomplete="off" required/>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="bootstrap-timepicker">
                            <div class="input-group">
                                <input type="text" class="form-control jam_mulai" name="jam_mulai" placeholder="00:00" autocomplete="off" required>
                                <div class="input-group-addon">
                                    <i class="fa fa-clock-o"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="tgl_stop" class="col-sm-3 control-label">Tgl Selesai Stop Mesin</label>
                    <div class="col-sm-4">
                        <div class="input-group date">
                            <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
                            <input name="tgl_stop" type="text" class="form-control pull-right" id="datepicker1" placeholder="Tanggal Selesai"  autocomplete="off" required/>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="bootstrap-timepicker">
                            <div class="input-group">
                                <input type="text" class="form-control jam_mulai" name="jam_stop" placeholder="00:00" autocomplete="off" required>
                                <div class="input-group-addon">
                                    <i class="fa fa-clock-o"></i>
                                </div>
                            </div>
                        </div> 
                    </div>
                </div>
                <div class="form-group">
					<label for="kode_stop" class="col-sm-3 control-label">Kode Stop</label>
					<div class="col-sm-5">
						<select name="kode_stop" class="form-control" id="kode_stop" required>
							<option value="">-Pilih-</option>
							<?php
							$sqlKD = sqlsrv_query($con,"SELECT kode FROM db_ikg.tbl_stop_mesin ORDER BY id ASC");
							while ($rKD = sqlsrv_fetch_array($sqlKD)) {
							?>
								<option value="<?php echo $rKD['kode']; ?>"><?php echo $rKD['kode']; ?></option>
							<?php } ?>
						</select>
					</div>
				</div> 
            </div>
        </div>
        <div class="box-footer">
			<button type="submit" class="btn btn-primary pull-right" name="save" value="save">Simpan <i class="fa fa-save"></i></button>
        </div>
        <!-- /.box-footer -->
	</div>
</form>
</body>
</html>
<?php
if ($_POST['save'] == "save") {
    if(strlen($_POST['jam_mulai'])==5){
        $jamMulai = $_POST['jam_mulai'];
    }else{
        $jamMulai = '0'.$_POST['jam_mulai'];
    }
    if(strlen($_POST['jam_stop'])==5){
        $jamStop = $_POST['jam_stop'];
    }else{
        $jamStop = '0'.$_POST['jam_stop'];
    }
	$g_shift=cek_input('g_shift');
	$shift = cek_input('shift');
	$jenis_mesin = cek_input('jenis_mesin');
	$nama_mesin = cek_input('nama_mesin');
	$tgl_mulai = cek_input('tgl_mulai');
	$tgl_stop = cek_input('tgl_stop');
	$kode_stop = cek_input('kode_stop');
	$now=new DateTime();
    $operator = strtoupper($_POST['operator']);
	$data=[$g_shift,
	$shift,
$jenis_mesin,
$nama_mesin, 
$operator,   
$tgl_mulai,  
$tgl_stop,   
$jamMulai,  
$jamStop,   
$kode_stop,  
$now,   
$now ];
    $sqlData="INSERT INTO db_ikg.tbl_stoppage_mc (g_shift,shift,jenis_mesin,nama_mesin,operator,tgl_mulai,
	tgl_stop,jam_mulai,jam_stop,kode_stop,tgl_buat,tgl_update) VALUES(?,?,?,?,?,?,?,?,?,?,?,?)";
	$stmt = sqlsrv_prepare($con, $sqlData, $data);

	if ($stmt === false) {
		die(print_r(sqlsrv_errors(), true));
	}
	$result = sqlsrv_execute($stmt);

	if ($result === false) {
		die(print_r(sqlsrv_errors(), true));
	}
	echo "<script>swal({
    title: 'Data Tersimpan',   
    text: 'Klik Ok untuk input data kembali',
    type: 'success',
    }).then((result) => {
    if (result.value) {
    window.location.href='StoppageMC';

    }
    });</script>";
    
}
?>