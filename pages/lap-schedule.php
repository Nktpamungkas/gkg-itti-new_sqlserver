<?PHP
ini_set("error_reporting", 1);
session_start();
include "koneksi.php";
include("../utils/helper.php");
?>

<!DOCTYPE html
	PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title></title>
</head>
<style>
	#TableTodayReport td.details-control {
		background: url('bower_components/datatables.net/img/details_open.png') no-repeat center center;
		cursor: pointer;
	}

	#TableTodayReport tr.shown td.details-control {
		background: url('bower_components/datatables.net/img/details_close.png') no-repeat center center;
	}

	#TableTodayReport tr:hover {
		background-color: rgb(151, 170, 212);
	}
</style>

<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
	session_start();
}

// Check user level
if ($_SESSION['lvl_idGkg'] == "LEADER" || $_SESSION['lvl_idGkg'] == "ADMIN") {
	$sql = "SELECT 
        a.id, 
        a.no_mesin, 
        a.no_urut, 
        a.buyer, 
        a.langganan, 
        a.no_order, 
        a.nokk, 
        a.jenis_kain,
        a.warna, 
        a.no_warna, 
        SUM(a.rol) AS rol, 
        SUM(a.bruto) AS bruto, 
        a.proses, 
        a.dept_tujuan, 
        a.pic_schedule, 
        a.status, 
        a.lot, 
        a.catatan, 
        a.ket_status, 
        a.tgl_delivery, 
        b.no_gerobak1, 
        b.tgl_out1, 
        b.no_gerobak2, 
        b.tgl_out2, 
        b.no_gerobak3, 
        b.tgl_out3, 
        b.no_gerobak4, 
        b.tgl_out4, 
        b.no_gerobak5, 
        b.tgl_out5, 
        b.no_gerobak6, 
        b.tgl_out6, 
        a.petugas_obras,
        a.petugas_buka, 
        a.approve_by, 
        a.create_by, 
        a.selesai_by,
        a.tgl_mulai, 
        a.approve_time, 
        a.create_time, 
        a.tgl_stop
    FROM 
        db_ikg.tbl_schedule a
    LEFT JOIN 
        db_ikg.tbl_gerobak b ON a.id = b.id_schedule 
    WHERE 
        a.status = 'selesai' 
        AND a.leader_check = 'TRUE' 
        AND CONVERT(VARCHAR(10), a.tgl_update, 120) = CONVERT(VARCHAR(10), GETDATE(), 120)
    GROUP BY 
        a.id, a.no_mesin, a.no_urut, a.buyer, a.langganan, a.no_order, 
        a.nokk, a.jenis_kain, a.warna, a.no_warna, a.proses, 
        a.dept_tujuan, a.pic_schedule, a.status, a.lot, a.catatan, 
        a.ket_status, a.tgl_delivery, b.no_gerobak1, b.tgl_out1, 
        b.no_gerobak2, b.tgl_out2, b.no_gerobak3, b.tgl_out3, 
        b.no_gerobak4, b.tgl_out4, b.no_gerobak5, b.tgl_out5, 
        b.no_gerobak6, b.tgl_out6, a.petugas_obras, a.petugas_buka, 
        a.approve_by, a.create_by, a.selesai_by, a.tgl_mulai, 
        a.approve_time, a.create_time, a.tgl_stop
    ORDER BY 
        a.no_mesin ASC, a.no_urut ASC";
} else {
	$sql = "SELECT 
        a.id, 
        a.no_mesin, 
        a.no_urut, 
        a.buyer, 
        a.langganan, 
        a.no_order, 
        a.nokk, 
        a.jenis_kain,
        a.warna, 
        a.no_warna, 
        SUM(a.rol) AS rol, 
        SUM(a.bruto) AS bruto, 
        a.proses, 
        a.dept_tujuan, 
        a.pic_schedule, 
        a.status, 
        a.lot, 
        a.catatan, 
        a.ket_status, 
        a.tgl_delivery, 
        b.no_gerobak1, 
        b.tgl_out1, 
        b.no_gerobak2, 
        b.tgl_out2, 
        b.no_gerobak3, 
        b.tgl_out3, 
        b.no_gerobak4, 
        b.tgl_out4, 
        b.no_gerobak5, 
        b.tgl_out5, 
        b.no_gerobak6, 
        b.tgl_out6, 
        a.petugas_obras,
        a.petugas_buka, 
        a.approve_by, 
        a.create_by, 
        a.selesai_by,
        a.tgl_mulai, 
        a.approve_time, 
        a.create_time, 
        a.tgl_stop
    FROM 
        db_ikg.tbl_schedule a
    LEFT JOIN 
        db_ikg.tbl_gerobak b ON a.id = b.id_schedule 
    WHERE 
        a.status = 'selesai' 
        AND a.leader_check = 'TRUE' 
        AND CONVERT(VARCHAR(10), a.tgl_update, 120) = CONVERT(VARCHAR(10), GETDATE(), 120) 
        AND petugas_buka = ?
	GROUP BY 
        a.id, a.no_mesin, a.no_urut, a.buyer, a.langganan, a.no_order, 
        a.nokk, a.jenis_kain, a.warna, a.no_warna, a.proses, 
        a.dept_tujuan, a.pic_schedule, a.status, a.lot, a.catatan, 
        a.ket_status, a.tgl_delivery, b.no_gerobak1, b.tgl_out1, 
        b.no_gerobak2, b.tgl_out2, b.no_gerobak3, b.tgl_out3, 
        b.no_gerobak4, b.tgl_out4, b.no_gerobak5, b.tgl_out5, 
        b.no_gerobak6, b.tgl_out6, a.petugas_obras, a.petugas_buka, 
        a.approve_by, a.create_by, a.selesai_by, a.tgl_mulai, 
        a.approve_time, a.create_time, a.tgl_stop";

	// Prepare statement
	$params = [$_SESSION['nama1Gkg']];
}

