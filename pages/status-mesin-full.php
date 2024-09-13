<?php
ini_set("error_reporting", 1);
session_start();
include"./../koneksi.php";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="refresh" content="180">
		<title>Status Mesin Dyeing ITTI</title>
<style>
td{
		padding: 1px 0px;
}	
			.blink_me {
  animation: blinker 1s linear infinite;
}
.blink_me1 {
  animation: blinker 7s linear infinite;
}
	@keyframes blinker {
  50% { opacity: 0; }
}
	body{
		font-family: Calibri, "sans-serif", "Courier New";  /* "Calibri Light","serif" */
		font-style: normal;
	}
</style>

		<link rel="stylesheet" href="./../bower_components/bootstrap/dist/css/bootstrap.min.css">
		<!-- Font Awesome -->
		<link rel="stylesheet" href="./../bower_components/font-awesome/css/font-awesome.min.css">
		<!-- Ionicons -->
		<link rel="stylesheet" href="./../bower_components/Ionicons/css/ionicons.min.css">
		<!-- Theme style -->
		<link rel="stylesheet" href="./../dist/css/AdminLTE.min.css">
		<!-- toast CSS -->
		<link href="./../bower_components/toast-master/css/jquery.toast.css" rel="stylesheet">
		<!-- DataTables -->
		<link rel="stylesheet" href="./../bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
		<!-- bootstrap datepicker -->
		<link rel="stylesheet" href="./../bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
		<!-- AdminLTE Skins. We have chosen the skin-blue for this starter
        page. However, you can choose any other skin. Make sure you
        apply the skin class to the body tag so the changes take effect. -->
		<link rel="stylesheet" href="./../dist/css/skins/skin-purple.min.css">

		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

		<!-- Google Font -->
		<!--
  <link rel="stylesheet"
        href="./../dist/css/font/font.css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
	-->

		<link rel="icon" type="image/png" href="./../dist/img/index.ico">
		<style type="text/css">
			.teks-berjalan {
				background-color: #03165E;
				color: #F4F0F0;
				font-family: monospace;
				font-size: 24px;
				font-style: italic;
			}

			.border-dashed {
				border: 4px dashed #083255;
			}

			.bulat {
				border-radius: 50%;
				/*box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.19);*/
			}

		</style>
	</head>

	<body>
		
		<div class="row">
			<div class="col-xs-12">
				<div class="box">
					<div class="box-header with-border">
						<h3 class="box-title">Status Mesin Dyeing ITTI</h3>
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
							<a href="../index1.php?p=Status-Mesin" class="btn btn-xs" data-toggle="tooltip" data-html="true" data-placement="bottom" title="MiniScreen"><i class="fa fa-expand"></i></a>
						</div>
					</div>
					<div class="box-body table-responsive">
