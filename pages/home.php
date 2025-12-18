<?php
ini_set("error_reporting", 1);
session_start();
include "koneksi.php";
include "dashboard_function.php";
include "utils/helper.php";
?>

<!DOCTYPE html>
<html dir="ltr" lang="en-US">

<head>
    <meta charset="utf-8">
    <!-- <meta http-equiv="refresh" content="180"> -->
    <title>Home</title>
    <meta name="viewport" content="initial-scale = 1.0, maximum-scale = 1.0, user-scalable = no, width = device-width">
    <script src="plugins/highcharts/code/highcharts.js"></script>
    <script src="plugins/highcharts/code/highcharts-3d.js"></script>
    <script src="plugins/highcharts/code/modules/exporting.js"></script>
    <script src="plugins/highcharts/code/modules/export-data.js"></script>
    <style type="text/css">
        .highcharts-figure,
        .highcharts-data-table table {
            min-width: 310px;
            max-width: 800px;
            margin: 1em auto;
        }

        #container {
            height: 450px;
        }

        .highcharts-data-table table {
            font-family: Verdana, sans-serif;
            border-collapse: collapse;
            border: 1px solid #EBEBEB;
            margin: 10px auto;
            text-align: center;
            width: 100%;
            max-width: 500px;
        }

        .highcharts-data-table caption {
            padding: 1em 0;
            font-size: 1.2em;
            color: #555;
        }

        .highcharts-data-table th {
            font-weight: 600;
            padding: 0.5em;
        }

        .highcharts-data-table td,
        .highcharts-data-table th,
        .highcharts-data-table caption {
            padding: 0.5em;
        }

        .highcharts-data-table thead tr,
        .highcharts-data-table tr:nth-child(even) {
            background: #f8f8f8;
        }

        .highcharts-data-table tr:hover {
            background: #f1f7ff;
        }
    </style>
</head>

