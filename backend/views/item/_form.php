<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kartik\money\MaskMoney;
use backend\models\ItemCategory;
use backend\models\Storage;
use backend\components\NotificationDialog;

/* @var $this yii\web\View */
/* @var $model backend\models\Item */
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
                <div class="item-form">                    
                    
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
                    
                    <?= $form->field($model, 'parent_item_category_id')->dropDownList(
                            ArrayHelper::map(
                                ItemCategory::find()->where(['IS', 'parent_category_id', NULL])->all(), 
                                'id', 
                                function($data) { 
                                    return '(' . $data->id . ') ' . $data->nama_category;                                 
                                }
                            ), 
                            [
                                'prompt' => '',
                                'style' => 'width: 70%'
                            ]) ?>

                    <?= $form->field($model, 'item_category_id')->textInput(['maxlength' => 16, 'style' => 'width: 70%']) ?>

                    <?= $form->field($model, 'nama_item')->textInput(['maxlength' => 32]) ?>

                    <?= $form->field($model, 'keterangan')->textarea(['rows' => 2]) ?>

                    <?= $form->field($model, 'not_active')->checkbox(['value' => true], false) ?>
                                                                              
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-2"></div>
</div><!-- /.row -->

<div class="row">
    <div class="col-sm-12">
        <div class="box box-danger">
            <div class="box-body">
                <div class="item-form">
                    <div class="row">                                            
                        <?php
                        $template =[
                            'template' => '{label}<div style="{width}">{input}</div>{error}'
                        ];

                        $storageRack = [];
                        for ($i = 1; $i <= count($modelSkus); $i++):
                            $opt = ['style' => 'width:35%'];
                            if ($i == 1)
                                $opt = ['style' => 'width:35%', 'value' => 1, 'readonly' => 'readonly'] ;
                            
                            if (!empty($modelSkus[$i]->storage_rack_id)) {
                                $storageRack[$i]['storageId'] = $modelSkus[$i]->storage_id;
                                $storageRack[$i]['id'] = $modelSkus[$i]->storage_rack_id;
                                $storageRack[$i]['nama'] = $modelSkus[$i]->storageRack->nama_rak;
                                $storageRack[$i]['component'] = '$("input#itemsku-' . $i . '-storage_rack_id")';
                            } ?>

                            <div class="col-lg-3">                                    

                                <?= $form->field($modelSkus[$i], '[' . $i . ']id', [
                                        'template' => $template['template'],
                                        'enableAjaxValidation' => true
                                    ])->textInput(['maxlength' => 16, 'style' => 'width:50%']) ?>                                   

                                <?= $form->field($modelSkus[$i], '[' . $i . ']no_urut', $template)->textInput(['maxlength' => 32, 'style' => 'width:50%', 'value' => $i, 'readonly' => 'readonly']) ?>

                                <?= $form->field($modelSkus[$i], '[' . $i . ']barcode', $template)->textInput(['maxlength' => 32, 'style' => 'width:50%']) ?>

                                <?= $form->field($modelSkus[$i], '[' . $i . ']nama_sku', $template)->textInput(['maxlength' => 32]) ?>

                                <?= $form->field($modelSkus[$i], '[' . $i . ']stok_minimal', $template)->textInput(['style' => 'width:35%']) ?>

                                <?= $form->field($modelSkus[$i], '[' . $i . ']per_stok', $template)->textInput($opt) ?>

                                <?= $form->field($modelSkus[$i], '[' . $i . ']harga_satuan', $template)->widget(MaskMoney::className(), ['options' => ['style' => 'width:55%']]) ?>

                                <?= $form->field($modelSkus[$i], '[' . $i . ']harga_beli', $template)->widget(MaskMoney::className(), ['options' => ['style' => 'width:55%']]) ?>

                                <?= $form->field($modelSkus[$i], '[' . $i . ']storage_id', $template)->dropDownList(
                                        ArrayHelper::map(
                                            Storage::find()->all(), 
                                            'id', 
                                            function($data) { 
                                                return '(' . $data->id . ') ' . $data->nama_storage;                                 
                                            }
                                        ), 
                                        [
                                            'prompt' => '',
                                            'class' => 'form-control itemsku-storage_id'
                                        ]
                                    ); ?>

                                <?= $form->field($modelSkus[$i], '[' . $i . ']storage_rack_id', $template)->textInput(['maxlength' => 20, 'class' => 'form-control itemsku-storage_rack_id']); ?>

                            </div>

                        <?php
                        endfor; ?>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-lg-12" style="text-align: center">
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
    </div>
