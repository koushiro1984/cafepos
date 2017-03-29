<?php

use yii\helpers\Html;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use backend\models\User;
use backend\components\Tools;
use backend\components\PrinterDialog;
use backend\components\NotificationDialog;


/* @var $this yii\web\View */
/* @var $model backend\models\SaleInvoice */

$this->title = 'Laporan Faktur Kasir Harian';
$this->params['breadcrumbs'][] = $this->title; ?>

<div class="sale-invoice-report">

    <div class="row">
        <div class="col-sm-2"></div>
        <div class="col-sm-8">
            <div class="box box-danger">
                <div class="box-body">
                    <div class="sale-invoice-form">    
                        
                        <?= Html::beginForm() ?>                        

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-3">
                                        <label class="control-label">Tanggal</label>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="col-lg-8">
                                            <?= DatePicker::widget([
                                                    'name' => 'tanggalFrom',
                                                    'options' => ['id' => 'tanggalFrom'],
                                                    'pluginOptions' => Yii::$app->params['datepickerOptions'],
                                                ]); ?>
                                            
                                            &nbsp; &nbsp; s/d &nbsp; &nbsp;
                                            
                                            <?= DatePicker::widget([
                                                    'name' => 'tanggalTo',
                                                    'options' => ['id' => 'tanggalTo'],
                                                    'pluginOptions' => Yii::$app->params['datepickerOptions'],
                                                ]); ?>
                                        </div>
                                    </div>                                           
                                </div>
                            </div>
                        
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-3">
                                        <label class="control-label">Kasir</label>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="col-lg-12">
                                            <label><?= Html::checkbox('kasirAll', false, ['id' => 'kasirAll']) ?> ALL</label>
                                            <br>
                                            <?= Html::dropDownList('kasir', null, 
                                                ArrayHelper::map(
                                                    User::find()->andWhere(['not_active' => false])->all(), 
                                                    'id', 
                                                    function($data) { 
                                                        return '(' . $data->id . ') ' . $data->kdKaryawan->nama;                                 
                                                    }
                                                ),
                                                [
                                                    'id' => 'kasir',
                                                    'style' => 'width: 100%',
                                                    'prompt' => ''
                                                ]) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-3"></div>
                                    <div class="col-lg-6">
                                        <?php
                                        $icon = '<i class="fa fa-print"></i>&nbsp;&nbsp;&nbsp;';
                                        echo Html::submitButton($icon . 'Print', ['class' => 'btn btn-warning', 'name' => 'print', 'value' => 'print']); 
                                        echo '&nbsp;&nbsp;&nbsp;&nbsp;';
                                        
                                        $icon = '<i class="fa fa-file"></i>&nbsp;&nbsp;&nbsp;';
                                        echo Html::submitButton($icon . 'PDF', ['class' => 'btn btn-success', 'name' => 'print', 'value' => 'pdf']); 
                                        echo '&nbsp;&nbsp;&nbsp;&nbsp;';
                                        echo Html::submitButton($icon . 'Excel', ['class' => 'btn btn-primary', 'name' => 'print', 'value' => 'excel']); ?>

                                    </div>
                                </div>
                            </div>
                        
                        <?= Html::endForm() ?>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-2"></div>
    </div><!-- /.row -->
</div>

<?php

