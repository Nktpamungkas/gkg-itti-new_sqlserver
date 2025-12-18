<?PHP
// Bk TObi
ini_set("error_reporting", 1);
session_start();
include "../../koneksi.php";
include "../../utils/helper.php";
$time = date('Y-m-d H:i:s');

$sql_get = sqlsrv_query($con,"SELECT * from db_ikg.tbl_laporanharian where date_laporan = '$_POST[tgl_laporan]'");
$get = sqlsrv_fetch_array($sql_get);
// var_dump($_POST('buka_kain_s1'));
$masuk_kain_s1 = cek_input('masuk_kain_s1');
$masuk_kain_s2 = cek_input('masuk_kain_s2');
$masuk_kain_s3 = cek_input('masuk_kain_s3');
$pembagian_kain_s1 = cek_input('pembagian_kain_s1');
$pembagian_kain_s2 = cek_input('pembagian_kain_s2');
$pembagian_kain_s3 = cek_input('pembagian_kain_s3');
$buka_kain_s1 = cek_input('buka_kain_s1');
$buka_kain_s2 = cek_input('buka_kain_s2');
$buka_kain_s3 = cek_input('buka_kain_s3');
$belah_kain_s1 = cek_input('belahkains1');
$belah_kain_s2 = cek_input('belahkains2');
$belah_kain_s3 = cek_input('belahkains3');
$penyusunan_s1  = addslashes($_POST['penyusunan_s1']);
$penyusunan_s2  = addslashes($_POST['penyusunan_s2']);
$penyusunan_s3  = addslashes($_POST['penyusunan_s3']);
$masalah_s1 = addslashes($_POST['masalah_s1']);
$masalah_s2 = addslashes($_POST['masalah_s2']);
$masalah_s3 = addslashes($_POST['masalah_s3']);
$date_laporan=cek_input('tgl_laporan');
$absensi_s1=cek_input('absensi_s1');
$absensi_s2=cek_input('absensi_s2');
$absensi_s3=cek_input('absensi_s3');
$group_s1=cek_input('group_s1');
$group_s2=cek_input('group_s2');
$group_s3=cek_input('group_s3');
$hadir_s1=cek_input('hadir_s1');
$hadir_s2=cek_input('hadir_s2');
$hadir_s3=cek_input('hadir_s3');
$sakit_s1=cek_input('sakit_s1');
$sakit_s2=cek_input('sakit_s2');
$sakit_s3=cek_input('sakit_s3');
$mangkir_s1=cek_input('mangkir_s1');
$mangkir_s2=cek_input('mangkir_s2');
$mangkir_s3=cek_input('mangkir_s3');
$cuti_s1=cek_input('cuti_s1');
$cuti_s2=cek_input('cuti_s2');
$cuti_s3=cek_input('cuti_s3');
$libur_s1=cek_input('libur_s1');
$libur_s2=cek_input('libur_s2');
$libur_s3=cek_input('libur_s3');
$izin_s1=cek_input('izin_s1');
$izin_s2=cek_input('izin_s2');
$izin_s3=cek_input('izin_s3');
$terima_kain_s1=cek_input('terima_kain_s1');
$terima_kain_s2=cek_input('terima_kain_s2');
$terima_kain_s3=cek_input('terima_kain_s3');
$inspeksi_s1=cek_input('inspeksi_s1');
$inspeksi_s2=cek_input('inspeksi_s2');
$inspeksi_s3=cek_input('inspeksi_s3');
$bagi_kain_s1=cek_input('bagi_kain_s1');
$bagi_kain_s2=cek_input('bagi_kain_s2');
$bagi_kain_s3=cek_input('bagi_kain_s3');
$buka_kain_s1=cek_input('bukakains1');
$buka_kain_s2=cek_input('bukakains2');
$buka_kain_s3=cek_input('bukakains3');
$leader_s1=cek_input('leader_s1');
$leader_s2=cek_input('leader_s2');
$leader_s3=cek_input('leader_s3');
$mc_bk_s1=cek_input('mc_bk_s1');
$mc_bk_s2=cek_input('mc_bk_s2');
$mc_bk_s3=cek_input('mc_bk_s3');
$mc_blk_s1=cek_input('mc_blk_s1');
$mc_blk_s2=cek_input('mc_blk_s2');
$mc_blk_s3=cek_input('mc_blk_s3');
$mc_blh_s1=cek_input('mc_blh_s1');
$mc_blh_s2=cek_input('mc_blh_s2');
$mc_blh_s3=cek_input('mc_blh_s3');
$mc_jhtpgr_s1=cek_input('mc_jhtpgr_s1');
$mc_jhtpgr_s2=cek_input('mc_jhtpgr_s2');
$mc_jhtpgr_s3=cek_input('mc_jhtpgr_s3');
$masalah_hadir_s1=cek_input('masalah_hadir_s1');
$masalah_hadir_s2=cek_input('masalah_hadir_s2');
$masalah_hadir_s3=cek_input('masalah_hadir_s3');
$status = 'NOT COMPLETED';
$last_updated_at='';
$last_updated_by = '';
$created_by = $_SESSION['usridGkg'];
$created_at = $time;

