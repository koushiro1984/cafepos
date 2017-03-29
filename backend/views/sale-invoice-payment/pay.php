<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\money\MaskMoney;
use backend\models\PaymentMethod;
use backend\components\NotificationDialog;

/* @var $this yii\web\View */
/* @var $model backend\models\SaleInvoicePayment */

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

endif; 

$this->title = 'Penerimaan Pembayaran Piutang';
$this->params['breadcrumbs'][] = ['label' => 'Piutang', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Pembayaran';
?>

<div class="sale-invoice-payment-update">

    <div class="row">
        <div class="col-sm-2"></div>
        <div class="col-sm-8">
            <div class="box box-danger">
                <div class="box-body">
                    <div class="sale-invoice-payment-form">

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

                        <?= $form->field($model, 'sale_invoice_id', [
                                'parts' => [
                                    '{inputClass}' => 'col-lg-7'
                                ],
                            ])->textInput(['maxlength' => 15, 'readonly' => 'readonly']) ?>
                        

                        <?= $form->field($model, 'jumlah_bayar', [
                                'parts' => [
                                    '{inputClass}' => 'col-lg-7'
                                ],
                            ])->widget(MaskMoney::className(), ['readonly' => 'readonly']) ?>
                        
                        <?= $form->field($model, 'payment_method_id')->dropDownList(
                            ArrayHelper::map(
                                PaymentMethod::find()->andWhere(['type' => 'account-receiveable'])->all(), 
                                'id', 
                                function($data) { 
                                    return '(' . $data->id . ') ' . $data->nama_payment;        
                                }
                            ), 
                            [
                                'prompt' => '',
                                'style' => 'width: 85%'
                            ]) ?>
                        
                        <?= $form->field($model, 'jumlah_bayar_child', [
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

</div>

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
    $("#saleinvoicepayment-payment_method_id").select2({
        placeholder: "Select Payment Method",
        allowClear: true
    });
            
    $("#saleinvoicepayment-jumlah_bayar-disp").off("keypress");
    $("#saleinvoicepayment-jumlah_bayar-disp").off("keyup");
';

$this->registerJs($jscript); ?>