if (!empty($modelSaleInvoice)):
    $dataMenu = [];   
    $dataInvoice = [];

    $jumlahFaktur = 0;
    $jumlahDiskon = 0;
    $jumlahTotal = 0;
    $jumlahServiceCharge = 0;
    $jumlahPajak = 0;
    $jumlahRefund = 0;
    $jumlahRefundServiceCharge = 0;
    $jumlahRefundPajak = 0;
    $jumlahVoid = 0;
    $jumlahFreeMenu = 0;
    $jumlahGrandTotal = 0;
    $jumlahKembalian = 0;
    
    $dataPayment = [];
    $paymentJumlahTotal = 0;

    foreach ($modelSaleInvoice as $dataSaleInvoice) {                       

        $jumlahFaktur ++;
        $jumlahSubtotal = 0;
        $jumlahSubtotalDiskon = 0;
        $jumlahSubtotalRefund = 0;
        $jumlahSubtotalRefundServiceCharge = 0;
        $jumlahSubtotalRefundPajak = 0;
        $jumlahSubtotalVoid = 0;
        $jumlahSubtotalFreeMenu = 0;

        foreach ($dataSaleInvoice['saleInvoiceDetails'] as $dataSaleInvoiceDetail) {
            $keyMenu = $dataSaleInvoiceDetail['menu']['id'];

            $dataMenu[$keyMenu]['nama_menu'] = $dataSaleInvoiceDetail['menu']['nama_menu'];

            if (!empty($dataMenu[$keyMenu]['qty']))
                $dataMenu[$keyMenu]['qty'] += $dataSaleInvoiceDetail['jumlah'];
            else
                $dataMenu[$keyMenu]['qty'] = $dataSaleInvoiceDetail['jumlah'];


            $subtotal = $dataSaleInvoiceDetail['harga'] * $dataSaleInvoiceDetail['jumlah'];
            $discount = 0;
            if ($dataSaleInvoiceDetail['discount_type'] == 'percent') {
                $discount = ($dataSaleInvoiceDetail['harga'] * $dataSaleInvoiceDetail['discount'] / 100) * $dataSaleInvoiceDetail['jumlah'];
            } elseif ($dataSaleInvoiceDetail['discount_type'] == 'value') {
                $discount = $dataSaleInvoiceDetail['discount'] * $dataSaleInvoiceDetail['jumlah']; 
            }
            
            if ($dataSaleInvoiceDetail['is_void']) {
                $jumlahSubtotalVoid += $dataSaleInvoiceDetail['harga'] * $dataSaleInvoiceDetail['jumlah'];
                $jumlahVoid += $dataSaleInvoiceDetail['harga'] * $dataSaleInvoiceDetail['jumlah'];
            }
            
            if ($dataSaleInvoiceDetail['is_free_menu']) {
                $jumlahSubtotalFreeMenu += $dataSaleInvoiceDetail['harga'] * $dataSaleInvoiceDetail['jumlah'];
                $jumlahFreeMenu += $dataSaleInvoiceDetail['harga'] * $dataSaleInvoiceDetail['jumlah'];
            }
            
            if (!empty($dataMenu[$keyMenu]['subtotal']))
                $dataMenu[$keyMenu]['subtotal'] += $subtotal;
            else
                $dataMenu[$keyMenu]['subtotal'] = $subtotal;  


            $subtotalRefund = $dataSaleInvoiceDetail['returSale']['harga'] * $dataSaleInvoiceDetail['returSale']['jumlah'];
            if ($dataSaleInvoiceDetail['returSale']['discount_type'] == 'percent') {
                $subtotalRefund = $subtotalRefund - ($subtotalRefund * $dataSaleInvoiceDetail['returSale']['discount'] / 100);
            } elseif ($dataSaleInvoiceDetail['returSale']['discount_type'] == 'value') {
                $subtotalRefund = $subtotalRefund - ($dataSaleInvoiceDetail['returSale']['discount'] * $dataSaleInvoiceDetail['returSale']['jumlah']);
            }
            
            $scp = Tools::hitungServiceChargePajak($subtotalRefund, $dataSaleInvoice['service_charge'], $dataSaleInvoice['pajak']);              
            
            $jumlahRefund += $subtotalRefund;
            $jumlahRefundServiceCharge += $scp['serviceCharge'];
            $jumlahRefundPajak += $scp['pajak'];
            
            $jumlahSubtotalRefund += $subtotalRefund;
            $jumlahSubtotalRefundServiceCharge += $scp['serviceCharge'];
            $jumlahSubtotalRefundPajak += $scp['pajak'];

            $jumlahDiskon += $discount;
            $jumlahSubtotalDiskon += $discount;
            $jumlahTotal += $subtotal;
            $jumlahSubtotal += $subtotal;                        
        }            

        $scp = Tools::hitungServiceChargePajak($jumlahSubtotal - ($jumlahSubtotalDiskon + $jumlahSubtotalRefund + $jumlahSubtotalVoid + $jumlahSubtotalFreeMenu), $dataSaleInvoice['service_charge'], $dataSaleInvoice['pajak']);                                        
        $serviceCharge = $scp['serviceCharge'];
        $pajak = $scp['pajak']; 
        $grandTotal = ($jumlahSubtotal - ($jumlahSubtotalDiskon + $jumlahSubtotalRefund + $jumlahSubtotalVoid + $jumlahSubtotalFreeMenu)) + $serviceCharge + $pajak;
        
        $jumlahKembalian += $dataSaleInvoice['jumlah_kembali'];

        $jumlahServiceCharge += $serviceCharge;
        $jumlahPajak += $pajak;
        $jumlahGrandTotal += $grandTotal;
        
        $keyInvoice = $dataSaleInvoice['id'];
        $dataInvoice[$keyInvoice]['invoiceId'] = $dataSaleInvoice['id'];
        $dataInvoice[$keyInvoice]['jumlah'] = $jumlahSubtotal;
        $dataInvoice[$keyInvoice]['jam'] = Yii::$app->formatter->asDate($dataSaleInvoice['date']) . ' / ' . date('H:i', strtotime($dataSaleInvoice['created_at']));
        
        foreach ($dataSaleInvoice['saleInvoicePayments'] as $dataPaymentMethod) {
            $keyMenu = $dataPaymentMethod['paymentMethod']['id'];
            
            $dataPayment[$keyMenu]['namaPayment'] = $dataPaymentMethod['paymentMethod']['nama_payment'];
            $dataPayment[$keyMenu]['method'] = $dataPaymentMethod['paymentMethod']['method'];
            
            if (!empty($dataPayment[$keyMenu]['jumlahBayar']))
                $dataPayment[$keyMenu]['jumlahBayar'] += $dataPaymentMethod['jumlah_bayar'];
            else
                $dataPayment[$keyMenu]['jumlahBayar'] = $dataPaymentMethod['jumlah_bayar'];
            
            if (!empty($dataPayment[$keyMenu]['count']))
                $dataPayment[$keyMenu]['count'] += 1;
            else
                $dataPayment[$keyMenu]['count'] = 1;
            
            $paymentJumlahTotal = $dataPaymentMethod['jumlah_bayar'];            
        }
    } ?>

    <?= Html::hiddenInput('tanggalTransaksi', Yii::$app->formatter->asDate($tanggal), ['id' => 'tanggalTransaksi']) ?>
    <?= Html::hiddenInput('jumlahFaktur', $jumlahFaktur, ['id' => 'jumlahFaktur']) ?>
    <?= Html::hiddenInput('jumlahTotal', $jumlahTotal, ['id' => 'jumlahTotal']) ?>
    <?= Html::hiddenInput('jumlahDiskon', $jumlahDiskon, ['id' => 'jumlahDiskon']) ?>
    <?= Html::hiddenInput('jumlahServiceCharge', $jumlahServiceCharge, ['id' => 'jumlahServiceCharge']) ?>
    <?= Html::hiddenInput('jumlahPajak', $jumlahPajak, ['id' => 'jumlahPajak']) ?>
    <?= Html::hiddenInput('jumlahRefund', $jumlahRefund, ['id' => 'jumlahRefund']) ?>
    <?= Html::hiddenInput('jumlahRefundServiceCharge', $jumlahRefundServiceCharge, ['id' => 'jumlahRefundServiceCharge']) ?>
    <?= Html::hiddenInput('jumlahRefundPajak', $jumlahRefundPajak, ['id' => 'jumlahRefundPajak']) ?>
    <?= Html::hiddenInput('jumlahVoid', $jumlahVoid, ['id' => 'jumlahVoid']) ?>
    <?= Html::hiddenInput('jumlahFreeMenu', $jumlahFreeMenu, ['id' => 'jumlahFreeMenu']) ?>
    <?= Html::hiddenInput('jumlahGrandTotal', $jumlahGrandTotal, ['id' => 'jumlahGrandTotal']) ?>    

    <?= Html::hiddenInput('paymentJumlahTotal', $paymentJumlahTotal, ['id' => 'paymentJumlahTotal']) ?>

    <?= Html::hiddenInput('jumlahKembalian', $jumlahKembalian, ['id' => 'jumlahKembalian']) ?>

    <?= Html::hiddenInput('saldoKasirAwal', !empty($modelSaldoKasir['saldo_awal']) ? $modelSaldoKasir['saldo_awal'] : 0, ['id' => 'saldoKasirAwal']) ?>

    <?php
    asort($dataMenu);
    foreach ($dataMenu as $menu): ?>

        <div class="rowMenu" style="display: none">
            <?= Html::hiddenInput('namaMenu', $menu['nama_menu'], ['id' => 'namaMenu']) ?>
            <?= Html::hiddenInput('qty', $menu['qty'], ['id' => 'qty']) ?>
            <?= Html::hiddenInput('subtotal', $menu['subtotal'], ['id' => 'subtotal']) ?>
        </div>

    <?php
    endforeach; 
    
    asort($dataPayment);
    foreach ($dataPayment as $payment): ?>

        <div class="rowPayment" style="display: none">
            <?= Html::hiddenInput('namaPayment', $payment['namaPayment'], ['id' => 'namaPayment']) ?>
            <?= Html::hiddenInput('jumlahBayar', $payment['jumlahBayar'], ['id' => 'jumlahBayar']) ?>
            <?= Html::hiddenInput('method', $payment['method'], ['id' => 'method']) ?>
            <?= Html::hiddenInput('count', $payment['count'], ['id' => 'count']) ?>
        </div>

    <?php
    endforeach;
    
    foreach ($dataInvoice as $invoice): ?>

        <div class="rowInvoice" style="display: none">
            <?= Html::hiddenInput('invoiceId', $invoice['invoiceId'], ['id' => 'invoiceId']) ?>
            <?= Html::hiddenInput('jumlah', $invoice['jumlah'], ['id' => 'jumlah']) ?>
            <?= Html::hiddenInput('jam', $invoice['jam'], ['id' => 'jam']) ?>
        </div>

    <?php
    endforeach;    
    
    if ($kasirAll)
        echo Html::hiddenInput('userKasir', 'ALL', ['id' => 'userKasir']);
    else
        echo Html::hiddenInput('userKasir', $modelUser->kdKaryawan->nama, ['id' => 'userKasir']);
