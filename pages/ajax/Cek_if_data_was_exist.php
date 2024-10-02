<?PHP
ini_set("error_reporting", 1);
session_start();
include "../../koneksi.php";

$sql_get = sqlsrv_query($con,"SELECT * from db_ikg.tbl_laporanharian where date_laporan = '$_POST[tgl_laporan]'");
$get = sqlsrv_fetch_array($sql_get);

if (empty($get)) {
    $response = array(
        'session' => 'LIB_NULL',
        'data' => ""
    );
} else {
    $response = array(
        'session' => 'LIB_SUCCSS',
        'data' => $get
    );
}

echo json_encode($response);