// Execute the query
$data = sqlsrv_query($con, $sql, isset($params) ? $params : null);

// Check for errors in query execution
if ($data === false) {
	die(print_r(sqlsrv_errors(), true));
}

$no = 1;
?>

<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="alert alert-info text-center" role="alert">
				<p style="font-style: italic;">FYI: Table ini berisi data schedule hari ini yang telah di approve oleh
					LEADER</p>
			</div>
			<div class="box-header">
				<a href="pages/cetak/cetak_schedule.php" class="btn btn-danger pull-right" target="_blank">
					<i class="fa fa-print"></i> Cetak
				</a>
			</div>
			<div class="box-body">
				<table id="TableTodayReport" class="table table-bordered table-hover table-striped display compact"
					width="100%">
					<thead class="bg-blue">
						<tr>
							<th>#</th>
							<th>hidden number</th>
							<th>
								<div align="center">Mesin/Urut</div>
							</th>
							<th>
								<div align="center">Pelanggan</div>
							</th>
							<th>
								<div align="center">No. Order</div>
							</th>
							<th>
								<div align="center">Item</div>
							</th>
							<th>
								<div align="center">Warna</div>
							</th>
							<th>
								<div align="center">Rol</div>
							</th>
							<th>
								<div align="center">Kg</div>
							</th>
							<th>
								<div align="center">Lot</div>
							</th>
							<th>
								<div align="center">Delivery</div>
							</th>
							<th>
								<div align="center">Keterangan</div>
							</th>
							<th>gerobak 1</th>
							<th>out gerobak 1</th>
							<th>gerobak 2</th>
							<th>out gerobak 2</th>
							<th>gerobak 3</th>
							<th>out gerobak 3</th>
							<th>gerobak 4</th>
							<th>out gerobak 4</th>
							<th>gerobak 5</th>
							<th>out gerobak 5</th>
							<th>gerobak 6</th>
							<th>out gerobak 6</th>
							<th>id</th>
							<th>petugas_buka</th>
							<th>approve_by</th>
							<th>create_by</th>
							<th>selesai_by</th>
							<th>tgl_mulai</th>
							<th>approve_time</th>
							<th>create_time</th>
							<th>tgl_stop</th>
							<th>Procs/To</th>
							<th>PIC</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$col = 0;
						while ($rowd = sqlsrv_fetch_array($data, SQLSRV_FETCH_ASSOC)) {
							$bgcolor = ($col++ & 1) ? 'gainsboro' : 'antiquewhite';
							?>
							<tr bgcolor="<?php echo $bgcolor; ?>">
								<td class="details-control"></td>
								<td align="center"><?php echo $no++; ?></td>
								<td align="center"><?php echo $rowd['no_mesin'] . ' / ' . $rowd['no_urut']; ?>
									<button class="btn btn-xs btn-info disabled"
										disabled><?php echo $rowd['status']; ?></button>
								</td>
								<td><?php echo $rowd['langganan'] . "/" . $rowd['buyer']; ?></td>
								<td align="center"><?php echo $rowd['no_order']; ?></td>
								<td><?php echo $rowd['jenis_kain']; ?></td>
								<td align="center"><?php echo $rowd['warna']; ?></td>
								<td align="center"><?php echo $rowd['rol']; ?></td>
								<td align="center"><?php echo $rowd['bruto']; ?></td>
								<td align="center"><a href="#"><?php echo $rowd['lot']; ?></a></td>
								<td align="center"><?php echo cek($rowd['tgl_delivery']); ?></td>
								<td>
									<i><?php echo $rowd['nokk']; ?></i><br />
									<i style="color:red;"><strong><?php echo $rowd['catatan']; ?></strong></i><br />
									<a href="#" id='<?php echo $rowd['id']; ?>' class="detail_kartu">
										<span class="label label-danger"><?php echo $rowd['ket_kartu']; ?></span>
									</a>
								</td>
								<td><?php echo $rowd['no_gerobak1']; ?></td>
								<td><?php echo $rowd['tgl_out1']; ?></td>
								<td><?php echo $rowd['no_gerobak2']; ?></td>
								<td><?php echo cek($rowd['tgl_out2']); ?></td>
								<td><?php echo $rowd['no_gerobak3']; ?></td>
								<td><?php echo cek($rowd['tgl_out3']); ?></td>
								<td><?php echo $rowd['no_gerobak4']; ?></td>
								<td><?php echo cek($rowd['tgl_out4']); ?></td>
								<td><?php echo $rowd['no_gerobak5']; ?></td>
								<td><?php echo cek($rowd['tgl_out5']); ?></td>
								<td><?php echo $rowd['no_gerobak6']; ?></td>
								<td><?php echo cek($rowd['tgl_out6']); ?></td>
								<td><?php echo $rowd['id']; ?></td>
								<td><?php echo $rowd['petugas_buka']; ?></td>
								<td><?php echo $rowd['approve_by']; ?></td>
								<td><?php echo $rowd['create_by']; ?></td>
								<td><?php echo $rowd['selesai_by']; ?></td>
								<td><?php echo cek($rowd['tgl_mulai']); ?></td>
								<td><?php echo cek($rowd['approve_time']); ?></td>
								<td><?php echo cek($rowd['create_time']); ?></td>
								<td><?php echo cek($rowd['tgl_stop']); ?></td>
								<td>
									<span class="badge badge-dark"><?php echo $rowd['proses']; ?></span> /
									<span class="label label-info"><?php echo $rowd['dept_tujuan']; ?></span>
								</td>
								<td><?php echo $rowd['pic_schedule']; ?></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<!-- Modal Popup untuk delete-->
<div class="modal fade" id="delSchedule" tabindex="-1">
	<div class="modal-dialog modal-sm">
		<div class="modal-content" style="margin-top:100px;">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" style="text-align:center;">Are you sure to delete this information ?</h4>
			</div>

			<div class="modal-footer" style="margin:0px; border-top:0px; text-align:center;">
				<a href="#" class="btn btn-danger" id="del_link">Delete</a>
				<button type="button" class="btn btn-success" data-dismiss="modal">Cancel</button>
			</div>
		</div>
	</div>
</div>
<table>
	<thead></thead>
</table>
<div id="ScheduleEdit" class="modal fade modal-3d-slit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
	aria-hidden="true">
</div>
<div id="MesinMulaiEdit" class="modal fade modal-3d-slit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
	aria-hidden="true">
</div>
<div id="MesinBerhentiEdit" class="modal fade modal-3d-slit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
	aria-hidden="true">
</div>
<div id="EditStatusMesin" class="modal fade modal-3d-slit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
	aria-hidden="true">
</div>
<div id="GerobakTambah" class="modal fade modal-3d-slit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
	aria-hidden="true">
</div>
<div id="DetailKartu" class="modal fade modal-3d-slit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
	aria-hidden="true">
</div>
</body>

</html>
<script type="text/javascript">
	$(document).ready(function () {
		var table = $('#TableTodayReport').DataTable({
			responsive: true,
			dom: 'Bfrtip',
			buttons: [
				'copyHtml5',
				'excelHtml5',
				'csvHtml5',
				'pdfHtml5'
			],
			"columnDefs": [{
				"className": "align-center",
				"targets": [0]
			},
			{
				"targets": [1, 10, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27,
					28, 29, 30, 31, 32
				],
				"visible": false
			},
			{
				"targets": [0, 1, 2],
				"orderable": false
			}
			]
		});

		$('#TableTodayReport tbody').on('click', 'td.details-control', function () {
			var tr = $(this).parents('tr');
			var row = table.row(tr);

			if (row.child.isShown()) {
				// This row is already open - close it
				row.child.hide();
				tr.removeClass('shown');
			} else {
				// Open this row (the format() function would return the data to be shown)
				row.child(format(row.data())).show();
				tr.addClass('shown');
			}
		});

		$('#TableTodayReport tbody').on('click', 'tr', function () {
			$(this).toggleClass('selected');
		});

		function format(d) {
			return '<div class="col-md-12" style="background: #97aad4;">' +
				'<div class="container-fluid">' +
				'<table class="table table-bordered table-striped" width="100%">' +
				'<thead>' +
				'<tr style="background-color: blueviolet; border: 1px solid black;">' +
				'<th class="text-center" style="color: white; border: 1px solid black;">Waktu Buat / Oleh</th>' +
				'<th class="text-center" style="color: white; border: 1px solid black;">Waktu Mulai / Oleh</th>' +
				'<th class="text-center" style="color: white; border: 1px solid black;">Waktu Selesai / Oleh</th>' +
				'<th class="text-center" style="color: white; border: 1px solid black;">Waktu Approve / Oleh</th>' +
				'<th class="text-center" style="color: white; border: 1px solid black;">Delivery KK</th>' +
				'</tr>' +
				'</thead>' +
				'<tbody>' +
				'<tr style="border: 1px solid black;">' +
				'<td style="border: 1px solid black;">' + d[31] + ' / ' + d[27] + '</td>' +
				'<td style="border: 1px solid black;">' + d[29] + ' / ' + d[25] + '</td>' +
				'<td style="border: 1px solid black;">' + d[32] + ' / ' + d[28] + '</td>' +
				'<td style="border: 1px solid black;">' + d[30] + ' / ' + d[26] + '</td>' +
				'<td style="border: 1px solid black;">' + d[10] + '</td>' +
				'</tr>' +
				'</tbody>' +
				'</table>' +
				'<hr class="divider">' +
				'<table class="table table-sm table-striped table-bordered" id="tablee" width="100%" style="margin-top: 10px;">' +
				'<tbody>' +
				// 1
				'<tr style="border: 1px solid black;">' +
				'<th class="bg-primary" style="border: 1px solid black; width: 100px;">Gerobak 1 :</th>' +
				'<td align="center" style="border: 1px solid black;">' + d[12] + '</td>' +
				'<th class="bg-primary" style="border: 1px solid black; width: 100px;">Out Gerobak 1 :</th>' +
				'<td align="center" style="border: 1px solid black;">' + d[13] + '</td>' +
				'<td align="center" style="border: 1px solid black; width: 100px"><a href="pages/cetak/iden_produk.php?id=' +
				d[24] +
				'" class="btn btn-xs btn-danger" target="_blank"><i class="fa fa-print"></i> gerobak 1</a></td>' +
				'</tr>' +
				// 2
				'<tr style="border: 1px solid black;">' +
				'<th class="bg-primary" style="border: 1px solid black; width: 100px;">Gerobak 2 :</th>' +
				'<td align="center" style="border: 1px solid black;">' + d[14] + '</td>' +
				'<th class="bg-primary" style="border: 1px solid black; width: 100px;">Out Gerobak 2 :</th>' +
				'<td align="center" style="border: 1px solid black;">' + d[15] + '</td>' +
				'<td align="center" style="border: 1px solid black; width: 100px"><a href="pages/cetak/iden_produk2.php?id=' +
				d[24] +
				'" class="btn btn-xs btn-danger" target="_blank"><i class="fa fa-print"></i> gerobak 2</a></td>' +
				'</tr>' +
				// 3
				'<tr style="border: 1px solid black;">' +
				'<th class="bg-primary" style="border: 1px solid black; width: 100px;">Gerobak 3 :</th>' +
				'<td align="center" style="border: 1px solid black;">' + d[16] + '</td>' +
				'<th class="bg-primary" style="border: 1px solid black; width: 100px;">Out Gerobak 3 :</th>' +
				'<td align="center" style="border: 1px solid black;">' + d[17] + '</td>' +
				'<td align="center" style="border: 1px solid black; width: 100px"><a href="pages/cetak/iden_produk3.php?id=' +
				d[24] +
				'" class="btn btn-xs btn-danger" target="_blank"><i class="fa fa-print"></i> gerobak 3</a></td>' +
				'</tr>' +
				// 4
				'<tr style="border: 1px solid black;">' +
				'<th class="bg-primary" style="border: 1px solid black; width: 100px;">Gerobak 4 :</th>' +
				'<td align="center" style="border: 1px solid black;">' + d[18] + '</td>' +
				'<th class="bg-primary" style="border: 1px solid black; width: 100px;">Out Gerobak 4 :</th>' +
				'<td align="center" style="border: 1px solid black;">' + d[19] + '</td>' +
				'<td align="center" style="border: 1px solid black; width: 100px"><a href="pages/cetak/iden_produk4.php?id=' +
				d[24] +
				'" class="btn btn-xs btn-danger" target="_blank"><i class="fa fa-print"></i> gerobak 4</a></td>' +
				'</tr>' +
				// 5
				'<tr style="border: 1px solid black;">' +
				'<th class="bg-primary" style="border: 1px solid black; width: 100px;">Gerobak 5 :</th>' +
				'<td align="center" style="border: 1px solid black;">' + d[20] + '</td>' +
				'<th class="bg-primary" style="border: 1px solid black; width: 100px;">Out Gerobak 5 :</th>' +
				'<td align="center" style="border: 1px solid black;">' + d[21] + '</td>' +
				'<td align="center" style="border: 1px solid black; width: 100px"><a href="pages/cetak/iden_produk5.php?id=' +
				d[24] +
				'" class="btn btn-xs btn-danger" target="_blank"><i class="fa fa-print"></i> gerobak 5</a></td>' +
				'</tr>' +
				// 6
				'<tr style="border: 1px solid black;">' +
				'<th class="bg-primary" style="border: 1px solid black; width: 100px;">Gerobak 6 :</th>' +
				'<td align="center" style="border: 1px solid black;">' + d[22] + '</td>' +
				'<th class="bg-primary" style="border: 1px solid black; width: 100px;">Out Gerobak 6 :</th>' +
				'<td align="center" style="border: 1px solid black;">' + d[23] + '</td>' +
				'<td align="center" style="border: 1px solid black; width: 100px"><a href="pages/cetak/iden_produk6.php?id=' +
				d[24] +
				'" class="btn btn-xs btn-danger" target="_blank"><i class="fa fa-print"></i> gerobak 6</a></td>' +
				'</tr>' +
				// end here
				'</tbody>' +
				'</table>' +
				'</div>' +
				'</div>';
		}
	});
</script>