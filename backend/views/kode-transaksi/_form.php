<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\components\NotificationDialog;

/* @var $this yii\web\View */
/* @var $model backend\models\KodeTransaksi */
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
                <div class="kode-transaksi-form">

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

                    <?= $form->field($model, 'account_id', [
                            'parts' => [
                                '{inputClass}' => 'col-lg-7'
                            ],
                            'enableAjaxValidation' => true
                        ])->textInput(['maxlength' => 16]) ?>

                    <?= $form->field($model, 'nama_account')->textInput(['maxlength' => 32]) ?>

                    <?= $form->field($model, 'account_type')->radioList(
                        [
                            'cash-in' => 'Cash-in', 
                            'cash-out' => 'Cash-out', 
                        ], 
                        [
                            'separator' => '&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;'
                        ]) ?>

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
};

$this->registerJs(Yii::$app->params['checkbox-radio-script']()); ?>