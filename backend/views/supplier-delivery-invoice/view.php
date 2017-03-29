<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use backend\components\ModalDialog;

/* @var $this yii\web\View */
/* @var $model backend\models\SupplierDeliveryInvoice */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Invoice Penerimaan Item', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title; ?>

<div class="supplier-delivery-invoice-view">
    
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
                        'date:date',
                        'supplier_delivery_id',
                        'payment_method',
                        'jumlah_harga:currency',
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
                        Item
                    </h3>
                    <div class="box-tools">

                    </div>
                </div>
                <div class="box-body table-responsive no-padding" id="contentItem">


                </div>
            </div>
        </div>
        <div class="col-sm-1"></div>
    </div>

</div>

<div class="row">
    <div class="col-sm-1"></div>
    <div class="col-sm-10">
        <div class="box box-danger">
            <div class="box-header">
                <h3 class="box-title">
                    Retur
                </h3>
                <div class="box-tools">
                    
                </div>
            </div>
            <div class="box-body table-responsive no-padding" id="contentRetur">                                
                
            </div>
        </div>
    </div>
    <div class="col-sm-1"></div>
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
        url: "' . Yii::$app->urlManager->createUrl('supplier-delivery-trx/get-data-trx') . '?id=' . $model->supplier_delivery_id . '",
        success: function(response) {
            $("div#contentItem").html(response);
        }
    });

    $.ajax({
        type: "POST",
        cache: false,
        url: "' . Yii::$app->urlManager->createUrl('retur-purchase-trx/get-data-trx') . '?id=' . $model->supplier_delivery_id . '",
        success: function(response) {
            $("div#contentRetur").html(response);
        }
    });
';
    
$this->registerJs($jscript); ?>