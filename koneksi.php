<?php
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
date_default_timezone_set('Asia/Jakarta');
// $host = "10.0.0.174";
// $username = "ditprogram";
// $password = "Xou@RUnivV!6";
// $db_name = "TM";
// $connInfo = array("Database" => $db_name, "UID" => $username, "PWD" => $password);
// $conn = sqlsrv_connect($host, $connInfo);
// $con = mysqli_connect("10.0.0.10", "dit", "4dm1n", "db_ikg");
//$con = mysqli_connect("localhost", "root", "", "db_ikg");
// $connn = mysqli_connect("10.0.0.10", "dit", "4dm1n", "dbnow_gkg");
// $conr = mysqli_connect("10.0.0.10", "dit", "4dm1n", "dbnow_gerobak");

// Koneksi ke SQLSERVER
$hostSVR19 = "10.0.0.221";
$usernameSVR19 = "sa";
$passwordSVR19 = "Ind@taichen2024";
$gkgitti = "db_ikg";
$nowgkg ="dbnow_gkg";
$nowgerobak = "dbnow_gerobak";

$db_ikg = array("Database" => $gkgitti, "UID" => $usernameSVR19, "PWD" => $passwordSVR19);
$dbnow_gkg = array("Database" => $nowgkg, "UID" => $usernameSVR19, "PWD" => $passwordSVR19);
$dbnow_gerobak = array("Database" => $nowgerobak, "UID" => $usernameSVR19, "PWD" => $passwordSVR19);

$con = sqlsrv_connect($hostSVR19, $db_ikg);
$connn = sqlsrv_connect($hostSVR19, $dbnow_gkg);
$conr = sqlsrv_connect($hostSVR19, $dbnow_gerobak);
if ($con) {
} else {
    exit("SQLSVR19 db_ikg Connection failed");
}
if ($connn) {
} else {
    exit("SQLSVR19 dbnow_gkg Connection failed");
}
if ($conr) {
} else {
    exit("SQLSVR19 dbnow_gerobak Connection failed");
}
// End Con


$hostname = "10.0.0.21";
$database = "NOWPRD";
$user = "db2admin";
$passworddb2 = "Sunkam@24809";
$port = "25000";
$conn_string = "DRIVER={IBM ODBC DB2 DRIVER}; HOSTNAME=$hostname; PORT=$port; PROTOCOL=TCPIP; UID=$user; PWD=$passworddb2; DATABASE=$database;";
$conn1 = db2_connect($conn_string, '', '');

if ($conn1) {
} else {
    exit("DB2 Connection failed");
}