// var_dump($buka_kain_s1);

if (empty($get)) {
    $data = [
        $date_laporan,
        $absensi_s1,
        $absensi_s2,
        $absensi_s3,
        $group_s1,
        $group_s2,
        $group_s3,
        $hadir_s1,
        $hadir_s2,
        $hadir_s3,
        $sakit_s1,
        $sakit_s2,
        $sakit_s3,
        $mangkir_s1,
        $mangkir_s2,
        $mangkir_s3,
        $cuti_s1,
        $cuti_s2,
        $cuti_s3,
        $libur_s1,
        $libur_s2,
        $libur_s3,
        $izin_s1,
        $izin_s2,
        $izin_s3,
        $masuk_kain_s1,
        $masuk_kain_s2,
        $masuk_kain_s3,
        $pembagian_kain_s1,
        $pembagian_kain_s2,
        $pembagian_kain_s3,
        $buka_kain_s1,
        $buka_kain_s2,
        $buka_kain_s3,
        $belah_kain_s1,
        $belah_kain_s2,
        $belah_kain_s3,
        $penyusunan_s1,
        $penyusunan_s2,
        $penyusunan_s3,
        $masalah_s1,
        $masalah_s2,
        $masalah_s3,
        $terima_kain_s1,
        $terima_kain_s2,
        $terima_kain_s3,
        $inspeksi_s1,
        $inspeksi_s2,
        $inspeksi_s3,
        $bagi_kain_s1,
        $bagi_kain_s2,
        $bagi_kain_s3,
        $buka_kain_s1,
        $buka_kain_s2,
        $buka_kain_s3,
        $leader_s1,
        $leader_s2,
        $leader_s3,
        $mc_bk_s1,
        $mc_bk_s2,
        $mc_bk_s3,
        $mc_blk_s1,
        $mc_blk_s2,
        $mc_blk_s3,
        $mc_blh_s1,
        $mc_blh_s2,
        $mc_blh_s3,
        $mc_jhtpgr_s1,
        $mc_jhtpgr_s2,
        $mc_jhtpgr_s3,
        $masalah_hadir_s1,
        $masalah_hadir_s2,
        $masalah_hadir_s3,
        $created_at,
        $created_by,
        $last_updated_at,
        $last_updated_by,
        $status];
    $sql = "INSERT INTO db_ikg.tbl_laporanharian (
    date_laporan ,
absensi_s1 ,
absensi_s2 ,
absensi_s3 ,
group_s1 ,
group_s2 ,
group_s3 ,
hadir_s1 ,
hadir_s2 ,
hadir_s3 ,
sakit_s1,
sakit_s2,
sakit_s3 ,
mangkir_s1 ,
mangkir_s2 ,
mangkir_s3 ,
cuti_s1 ,
cuti_s2 ,
cuti_s3 ,
libur_s1,
libur_s2,
libur_s3,
izin_s1 ,
izin_s2 ,
izin_s3 ,
masuk_kain_s1 ,
masuk_kain_s2 ,
masuk_kain_s3 ,
pembagian_kain_s1 ,
pembagian_kain_s2 ,
pembagian_kain_s3 ,
buka_kain_s1 ,
buka_kain_s2 ,
buka_kain_s3 ,
belah_kain_s1 ,
belah_kain_s2 ,
belah_kain_s3 ,
penyusunan_s1 ,
penyusunan_s2 ,
penyusunan_s3 ,
masalah_s1 ,
masalah_s2 ,
masalah_s3 ,
terimakains1 ,
terimakains2 ,
terimakains3 ,
inspeksis1 ,
inspeksis2 ,
inspeksis3 ,
bagikains1 ,
bagikains2 ,
bagikains3 ,
bukakains1 ,
bukakains2 ,
bukakains3 ,
leader_s1 ,
leader_s2 ,
leader_s3 ,
mc_buka_s1 ,
mc_buka_s2 ,
mc_buka_s3 ,
mc_balik_s1 ,
mc_balik_s2 ,
mc_balik_s3 ,
mc_belah_s1 ,
mc_belah_s2 ,
mc_belah_s3 ,
jahit_pinggir_s1 ,
jahit_pinggir_s2 ,
jahit_pinggir_s3 ,
masalah_hadir_s1 ,
masalah_hadir_s2 ,
masalah_hadir_s3 ,
created_at ,
created_by ,
last_updated_at,
last_updated_by ,
[status]) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,
?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $stmt = sqlsrv_prepare($con, $sql, $data);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }
    $result = sqlsrv_execute($stmt);

    if ($result === false) {
        die(print_r(sqlsrv_errors(), true));
    }
    // sqlsrv_query($con,$sql);
