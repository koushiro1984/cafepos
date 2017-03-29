<?php

use backend\components\Tools;
use backend\models\Voting;

$this->title = 'Dashboard';
$this->params['breadcrumbs'][] = $this->title; 

$dataPenjualan = [];
foreach ($modelSaleInvoice as $dataSaleInvoice) {
        
    $dateSplit = explode('-', $dataSaleInvoice['date']);
    $key = $dateSplit[0] . '-' . $dateSplit[1] . '-' . '01';
    
    $dataPenjualan[$key]['bulan'] = $key;
    
    if (empty($dataPenjualan[$key]['total']))
        $dataPenjualan[$key]['total'] = 0;
    
    $jumlahSubtotal = 0;
    foreach ($dataSaleInvoice['saleInvoiceDetails'] as $dataSaleInvoiceDetail) {                        
        
        $subtotal = $dataSaleInvoiceDetail['harga'] * $dataSaleInvoiceDetail['jumlah'];
        $discount = 0;
        if ($dataSaleInvoiceDetail['discount_type'] == 'percent') {
            $discount = ($dataSaleInvoiceDetail['harga'] * $dataSaleInvoiceDetail['discount'] / 100) * $dataSaleInvoiceDetail['jumlah'];
            $subtotal = $subtotal - $discount;
        } elseif ($dataSaleInvoiceDetail['discount_type'] == 'value') {
            $discount = $dataSaleInvoiceDetail['discount'] * $dataSaleInvoiceDetail['jumlah']; 
            $subtotal = $subtotal - $discount;
        }
        
        $jumlahSubtotal += $subtotal;                
    }      
    
    $scp = Tools::hitungServiceChargePajak($jumlahSubtotal, $dataSaleInvoice['service_charge'], $dataSaleInvoice['pajak']);                                        
    $serviceCharge = $scp['serviceCharge'];
    $pajak = $scp['pajak']; 
    $grandTotal = $jumlahSubtotal + $serviceCharge + $pajak;
    
    $dataPenjualan[$key]['total'] += $grandTotal;
} ?>

<!-- Small boxes (Stat box) -->
<div class="row">
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-aqua">
            <div class="inner">
                <h3>
                    <?= !empty($sumPenjualan['jumlah']) ? $sumPenjualan['jumlah'] : '&nbsp;' ?>
                </h3>
                <p>
                    Penjualan Menu <?= date('Y') ?>
                </p>
            </div>
            <div class="icon">
                <i class="ion ion-pizza"></i>
            </div>
            <a href="#" class="small-box-footer">
                &nbsp;
            </a>
        </div>
    </div><!-- ./col -->
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-yellow">
            <div class="inner">
                <h3>
                    <?= Voting::getPoint(); ?> Point
                </h3>
                <p>
                    Tingkat Kepuasan &amp; Saran
                </p>
            </div>
            <div class="icon">
                <i class="ion ion-star"></i>
            </div>
            <a href="<?= Yii::$app->urlManager->createUrl('voting/display'); ?>" class="small-box-footer">
                More info <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div><!-- ./col -->
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-green">
            <div class="inner">
                <h3>
                    <?= !empty($jumlahTamu['jumlah_guest']) ? $jumlahTamu['jumlah_guest'] : '&nbsp;' ?>
                </h3>
                <p>
                    Pengunjung
                </p>
            </div>
            <div class="icon">
                <i class="ion ion-person-add"></i>
            </div>
            <a href="#" class="small-box-footer">
                &nbsp;
            </a>
        </div>
    </div><!-- ./col -->
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-red">
            <div class="inner">
                <h3>
                    <?= $countStokKritis['count'] ?>
                </h3>
                <p>
                    Stok Barang Kritis
                </p>
            </div>
            <div class="icon">
                <i class="ion ion-cube"></i>
            </div>
            <a href="<?= Yii::$app->urlManager->createUrl('stock/stock-kritis'); ?>" class="small-box-footer">
                More info <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div><!-- ./col -->
</div><!-- /.row -->

<div class="row">
    <!-- Left col -->
    <section class="col-lg-6">                            
        <div class="chart-container" id="chart-penjualan" style="position: relative; height: 400px;"></div>                                
    </section><!-- /.Left col -->
    <!-- right col (We are only adding the ID to make the widgets sortable)-->
    <section class="col-lg-6"> 
        <div class="chart-container" id="chart-menu" style="position: relative; height: 400px;"></div>                                
    </section><!-- right col -->
</div><!-- /.row (main row) -->

<?php

$penjualanBulan = '';
$penjualanValue = '';
foreach ($dataPenjualan as $value) {    
    $penjualanValue .= $value['total'] . ',';
    $penjualanBulan .= '"' . date("M", strtotime($value['bulan'])) . '",';
}

$topMenuItem = '';
$topMenuValue = '';

foreach ($topMenu as $key => $value) {    
    
    $temp = '';
    foreach ($value as $key2 => $value2) {
        $topMenuValue .= $value2['jumlah'] . ",";
        $temp .= '"' . $value2['nama_menu'] . '",'; 
        
        if ($key2 == 5) break;
    }    
    
    $topMenuItem .= '
        {
            name: "' . date("M", strtotime($key)) . '",
            categories: [' . $temp . ']
        },
    ';   
}

$this->params['regJsFile'][] = function() {
    $this->registerJsFile(Yii::getAlias('@common-web') . '/js/plugins/highchart/highcharts.js');
    $this->registerJsFile(Yii::getAlias('@common-web') . '/js/plugins/highchart/themes/dark-unica.js');
    $this->registerJsFile(Yii::getAlias('@common-web') . '/js/plugins/highchart/plugins/grouped-categories.js');
}; 

$jscript = '
    var chart_penjualan = new Highcharts.Chart({
        chart: {
            renderTo: "chart-penjualan",
            type: "line"
        },
        title: {
            text: "Penjualan Tahun ' . date('Y') . '"
        },
        legend: {
            enabled: false
        },
        yAxis: {
            min: 0,
            title: {
                text: "Nilai Penjualan (Rupiah)"
            }
        },
        series: [
            {
                name: "Nilai",
                data: [' . trim($penjualanValue, ',') . ']
            }
        ],
        plotOptions: {
            series: {
                borderWidth: 0,
                dataLabels: {
                    enabled: true,
                }
            }
        },
        colors: ["#F56954"],
        xAxis: {
            categories: [
                ' . trim($penjualanBulan, ',') . '
            ]
        },
        credits: {
            enabled: false
        }
    });
    
    var chart_menu = new Highcharts.Chart({
        chart: {
            renderTo: "chart-menu",
            type: "column",
        },
        title: {
            text: "Top Menu ' . date('Y') . '"
        },
        legend: {
            enabled: false
        },
        yAxis: {
            min: 0,
            title: {
                text: "Jumlah Penjualan"
            }
        },
        series: [{
            name: "Jumlah",
            data: [
                ' . $topMenuValue . '
            ]              
        }],
        plotOptions: {
            series: {
                borderWidth: 0,
                dataLabels: {
                    enabled: true,
                }
            }
        },
        xAxis: {
            categories: [
                ' . $topMenuItem . '
            ]
        },
        credits: {
            enabled: false
        }
    });   
'; 

$this->registerJs($jscript); ?>