<?php
function NoMesin($mc)
{
	include"./../koneksi.php";
	$qMC=mysqli_query($con,"SELECT *,IF(DATEDIFF(now(),a.tgl_delivery) > 0,'Urgent',
IF(DATEDIFF(now(),a.tgl_delivery) > -4,'Potensi Delay','')) as `sts` FROM tbl_schedule a 
LEFT JOIN tbl_montemp b ON a.id=b.id_schedule 
WHERE a.no_mesin='$mc' and (b.`status`='sedang jalan' or a.`status`='antri mesin') ORDER BY a.no_urut ASC");
	$dMC=mysqli_fetch_array($qMC);	
	$qLama=mysqli_query($con,"SELECT round(TIME_FORMAT(timediff(b.tgl_target,now()),'%H')) as lama FROM tbl_schedule a
LEFT JOIN tbl_montemp b ON a.id=b.id_schedule
WHERE a.no_mesin='$mc' AND b.status='sedang jalan' ORDER BY a.no_urut ASC");
	$dLama=mysqli_fetch_array($qLama);
	
		if($dMC['ket_status']=="Tolak Basah"){
			if($dLama['lama']<"1" and $dLama['lama']!=""){ 
				$warnaMc="btn-warning blink_me1";
			}else if($dMC['sts']=="Potensi Delay"){
				$warnaMc="btn-warning border-dashed";							
			}else if($dMC['sts']=="Urgent"){ 
				$warnaMc="btn-warning blink_me";
			}else{ 
				$warnaMc="btn-warning";}
		}else if($dMC['ket_status']=="Mini Bulk"){
			if($dLama['lama']<"1" and $dLama['lama']!=""){ 
				$warnaMc="btn-primary blink_me1";
			}else if($dMC['sts']=="Potensi Delay"){
				$warnaMc="btn-primary border-dashed";				
			}else if($dMC['sts']=="Urgent"){ 
				$warnaMc="btn-primary blink_me";
			}else{ 
				$warnaMc="btn-primary";}			
		}else if($dMC['ket_status']=="MC Stop"){
			if($dLama['lama']<"1" and $dLama['lama']!=""){ 
				$warnaMc="bg-black blink_me1";
			}else if($dMC['sts']=="Potensi Delay"){
				$warnaMc="bg-black border-dashed";				
			}else if($dMC['sts']=="Urgent"){ 
				$warnaMc="bg-black blink_me";
			}else{ 
				$warnaMc="bg-black";}
		}else if($dMC['ket_status']=="MC Rusak"){
			if($dLama['lama']<"1" and $dLama['lama']!=""){ 
				$warnaMc="bg-abu blink_me1";
			}else if($dMC['sts']=="Potensi Delay"){
				$warnaMc="bg-abu border-dashed";				
			}else if($dMC['sts']=="Urgent"){ 
				$warnaMc="bg-abu blink_me";
			}else{ 
				$warnaMc="bg-abu";}
		}else if($dMC['ket_status']=="Test Proses"){
			if($dLama['lama']<"1" and $dLama['lama']!=""){ 
				$warnaMc="bg-navy blink_me1";
			}else if($dMC['sts']=="Potensi Delay"){
				$warnaMc="bg-navy border-dashed";				
			}else if($dMC['sts']=="Urgent"){ 
				$warnaMc="bg-navy blink_me";
			}else{ 
				$warnaMc="bg-navy";}
		}else if($dMC['ket_status']=="Cuci Misty"){
			if($dLama['lama']<"1" and $dLama['lama']!=""){ 
				$warnaMc="bg-teal blink_me1";
			}else if($dMC['sts']=="Potensi Delay"){
				$warnaMc="bg-teal border-dashed";				
			}else if($dMC['sts']=="Urgent"){ 
				$warnaMc="bg-teal blink_me";
			}else{ 
				$warnaMc="bg-teal";}
		}else if($dMC['ket_status']=="Kain Basah"){
			if($dLama['lama']<"1" and $dLama['lama']!=""){ 
				$warnaMc="bg-maroon blink_me1";
			}else if($dMC['sts']=="Potensi Delay"){
				$warnaMc="bg-maroon border-dashed";				
			}else if($dMC['sts']=="Urgent"){ 
				$warnaMc="bg-maroon blink_me";
			}else{ 
				$warnaMc="bg-maroon";}
		}else if($dMC['ket_status']=="Relaxing-Preset" or $dMC['ket_status']=="Scouring-Preset"){
			if($dLama['lama']<"1" and $dLama['lama']!=""){ 
				$warnaMc="bg-purple blink_me1";
			}else if($dMC['sts']=="Potensi Delay"){
				$warnaMc="bg-purple border-dashed";				
			}else if($dMC['sts']=="Urgent"){ 
				$warnaMc="bg-purple blink_me";
			}else{ 
				$warnaMc="bg-purple";}
		}else if($dMC['ket_status']=="Greige"){	
			if($dLama['lama']<"1" and $dLama['lama']!=""){ 
				$warnaMc="btn-success blink_me1";
			}else if($dMC['sts']=="Potensi Delay"){
				$warnaMc="btn-success border-dashed";				
			}else if($dMC['sts']=="Urgent"){ 
				$warnaMc="btn-success blink_me";
			}else{ 
				$warnaMc="btn-success";}
		}else if($dMC['ket_status']=="Perbaikan"){
			if($dLama['lama']<"1" and $dLama['lama']!=""){ 
				$warnaMc="btn-danger blink_me1";	
			}else if($dMC['sts']=="Potensi Delay"){
				$warnaMc="btn-danger border-dashed";
			}else if($dMC['sts']=="Urgent"){ 
				$warnaMc="btn-danger blink_me";
			}else{ 
				$warnaMc="btn-danger";}
		}else if($dMC['ket_status']=="Gagal Proses"){	
			if($dLama['lama']<"1" and $dLama['lama']!=""){ 
				$warnaMc="bg-kuning blink_me1";
			}else if($dMC['sts']=="Potensi Delay"){

				$warnaMc="bg-kuning border-dashed";				
			}else if($dMC['sts']=="Urgent"){ 
				$warnaMc="bg-kuning blink_me";
			}else{ 
				$warnaMc="bg-kuning";}
		}else if($dMC['ket_status']=="Cuci YD"){	
			if($dLama['lama']<"1" and $dLama['lama']!=""){ 
				$warnaMc="bg-hijau blink_me1";
			}else if($dMC['sts']=="Potensi Delay"){
				$warnaMc="bg-hijau border-dashed";				
			}else if($dMC['sts']=="Urgent"){ 
				$warnaMc="bg-hijau blink_me";
			}else{ 
				$warnaMc="bg-hijau";}
		}else if($dMC['ket_status']=="Development Sample"){
			if($dLama['lama']<"1" and $dLama['lama']!=""){ 
				$warnaMc="bg-fuchsia blink_me1";
			}else if($dMC['sts']=="Potensi Delay"){
				$warnaMc="bg-fuchsia border-dashed";				
			}else if($dMC['sts']=="Urgent"){ 
				$warnaMc="bg-fuchsia blink_me";
			}else{ 
				$warnaMc="bg-fuchsia";}
		}else if($dMC['ket_status']=="Salesmen Sample" or $dMC['ket_status']=="First Lot"){
			if($dLama['lama']<"1" and $dLama['lama']!=""){ 
				$warnaMc="bg-lime blink_me1";
			}else if($dMC['sts']=="Potensi Delay"){
				$warnaMc="bg-lime border-dashed";				
			}else if($dMC['sts']=="Urgent"){ 
				$warnaMc="bg-lime blink_me";
			}else{ 
				$warnaMc="bg-lime";}
		}else if($dMC['ket_status']=="Cuci Mesin"){	
			if($dLama['lama']<"1" and $dLama['lama']!=""){ 
				$warnaMc="bg-violet blink_me1";
			}else if($dMC['sts']=="Potensi Delay"){
				$warnaMc="bg-violet border-dashed";				
			}else if($dMC['sts']=="Urgent"){ 
				$warnaMc="bg-violet blink_me";
			}else{ 
				$warnaMc="bg-violet";}	
		}else if($dMC['ket_status']=="Greige Delay"){	
			if($dLama['lama']<"1" and $dLama['lama']!=""){ 
				$warnaMc="btn-default blink_me1";
			}else if($dMC['sts']=="Potensi Delay"){
				$warnaMc="btn-default border-dashed";				
			}else if($dMC['sts']=="Urgent"){ 
				$warnaMc="btn-default blink_me";
			}else{ 
				$warnaMc="btn-default";}	
		}else{
		/*Tidak Ada PO*/ 
		$warnaMc="";
		}

    return $warnaMc;
}
function Rajut($mc)
{
	include"./../koneksi.php";
	$qMC=mysqli_query($con,"SELECT a.langganan,a.no_order,a.warna,a.proses FROM tbl_schedule a 
	LEFT JOIN tbl_montemp b ON a.id=b.id_schedule
	WHERE a.no_mesin='$mc' and b.status='sedang jalan' ORDER BY a.no_urut ASC LIMIT 1");
	$dMC=mysqli_fetch_array($qMC);
    echo "<font size=+2><u>".$mc."</u></font> <br>".$dMC['no_order']."<br> ".$dMC['langganan']."<br>".$dMC['warna']."<br>".$dMC['proses'];	
}
		function Waktu($mc){
			include"./../koneksi.php";
			$qLama=mysqli_query($con,"SELECT TIME_FORMAT(timediff(b.tgl_target,now()),'%H:%i') as lama FROM tbl_schedule a
LEFT JOIN tbl_montemp b ON a.id=b.id_schedule
WHERE a.no_mesin='$mc' AND b.status='sedang jalan' AND (ISNULL(b.tgl_stop) or NOT ISNULL(b.tgl_mulai)) ORDER BY a.no_urut ASC");
			$dLama=mysqli_fetch_array($qLama);
			if($dLama['lama']!=""){echo $dLama['lama'];}else{echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ";}
		}

/* Total Status Mesin */
    $sqlStatus=mysqli_query($con,"SELECT no_mesin FROM tbl_mesin");
    while ($rM=mysqli_fetch_array($sqlStatus)) {
        $sts=NoMesin($rM['no_mesin']);
        if ($sts=="btn-primary" or
           $sts=="btn-primary border-dashed" or
		   $sts=="btn-primary blink_me1" or	
           $sts=="btn-primary blink_me") {
            $MB="1";
        } else {
            $MB="0";
        }
		if ($sts=="bg-purple" or
           $sts=="bg-purple border-dashed" or
		   $sts=="bg-purple blink_me1" or	
           $sts=="bg-purple blink_me") {
            $SPT="1";
        } else {
            $SPT="0";
        }
        if ($sts=="btn-warning" or
           $sts=="btn-warning border-dashed" or
		   $sts=="btn-warning blink_me1" or	
           $sts=="btn-warning blink_me") {
            $FL="1";
        } else {
            $FL="0";
        }
        if ($sts=="btn-danger" or
           $sts=="btn-danger border-dashed" or
		   $sts=="btn-danger blink_me1" or	
           $sts=="btn-danger blink_me") {
            $PBK="1";
        } else {
            $PBK="0";
        }
        if ($sts=="btn-success" or
           $sts=="btn-success border-dashed" or
		   $sts=="btn-success blink_me1" or	
           $sts=="btn-success blink_me") {
            $GRG="1";
        } else {
            $GRG="0";
        }
        if ($sts=="btn-default" or
		   $sts=="btn-default border-dashed" or
		   $sts=="btn-default blink_me1" or	
           $sts=="btn-default blink_me") {
            $GD="1";
        } else {
            $GD="0";
        }
        if ($sts=="bg-kuning" or
           $sts=="bg-kuning border-dashed" or
		   $sts=="bg-kuning blink_me1" or	
           $sts=="bg-kuning blink_me") {
            $GPS="1";
        } else {
            $GPS="0";
        }
		if ($sts=="bg-hijau" or
           $sts=="bg-hijau border-dashed" or
		   $sts=="bg-hijau blink_me1" or	
           $sts=="bg-hijau blink_me") {
            $CYD="1";
        } else {
            $CYD="0";
        }
        if ($sts=="bg-black" or
           $sts=="bg-black border-dashed" or
		   $sts=="bg-black blink_me1" or	
           $sts=="bg-black blink_me") {
            $MCS="1";
        } else {
            $MCS="0";
        }
        if ($sts=="bg-abu" or
           $sts=="bg-abu border-dashed" or
		   $sts=="bg-abu blink_me1" or	
           $sts=="bg-abu blink_me") {
            $MCR="1";
        } else {
            $MCR="0";
        }
		if ($sts=="bg-teal" or
           $sts=="bg-teal border-dashed" or
		   $sts=="bg-teal blink_me1" or	
           $sts=="bg-teal blink_me") {
            $CMY="1";
        } else {
            $CMY="0";
        }
		if ($sts=="bg-fuchsia" or
           $sts=="bg-fuchsia border-dashed" or
		   $sts=="bg-fuchsia blink_me1" or	
           $sts=="bg-fuchsia blink_me") {
            $DTS="1";
        } else {
            $DTS="0";
        }
		if ($sts=="bg-lime" or
           $sts=="bg-lime border-dashed" or
		   $sts=="bg-lime blink_me1" or	
           $sts=="bg-lime blink_me") {
            $SMS="1";
        } else {
            $SMS="0";
        }
		if ($sts=="bg-violet" or
           $sts=="bg-violet border-dashed" or
		   $sts=="bg-violet blink_me1" or	
           $sts=="bg-violet blink_me") {
            $CMS="1";
        } else {
            $CMS="0";
        }
        if ($sts=="bg-abu border-dashed" or
           $sts=="bg-black border-dashed" or
           $sts=="bg-kuning border-dashed" or
		   $sts=="bg-hijau border-dashed" or	
           $sts=="btn-success border-dashed" or
           $sts=="btn-danger border-dashed" or
           $sts=="btn-warning border-dashed" or
           $sts=="btn-primary border-dashed" or
		   $sts=="bg-teal border-dashed" or
		   $sts=="bg-purple border-dashed" or	
		   $sts=="bg-fuchsia border-dashed" or
		   $sts=="bg-lime border-dashed" or	
		   $sts=="bg-violet border-dashed") {
            $PTD="1";
        } else {
            $PTD="0";
        }
        if ($sts=="bg-abu blink_me" or
           $sts=="bg-black blink_me" or
		   $sts=="bg-kuning blink_me" or
		   $sts=="bg-hijau blink_me" or	
           $sts=="btn-success blink_me" or
           $sts=="btn-danger blink_me" or
           $sts=="btn-warning blink_me" or
           $sts=="btn-primary blink_me" or
		   $sts=="bg-teal blink_me" or
		   $sts=="bg-fuchsia blink_me" or
		   $sts=="bg-lime blink_me" or	
		   $sts=="bg-purple blink_me" or
		   $sts=="bg-violet blink_me") {
            $URG="1";
        } else {
            $URG="0";
        }
		
        $totPTD=$totPTD+$PTD;
        $totURG=$totURG+$URG;
		$totGRG=$totGRG+$GRG;
		$totCYD=$totCYD+$CYD;
		$totGPS=$totGPS+$GPS;
		$totPBK=$totPBK+$PBK;
		$totFL=$totFL+$FL;
		$totMB=$totMB+$MB;
		$totGD=$totGD+$GD;
		$totSPT=$totSPT+$SPT;
		$totMCR=$totMCR+$MCR;
		$totMCS=$totMCS+$MCS;
		$totCMY=$totCMY+$CMY;
		$totDTS=$totDTS+$DTS;
		$totSMS=$totSMS+$SMS;
		$totCMS=$totCMS+$CMS;
		
    }
    //$totMesin=$totTS+$totTPB+$totTBW+$totAM+$totTBS+$totTQ+$totPM+$totSJ+$totTAP;
?>


						<table width="100%" border="0">
							<tbody>
								<tr>
								  <td colspan="2" align="center" class="bg-blue">1200 KGs</td>
								  <td colspan="2" align="center" class="bg-yellow">900 KGs</td>
								  <td colspan="2" align="center" class="bg-red">800 KGs</td>
								  <td colspan="2" align="center" class="bg-purple"> 750 KGs </td>
								  <td colspan="2" align="center" class="bg-black"> 600 KGs </td>
								  <td colspan="2" align="center" class="bg-kuning"> 500 KGs </td>
								  <td colspan="2" align="center" class="bg-abu"> 400 KGs </td>
								  <td colspan="2" align="center" class="bg-fuchsia"> 300 KGs </td>
							  </tr>
								<tr>
								  <td colspan="16" align="center" ></td>
							  </tr>
								<tr>
									<td align="center" bgcolor="#E0DDDD"><span class="detail_status btn btn-lg <?php echo NoMesin("01"); ?>" id="01" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("01"); ?>">01<p><?php echo Waktu("01"); ?></p></span></td>
									<td align="center" bgcolor="#E0DDDD"> <span class="detail_status btn btn-lg <?php echo NoMesin("14"); ?>" id="14" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("14"); ?>">14<p><?php echo Waktu("14"); ?></p></span> </td>
									<td align="center" bgcolor="#ECE7E7"> <span class="detail_status btn btn-lg <?php echo NoMesin("10"); ?>" id="10" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("10"); ?>">10<p><?php echo Waktu("10"); ?></p></span></td>
									<td align="center" bgcolor="#ECE7E7">&nbsp;&nbsp;</td>
									<td align="center" bgcolor="#E0DDDD"> <span class="detail_status btn btn-lg <?php echo NoMesin("52"); ?>" id="52" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("52"); ?>">52<p><?php echo Waktu("52"); ?></p></span> </td>
									<td align="center" bgcolor="#E0DDDD"> <span class="detail_status btn btn-lg <?php echo NoMesin("63"); ?>" id="63" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("63"); ?>">63<p><?php echo Waktu("63"); ?></p></span> </td>
									<td align="center" bgcolor="#ECE7E7"> <span class="detail_status btn btn-lg <?php echo NoMesin("34"); ?>" id="34" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("34"); ?>">34<p><?php echo Waktu("34"); ?></p></span> </td>
									<td align="center" bgcolor="#ECE7E7">&nbsp;&nbsp;</td>
									<td align="center" bgcolor="#E0DDDD"> <span class="detail_status btn btn-lg <?php echo NoMesin("09"); ?>" id="09" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("09"); ?>">09<p><?php echo Waktu("09"); ?></p></span> </td>
									<td align="center" bgcolor="#E0DDDD"> <span class="detail_status btn btn-lg <?php echo NoMesin("18"); ?>" id="18" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("18"); ?>">18<p><?php echo Waktu("18"); ?></p></span> </td>
									<td align="center" bgcolor="#ECE7E7"> <span class="detail_status btn btn-lg <?php echo NoMesin("12"); ?>" id="12" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("12"); ?>">12<p><?php echo Waktu("12"); ?></p></span> </td>
									<td align="center" bgcolor="#ECE7E7"> <span class="detail_status btn btn-lg <?php echo NoMesin("47"); ?>" id="47" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("47"); ?>">47<p><?php echo Waktu("47"); ?></p></span> </td>
									<td align="center" bgcolor="#E0DDDD"> <span class="detail_status btn  btn-lg <?php echo NoMesin("66"); ?>" id="66" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("66"); ?>">66<p><?php echo Waktu("66"); ?></p></span> </td>
									<td align="center" bgcolor="#E0DDDD"> <span class="detail_status btn  btn-lg  <?php echo NoMesin("67"); ?>" id="67" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("67"); ?>">67<p><?php echo Waktu("67"); ?></p></span> </td>
									<td align="center" bgcolor="#ECE7E7"> <span class="detail_status btn  btn-lg <?php echo NoMesin("03"); ?>" id="03" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("03"); ?>">03<p><?php echo Waktu("03"); ?></p></span> </td>
									<td align="center" bgcolor="#ECE7E7"> <span class="detail_status btn btn-lg  <?php echo NoMesin("07"); ?>" id="07" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("07"); ?>">07<p><?php echo Waktu("07"); ?></p></span> </td>
							  </tr>
								<tr>
									<td align="center" bgcolor="#E0DDDD"><span class="detail_status btn  btn-lg <?php echo NoMesin("02"); ?>" id="02" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("02"); ?>">02<p><?php echo Waktu("02"); ?></p></span></td>
									<td align="center" bgcolor="#E0DDDD"> <span class="detail_status btn btn-lg  <?php echo NoMesin("17"); ?>" id="17" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("17"); ?>">17<p><?php echo Waktu("17"); ?></p>
									</span></td>
									<td align="center" bgcolor="#ECE7E7">&nbsp;</td>
									<td align="center" bgcolor="#ECE7E7">&nbsp;</td>
									<td align="center" bgcolor="#E0DDDD"> <span class="detail_status btn  btn-lg <?php echo NoMesin("54"); ?>" id="54" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("54"); ?>">54<p><?php echo Waktu("54"); ?></p></span> </td>
									<td align="center" bgcolor="#E0DDDD"> <span class="detail_status btn  btn-lg <?php echo NoMesin("65"); ?>" id="65" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("65"); ?>">65<p><?php echo Waktu("65"); ?></p></span> </td>
									<td align="center" bgcolor="#ECE7E7">&nbsp;</td>
									<td align="center" bgcolor="#ECE7E7">&nbsp;</td>
									<td align="center" bgcolor="#E0DDDD"> <span class="detail_status btn btn-lg  <?php echo NoMesin("11"); ?>" id="11" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("11"); ?>">11<p><?php echo Waktu("11"); ?></p></span> </td>
									<td align="center" bgcolor="#E0DDDD"> <span class="detail_status btn  btn-lg <?php echo NoMesin("19"); ?>" id="19" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("19"); ?>">19<p><?php echo Waktu("19"); ?></p></span> </td>
									<td align="center" bgcolor="#ECE7E7"> <span class="detail_status btn  btn-lg <?php echo NoMesin("40"); ?>" id="40" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("40"); ?>">40<p><?php echo Waktu("40"); ?></p></span> </td>
									<td align="center" bgcolor="#ECE7E7"> <span class="detail_status btn btn-lg  <?php echo NoMesin("48"); ?>" id="48" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("48"); ?>">48<p><?php echo Waktu("48"); ?></p></span> </td>
									<td align="center" bgcolor="#E0DDDD">&nbsp;</td>
									<td align="center" bgcolor="#E0DDDD">&nbsp;</td>
									<td align="center" bgcolor="#ECE7E7"> <span class="detail_status btn  btn-lg <?php echo NoMesin("04"); ?>" id="04" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("04"); ?>">04<p><?php echo Waktu("04"); ?></p></span> </td>
									<td align="center" bgcolor="#ECE7E7"> <span class="detail_status btn  btn-lg <?php echo NoMesin("08"); ?>" id="08" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("08"); ?>">08<p><?php echo Waktu("08"); ?></p></span> </td>
							  </tr>
								<tr>
									<td align="center" bgcolor="#E0DDDD"> <span class="detail_status btn  btn-lg <?php echo NoMesin("05"); ?>" id="05" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("05"); ?>">05<p><?php echo Waktu("05"); ?></p></span></td>
									<td align="center" bgcolor="#E0DDDD"> <span class="detail_status btn  btn-lg <?php echo NoMesin("33"); ?>" id="33" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("33"); ?>">33<p><?php echo Waktu("33"); ?></p>
									</span></td>
									<td align="center" bgcolor="#ECE7E7">&nbsp;</td>
									<td align="center" bgcolor="#ECE7E7">&nbsp;</td>
									<td align="center" bgcolor="#E0DDDD"> <span class="detail_status btn btn-lg  <?php echo NoMesin("60"); ?>" id="60" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("60"); ?>">60<p><?php echo Waktu("60"); ?></p></span> </td>
									<td align="center" bgcolor="#E0DDDD"> <span class="detail_status btn  btn-lg <?php echo NoMesin("68"); ?>" id="68" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("68"); ?>">68<p><?php echo Waktu("68"); ?></p></span> </td>
									<td align="center" bgcolor="#ECE7E7">&nbsp;</td>
									<td align="center" bgcolor="#ECE7E7">&nbsp;</td>
									<td align="center" bgcolor="#E0DDDD"> <span class="detail_status btn  btn-lg <?php echo NoMesin("15"); ?>" id="15" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("15"); ?>">15<p><?php echo Waktu("15"); ?></p></span> </td>
									<td align="center" bgcolor="#E0DDDD"> <span class="detail_status btn btn-lg  <?php echo NoMesin("20"); ?>" id="20" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("20"); ?>">20<p><?php echo Waktu("20"); ?></p></span> </td>
									<td align="center" bgcolor="#ECE7E7">&nbsp;</td>
									<td align="center" bgcolor="#ECE7E7">&nbsp;</td>
									<td align="center" bgcolor="#E0DDDD">&nbsp;</td>
									<td align="center" bgcolor="#E0DDDD">&nbsp;</td>
									<td align="center" bgcolor="#ECE7E7"> <span class="detail_status btn btn-lg <?php echo NoMesin("06"); ?>" id="06" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("06"); ?>">06<p><?php echo Waktu("06"); ?></p></span> </td>
									<td align="center" bgcolor="#ECE7E7">&nbsp;</td>
							  </tr>
								<tr>
									<td bgcolor="#E0DDDD">&nbsp;</td>
									<td bgcolor="#E0DDDD">&nbsp;</td>
									<td bgcolor="#ECE7E7">&nbsp;</td>
									<td bgcolor="#ECE7E7">&nbsp;</td>
									<td align="center" bgcolor="#E0DDDD"> <span class="detail_status btn btn-lg  <?php echo NoMesin(61); ?>" id="61" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("61"); ?>">61<p><?php echo Waktu("61"); ?></p></span> </td>
									<td align="center" bgcolor="#E0DDDD"> <span class="detail_status btn btn-lg  <?php echo NoMesin("69"); ?>" id="69" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("69"); ?>">69<p><?php echo Waktu("69"); ?></p></span> </td>
									<td align="center" bgcolor="#ECE7E7">&nbsp;</td>
									<td align="center" bgcolor="#ECE7E7">&nbsp;</td>
									<td align="center" bgcolor="#E0DDDD"> <span class="detail_status btn  btn-lg <?php echo NoMesin("16"); ?>" id="16" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("16"); ?>">16<p><?php echo Waktu("16"); ?></p></span> </td>
									<td align="center" bgcolor="#E0DDDD"> <span class="detail_status btn btn-lg  <?php echo NoMesin("32"); ?>" id="32" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("32"); ?>">32<p><?php echo Waktu("32"); ?></p></span> </td>
									<td align="center" bgcolor="#ECE7E7">&nbsp;</td>
									<td align="center" bgcolor="#ECE7E7">&nbsp;</td>
									<td align="center" bgcolor="#E0DDDD">&nbsp;</td>
									<td align="center" bgcolor="#E0DDDD">&nbsp;</td>
									<td align="center" bgcolor="#ECE7E7">&nbsp;</td>
									<td align="center" bgcolor="#ECE7E7">&nbsp;</td>
							  </tr>
								<tr>
								  <td colspan="2" align="center" class="bg-aqua"> 200 KGs </td>
								  <td colspan="2" align="center" class="bg-navy"> 150 KGs </td>
								  <td colspan="2" align="center" class="bg-info"> 100 KGs </td>
								  <td colspan="2" align="center" class="bg-maroon"> 50 KGs </td>
								  <td colspan="2" align="center" class="bg-green"> 30 KGs </td>
								  <td colspan="2" align="center" class="bg-gray"> 20 KGs </td>
								  <td colspan="2" align="center" class="bg-lime"> 10 KGs </td>
								  <td align="center" class="bg-teal"> 0 KGs </td>
								  <td align="center" bgcolor="#ECE7E7">&nbsp;</td>
							  </tr>
								<tr>
								  <td align="center" bgcolor="#E0DDDD"><span class="detail_status btn  btn-lg <?php echo NoMesin("64"); ?>" id="64" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("64"); ?>">64
								    <p><?php echo Waktu("64"); ?></p>
								    </span></td>
								  <td align="center" bgcolor="#E0DDDD">&nbsp;&nbsp;</td>
								  <td align="center" bgcolor="#ECE7E7"><span class="detail_status btn  btn-lg <?php echo NoMesin("31"); ?>" id="31" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("31"); ?>">31
								    <p><?php echo Waktu("31"); ?></p>
								    </span></td>
								  <td align="center" bgcolor="#ECE7E7">&nbsp;&nbsp;</td>
								  <td align="center" bgcolor="#E0DDDD"><span class="detail_status btn  btn-lg <?php echo NoMesin("22"); ?>" id="22" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("22"); ?>">22
								    <p><?php echo Waktu("22"); ?></p>
								    </span></td>
								  <td align="center" bgcolor="#E0DDDD"><span class="detail_status btn  btn-lg <?php echo NoMesin("49"); ?>" id="49" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("49"); ?>">49
								    <p><?php echo Waktu("49"); ?></p>
								    </span></td>
								  <td align="center" bgcolor="#ECE7E7"><span class="detail_status btn  btn-lg <?php echo NoMesin("23"); ?>" id="23" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("23"); ?>">23
								    <p><?php echo Waktu("23"); ?></p>
								    </span></td>
								  <td align="center" bgcolor="#ECE7E7"><span class="detail_status btn btn-lg  <?php echo NoMesin("27"); ?>" id="27" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("27"); ?>">27
								    <p><?php echo Waktu("27"); ?></p>
								    </span></td>
								  <td align="center" bgcolor="#E0DDDD"><span class="detail_status btn btn-lg <?php echo NoMesin("53"); ?>" id="53" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("53"); ?>">53
								    <p><?php echo Waktu("53"); ?></p>
								    </span></td>
								  <td align="center" bgcolor="#E0DDDD">&nbsp;&nbsp;</td>
								  <td align="center" bgcolor="#ECE7E7"><span class="detail_status btn  btn-lg  <?php echo NoMesin("43"); ?>" id="43" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("43"); ?>">43
								    <p><?php echo Waktu("43"); ?></p>
								    </span></td>
								  <td align="center" bgcolor="#ECE7E7"><span class="detail_status btn  btn-lg <?php echo NoMesin("56"); ?>" id="56" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("56"); ?>">56
								    <p><?php echo Waktu("56"); ?></p>
								    </span></td>
								  <td align="center" bgcolor="#E0DDDD"><span class="detail_status btn btn-lg  <?php echo NoMesin("59"); ?>" id="59" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("59"); ?>">59
								    <p><?php echo Waktu("59"); ?></p>
								    </span></td>
								  <td align="center" bgcolor="#E0DDDD">&nbsp;</td>
								  <td align="center" bgcolor="#ECE7E7"><span class="detail_status btn  btn-lg  <?php echo NoMesin("WS"); ?>" id="WS" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("WS"); ?>">WS
								    <p><?php echo Waktu("WS"); ?></p>
								    </span></td>
								  <td align="center" bgcolor="#ECE7E7">&nbsp;</td>
							  </tr>
								<tr>
								  <td bgcolor="#E0DDDD">&nbsp;</td>
								  <td bgcolor="#E0DDDD">&nbsp;</td>
								  <td bgcolor="#ECE7E7">&nbsp;</td>
								  <td bgcolor="#ECE7E7">&nbsp;</td>
								  <td align="center" bgcolor="#E0DDDD"><span class="detail_status btn btn-lg  <?php echo NoMesin("28"); ?>" id="28" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("28"); ?>">28
								    <p><?php echo Waktu("28"); ?></p>
								    </span></td>
								  <td align="center" bgcolor="#E0DDDD"><span class="detail_status btn  btn-lg <?php echo NoMesin("50"); ?>" id="50" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("50"); ?>">50
								    <p><?php echo Waktu("50"); ?></p>
								    </span></td>
								  <td align="center" bgcolor="#ECE7E7"><span class="detail_status btn  btn-lg <?php echo NoMesin("24"); ?>" id="24" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("24"); ?>">24
								    <p><?php echo Waktu("24"); ?></p>
								    </span></td>
								  <td align="center" bgcolor="#ECE7E7"><span class="detail_status btn btn-lg  <?php echo NoMesin("51"); ?>" id="51" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("51"); ?>">51
								    <p><?php echo Waktu("51"); ?></p>
								    </span></td>
								  <td align="center" bgcolor="#E0DDDD">&nbsp;</td>
								  <td align="center" bgcolor="#E0DDDD">&nbsp;</td>
								  <td align="center" bgcolor="#ECE7E7"><span class="detail_status btn btn-lg  <?php echo NoMesin("44"); ?>" id="44" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("44"); ?>">44
								    <p><?php echo Waktu("44"); ?></p>
								    </span></td>
								  <td align="center" bgcolor="#ECE7E7"><span class="detail_status btn btn-lg  <?php echo NoMesin("57"); ?>" id="57" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("57"); ?>">57
								    <p><?php echo Waktu("57"); ?></p>
								    </span></td>
								  <td align="center" bgcolor="#E0DDDD">&nbsp;</td>
								  <td align="center" bgcolor="#E0DDDD">&nbsp;</td>
								  <td align="center" bgcolor="#ECE7E7"><span class="detail_status btn  btn-lg  <?php echo NoMesin("CB"); ?>" id="CB" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("CB"); ?>">CB
								    <p><?php echo Waktu("CB"); ?></p>
								    </span></td>
								  <td align="center" bgcolor="#ECE7E7">&nbsp;</td>
							  </tr>
								<tr>
								  <td bgcolor="#E0DDDD">&nbsp;</td>
								  <td bgcolor="#E0DDDD">&nbsp;</td>
								  <td bgcolor="#ECE7E7">&nbsp;</td>
								  <td bgcolor="#ECE7E7">&nbsp;</td>
								  <td align="center" bgcolor="#E0DDDD"><span class="detail_status btn btn-lg  <?php echo NoMesin("29"); ?>" id="29" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("29"); ?>">29
								    <p><?php echo Waktu("29"); ?></p>
								    </span></td>
								  <td align="center" bgcolor="#E0DDDD">&nbsp;</td>
								  <td align="center" bgcolor="#ECE7E7"><span class="detail_status btn btn-lg  <?php echo NoMesin("25"); ?>" id="25" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("25"); ?>">25
								    <p><?php echo Waktu("25"); ?></p>
								    </span></td>
								  <td align="center" bgcolor="#ECE7E7"><span class="detail_status btn btn-lg  <?php echo NoMesin("62"); ?>" id="62" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("62"); ?>">62
								    <p><?php echo Waktu("62"); ?></p>
								    </span></td>
								  <td align="center" bgcolor="#E0DDDD">&nbsp;</td>
								  <td align="center" bgcolor="#E0DDDD">&nbsp;</td>
								  <td align="center" bgcolor="#ECE7E7"><span class="detail_status btn btn-lg  <?php echo NoMesin("45"); ?>" id="45" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("45"); ?>">45
								    <p><?php echo Waktu("45"); ?></p>
								    </span></td>
								  <td align="center" bgcolor="#ECE7E7"><span class="detail_status btn  btn-lg <?php echo NoMesin(58); ?>" id="58" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(58); ?>">58
								    <p><?php echo Waktu("58"); ?></p>
								    </span></td>
								  <td align="center" bgcolor="#E0DDDD">&nbsp;</td>
								  <td align="center" bgcolor="#E0DDDD">&nbsp;</td>
								  <td align="center" bgcolor="#ECE7E7">&nbsp;</td>
								  <td align="center" bgcolor="#ECE7E7">&nbsp;</td>
							  </tr>
								<tr>
								  <td bgcolor="#E0DDDD">&nbsp;</td>
								  <td bgcolor="#E0DDDD">&nbsp;</td>
								  <td bgcolor="#ECE7E7">&nbsp;</td>
								  <td bgcolor="#ECE7E7">&nbsp;</td>
								  <td align="center" bgcolor="#E0DDDD">&nbsp;</td>
								  <td align="center" bgcolor="#E0DDDD">&nbsp;</td>
								  <td align="center" bgcolor="#ECE7E7"><span class="detail_status btn  btn-lg <?php echo NoMesin("26"); ?>" id="26" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("26"); ?>">26
								    <p><?php echo Waktu("26"); ?></p>
								    </span></td>
								  <td align="center" bgcolor="#ECE7E7">&nbsp;</td>
								  <td align="center" bgcolor="#E0DDDD">&nbsp;</td>
								  <td align="center" bgcolor="#E0DDDD">&nbsp;</td>
								  <td align="center" bgcolor="#ECE7E7"><span class="detail_status btn  btn-lg <?php echo NoMesin("46"); ?>" id="46" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("46"); ?>">46
								    <p><?php echo Waktu("46"); ?></p>
								    </span></td>
								  <td align="center" bgcolor="#ECE7E7">&nbsp;</td>
								  <td align="center" bgcolor="#E0DDDD">&nbsp;</td>
								  <td align="center" bgcolor="#E0DDDD">&nbsp;</td>
								  <td align="center" bgcolor="#ECE7E7">&nbsp;</td>
								  <td align="center" bgcolor="#ECE7E7">&nbsp;</td>
							  </tr>
								<tr>
									<td colspan="3">Greige <span class="label label-success">&nbsp;<?php echo $totGRG;?></span></td>
									<td colspan="3">Tolak Basah <span class="label label-warning">&nbsp;<?php echo $totFL;?></span></td>
									<td>&nbsp;</td>
									<td colspan="3">Development Sample <span class="label bg-fuchsia"> &nbsp;<?php echo $totDTS;?></span></td>
									<td colspan="4">Greige Delay <span class="label label-default"> &nbsp;<?php echo $totGD;?></span></td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
								</tr>
								<tr>
								  <td colspan="3">Cuci Y/D <span class="label bg-hijau">&nbsp;<?php echo $totCYD;?></span></td>
								  <td colspan="3">Cuci Misty <span class="label bg-teal">&nbsp;<?php echo $totCMY;?></span></td>
								  <td>&nbsp;</td>
								  <td colspan="3">Salesmen Sample-1st Lot <span class="label bg-lime">&nbsp;<?php echo $totSMS;?></span></td>
								  <td colspan="4">Cuci Mesin <span class="label bg-violet">&nbsp;<?php echo $totCMS;?></span></td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
							    </tr>
								<tr>
								  <td colspan="3">Gagal Proses <span class="label bg-kuning">&nbsp;<?php echo $totGPS;?></span></td>
								  <td colspan="3">Mini Bulk <span class="label label-primary">&nbsp;<?php echo $totMB;?></span></td>
								  <td>&nbsp;</td>
								  <td colspan="3">Urgent <span class="label btn-sm bg-abu blink_me">&nbsp;<?php echo $totURG;?></span></td>
								  <td colspan="4">Mesin Rusak <span class="label bg-abu">&nbsp;<?php echo $totMCR;?></span></td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
							  </tr>
								<tr>
								  <td colspan="3">Perbaikan <span class="label label-danger">&nbsp;<?php echo $totPBK;?></span></td>
								  <td colspan="3">Cont/Scour/Relax-Preset <span class="label btn-sm bg-purple">&nbsp;<?php echo $totSPT;?></span></td>
								  <td>&nbsp;</td>
								  <td colspan="3">Potensi Delay <span class="label bg-abu border-dashed">&nbsp;<?php echo $totPTD;?></span></td>
								  <td colspan="4">Mesin Stop <span class="label btn-sm bg-black">&nbsp;<?php echo $totMCS;?></span></td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
							  </tr>
								<tr>
								  <td colspan="16" style="padding: 1px;">&nbsp;</td>
							  </tr>
								<tr>
									<td colspan="16" style="padding: 5px;">
										<marquee class="teks-berjalan" behavior="scroll" direction="left" onmouseover="this.stop();" onmouseout="this.start();">
											<?php
$news=mysqli_query($con,"SELECT GROUP_CONCAT(news_line SEPARATOR ' :: ') as news_line FROM tbl_news_line WHERE gedung='LT 1' AND status='Tampil'");
$rNews=mysqli_fetch_array($news);
$totMesin='0';
?>
											<?php echo $rNews['news_line'];?>
										</marquee>
									</td>
								</tr>								
								
								<!--
    <tr>
      <td colspan="26" style="padding: 5px;">&nbsp;</td>
    </tr> -->
							</tbody>
						</table>

				  </div>

				</div>

			</div>
		</div>			
<div id="CekDetailStatus" class="modal fade modal-3d-slit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>

	</body>
	<!-- Tooltips -->
	<!-- jQuery 3 -->
	<script src="./../bower_components/jquery/dist/jquery.min.js"></script>
	<!-- Bootstrap 3.3.7 -->
	<script src="./../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
	<!-- AdminLTE App -->
	<script src="./../dist/js/adminlte.min.js"></script>

	<!-- DataTables -->
	<script src="./../bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
	<script src="./../bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
	<!-- bootstrap datepicker -->
	<script src="./../bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
	<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. -->
	<script src="./../bower_components/toast-master/js/jquery.toast.js"></script>
	<!-- Tooltips -->
	<script src="./../../dist/js/tooltips.js"></script>
	<script>
		$(document).ready(function() {
			$('[data-toggle="tooltip"]').tooltip();
		});

	</script>
	<!-- Javascript untuk popup modal Edit-->
	<script type="text/javascript">
		$(document).on('click', '.detail_status', function(e) {
			var m = $(this).attr("id");
			$.ajax({
				url: "./cek-status-mesin.php",
				type: "GET",
				data: {
					id: m,
				},
				success: function(ajaxData) {
					$("#CekDetailStatus").html(ajaxData);
					$("#CekDetailStatus").modal('show', {
						backdrop: 'true'
					});
				}
			});
		});

		//            tabel lookup KO status terima
		$(function() {
			$("#lookup").dataTable();
		});

	</script>
	<script>
		$(document).ready(function() {
			"use strict";
			// toat popup js
			$.toast({
				heading: 'Selamat Datang',
				text: 'Dyeing Indo Taichen',
				position: 'bottom-right',
				loaderBg: '#ff6849',
				icon: 'success',
				hideAfter: 3500,
				stack: 6
			})


		});
		$(".tst1").on("click", function() {
			var msg = $('#message').val();
			var title = $('#title').val() || '';
			$.toast({
				heading: 'Info',
				text: msg,
				position: 'top-right',
				loaderBg: '#ff6849',
				icon: 'info',
				hideAfter: 3000,
				stack: 6
			});

		});

	</script>

</html>
