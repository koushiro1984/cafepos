<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\money\MaskMoney;
use kartik\date\DatePicker;
use backend\models\Menu;
use backend\models\MenuCategory;
use backend\components\NotificationDialog;

/* @var $this yii\web\View */
/* @var $model backend\models\MenuDiscount */
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
                <div class="menu-discount-form">

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

                    <?= $form->field($model, 'type')->radioList(
                        [
                            'menu' => 'Menu', 
                            'menu_category' => 'Menu category', 
                            'all' => 'All', 
                        ], 
                        [
                            'separator' => '&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;'
                        ]) ?>

                    <?= $form->field($model, 'menu_id')->dropDownList(
                            ArrayHelper::map(
                                Menu::find()->where(['not_active' => false])->all(), 
                                'id', 
                                function($data) { 
                                    return '(' . $data->id . ') ' . $data->nama_menu;                                 
                                }
                            ), 
                            [
                                'prompt' => '',
                                'style' => 'width: 70%'
                            ]) ?>

                    <?= $form->field($model, 'menu_category_id')->dropDownList(
                            ArrayHelper::map(
                                MenuCategory::find()->where(['not_active' => false])->all(), 
                                'id', 
                                function($data) { 
                                    return '(' . $data->id . ') ' . $data->nama_category;                                 
                                }
                            ), 
                            [
                                'prompt' => '',
                                'style' => 'width: 70%'
                            ]) ?>

                    <?= $form->field($model, 'discount_type')->radioList(
                        [
                            'percent' => 'Percent', 
                            'value' => 'Value', 
                        ], 
                        [
                            'separator' => '&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;'
                        ]) ?>

                    <?= $form->field($model, 'jumlah_discount', [
                            'parts' => [
                                '{inputClass}' => 'col-lg-7'
                            ],
                        ])->widget(MaskMoney::className(), [
                            'pluginOptions' => [
                                'prefix' => $model->discount_type == 'value' ? 'Rp. ' : '',
                            ]
                        ]) ?>

                    <?= $form->field($model, 'start_date', [
                            'parts' => [
                                '{inputClass}' => 'col-lg-7'
                            ],
                        ])->widget(DatePicker::className(), [
                            'pluginOptions' => Yii::$app->params['datepickerOptions']
                        ]) ?>

                    <?= $form->field($model, 'end_date', [
                            'parts' => [
                                '{inputClass}' => 'col-lg-7'
                            ],
                        ])->widget(DatePicker::className(), [
                            'pluginOptions' => Yii::$app->params['datepickerOptions']
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

$this->params['regCssFile'][] = function() {
    $this->registerCssFile(Yii::getAlias('@common-web') . '/css/select2/select2.css');
    $this->registerCssFile(Yii::getAlias('@common-web') . '/css/select2/select2-bootstrap.css');
}; 

$this->params['regJsFile'][] = function() {
    $this->registerJsFile(Yii::getAlias('@common-web') . '/js/plugins/iCheck/icheck.min.js');
    $this->registerJsFile(Yii::getAlias('@common-web') . '/js/plugins/input-mask/jquery.inputmask.js');
    $this->registerJsFile(Yii::getAlias('@common-web') . '/js/plugins/input-mask/jquery.inputmask.date.extensions.js');
    $this->registerJsFile(Yii::getAlias('@common-web') . '/js/plugins/input-mask/jquery.inputmask.extensions.js');
    $this->registerJsFile(Yii::getAlias('@common-web') . '/js/plugins/select2/select2.min.js');
};

$jscript = '$("#menudiscount-start_date, #menudiscount-end_date").inputmask("yyyy-mm-dd", {"placeholder": "yyyy-mm-dd"});
        $(\'input[name="MenuDiscount[discount_type]"]\').on("ifChecked", function() {
            var val = parseFloat($("#menudiscount-jumlah_discount").val());            
            if ($(this).val() == "percent") {
                $("#menudiscount-jumlah_discount-disp").maskMoney({prefix: "", suffix: ""}, val);                
            } else if ($(this).val() == "value") {
                $("#menudiscount-jumlah_discount-disp").maskMoney({prefix: "Rp. ", suffix: ""}, val);
            }
            $("#menudiscount-jumlah_discount-disp").maskMoney("mask");
        });
        
        $(\'input[name="MenuDiscount[type]"]\').on("ifChecked", function() {
            if ($(this).val() == "menu") {
                $(".field-menudiscount-menu_id").show();
                $(".field-menudiscount-menu_category_id").hide();
            } else if ($(this).val() == "menu_category") {
                $(".field-menudiscount-menu_category_id").show();
                $(".field-menudiscount-menu_id").hide();
            } else if ($(this).val() == "all") {
                $(".field-menudiscount-menu_category_id").hide();
                $(".field-menudiscount-menu_id").hide();
            }
        });';

$jscript .= '$("#menudiscount-menu_id").select2({
                placeholder: "Select Menu",
                allowClear: true
            });
            $("#menudiscount-menu_category_id").select2({
                placeholder: "Select Menu Category",
                allowClear: true
            });';

if ($model->type === 'menu')
    $jscript .= '$(".field-menudiscount-menu_category_id").hide();';
elseif ($model->type === 'menu_category')
    $jscript .= '$(".field-menudiscount-menu_id").hide();';
elseif ($model->type === 'all') {
    $jscript .= '$(".field-menudiscount-menu_id").hide();'
            . '$(".field-menudiscount-menu_category_id").hide();';
}
    


$this->registerJs($jscript . Yii::$app->params['checkbox-radio-script']()); ?>
