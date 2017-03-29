<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\SaleInvoice */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Sale Invoices', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title; 

$jscript = ''; ?>

<div class="sale-invoice-view">
    
    <div class="row">
        <div class="col-sm-2"></div>
        <div class="col-sm-8">
            <div class="box box-danger">
                <div class="box-header">
                    <h3 class="box-title">
                        <?= Html::a('<i class="fa fa-rotate-left"></i>&nbsp;&nbsp;&nbsp;' . 'Cancel', 
                            ['index'], 
                            [
                                'class' => 'btn btn-default',
                            ]) ?>
                    </h3>
                </div>
                
                <?= DetailView::widget([
                    'model' => $model,
                    'options' => [
                        'class' => 'table'
                    ],
                    'attributes' => [
                        'id',
                        'date:date',
                        'mtable_session_id',
                        'user_operator',
                        'jumlah_harga:currency',
                        'jumlah_bayar:currency',
                        'jumlah_kembali:currency',
                    ],
                ]) ?>
                        
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-danger">
                <div class="box-header">
                    <h3 class="box-title">
                        Menu
                    </h3>
                    <div class="box-tools">
                        
                    </div>
                </div>
                <div class="box-body table-responsive no-padding">

                    <table id="menu-receipts" class="table table-striped table-responsive">
                        <thead>
                            <tr>
                                <th>Menu ID</th>
                                <th>Nama Menu</th>
                                <th>Jumlah</th>
                                <th>Harga</th>
                                <th>Discount</th>
                                <th>Total Harga</th>
                                <th>Jumlah Retur</th>
                            </tr>
                        </thead>                    
                        <tbody id="tbodyItem">           
                            <?php
                            foreach ($model->saleInvoiceDetails as $dataSaleInvoiceDetails): ?>

                                <tr>
                                    <td><?= $dataSaleInvoiceDetails->menu_id ?></td>
                                    <td><?= $dataSaleInvoiceDetails->menu->nama_menu ?></td>
                                    <td><?= $dataSaleInvoiceDetails->jumlah ?></td>
                                    <td><?= Yii::$app->formatter->asCurrency($dataSaleInvoiceDetails->harga) ?></td>
                                    <td>
                                        <?php 
                                        $subtotal = $dataSaleInvoiceDetails->jumlah * $dataSaleInvoiceDetails->harga;
                                        if ($dataSaleInvoiceDetails->discount_type == 'percent') {
                                            echo $dataSaleInvoiceDetails->discount . ' %';
                                            $subtotal = $subtotal - ($subtotal * $dataSaleInvoiceDetails->discount * 0.01);
                                        } elseif ($dataSaleInvoiceDetails->discount_type == 'value') {
                                            echo Yii::$app->formatter->asCurrency($dataSaleInvoiceDetails->discount); 
                                            $subtotal = $subtotal - $dataSaleInvoiceDetails->discount;
                                        } ?>
                                    </td>
                                    <td><?= Yii::$app->formatter->asCurrency($subtotal) ?></td>
                                    <td>
                                        <?php
                                        $jml = 0;
                                        $keterangan = "";
                                        $saleInvoiceDetailId = 0;
                                        if (!empty(($dataReturSale = $dataSaleInvoiceDetails->returSale))) {
                                            $jml = $dataReturSale->jumlah;   
                                            $keterangan = $dataReturSale->keterangan;
                                            $saleInvoiceDetailId = $dataReturSale->sale_invoice_detail_id;
                                        }
                                        
                                        echo '<a href="javascript:;" id="jumlah-' . $dataSaleInvoiceDetails->id . '" data-type="address" data-pk="'. $dataSaleInvoiceDetails->id .'" data-name="jumlah" data-url="' . Yii::$app->urlManager->createUrl('retur-sale/update-jumlah') . '" data-title="Enter Jumlah">' . $jml . '</a>';                                            
                                        
                                        $jscript .= '
                                            $("a#jumlah-' . $dataSaleInvoiceDetails->id . '").editable({
                                                params: function(params) {
                                                    params.menuId = "' . $dataSaleInvoiceDetails->menu_id . '";
                                                    params.saleInvoiceDetailId = ' . $dataSaleInvoiceDetails->id . ';
                                                    params.jumlah = ' . $dataSaleInvoiceDetails->jumlah . ';
                                                    params.discountType = "' . $dataSaleInvoiceDetails->discount_type . '";
                                                    params.discount = ' . $dataSaleInvoiceDetails->discount . ';
                                                    params.harga = ' . $dataSaleInvoiceDetails->harga . ';
                                                    params.returSaleId = ' . $saleInvoiceDetailId . ';
                                                    return params;
                                                },       
                                                value: {
                                                    jumlah: "' . $jml . '",
                                                    keterangan: "' . $keterangan . '"
                                                },      
                                                showbuttons: "bottom",
                                                success: function(response, newValue) {
                                                    var data = $.parseJSON(response);
                                                    if (data.message.length != 0) {
                                                        return data.message;
                                                    }
                                                },
                                                display: function(value) {
                                                    if(!value) {
                                                        $(this).empty();
                                                        return; 
                                                    }                                                    
                                                    $(this).html(value.jumlah); 
                                                }
                                            });'; ?>                                        
                                    </td>
                                </tr>
                             
                            <?php
                             endforeach; ?>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>

</div>

<?php    

$this->params['regCssFile'][] = function() {
    $this->registerCssFile(Yii::getAlias('@common-web') . '/css/x-editable/bootstrap-editable.css');
    $this->registerCssFile(Yii::getAlias('@common-web') . '/css/x-editable/address.css');
}; 

$this->params['regJsFile'][] = function() {
    $this->registerJsFile(Yii::getAlias('@common-web') . '/js/plugins/x-editable/bootstrap-editable.js');
    $this->registerJsFile(Yii::getAlias('@common-web') . '/js/plugins/x-editable/address.js');
}; 

$this->registerJs($jscript); ?>