$response = array(
        'session' => 'LIB_SUCCSS',
        'data' => 'data baru berhasil di tambahkan'
    );
} else {
    $sql = "UPDATE db_ikg.tbl_laporanharian SET
    absensi_s1 = ?,
    absensi_s2 = ?,
    absensi_s3 = ?,
    group_s1 = ?,
    group_s2 = ?,
    group_s3 = ?,
    hadir_s1 = ?,
    hadir_s2 = ?,
    hadir_s3 = ?,
    sakit_s1 = ?,
    sakit_s2 = ?,
    sakit_s3 = ?,
    mangkir_s1 = ?,
    mangkir_s2 = ?,
    mangkir_s3 = ?,
    cuti_s1 = ?,
    cuti_s2 = ?,
    cuti_s3 = ?,
    libur_s1 = ?,
    libur_s2 = ?,
    libur_s3 = ?,
    izin_s1 = ?,
    izin_s2 = ?,
    izin_s3 = ?,
    masuk_kain_s1 = ?,
    masuk_kain_s2 = ?,
    masuk_kain_s3 = ?,
    pembagian_kain_s1 = ?,
    pembagian_kain_s2 = ?,
    pembagian_kain_s3 = ?,
    buka_kain_s1 = ?,
    buka_kain_s2 = ?,
    buka_kain_s3 = ?,
    belah_kain_s1 = ?,
    belah_kain_s2 = ?,
    belah_kain_s3 = ?,
    penyusunan_s1 = ?,
    penyusunan_s2 = ?,
    penyusunan_s3 = ?,
    masalah_s1 = ?,
    masalah_s2 = ?,
    masalah_s3 = ?,
    terimakains1 = ?,
    terimakains2 = ?,
    terimakains3 = ?,
    inspeksis1 = ?,
    inspeksis2 = ?,
    inspeksis3 = ?,
    bagikains1 = ?,
    bagikains2 = ?,
    bagikains3 = ?,
    bukakains1 = ?,
    bukakains2 = ?,
    bukakains3 = ?,
    leader_s1 = ?,
    leader_s2 = ?,
    leader_s3 = ?,
    mc_buka_s1 = ?,
    mc_buka_s2 = ?,
    mc_buka_s3 = ?,
    mc_balik_s1 = ?,
    mc_balik_s2 = ?,
    mc_balik_s3 = ?,
    mc_belah_s1 = ?,
    mc_belah_s2 = ?,
    mc_belah_s3 = ?,
    jahit_pinggir_s1 = ?,
    jahit_pinggir_s2 = ?,
    jahit_pinggir_s3 = ?,
    masalah_hadir_s1 = ?,
    masalah_hadir_s2 = ?,
    masalah_hadir_s3 = ?,
    created_at = ?,
    created_by = ?,
    last_updated_at = ?,
    last_updated_by = ?,
    status = 'COMPLETED' 
WHERE date_laporan = ?";

    // Siapkan parameter
    $params = [
        $_POST['absensi_s1'],
        $_POST['absensi_s2'],
        $_POST['absensi_s3'],
        $_POST['group_s1'],
        $_POST['group_s2'],
        $_POST['group_s3'],
        $_POST['hadir_s1'],
        $_POST['hadir_s2'],
        $_POST['hadir_s3'],
        $_POST['sakit_s1'],
        $_POST['sakit_s2'],
        $_POST['sakit_s3'],
        $_POST['mangkir_s1'],
        $_POST['mangkir_s2'],
        $_POST['mangkir_s3'],
        $_POST['cuti_s1'],
        $_POST['cuti_s2'],
        $_POST['cuti_s3'],
        $_POST['libur_s1'],
        $_POST['libur_s2'],
        $_POST['libur_s3'],
        $_POST['izin_s1'],
        $_POST['izin_s2'],
        $_POST['izin_s3'],
        $masuk_kain_s1,
        $masuk_kain_s2,
        $masuk_kain_s3,
        $pembagian_kain_s1,
        $pembagian_kain_s2,
        $pembagian_kain_s3,
        $buka_kain_s1,
        $buka_kain_s2,
        $buka_kain_s3,
        $belah_kain_s1,
        $belah_kain_s2,
        $belah_kain_s3,
        $penyusunan_s1,
        $penyusunan_s2,
        $penyusunan_s3,
        $masalah_s1,
        $masalah_s2,
        $masalah_s3,
        $_POST['terima_kain_s1'],
        $_POST['terima_kain_s2'],
        $_POST['terima_kain_s3'],
        $_POST['inspeksi_s1'],
        $_POST['inspeksi_s2'],
        $_POST['inspeksi_s3'],
        $_POST['bagi_kain_s1'],
        $_POST['bagi_kain_s2'],
        $_POST['bagi_kain_s3'],
        $_POST['bukakains1'],
        $_POST['bukakains2'],
        $_POST['bukakains3'],
        $_POST['leader_s1'],
        $_POST['leader_s2'],
        $_POST['leader_s3'],
        $_POST['mc_bk_s1'],
        $_POST['mc_bk_s2'],
        $_POST['mc_bk_s3'],
        $_POST['mc_blk_s1'],
        $_POST['mc_blk_s2'],
        $_POST['mc_blk_s3'],
        $_POST['mc_blh_s1'],
        $_POST['mc_blh_s2'],
        $_POST['mc_blh_s3'],
        $_POST['mc_jhtpgr_s1'],
        $_POST['mc_jhtpgr_s2'],
        $_POST['mc_jhtpgr_s3'],
        $_POST['masalah_hadir_s1'],
        $_POST['masalah_hadir_s2'],
        $_POST['masalah_hadir_s3'],
        $time,
        $_SESSION['usridGkg'],
        $time,
        $_SESSION['usridGkg'],
        $_POST['tgl_laporan'],
    ];

    // Eksekusi query
    $stmt = sqlsrv_query($con, $sql, $params);

    if ($stmt === false) {
        // Tangani error
        die(print_r(sqlsrv_errors(), true));
    }
    $response = array(
        'session' => 'LIB_SUCCSS',
        'data' => 'data berhasil di update'
    );
}

echo json_encode($response);