endif; ?>    

<?php 

$this->params['regCssFile'][] = function() {
    $this->registerCssFile(Yii::getAlias('@common-web') . '/css/select2/select2.css');
    $this->registerCssFile(Yii::getAlias('@common-web') . '/css/select2/select2-bootstrap.css');
}; 

$this->params['regJsFile'][] = function() {
    $this->registerJsFile(Yii::getAlias('@common-web') . '/js/plugins/input-mask/jquery.inputmask.js');
    $this->registerJsFile(Yii::getAlias('@common-web') . '/js/plugins/input-mask/jquery.inputmask.date.extensions.js');
    $this->registerJsFile(Yii::getAlias('@common-web') . '/js/plugins/input-mask/jquery.inputmask.extensions.js');
    $this->registerJsFile(Yii::getAlias('@common-web') . '/js/plugins/jquery-currency/jquery.currency.js');
    $this->registerJsFile(Yii::getAlias('@common-web') . '/js/plugins/iCheck/icheck.min.js');
    $this->registerJsFile(Yii::getAlias('@common-web') . '/js/plugins/select2/select2.min.js');   
    $this->registerJsFile(Yii::getAlias('@common-web') . '/js/plugins/qzprint/deployJava.js');
};

$jscript = '
    $("#tanggal").inputmask("yyyy-mm-dd", {"placeholder": "yyyy-mm-dd"});
    
    $("#kasir").select2({
        placeholder: "Select User Kasir",
        allowClear: true
    });        
    
    $("#kasirAll").on("ifChecked", function() {
        $("#kasir").select2("enable", false);
    });  
    
    $("#kasirAll").on("ifUnchecked", function() {
        $("#kasir").select2("enable", true);
    });
