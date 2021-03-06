<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\money\MaskMoney;
use kartik\date\DatePicker;
use backend\components\NotificationDialog;

/* @var $this yii\web\View */
/* @var $model backend\models\Voucher */
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
                <div class="voucher-form">

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

                    <?= $form->field($model, 'id', [
                            'parts' => [
                                '{inputClass}' => 'col-lg-7'
                            ],
                            'enableAjaxValidation' => true
                        ])->textInput(['maxlength' => 16, $model->isNewRecord ? '' : 'readonly' => $model->isNewRecord ? '' : 'readonly']) ?>

                    <?= $form->field($model, 'voucher_type')->radioList(
                        [
                            'percent' => 'Percent', 
                            'value' => 'Value'
                        ], 
                        [
                            'separator' => '&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;'
                        ]) ?>                   

                    <?= $form->field($model, 'jumlah_voucher', [
                            'parts' => [
                                '{inputClass}' => 'col-lg-7'
                            ],
                        ])->widget(MaskMoney::className(), [
                            'pluginOptions' => [
                                'prefix' => $model->voucher_type == 'value' ? 'Rp. ' : '',
                            ]
                        ]) ?>

                    <?= $form->field($model, 'start_date', [
                            'parts' => [
                                '{inputClass}' => 'col-lg-7'
                            ],
                        ])->widget(DatePicker::className(), [
                            'pluginOptions' => Yii::$app->params['datepickerOptions'],
                        ]) ?>

                    <?= $form->field($model, 'end_date', [
                            'parts' => [
                                '{inputClass}' => 'col-lg-7'
                            ],
                        ])->widget(DatePicker::className(), [
                            'pluginOptions' => Yii::$app->params['datepickerOptions'],
                        ]) ?>
                    
                    <?= $form->field($model, 'not_active')->checkbox(['value' => true], false) ?>

                    <?= $form->field($model, 'keterangan')->textarea(['rows' => 2]) ?>

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

<?php 

$this->params['regJsFile'][] = function() {
    $this->registerJsFile(Yii::getAlias('@common-web') . '/js/plugins/iCheck/icheck.min.js');
    $this->registerJsFile(Yii::getAlias('@common-web') . '/js/plugins/input-mask/jquery.inputmask.js');
    $this->registerJsFile(Yii::getAlias('@common-web') . '/js/plugins/input-mask/jquery.inputmask.date.extensions.js');
    $this->registerJsFile(Yii::getAlias('@common-web') . '/js/plugins/input-mask/jquery.inputmask.extensions.js');
};

$jscript = '$("#voucher-start_date, #voucher-end_date").inputmask("yyyy-mm-dd", {"placeholder": "yyyy-mm-dd"});'
        . '$("input[type=radio]").on("ifChecked", function() {'
            . 'var val = parseFloat($("#voucher-jumlah_voucher").val());'            
            . 'if ($(this).val() == "percent") {'
                . '$("#voucher-jumlah_voucher-disp").maskMoney({prefix: "", suffix: ""}, val);'                
            . '} else if ($(this).val() == "value") {'
                . '$("#voucher-jumlah_voucher-disp").maskMoney({prefix: "Rp. ", suffix: ""}, val);'
            . '}'
            . '$("#voucher-jumlah_voucher-disp").maskMoney("mask");'
        . '});';


$this->registerJs($jscript . Yii::$app->params['checkbox-radio-script']()); ?>
