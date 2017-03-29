<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Inflector;
use kartik\file\FileInput;
use backend\components\NotificationDialog;


/* @var $this yii\web\View */
/* @var $model backend\models\Settings */

$this->title = 'Setting ' . $judul;
$this->params['breadcrumbs'][] = $this->title;

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

<div class="settings-create">

    <div class="row">
        <div class="col-sm-2"></div>
        <div class="col-sm-8">
            <div class="box box-danger">
                <div class="box-body">
                    <div class="settings-form">

                        <?php $form = ActiveForm::begin([
                                'options' => [
                                    'enctype' => 'multipart/form-data'
                                ],
                                'fieldConfig' => [
                                    'parts' => [
                                        '{inputClass}' => 'col-lg-12',
                                        '{theLabel}' => '',
                                    ],
                                    'template' => '<div class="row">'
                                                    . '<div class="col-lg-3">'
                                                        . '{theLabel}'
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
                        
                        <?php
                        foreach ($models as $key => $model) :
                                                                                                                           
                            echo $form->field($model, '[' . $key . ']' . 'setting_value', [
                                    'parts' => [
                                        '{theLabel}' => Inflector::camel2words($model->setting_name),
                                    ]
                                ])->widget(FileInput::classname(), [
                                    'options' => [
                                        'accept' => 'image/*'
                                    ],
                                    'pluginOptions' => [
                                        'initialPreview' => [
                                            Html::img(Yii::$app->request->baseUrl . '/img/slideshow/' . $model->setting_value, ['class'=>'file-preview-image']),
                                        ],
                                        'showRemove' => false,
                                        'showUpload' => false,
                                    ]
                                ]);
                        
                        endforeach; ?>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-lg-3"></div>
                                <div class="col-lg-6">
                                    <?php
                                    $icon = '<i class="fa fa-floppy-o"></i>&nbsp;&nbsp;&nbsp;';
                                    echo Html::submitButton($icon . 'Update', ['class' => 'btn btn-primary']); ?>
                                    <a class="btn btn-default" href="<?= Yii::$app->urlManager->createUrl(['settings/slideshow']); ?>">
                                        <i class="fa fa-rotate-left"></i>&nbsp;&nbsp;&nbsp;Cancel
                                    </a>
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
