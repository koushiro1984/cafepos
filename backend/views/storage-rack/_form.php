<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use backend\models\Storage;
use backend\components\NotificationDialog;

/* @var $this yii\web\View */
/* @var $model backend\models\StorageRack */
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
                <div class="storage-rack-form">

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

                    <?= $form->field($model, 'storage_id')->dropDownList(
                            ArrayHelper::map(
                                Storage::find()->all(), 
                                'id', 
                                function($data) { 
                                    return '(' . $data->id . ') ' . $data->nama_storage;                                 
                                }
                            ), 
                            [
                                'prompt' => '',
                                'style' => 'width: 70%'
                            ]) ?>

                    <?= $form->field($model, 'nama_rak', [
                        'enableAjaxValidation' => true
                    ])->textInput(['maxlength' => 32]) ?>

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

$this->params['regCssFile'][] = function() {
    $this->registerCssFile(Yii::getAlias('@common-web') . '/css/select2/select2.css');
    $this->registerCssFile(Yii::getAlias('@common-web') . '/css/select2/select2-bootstrap.css');
}; 

$this->params['regJsFile'][] = function() {
    $this->registerJsFile(Yii::getAlias('@common-web') . '/js/plugins/select2/select2.min.js');
    
    $jscript = '
            $("#storagerack-storage_id").select2({
                placeholder: "Select Storage",
                allowClear: true
            });';

    $this->registerJs($jscript);
}; ?>
