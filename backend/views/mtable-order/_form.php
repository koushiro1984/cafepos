<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\components\NotificationDialog;

/* @var $this yii\web\View */
/* @var $model backend\models\MtableOrder */
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
                <div class="mtable-order-form">

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

                    <?= $form->field($model, 'mtable_session_id')->textInput(['maxlength' => 20]) ?>

    <?= $form->field($model, 'menu_id')->textInput(['maxlength' => 32]) ?>

    <?= $form->field($model, 'catatan')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'discount_type')->dropDownList([ 'percent' => 'Percent', 'value' => 'Value', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'discount')->textInput(['maxlength' => 14]) ?>

    <?= $form->field($model, 'harga_satuan')->textInput(['maxlength' => 14]) ?>

    <?= $form->field($model, 'jumlah')->textInput() ?>

    <?= $form->field($model, 'is_free_menu')->textInput() ?>

    <?= $form->field($model, 'is_void')->textInput() ?>

    <?= $form->field($model, 'void_at')->textInput() ?>

    <?= $form->field($model, 'user_void')->textInput(['maxlength' => 32]) ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'user_created')->textInput(['maxlength' => 32]) ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <?= $form->field($model, 'user_updated')->textInput(['maxlength' => 32]) ?>

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
