<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use backend\components\ModalDialog;

/* @var $this yii\web\View */
/* @var $model backend\models\SupplierDeliveryInvoicePayment */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Pembayaran Pembelian', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title; ?>

<div class="supplier-delivery-invoice-payment-view">
    
    <div class="row">
        <div class="col-sm-2"></div>
        <div class="col-sm-8">
            <div class="box box-danger">
                <div class="box-header">
                    <h3 class="box-title">
                        <?= Html::a('<i class="glyphicon glyphicon-pencil"></i>&nbsp;&nbsp;&nbsp;' . 'Edit', 
                            ['update', 'id' => $model->id], 
                            [
                                'class' => 'btn btn-primary',
                                'style' => 'color:white'
                            ]) ?>
                            
                        <?= Html::a('<i class="glyphicon glyphicon-trash"></i>&nbsp;&nbsp;&nbsp;' . 'Delete', 
                            ['delete', 'id' => $model->id], 
                            [
                                'id' => 'delete',
                                'class' => 'btn btn-danger',
                                'style' => 'color:white',
                                'model-id' => $model->id,
                                'model-name' => '',
                            ]) ?>                            
                        
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
                        'supplier_delivery_invoice_id',
                        'jumlah_bayar:currency',
                    ],
                ]) ?>
                        
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-sm-1"></div>
        <div class="col-sm-10">
            <div class="box box-danger">
                <div class="box-header">
                    <h3 class="box-title">
                        Invoice Penerimaan Item
                    </h3>
                    <div class="box-tools">

                    </div>
                </div>
                <div class="box-body table-responsive no-padding" id="contentInvoice">


                </div>
            </div>
        </div>
        <div class="col-sm-1"></div>
    </div>

</div>

<?php
    
$modalDialog = new ModalDialog([
    'clickedComponent' => 'a#delete',
    'modelAttributeId' => 'model-id',
    'modelAttributeName' => 'model-name',
]);

$modalDialog->theScript();

echo $modalDialog->renderDialog();

$jscript = '
    $.ajax({
        type: "POST",
        cache: false,
        url: "' . Yii::$app->urlManager->createUrl('supplier-delivery-invoice/get-data-invoice') . '?id=' . $model->supplier_delivery_invoice_id . '",
        success: function(response) {
            $("div#contentInvoice").html(response);
        }
    });
';

$this->registerJs($jscript); ?>