<?php
    $first_month = date('Y-m-01');
    $end_month = date('Y-m-t');
    $first_month_next = date('Y-m-01', strtotime('first day of next month'));

    function normalizeDate($tanggal){
        if ($tanggal instanceof DateTime) {
            return $tanggal->format('Y-m-d H:i:s');
        }
        if (is_array($tanggal) && isset($tanggal['date'])) {
            return date('Y-m-d H:i:s', strtotime($tanggal['date']));
        }
        if (is_string($tanggal)) {
            return $tanggal;
        }
        return null;
    }

    function getProductionBase(): int{
            $now = time();
            $today07 = strtotime('today 07:00');
            if ($now < $today07) {
                return strtotime('yesterday 07:00');
            }
            return $today07;
    }

    function summary_perday(array $data): array {
        $start = getProductionBase();
        $end = strtotime('+1 day', $start);
        $summary = [];

        foreach ($data as $d) {
            $tanggalStr = normalizeDate($d['Tanggal']);
            if (!$tanggalStr) continue;
            $tgl = strtotime($tanggalStr);

            if ($tgl >= $start && $tgl < $end) {
                $op = $d['Operator'];
                if (!isset($summary[$op])) {
                    $summary[$op] = [
                        'Operator' => $op,
                        'Tanggal' => date('Y-m-d', $start),
                        'Total_Roll' => 0,
                        'Total_Bagi_Kain' => 0,
                        'Jumlah_Order' => 0
                    ];
                }
                $summary[$op]['Total_Roll'] += $d['Jml_Roll'];
                $summary[$op]['Total_Bagi_Kain'] += $d['Bagi_Kain'];
                $summary[$op]['Jumlah_Order']++;
            }
        }

        return array_values($summary);
    }

    function summary_pershift(array $data): array {
        $base = getProductionBase();
        $summary = [];
        $shifts = [
            'Shift 1' => ['start' => $base, 'end' => strtotime('+8 hours', $base)], // 07:00–15:00
            'Shift 2' => ['start' => strtotime('+8 hours', $base), 'end' => strtotime('+15 hours', $base)], // 15:00–22:00
            'Shift 3' => ['start' => strtotime('+15 hours', $base), 'end' => strtotime('+24 hours', $base)], // 22:00–07:00 (besok)
        ];
        $ordered_shifts = ['Shift 1', 'Shift 2', 'Shift 3'];

        foreach ($ordered_shifts as $shift_name) {
            $range = $shifts[$shift_name];
            foreach ($data as $d) {
                $tanggalStr = normalizeDate($d['Tanggal']);
                if (!$tanggalStr) continue;
                $tgl = strtotime($tanggalStr);

                if ($tgl >= $range['start'] && $tgl < $range['end']) {
                    $op = $d['Operator'];
                    if (!isset($summary[$shift_name][$op])) {
                        $summary[$shift_name][$op] = [
                            'Operator' => $op,
                            'Shift' => $shift_name,
                            'Tanggal' => date('Y-m-d', $base),
                            'Total_Roll' => 0,
                            'Total_Bagi_Kain' => 0,
                            'Jumlah_Order' => 0
                        ];
                    }
                    $summary[$shift_name][$op]['Total_Roll'] += $d['Jml_Roll'];
                    $summary[$shift_name][$op]['Total_Bagi_Kain'] += $d['Bagi_Kain'];
                    $summary[$shift_name][$op]['Jumlah_Order']++;
                }
            }
        }

        $result = [];
        foreach ($ordered_shifts as $shift_name) {
            if (!empty($summary[$shift_name])) {
                foreach ($summary[$shift_name] as $row) {
                    $result[] = $row;
                }
            }
        }
        return $result;
    }

    function summary_tidakfull(array $data): array {
        $base = getProductionBase();
        $summary = [];
        $shifts = [
            'Shift 1 (Tidak Full)' => ['start' => $base, 'end' => strtotime('+4 hours', $base)],      
            'Shift 2 (Tidak Full)' => ['start' => strtotime('+8 hours', $base), 'end' => strtotime('+13 hours', $base)], 
            'Shift 3 (Tidak Full)' => ['start' => strtotime('+15 hours', $base), 'end' => strtotime('+22 hours', $base)],
        ];
        $ordered_shifts = ['Shift 1 (Tidak Full)', 'Shift 2 (Tidak Full)', 'Shift 3 (Tidak Full)'];

        foreach ($ordered_shifts as $shift_name) {
            $range = $shifts[$shift_name];
            foreach ($data as $d) {
                $tanggalStr = normalizeDate($d['Tanggal']);
                if (!$tanggalStr) continue;
                $tgl = strtotime($tanggalStr);

                if ($tgl >= $range['start'] && $tgl < $range['end']) {
                    $op = $d['Operator'];
                    if (!isset($summary[$shift_name][$op])) {
                        $summary[$shift_name][$op] = [
                            'Operator' => $op,
                            'Shift' => $shift_name,
                            'Tanggal' => date('Y-m-d', $base),
                            'Total_Roll' => 0,
                            'Total_Bagi_Kain' => 0,
                            'Jumlah_Order' => 0
                        ];
                    }
                    $summary[$shift_name][$op]['Total_Roll'] += $d['Jml_Roll'];
                    $summary[$shift_name][$op]['Total_Bagi_Kain'] += $d['Bagi_Kain'];
                    $summary[$shift_name][$op]['Jumlah_Order']++;
                }
            }
        }

        $result = [];
        foreach ($ordered_shifts as $shift_name) {
            if (!empty($summary[$shift_name])) {
                foreach ($summary[$shift_name] as $row) {
                    $result[] = $row;
                }
            }
        }
        return $result;
    }

    function summary_perbulan(array $data): array {
        date_default_timezone_set('Asia/Jakarta');

        $start = strtotime(date('Y-m-01 07:00:00'));
        $end = strtotime(date('Y-m-01 07:00:00', strtotime('first day of next month')));

        $summary = [];

        foreach ($data as $d) {
            $tanggalStr = normalizeDate($d['Tanggal']);
            if (!$tanggalStr) continue;
            $tgl = strtotime($tanggalStr);

            if ($tgl >= $start && $tgl < $end) {
                $op = $d['Operator'];
                if (!isset($summary[$op])) {
                    $summary[$op] = [
                        'Operator' => $op,
                        'Periode' => date('F Y', $start),
                        'Total_Roll' => 0,
                        'Total_Bagi_Kain' => 0,
                        'Jumlah_Order' => 0
                    ];
                }
                $summary[$op]['Total_Roll'] += $d['Jml_Roll'];
                $summary[$op]['Total_Bagi_Kain'] += $d['Bagi_Kain'];
                $summary[$op]['Jumlah_Order']++;
            }
        }

        return array_values($summary);
    }

    function summary_total_produksi(array $data): array {
        $base = getProductionBase();

        $today_start = $base;
        $today_end   = strtotime('+1 day', $today_start);

        $yesterday_start = strtotime('-1 day', $today_start);
        $yesterday_end   = $today_start;

        $total = [
            'Total_Berat_Hari_Ini' => 0,
            'Total_Roll_Hari_Ini'  => 0,
            'Total_Berat_Kemarin'  => 0,
            'Total_Roll_Kemarin'   => 0
        ];

        foreach ($data as $d) {
            $tanggalStr = normalizeDate($d['Tanggal']);
            if (!$tanggalStr) continue;
            $tgl = strtotime($tanggalStr);

            if ($tgl >= $today_start && $tgl < $today_end) {
                $total['Total_Berat_Hari_Ini'] += (float)($d['Bagi_Kain'] ?? 0);
                $total['Total_Roll_Hari_Ini']  += (int)($d['Jml_Roll'] ?? 0);
            }

            if ($tgl >= $yesterday_start && $tgl < $yesterday_end) {
                $total['Total_Berat_Kemarin'] += (float)($d['Bagi_Kain'] ?? 0);
                $total['Total_Roll_Kemarin']  += (int)($d['Jml_Roll'] ?? 0);
            }
        }

        $total['Periode_Hari_Ini'] = date('Y-m-d', $today_start) . ' s/d ' . date('Y-m-d', $today_end);
        $total['Periode_Kemarin']  = date('Y-m-d', $yesterday_start) . ' s/d ' . date('Y-m-d', $yesterday_end);

        return $total;
    }

    $data = [];
    $query_tenseries = sqlsrv_query($con,"SELECT
                                            *
                                        FROM
                                            db_ikg.tbl_sync_greige t
                                        WHERE
                                            t.TANGGAL BETWEEN '$first_month 07:00:00' AND '$first_month_next 07:00:00'");
        while ($ten = sqlsrv_fetch_array($query_tenseries)) {
                $data[] = [
                                'Id' => $ten['ABSUNIQUEID'],
                                'Operator' => $ten['OPERATOR'],
                                'Production_Order' => $ten['PRODUCTIONORDER'],
                                'Jml_Roll' => $ten['ROLL'],
                                'Bagi_Kain' => $ten['BAGIKAIN'],
                                'Tanggal' => $ten['TANGGAL']
                            ];
        }
        $summary_perday = summary_perday($data);
        $summary_pershift = summary_pershift($data);
        $summary_tidakfull = summary_tidakfull($data);
        $summary_perbulan = summary_perbulan($data);
        $rekap = summary_total_produksi($data);
        $eleven_2dayago = date('Y-m-d', strtotime("-2 days")) . ' 07:00';
        $eleven_ystrdy = date('Y-m-d', strtotime("-1 days")) . ' 07:00';
        $eleven_today = date('Y-m-d') . ' 07:00';

$todayQty = sqlsrv_query($con,"SELECT sum(bruto) as sumqty from db_ikg.tbl_schedule where CAST(tgl_update as DATETIME) >= '$eleven_ystrdy' AND CAST(tgl_update as DATETIME) <= '$eleven_today'");
$TodayRoll = sqlsrv_query($con,"SELECT sum(rol) as sumrol from db_ikg.tbl_schedule where CAST(tgl_update as DATETIME) >= '$eleven_ystrdy' AND CAST(tgl_update as DATETIME) <= '$eleven_today'");
$YstrdyQty = sqlsrv_query($con,"SELECT sum(bruto) as sumqty from db_ikg.tbl_schedule where CAST(tgl_update as DATETIME) >= '$eleven_2dayago' AND CAST(tgl_update as DATETIME) <= '$eleven_ystrdy'");
$YstrdyRoll = sqlsrv_query($con,"SELECT sum(rol) as sumrol from db_ikg.tbl_schedule where CAST(tgl_update as DATETIME) >= '$eleven_2dayago' AND CAST(tgl_update as DATETIME) <= '$eleven_ystrdy'");
?>

<body>
    <div class="row">
        <?php $sql_ann = sqlsrv_query($con,"SELECT * from db_ikg.announcement where id = 1");
        $ann = sqlsrv_fetch_array($sql_ann); ?>
        <?php if ($ann['is_active'] == 1) : ?>
            <div class="alert" role="alert" style="background-color: #214d66;">
                <marquee style="color: white; font-style: italic; font-weight: bold;">
                    <h4> <?php echo $ann['ann'] ?> </h4>
                </marquee>
            </div>
        <?php endif; ?>
    </div>
    <div class="row">
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-aqua">
                <div class="inner">
                    <h3><?php
                       echo number_format($rekap['Total_Berat_Hari_Ini'],2);
                        ?> Kg</h3>
                    <p>TOTAL BERAT PRODUKSI HARI INI</p>
                </div>
                <div class="icon">
                    <i class="fa fa-shopping-basket" aria-hidden="true"></i>
                </div>
                <a href="#" class="small-box-footer">
                    <?php echo date('d-F-Y') . ' 07:00'; ?> <strong> &nbsp;&nbsp; S/D &nbsp;&nbsp; </strong> <?php echo  date('d-F-Y', strtotime("+1 days")) . ' 07:00'; ?> <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-green">
                <div class="inner">
                    <h3><?php
                       echo number_format($rekap['Total_Roll_Hari_Ini']);
                        ?> Rol</h3>

                    <p>TOTAL ROLL SELESAI HARI INI</p>
                </div>
                <div class="icon">
                    <i class="fa fa-sitemap" aria-hidden="true"></i>
                </div>
                <a href="#" class="small-box-footer">
                    <?php echo date('d-F-Y') . ' 07:00'; ?> <strong> &nbsp;&nbsp; S/D &nbsp;&nbsp; </strong> <?php echo  date('d-F-Y', strtotime("+1 days")) . ' 07:00'; ?> <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-yellow">
                <div class="inner">
                    <h3><?php
                        echo number_format($rekap['Total_Berat_Kemarin'],2);
                        ?> Kg</h3>

                    <p>TOTAL BERAT PRODUKSI KEMARIN</p>
                </div>
                <div class="icon">
                    <i class="fa fa-book" aria-hidden="true"></i>
                </div>
                <a href="#" class="small-box-footer">
                    <?php echo date('d-F-Y', strtotime("-1 days")) . ' 07:00'; ?> <strong> &nbsp;&nbsp; S/D &nbsp;&nbsp; </strong> <?php echo  date('d-F-Y') . ' 07:00'; ?> <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-red">
                <div class="inner">
                    <h3><?php
                       echo number_format($rekap['Total_Roll_Kemarin']);
                        ?> Rol</h3>
                    <p>TOTAL ROLL SELESAI KEMARIN</p>
                </div>
                <div class="icon">
                    <i class="fa fa-yelp" aria-hidden="true"></i>
                </div>
                <a href="#" class="small-box-footer">
                    <?php echo date('d-F-Y', strtotime("-1 days")) . ' 07:00'; ?> <strong> &nbsp;&nbsp; S/D &nbsp;&nbsp; </strong> <?php echo  date('d-F-Y') . ' 07:00'; ?> <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <!-- ./col -->
    </div>
    <div class="row">
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-aqua">A</span>
                <div class="info-box-content">
                    <span class="info-box-text"><?php echo date('d-F-Y', strtotime("-1 days")) . ' 07:00'; ?><br /><?php echo  date('d-F-Y') . ' 07:00'; ?></span>
                    <span class="info-box-number">
                        <?php $sql_a = sqlsrv_query($con,"SELECT sum(bruto) as sumqty from db_ikg.tbl_schedule where CAST(tgl_update AS DATETIME) >= '$eleven_ystrdy' AND CAST(tgl_update AS DATETIME) <= '$eleven_today' and g_shift = 'A'");
                        $rslt_a = sqlsrv_fetch_array($sql_a);
                        ?>
                        <h4 style="font-weight: bold;"><?php echo number_format($rslt_a['sumqty'] ?? 0.0, 2, ',', '.') ?> KG</h4>
                    </span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-red">B</span>

                <div class="info-box-content">
                    <span class="info-box-text"><?php echo date('d-F-Y', strtotime("-1 days")) . ' 07:00'; ?><br /><?php echo  date('d-F-Y') . ' 07:00'; ?></span>
                    <span class="info-box-number">
                        <?php $sql_b = sqlsrv_query($con,"SELECT sum(bruto) as sumqty from db_ikg.tbl_schedule where CAST(tgl_update AS DATETIME) >= '$eleven_ystrdy' AND CAST(tgl_update AS DATETIME) <= '$eleven_today' and g_shift = 'B'");
                        $rslt_b = sqlsrv_fetch_array($sql_b);
                        ?>
                        <h4 style="font-weight: bold;"><?php echo number_format($rslt_b['sumqty'] ?? 0.0, 2, ',', '.') ?> KG</h4>
                    </span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-yellow">C</span>

                <div class="info-box-content">
                    <span class="info-box-text"><?php echo date('d-F-Y', strtotime("-1 days")) . ' 07:00'; ?><br /><?php echo  date('d-F-Y') . ' 07:00'; ?></span>
                    <span class="info-box-number">
                        <?php $sql_c = sqlsrv_query($con,"SELECT sum(bruto) as sumqty from db_ikg.tbl_schedule where CAST(tgl_update AS DATETIME) >= '$eleven_ystrdy' AND CAST(tgl_update AS DATETIME) <= '$eleven_today' and g_shift = 'C'");
                        $rslt_c = sqlsrv_fetch_array($sql_c);
                        ?>
                        <h4 style="font-weight: bold;"><?php echo number_format($rslt_c['sumqty'] ?? 0.0, 2, ',', '.') ?> KG</h4>
                    </span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12">
            <?php
            $s = date('Y-m-d') . ' 07:00';
            $e = date('Y-m-d') . ' 15:00';
            $ee = date('Y-m-d') . ' 23:00';
            $sql_shift1 = sqlsrv_query($con,"SELECT 
                                                            sum(bruto) as totqty, 
                                                            count(g_shift) as [count]
                                                            -- , g_shift 
                                                            FROM db_ikg.tbl_schedule where CAST(tgl_update AS DATETIME) >= '$s' 
                                                            AND CAST(tgl_update AS DATETIME) <= '$e' 
                                                                order by [count] desc");
            $sql_shift2 = sqlsrv_query($con,"SELECT 
                                                            sum(bruto) as totqty, 
                                                            count(g_shift) as [count]
                                                            -- , g_shift 
                                                            FROM db_ikg.tbl_schedule where CAST(tgl_update AS DATETIME) >= '$e' 
                                                            AND CAST(tgl_update AS DATETIME) <= '$ee' 
                                                                order by [count] desc");
            $shift1 = sqlsrv_fetch_array($sql_shift1);
            $shift2 = sqlsrv_fetch_array($sql_shift2);
            ?>
            <div class="info-box bg-green">
                <span class="info-box-icon"><i class="fa fa-line-chart"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text" style="font-style: italic;">HASIL BUKA KAIN HARI INI SHIFT 1 & 2</span>
                    <span class="info-box-number">SHIFT 1-<?php echo $shift1['g_shift'] . ' : ' . number_format($shift1['totqty'] ?? 0.0, 2, ',', '.'); ?> KG</span>
                    <div class="progress">
                        <div class="progress-bar" style="width: 80%"></div>
                    </div>
                    <span class="info-box-number">SHIFT 2-<?php echo $shift2['g_shift'] . ' : ' . number_format($shift2['totqty'] ?? 0.0, 2, ',', '.'); ?> KG</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div id="container">
                <!-- Chart Here ! -->
            </div>
        </div>
    </div>
    <div class="row" style="margin-top: 20px;">
        <div class="container col-md-7">
            <div class="box box-primary">
                <div class="box-header ui-sortable-handle" style="cursor: move;">
                    <i class="fa fa-calendar"></i>
                    <h3 class="box-title">LAPORAN BULANAN GKG</h3>
                </div>
                <div class="box-body">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>BULAN</th>
                                <th>MASUK KAIN</th>
                                <th>NCP</th>
                                <th>BAGI KAIN</th>
                                <th>% DELAY</th>
                                <th>BUKA KAIN</th>
                                <th>% DELAY</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php for ($x = 1; $x <= 12; $x++) {
                                $month = date('Y-m', strtotime(date('Y-') . $x));
                                $sql_permonth = sqlsrv_query($con,"SELECT FORMAT(CAST(date_laporan as DATE),'yyyy-MM') as bulan, sum(masuk_kain_s3) as masuk_kain, sum(pembagian_kain_s3) as pembagian_kain, sum(buka_kain_s1 + buka_kain_s2 + buka_kain_s3) as buka_kain
                                FROM db_ikg.tbl_laporanharian
                                where  FORMAT(CAST(date_laporan as DATE),'yyyy-MM') = '$month'
                                group by FORMAT(CAST(date_laporan as DATE),'yyyy-MM') 
                                order by FORMAT(CAST(date_laporan as DATE),'yyyy-MM')");
                                $data = sqlsrv_fetch_array($sql_permonth);
                            ?>
                                <tr>
                                    <td style="text-align: center;"><?php echo $month ?></td>
                                    <td><?php echo number_format($data['masuk_kain'] ?? 0.0, 2, ',', '.') ?> Kg</td>
                                    <td>NCP</td>
                                    <td><?php echo number_format($data['pembagian_kain'] ?? 0.0, 2, ',', '.') ?> Kg</td>
                                    <td>0</td>
                                    <td><?php echo number_format($data['buka_kain'] ?? 0.0, 2, ',', '.') ?> Kg</td>
                                    <td>0</td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <div class="box-footer clearfix no-border"></div>
            </div>
        </div>
        <div class="container col-md-5">
            <div class="box box-danger">
                <div class="box-header ui-sortable-handle" style="cursor: move;">
                    <i class="fa fa-list-alt"></i>
                    <h3 class="box-title">RINCIAN GKG PERHARI BULAN INI</h3>
                </div>
                <?php
                $start = date('Y-m-01');
                $end = date('Y-m-t');
                $sql_PerDay = sqlsrv_query($con,"SELECT date_laporan, masuk_kain_s3, pembagian_kain_s3, (buka_kain_s1 + buka_kain_s2 + buka_kain_s3) as buka_kain
                FROM db_ikg.tbl_laporanharian
                where  CAST(date_laporan AS DATE) >= '$start' 
                AND CAST(date_laporan AS DATE) <= '$end'
                order by date_laporan");
                // var_dump($start);
                // var_dump($end);
                ?>
                <div class="box-body">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>TANGGAL</th>
                                <th>MASUK KAIN</th>
                                <th>BAGI KAIN</th>
                                <th>BUKA KAIN</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($li = sqlsrv_fetch_array($sql_PerDay)) : ?>
                                <tr>
                                    <td><?php echo date('d F Y', strtotime($li['date_laporan'])) ?></td>
                                    <td><?php echo number_format($li['masuk_kain_s3'], 2, ',', '.') ?></td>
                                    <td><?php echo number_format($li['pembagian_kain_s3'], 2, ',', '.') ?></td>
                                    <td><?php echo number_format($li['buka_kain'], 2, ',', '.') ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                        <tfoot>
                            <tr class="bg-info">
                                <th colspan="4" class="text-center"><i class="fa fa-exclamation-triangle text-danger" aria-hidden="true"></i>
                                    URUTAN TERAKHIR DI TABLE INI MERUPAKAN DATA SEMENTARA </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="box-footer clearfix no-border"></div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="container col-md-4">
            <div class="box box-success">
                <div class="box-header ui-sortable-handle" style="cursor: move;">
                    <h4><i class="fa fa-dashboard"></i> Output Harian Operator Buka Kain <?php echo !empty($summary_perday[0]['Tanggal']) ? date('d F Y', strtotime($summary_perday[0]['Tanggal'])) : '' ?></h4>
                </div>
                <div class="box-body">
                    <table class="table table-bordered" id="Table1">
                        <thead>
                            <tr class="bg-danger">
                                <th>#</th>
                                <th>Operator</th>
                                <th>Jumlah KK</th>
                                <th>Jumlah Roll</th>
                                <th>Jumlah Kg</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $monthNow = date('Y-m');
                            ?>
                            <?php foreach($summary_perday as $ten):?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo $ten['Operator'] ?></td>
                                    <td align="center"><?php echo $ten['Jumlah_Order'] ?></td>
                                    <td align="center"><?php echo $ten['Total_Roll'] ?> Rol</td>
                                    <td align="right"><?php echo number_format($ten['Total_Bagi_Kain'],2) ?> Kg</td>
                                </tr>
                            <?php endforeach;?>
                        </tbody>
                    </table>
                </div>
                <div class="box-footer clearfix no-border"></div>
            </div>
        </div>
        <div class="container col-md-4">
            <div class="box box-success">
                <div class="box-header ui-sortable-handle" style="cursor: move;">
                    <h4><i class="fa fa-dashboard"></i> Output Harian Per Shift Operator Buka Kain <?php echo !empty($summary_perday[0]['Tanggal']) ? date('d F Y', strtotime($summary_perday[0]['Tanggal'])) : '' ?></h4>
                </div>
                <div class="box-body">
                    <table class="table table-bordered" id="Table1">
                        <thead>
                            <tr class="bg-danger">
                                <th>#</th>
                                <th>Operator</th>
                                <th>Shift</th>
                                <th>Jumlah KK</th>
                                <th>Jumlah Roll</th>
                                <th>Jumlah Kg</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $hariSekarang = date('N');

                            if ($hariSekarang >= 1 && $hariSekarang <= 5):
                                foreach ($summary_pershift as $ten):
                            ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo $ten['Operator']; ?></td>
                                    <td align="center"><?php echo $ten['Shift']; ?></td>
                                    <td align="center"><?php echo $ten['Jumlah_Order']; ?></td>
                                    <td align="center"><?php echo $ten['Total_Roll']; ?> Rol</td>
                                    <td align="right"><?php echo number_format($ten['Total_Bagi_Kain'], 2); ?> Kg</td>
                                </tr>
                            <?php
                                endforeach;
                            else:
                            ?>
                                <tr>
                                    <td colspan="6" align="center" style="color:red;font-weight:bold;">
                                        Hanya Muncul Di Weekdays (Senin - Jumat)
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="box-footer clearfix no-border"></div>
            </div>
        </div>
        <div class="container col-md-4">
            <div class="box box-warning">
                <div class="box-header ui-sortable-handle" style="cursor: move;">
                    <h4><i class="fa fa-sticky-note-o" aria-hidden="true"></i> Output Harian Shift Tidak Full Operator Buka Kain <?php echo !empty($summary_perday[0]['Tanggal']) ? date('d F Y', strtotime($summary_perday[0]['Tanggal'])) : '' ?></h4>
                </div>
                <div class="box-body">
                    <table class="table table-bordered" id="Table1">
                        <thead>
                            <tr class="bg-danger">
                                <th>#</th>
                                <th>Operator</th>
                                <th>Shift</th>
                                <th>Jumlah KK</th>
                                <th>Jumlah Roll</th>
                                <th>Jumlah Kg</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $hariSekarang = date('N');

                            if ($hariSekarang > 5):
                                foreach($summary_tidakfull as $ten):
                            ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo $ten['Operator']; ?></td>
                                    <td align="center"><?php echo $ten['Shift']; ?></td>
                                    <td align="center"><?php echo $ten['Jumlah_Order']; ?></td>
                                    <td align="right"><?php echo $ten['Total_Roll']; ?> Rol</td>
                                    <td align="right"><?php echo number_format($ten['Total_Bagi_Kain'], 2); ?> Kg</td>
                                </tr>
                            <?php
                                endforeach;
                            else:
                            ?>
                                <tr>
                                    <td colspan="6" align="center" style="color:red;font-weight:bold;">
                                        Hanya Muncul Di Hari Sabtu
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="box-footer clearfix no-border"></div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="container col-md-6">
            <div class="box box-success">
                <div class="box-header ui-sortable-handle" style="cursor: move;">
                    <h4><i class="fa fa-dashboard"></i> Output Bulanan Operator Buka Kain <?php echo date('F Y') ?></h4>
                </div>
                <div class="box-body">
                    <table class="table table-bordered" id="Table1">
                        <thead>
                            <tr class="bg-danger">
                                <th>#</th>
                                <th>Operator</th>
                                <th>Jumlah KK</th>
                                <th>Jumlah Roll</th>
                                <th>Jumlah Kg</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $monthNow = date('Y-m');
                           foreach($summary_perbulan as $np):
                            ?>
                                <tr>
                                    <td><?php echo $no++ ?></td>
                                    <td><?php echo $np['Operator'] ?></td>
                                    <td align='center'><?php echo $np['Jumlah_Order'] ?></td>
                                    <td align='center'><?php echo $np['Total_Roll'] ?></td>
                                    <td align='right'><?php echo number_format($np['Total_Bagi_Kain'],2) ?> Kg</td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    
        <div class="container col-md-6">
            <div class="box box-success">
                <div class="box-header ui-sortable-handle" style="cursor: move;">
                    <h4><i class="fa fa-dashboard"></i> Jumlah Buka kain dan Belah kain </h4>

                </div>
                <div class="box-body">
                    <table class="table table-bordered" id="Table1">
                        <thead>
                            <tr class="bg-danger">
                                <th>Tanggal</th>
                                <th>Jumlah KG</th>
                                <!-- <th>Jumlah Roll</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query_total_bukabelahkain = sqlsrv_query($con,"SELECT
                                                                                TOP 12 date_laporan,
                                                                                SUM(
                                                                                    belah_kain_s1 + belah_kain_s2 + belah_kain_s3 + buka_kain_s1 + buka_kain_s2 + buka_kain_s3
                                                                                ) AS total
                                                                            FROM
                                                                                db_ikg.tbl_laporanharian
                                                                            GROUP BY
                                                                                date_laporan
                                                                            ORDER BY
                                                                                date_laporan DESC;");
                            while ($tb = sqlsrv_fetch_array($query_total_bukabelahkain)) {
                            ?>
                                <tr>
                                    <td><?php echo cek($tb['date_laporan'],'Y-m-d') ?></td>
                                    <td><?php echo $tb['total'] ?></td>

                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
    </div>
</body>

</html>
<script type="text/javascript">
    $(document).ready(function() {
        setTimeout(function() {
            location.reload();
        }, 1200000);
    })
</script>
<script>
    $(document).ready(function() {
        Highcharts.chart('container', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Persentase Perbandingan Hasil Roll Selesai 14 Hari'
            },
            subtitle: {
                text: 'PT. Indo Taichen Textile Industry'
            },
            xAxis: {
                categories: <?php echo $data_hari ?>,
                crosshair: true
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Roll/(day)'
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>{point.y:.1f} roll</b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            series: [{
                name: 'Grpup A',
                data: <?php echo $groupA ?>,
                color: '#cc2323'

            }, {
                name: 'Group B',
                data: <?php echo $groupB ?>,
                color: '#29ba33'

            }, {
                name: 'Group C',
                data: <?php echo $groupC ?>,
                color: '#0066ff'

            }]
        });
    })
</script>