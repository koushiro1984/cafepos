<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;
use backend\components\NotificationDialog;
use backend\models\Mtable;

/* @var $this yii\web\View */
/* @var $model backend\models\Booking */
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
                <div class="booking-form">

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
                        ])->textInput(['maxlength' => 16, 'readonly' => 'readonly']) ?>

                    <?= $form->field($model, 'mtable_id')->dropDownList(
                            ArrayHelper::map(
                                Mtable::find()->all(), 
                                'id', 
                                function($data) { 
                                    return '(' . $data->id . ') ' . $data->nama_meja;                                 
                                }
                            ), 
                            [
                                'prompt' => '',
                                'style' => 'width: 70%'
                            ]) ?>

                    <?= $form->field($model, 'nama_pelanggan')->textInput(['maxlength' => 64]) ?>

                    <?= $form->field($model, 'date', [
                            'parts' => [
                                '{inputClass}' => 'col-lg-7'
                            ],
                        ])->widget(DatePicker::className(), [
                            'pluginOptions' => Yii::$app->params['datepickerOptions'],
                        ]) ?>

                    <?= $form->field($model, 'time', [
                            'parts' => [
                                '{inputClass}' => 'col-lg-7'
                            ],
                        ])->textInput() ?>

                    <?= $form->field($model, 'keterangan')->textarea(['rows' => 6]) ?>
                    
                    <?= $form->field($model, 'is_closed')->checkbox(['value' => true], false) ?>

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

$this->params['regCssFile'][] = function() {
    $this->registerCssFile(Yii::getAlias('@common-web') . '/css/select2/select2.css');
    $this->registerCssFile(Yii::getAlias('@common-web') . '/css/select2/select2-bootstrap.css');
    $this->registerCssFile(Yii::getAlias('@common-web') . '/css/timepicker/bootstrap-timepicker.css');
};

$this->params['regJsFile'][] = function() {
    $this->registerJsFile(Yii::getAlias('@common-web') . '/js/plugins/select2/select2.min.js');
    $this->registerJsFile(Yii::getAlias('@common-web') . '/js/plugins/input-mask/jquery.inputmask.js');
    $this->registerJsFile(Yii::getAlias('@common-web') . '/js/plugins/input-mask/jquery.inputmask.date.extensions.js');
    $this->registerJsFile(Yii::getAlias('@common-web') . '/js/plugins/input-mask/jquery.inputmask.extensions.js');
    $this->registerJsFile(Yii::getAlias('@common-web') . '/js/plugins/timepicker/bootstrap-timepicker.js');
    $this->registerJsFile(Yii::getAlias('@common-web') . '/js/plugins/iCheck/icheck.min.js');
};

$jscript = '
    $("#booking-date").inputmask("yyyy-mm-dd", {"placeholder": "yyyy-mm-dd"});
    
    $("#booking-mtable_id").select2({
        placeholder: "Select Storage",
        allowClear: true
    });
    
    $("#booking-time").timepicker({
        showMeridian: false
    });
';


$this->registerJs($jscript . Yii::$app->params['checkbox-radio-script']()); ?>