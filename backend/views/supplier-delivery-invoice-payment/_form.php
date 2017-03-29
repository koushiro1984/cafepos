<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kartik\money\MaskMoney;
use backend\components\NotificationDialog;
use backend\models\SupplierDeliveryInvoice;

/* @var $this yii\web\View */
/* @var $model backend\models\SupplierDeliveryInvoicePayment */
/* @var $form yii\widgets\ActiveForm */

$status = Yii::$app->session->getFlash('status');
$message1 = Yii::$app->session->getFlash('message1');
$message2 = Yii::$app->session->getFlash('message2');

if ($status !== null) : 
    $notif = new NotificationDialog([
        'status' => $status,
        'message1' => $message1,
        'message2' => $message2,
    ]);

    $notif->theScript();
    echo $notif->renderDialog();

endif; ?>

<div class="row">
    <div class="col-sm-2"></div>
    <div class="col-sm-8">
        <div class="box box-danger">
            <div class="box-body">
                <div class="supplier-delivery-invoice-payment-form">

                    <?php $form = ActiveForm::begin([
                            'options' => [
                                
                            ],
                            'fieldConfig' => [
                                'parts' => [
                                    '{inputClass}' => 'col-lg-12'
                                ],
                                'template' => '<div class="row">'
                                                . '<div class="col-lg-3">'
                                                    . '{label}'
                                                . '</div>'
                                                . '<div class="col-lg-6">'
                                                    . '<div class="{inputClass}">'
                                                        . '{input}'
                                                    . '</div>'
                                                . '</div>'
                                                . '<div class="col-lg-3">'
                                                    . '{error}'
                                                . '</div>'
                                            . '</div>', 
                            ]
                    ]); ?>
                    
                    <div class="form-group">
                        <div class="row">
                            <div class="col-lg-6">
                                <?php
                                if (!$model->isNewRecord)
                                    echo Html::a('<i class="fa fa-upload"></i>&nbsp;&nbsp;&nbsp;' . 'Create', ['create'], ['class' => 'btn btn-success']); ?>
                            </div>
                        </div>
                    </div>
                    
                    <?php
                    $supplierInvoiceDeliveryId = null;
                    if ($model->isNewRecord)
                        $supplierInvoiceDeliveryId = SupplierDeliveryInvoice::find()->where('jumlah_bayar < jumlah_harga')->all();
                    else
                        $supplierInvoiceDeliveryId = SupplierDeliveryInvoice::find()->all(); ?>

                    <?= $form->field($model, 'supplier_delivery_invoice_id')->dropDownList(
                            ArrayHelper::map(
                                $supplierInvoiceDeliveryId, 
                                'id', 
                                function($data) { 
                                    return $data->id;                                 
                                }
                            ), 
                            [
                                'prompt' => '',
                                'style' => 'width: 85%'
                            ]) ?>

                    <?= $form->field($model, 'jumlah_bayar', [
                            'parts' => [
                                '{inputClass}' => 'col-lg-7'
                            ],
                        ])->widget(MaskMoney::className()) ?>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-lg-3"></div>
                            <div class="col-lg-6">
                                <?php
                                $icon = '<i class="fa fa-floppy-o"></i>&nbsp;&nbsp;&nbsp;';
                                echo Html::submitButton($model->isNewRecord ? $icon . 'Save' : $icon . 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
                                echo '&nbsp;&nbsp;&nbsp;';
                                echo Html::a('<i class="fa fa-rotate-left"></i>&nbsp;&nbsp;&nbsp;Cancel', ['index'], ['class' => 'btn btn-default']); ?>
                            </div>
                        </div>
                    </div>

                    <?php ActiveForm::end(); ?>

                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-2"></div>
</div><!-- /.row -->

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

<?php

$this->params['regCssFile'][] = function() {
    $this->registerCssFile(Yii::getAlias('@common-web') . '/css/select2/select2.css');
    $this->registerCssFile(Yii::getAlias('@common-web') . '/css/select2/select2-bootstrap.css');
}; 

$this->params['regJsFile'][] = function() {
    $this->registerJsFile(Yii::getAlias('@common-web') . '/js/plugins/select2/select2.min.js');        
}; 

$jscript = '  
    $("#supplierdeliveryinvoicepayment-supplier_delivery_invoice_id").select2({
        placeholder: "Select Supplier Delivery Invoice",
        allowClear: true
    });
    
    $("#supplierdeliveryinvoicepayment-supplier_delivery_invoice_id").on("select2-selecting", function(e) {                
                var selected = e;
                $.ajax({
                    type: "POST",
                    cache: false,
                    url: "' . Yii::$app->urlManager->createUrl('supplier-delivery-invoice/get-data-invoice') . '?id=" + selected.val,
                    success: function(response) {
                        $("div#contentInvoice").html(response);
                    }
                });
            });
            
            $("#supplierdeliveryinvoicepayment-supplier_delivery_invoice_id").on("select2-removed", function(e) {
                $("contentInvoice").html("");
            });
';

if (!$model->isNewRecord) {
    $jscript .= '
        $("#supplierdeliveryinvoicepayment-supplier_delivery_invoice_id").select2("readonly", true);
        $.ajax({
            type: "POST",
            cache: false,
            url: "' . Yii::$app->urlManager->createUrl('supplier-delivery-invoice/get-data-invoice') . '?id=" + $("#supplierdeliveryinvoicepayment-supplier_delivery_invoice_id").val(),
            success: function(response) {
                $("div#contentInvoice").html(response);
            }
        });
    ';
}

$this->registerJs($jscript); ?>
