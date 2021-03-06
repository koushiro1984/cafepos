<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use backend\models\MenuCategory;
use backend\components\NotificationDialog;

/* @var $this yii\web\View */
/* @var $model backend\models\MenuCategory */
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
                <div class="menu-category-form">

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
                        ])->textInput(['maxlength' => 32, $model->isNewRecord ? '' : 'readonly' => $model->isNewRecord ? '' : 'readonly']) ?>

                    <?= $form->field($model, 'nama_category')->textInput(['maxlength' => 128]) ?>
                    
                    <?= $form->field($model, 'parent_category_id')->dropDownList(
                            ArrayHelper::map(
                                MenuCategory::find()->where(['IS', 'parent_category_id', NULL])->all(), 
                                'id', 
                                function($data) { 
                                    return '(' . $data->id . ') ' . $data->nama_category;                                 
                                }
                            ), 
                            [
                                'prompt' => '',
                                'style' => 'width: 70%'
                            ]) ?>

                    <?= $form->field($model, 'color', [
                            'parts' => [
                                '{inputClass}' => 'col-lg-7'
                            ],
                            'template' => '<div class="row">'
                                            . '<div class="col-lg-3">'
                                                . '{label}'
                                            . '</div>'
                                            . '<div class="col-lg-6">'
                                                . '<div class="{inputClass}">'
                                                    . '<div class="input-group my-colorpicker">'
                                                        . '{input}'
                                                        . '<div class="input-group-addon">'
                                                            . '<i></i>'
                                                        . '</div>'
                                                    . '</div>'
                                                . '</div>'
                                            . '</div>'
                                            . '<div class="col-lg-3">'
                                                . '{error}'
                                            . '</div>'
                                        . '</div>',
                        ])->textInput(['maxlength' => 7]) ?>
                    
                    <?= $form->field($model, 'keterangan')->textarea(['rows' => 2]) ?>

                    <?= $form->field($model, 'not_active')->checkbox(['value' => true], false) ?>      
                    
                    <?php
                    if (!$model->isNewRecord): ?>
                    
                        <div class="form-group">
                            <div class="row">
                                <div class="col-lg-3"><label class="control-label">Printer</label></div>
                                <div class="col-lg-6"><?= Html::a('<i class="fa fa-plus"></i>&nbsp;&nbsp;&nbsp;Printer', ['printer', 'id' => $model->id], ['class' => 'btn btn-success',]) ?></div>
                            </div>
                        </div>
                    
                    <?php
                    endif; ?>

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
    $this->registerCssFile(Yii::getAlias('@common-web') . '/css/colorpicker/bootstrap-colorpicker.min.css');
};

$this->params['regJsFile'][] = function() {
    $this->registerJsFile(Yii::getAlias('@common-web') . '/js/plugins/select2/select2.min.js');   
    $this->registerJsFile(Yii::getAlias('@common-web') . '/js/plugins/iCheck/icheck.min.js');
    $this->registerJsFile(Yii::getAlias('@common-web') . '/js/plugins/colorpicker/bootstrap-colorpicker.min.js');
};

$jscript = '
    $(".my-colorpicker").colorpicker();' . Yii::$app->params['checkbox-radio-script']() . '
        
    $("#menucategory-parent_category_id").select2({
        placeholder: "Select Parent Category",
        allowClear: true
    });
    
';

$this->registerJs($jscript); ?>
