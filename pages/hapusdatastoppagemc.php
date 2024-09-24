<?php
ini_set("error_reporting", 1);
session_start();
include("../koneksi.php");
    $modal_id=$_GET['id'];
    $modal=sqlsrv_query($con,"DELETE FROM db_ikg.tbl_stoppage_mc WHERE id='$modal_id' ");
    if ($modal) {
        echo "<script>window.location='./LapStoppageMC';</script>";
    } else {
        echo "<script>alert('Gagal Hapus');window.location='./LapStoppageMC';</script>";
    }
