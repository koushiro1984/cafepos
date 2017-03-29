<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use kartik\money\MaskMoney;
use backend\components\NotificationDialog;
use backend\models\SupplierDelivery;
use backend\models\PaymentMethod;

/* @var $this yii\web\View */
/* @var $model backend\models\SupplierDeliveryInvoice */
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

<div class="row">
    <div class="col-sm-2"></div>
    <div class="col-sm-8">
        <div class="box box-danger">
            <div class="box-body">
                <div class="supplier-delivery-invoice-form">                    
                    
                    <div class="form-group">
                        <div class="row">
                            <div class="col-lg-6">
                                <?php
                                if (!$model->isNewRecord)
                                    echo Html::a('<i class="fa fa-upload"></i>&nbsp;&nbsp;&nbsp;' . 'Create', ['create'], ['class' => 'btn btn-success']); ?>
                            </div>
                        </div>
                    </div>

                    <?= $form->field($model, 'id', [
                            'parts' => [
                                '{inputClass}' => 'col-lg-7'
                            ],
                        ])->textInput(['maxlength' => 16, 'readonly' => 'readonly']) ?>

                    <?= $form->field($model, 'date', [
                            'parts' => [
                                '{inputClass}' => 'col-lg-7'
                            ],
                        ])->widget(DatePicker::className(), [
                            'pluginOptions' => Yii::$app->params['datepickerOptions'],
                        ]) ?>
                    
                    <?php
                    $supplierDeliveryId = null;
                    if ($model->isNewRecord)
                        $supplierDeliveryId = SupplierDelivery::find()->joinWith(['supplierDeliveryInvoices'])->andWhere(['IS', 'supplier_delivery_invoice.supplier_delivery_id', null])->all();
                    else
                        $supplierDeliveryId = SupplierDelivery::find()->joinWith(['supplierDeliveryInvoices'])->all(); ?>
                    
                    <?= $form->field($model, 'supplier_delivery_id')->dropDownList(
                            ArrayHelper::map(
                                $supplierDeliveryId, 
                                'id', 
                                function($data) { 
                                    return $data->id;                                 
                                }
                            ), 
                            [
                                'prompt' => '',
                                'style' => 'width: 85%'
                            ]) ?>
                    
                    <?= $form->field($model, 'payment_method')->dropDownList(
                            ArrayHelper::map(
                                PaymentMethod::find()->andWhere(['type' => 'purchase'])->all(), 
                                'id', 
                                function($data) { 
                                    return '(' . $data->id . ') ' . $data->nama_payment;        
                                }
                            ), 
                            [
                                'prompt' => '',
                                'style' => 'width: 85%'
                            ]) ?>

                    <?= $form->field($model, 'jumlah_harga', [
                            'parts' => [
                                '{inputClass}' => 'col-lg-7'
                            ],
                        ])->widget(MaskMoney::className(), ['readonly' => 'readonly']) ?>

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

<?php ActiveForm::end(); ?>


<?php

$this->params['regCssFile'][] = function() {
    $this->registerCssFile(Yii::getAlias('@common-web') . '/css/select2/select2.css');
    $this->registerCssFile(Yii::getAlias('@common-web') . '/css/select2/select2-bootstrap.css');
}; 

$this->params['regJsFile'][] = function() {
    $this->registerJsFile(Yii::getAlias('@common-web') . '/js/plugins/select2/select2.min.js');        
    $this->registerJsFile(Yii::getAlias('@common-web') . '/js/plugins/input-mask/jquery.inputmask.js');
    $this->registerJsFile(Yii::getAlias('@common-web') . '/js/plugins/input-mask/jquery.inputmask.date.extensions.js');
    $this->registerJsFile(Yii::getAlias('@common-web') . '/js/plugins/input-mask/jquery.inputmask.extensions.js');
};

$jscript = '
    
    $("#supplierdeliveryinvoice-date").inputmask("yyyy-mm-dd", {"placeholder": "yyyy-mm-dd"});
    
    $("#supplierdeliveryinvoice-supplier_delivery_id").select2({
        placeholder: "Select Supplier Delivery",
        allowClear: true
    });

    $("#supplierdeliveryinvoice-supplier_delivery_id").on("select2-selecting", function(e) {                
        var selected = e;
        var hitungTotalHarga = function() {
            var totalHarga = parseFloat($("div#contentItem").find("input#totalHarga").val());
            var totalHargaRetur = parseFloat($("div#contentRetur").find("input#totalHargaRetur").val());            
            var total = totalHarga - totalHargaRetur;
            
            $("td#jumlah-subtotal span#jumlah-subtotal-text").text(totalHarga);
            
            $("td#jumlah-subtotal-retur span#jumlah-subtotal-retur-text").text(totalHargaRetur);
            
            $("#supplierdeliveryinvoice-jumlah_harga").val(total);
            $("#supplierdeliveryinvoice-jumlah_harga-disp").maskMoney("mask", total);
        }

        $.ajax({
            type: "POST",
            cache: false,
            url: "' . Yii::$app->urlManager->createUrl('supplier-delivery-trx/get-data-trx') . '?id=" + selected.val,
            success: function(response) {
                $("div#contentItem").html(response);

                hitungTotalHarga();
            }
        });
        
        $.ajax({
            type: "POST",
            cache: false,
            url: "' . Yii::$app->urlManager->createUrl('retur-purchase-trx/get-data-trx') . '?id=" + selected.val,
            success: function(response) {
                $("div#contentRetur").html(response);

                hitungTotalHarga();
            }
        });
    });

    $("#supplierdeliveryinvoice-supplier_delivery_id").on("select2-removed", function(e) {
        $("div#contentItem").html("");

        $("#supplierdeliveryinvoice-jumlah_harga").val(0);
        $("#supplierdeliveryinvoice-jumlah_harga-disp").maskMoney("mask", 0);

        $("td#jumlah-subtotal span#jumlah-subtotal-text").text(0);
    });

    $("#supplierdeliveryinvoice-payment_method").select2({
        placeholder: "Select Payment Method",
        allowClear: true
    });

    $("#supplierdeliveryinvoice-jumlah_harga-disp").off("keypress");
    $("#supplierdeliveryinvoice-jumlah_harga-disp").off("keyup");
';   

if (!$model->isNewRecord) {
    $jscript .= '
        $("#supplierdeliveryinvoice-supplier_delivery_id").select2("readonly", true);        
        
        $.ajax({
            type: "POST",
            cache: false,
            url: "' . Yii::$app->urlManager->createUrl('supplier-delivery-trx/get-data-trx') . '?id=" + $("#supplierdeliveryinvoice-supplier_delivery_id").val(),
            success: function(response) {
                $("div#contentItem").html(response);
            }
        });
        
        $.ajax({
            type: "POST",
            cache: false,
            url: "' . Yii::$app->urlManager->createUrl('retur-purchase-trx/get-data-trx') . '?id=" + $("#supplierdeliveryinvoice-supplier_delivery_id").val(),
            success: function(response) {
                $("div#contentRetur").html(response);
            }
        });
    ';
}

$this->registerJs($jscript); ?>