</div><!-- /.row -->

<?php                    

ActiveForm::end();

$this->params['regCssFile'][] = function() {
    $this->registerCssFile(Yii::getAlias('@common-web') . '/css/select2/select2.css');
    $this->registerCssFile(Yii::getAlias('@common-web') . '/css/select2/select2-bootstrap.css');
}; 

$this->params['regJsFile'][] = function() {
    $this->registerJsFile(Yii::getAlias('@common-web') . '/js/plugins/select2/select2.min.js');
    $this->registerJsFile(Yii::getAlias('@common-web') . '/js/plugins/iCheck/icheck.min.js');          
}; 


$jscript = '
    $("#item-parent_item_category_id").select2({
        placeholder: "Select Item Category",
        allowClear: true                
    });

    var itemCategory = function(remoteData, initSel) {
        $("#item-item_category_id").select2({
            placeholder: "Select Sub Item Category",
            allowClear: true,
            query: function(query) {
                var data = {
                    results: remoteData
                };
                query.callback(data);
            },
            initSelection: initSel                        
        });
    };

    itemCategory([]);

    $("#item-parent_item_category_id").on("select2-selecting", function(e) {
        $("input#item-item_category_id").val("");
        var selected = e;
        $.ajax({
            dataType: "json",
            cache: false,
            url: "' . Yii::$app->urlManager->createUrl('item-category/sub-item-category') . '?id=" + selected.val,
            success: function(response) {
                itemCategory(response);
            }
        });
    });

    $("#item-parent_item_category_id").on("select2-removed", function(e) {
        $("#item-item_category_id").val("");
        itemCategory([]);
    });
    
    $("select.itemsku-storage_id").select2({
        placeholder: "Select Storage",
        allowClear: true
    });
    
    var storageRack = function(remoteData, initSel, component) {
        component.select2({
            placeholder: "Select Storage Rack",
            allowClear: true,
            query: function(query) {
                var data = {
                    results: remoteData
                };
                query.callback(data);
            },
            initSelection: initSel                        
        });
    };
    
    storageRack([], undefined, $("input.itemsku-storage_rack_id"));
    
    $("select.itemsku-storage_id").on("select2-selecting", function(e) {
        var component = $(this).parent().parent().parent().find("input.itemsku-storage_rack_id");
        component.val("");
        var selected = e;
        $.ajax({
            dataType: "json",
            cache: false,
            url: "' . Yii::$app->urlManager->createUrl('storage-rack/get-storage-rack') . '?id=" + selected.val,
            success: function(response) {
                storageRack(response, undefined, component);
            }
        });
    });
';

$jscriptTemp = '';

if (!$model->isNewRecord || $status == 'danger') {
    
    $jscript .= '
        var initSelection;                
    ';
    
    if (!empty($model->item_category_id)) {
        $jscriptTemp = ' 
            initSelection = function (element, callback) {
                var data = {id: "' . $model->item_category_id . '", text: "(' . $model->item_category_id . ')'  . $model->itemCategory->nama_category . '"};
                callback(data);
            };
        ';
    }
    
    $jscript .= $jscriptTemp . '
        $.ajax({
            dataType: "json",
            cache: false,
            url: "' . Yii::$app->urlManager->createUrl('item-category/sub-item-category') . '?id=' . $model->parent_item_category_id . '",
            success: function(response) {
                itemCategory(response, initSelection);
                $("#item-item_category_id").select2("val", "' . $model->item_category_id . '");
            }
        });
    ';    
    
    foreach ($storageRack as $key => $value) {
        $jscript .= '
            var initSelectionStorageRack' . $key . ';                
         
            initSelectionStorageRack' . $key . ' = function (element, callback) {
                var data = {id: "' . $value['id'] . '", text: "'  . $value['nama'] . '"};
                callback(data);
            };
        ';

        $jscript .= '
            $.ajax({
                dataType: "json",
                cache: false,
                url: "' . Yii::$app->urlManager->createUrl('storage-rack/get-storage-rack') . '?id=' . $value['storageId'] . '",
                success: function(response) {
                    storageRack(response, initSelectionStorageRack' . $key . ', ' . $value['component'] . ');' .
                    $value['component'] . '.select2("val", "' . $value['id'] . '");
                }
            });
        ';
        
    }
}

$this->registerJs($jscript . Yii::$app->params['checkbox-radio-script']()); ?>