';

if (!empty($modelSaleInvoice)) {
    if ($print == 'print') {
        $printerDialog = new PrinterDialog();
        $printerDialog->theScript();
        echo $printerDialog->renderDialog('backend');

        $jscript .= '        

            var print = function() {            
                var text = "";

                text += "\n" + separatorPrint(14) + "FAKTUR HARIAN\n\n";
                text += "Filter By" + separatorPrint(14 - "Filter By".length) + ": " + $("input#userKasir").val() + "\n";
                text += separatorPrint(40, "-") + "\n";
                text += "Tanggal" + separatorPrint(14 - "Tanggal".length) + ": " + $("input#tanggalTransaksi").val() + "\n";
                text += "Petugas" + separatorPrint(14 - "Petugas".length) + ": ' . Yii::$app->session->get('user_data')['employee']['nama'] . '" + "\n";
                text += separatorPrint(40, "-") + "\n";

                $("div.rowInvoice").each(function() {
                    var invoiceId = $(this).find("input#invoiceId").val();
                    var jumlah = $(this).find("input#jumlah").val();
                    var jam = $(this).find("input#jam").val();

                    var jumlahSpan = $("<span>").html(jumlah);
                    jumlahSpan.currency({' . Yii::$app->params['currencyOptionsPrint'] . '});

                    var separatorLength = 40 - (jam.length + jumlahSpan.html().length);                                        

                    text += invoiceId + "\n";
                    text += jam + separatorPrint(separatorLength) + jumlahSpan.html() + "\n";
                });                        

                text += separatorPrint(40, "-") + "\n";

                text += "Total Faktur" + separatorPrint(40 - ("Total Faktur" + $("input#jumlahFaktur").val()).length) + $("input#jumlahFaktur").val() + "\n";           

                var jumlahSubtotal = $("input#jumlahTotal").val();                    
                var jumlahSubtotalSpan = $("<span>").html(jumlahSubtotal);
                jumlahSubtotalSpan.currency({' . Yii::$app->params['currencyOptionsPrint'] . '});
                text += "Total Penjualan (Gross)" + separatorPrint(40 - ("Total Penjualan (Gross)" + jumlahSubtotalSpan.html()).length) + jumlahSubtotalSpan.html() + "\n";

                text += separatorPrint(40, "-") + "\n";

                var jumlahDisc = parseFloat($("input#jumlahDiskon").val());                    
                var jumlahDiscSpan = $("<span>").html(jumlahDisc);
                jumlahDiscSpan.currency({' . Yii::$app->params['currencyOptionsPrint'] . '});
                text += "Total Disc Item" + separatorPrint(40 - ("Total Disc Item" + "(" + jumlahDiscSpan.html() + ")").length) + "(" + jumlahDiscSpan.html() + ")\n";                       

                var jumlahFreeMenu = parseFloat($("input#jumlahFreeMenu").val());                    
                var jumlahFreeMenuSpan = $("<span>").html(jumlahFreeMenu);
                jumlahFreeMenuSpan.currency({' . Yii::$app->params['currencyOptionsPrint'] . '});
                text += "Total Free Menu" + separatorPrint(40 - ("Total Free Menu" + "(" + jumlahFreeMenuSpan.html() + ")").length) + "(" + jumlahFreeMenuSpan.html() + ")\n";

                text += separatorPrint(40, "-") + "\n";

                var jumlahRefund = parseFloat($("input#jumlahRefund").val());                    
                var jumlahRefundSpan = $("<span>").html(jumlahRefund);
                jumlahRefundSpan.currency({' . Yii::$app->params['currencyOptionsPrint'] . '});
                text += "Total Refund" + separatorPrint(40 - ("Total Refund" + "(" + jumlahRefundSpan.html() + ")").length) + "(" + jumlahRefundSpan.html() + ")\n";

                var jumlahVoid = parseFloat($("input#jumlahVoid").val());                    
                var jumlahVoidSpan = $("<span>").html(jumlahVoid);
                jumlahVoidSpan.currency({' . Yii::$app->params['currencyOptionsPrint'] . '});
                text += "Total Void" + separatorPrint(40 - ("Total Void" + "(" + jumlahVoidSpan.html() + ")").length) + "(" + jumlahVoidSpan.html() + ")\n";

                text += separatorPrint(40, "-") + "\n";

                var jumlahSubtotal = parseFloat($("input#jumlahTotal").val()) - (jumlahDisc + jumlahRefund + jumlahFreeMenu + jumlahVoid) ;                    
                var jumlahSubtotalSpan = $("<span>").html(jumlahSubtotal);
                jumlahSubtotalSpan.currency({' . Yii::$app->params['currencyOptionsPrint'] . '});
                text += "Total Penjualan (Netto)" + separatorPrint(40 - ("Total Penjualan (Netto)" + jumlahSubtotalSpan.html()).length) + jumlahSubtotalSpan.html() + "\n";                                

                var jumlahSc = $("input#jumlahServiceCharge").val();                    
                var jumlahScSpan = $("<span>").html(jumlahSc);
                jumlahScSpan.currency({' . Yii::$app->params['currencyOptionsPrint'] . '});
                text += "Total Service Charge" + separatorPrint(40 - ("Total Service Charge" + jumlahScSpan.html()).length) + jumlahScSpan.html() + "\n";

                var jumlahPajak = $("input#jumlahPajak").val();                    
                var jumlahPajakSpan = $("<span>").html(jumlahPajak);
                jumlahPajakSpan.currency({' . Yii::$app->params['currencyOptionsPrint'] . '});
                text += "Total Pajak" + separatorPrint(40 - ("Total Pajak" + jumlahPajakSpan.html()).length) + jumlahPajakSpan.html() + "\n";     

                text += separatorPrint(40, "-") + "\n";                        

                var jumlahGrandTotal = parseFloat($("input#jumlahGrandTotal").val());              
                var jumlahGrandTotalSpan = $("<span>").html(jumlahGrandTotal);
                jumlahGrandTotalSpan.currency({' . Yii::$app->params['currencyOptionsPrint'] . '});
                text += "GRAND TOTAL" + separatorPrint(40 - ("GRAND TOTAL" + jumlahGrandTotalSpan.html()).length) + jumlahGrandTotalSpan.html() + "\n";   

                text += separatorPrint(40, "-") + "\n";

                var cashPayment = 0;

                $("div.rowPayment").each(function() {                

                    var namaPayment = $(this).find("input#namaPayment").val();

                    var jumlahBayar = $(this).find("input#jumlahBayar").val();                          
                    var jumlahBayarSpan = $("<span>").html(jumlahBayar);
                    jumlahBayarSpan.currency({' . Yii::$app->params['currencyOptionsPrint'] . '});

                    var count = $(this).find("input#count").val();

                    var separatorLength = 40 - (qty.length + jumlahBayarSpan.html().length);                                   

                    text += "(" + count + ") " + namaPayment + separatorPrint(40 - ("(" + count + ") " + namaPayment + jumlahBayarSpan.html()).length) + jumlahBayarSpan.html() + "\n";

                    if ($(this).find("input#method").val() == "cash") {
                        cashPayment += parseFloat(jumlahBayar);
                    }
                });        

                text += separatorPrint(40, "-") + "\n";

                var jumlahKembalian = parseFloat($("input#jumlahKembalian").val());                    
                var jumlahKembalianSpan = $("<span>").html(jumlahKembalian);
                jumlahKembalianSpan.currency({' . Yii::$app->params['currencyOptionsPrint'] . '});
                text += "Total Kembalian" + separatorPrint(40 - ("Total Kembalian" + "(" + jumlahKembalianSpan.html() + ")").length) + "(" + jumlahKembalianSpan.html() + ")\n";

                text += separatorPrint(40, "-") + "\n";

                text += "Total Refund" + separatorPrint(40 - ("Total Refund" + "(" + jumlahRefundSpan.html() + ")").length) + "(" + jumlahRefundSpan.html() + ")\n";

                var jumlahRefundSc = parseFloat($("input#jumlahRefundServiceCharge").val());                    
                var jumlahRefundScSpan = $("<span>").html(jumlahRefundSc);
                jumlahRefundScSpan.currency({' . Yii::$app->params['currencyOptionsPrint'] . '});
                text += "Total Service Charge" + separatorPrint(40 - ("Total Service Charge" + "(" + jumlahRefundScSpan.html() + ")").length) + "(" + jumlahRefundScSpan.html() + ")\n";

                var jumlahRefundPajak = parseFloat($("input#jumlahRefundPajak").val());                    
                var jumlahRefundPajakSpan = $("<span>").html(jumlahRefundPajak);
                jumlahRefundPajakSpan.currency({' . Yii::$app->params['currencyOptionsPrint'] . '});
                text += "Total Pajak" + separatorPrint(40 - ("Total Pajak" + "(" + jumlahRefundPajakSpan.html() + ")").length) + "(" + jumlahRefundPajakSpan.html() + ")\n";

                text += separatorPrint(40, "-") + "\n";

                var jumlahSaldoAwal = parseFloat($("input#saldoKasirAwal").val());                    
                var jumlahSaldoAwalSpan = $("<span>").html(jumlahSaldoAwal);
                jumlahSaldoAwalSpan.currency({' . Yii::$app->params['currencyOptionsPrint'] . '});
                text += "SALDO AWAL" + separatorPrint(40 - ("SALDO AWAL" + jumlahSaldoAwalSpan.html()).length) + jumlahSaldoAwalSpan.html() + "\n";

                text += separatorPrint(40, "-") + "\n";

                var jumlahCashOnHand = (cashPayment - (jumlahKembalian + jumlahRefund + jumlahRefundSc + jumlahRefundPajak)) + jumlahSaldoAwal;                    
                var jumlahCashOnHandSpan = $("<span>").html(jumlahCashOnHand);
                jumlahCashOnHandSpan.currency({' . Yii::$app->params['currencyOptionsPrint'] . '});
                text += "CASH ON HAND" + separatorPrint(40 - ("CASH ON HAND" + jumlahCashOnHandSpan.html()).length) + jumlahCashOnHandSpan.html() + "\n";

                var content = [];

                $("input#printerKasir").each(function() {
                    content[$(this).val()] = text;
                });                

                printContent("", "", content);
            };

            print();
        ';
    }
} else {
    if (!empty(Yii::$app->request->post())) {
        $notif = new NotificationDialog([
            'status' => 'danger',
            'message1' => 'Alert',
            'message2' => 'Tidak ada data.',
        ]);

        $notif->theScript();
        echo $notif->renderDialog();
    }
}

$this->registerJs($jscript . Yii::$app->params['checkbox-radio-script']()); ?>