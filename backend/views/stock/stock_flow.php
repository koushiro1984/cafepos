<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;
use backend\models\Item;
use backend\models\Storage;
use backend\models\Branch;
use backend\components\NotificationDialog;


/* @var $this yii\web\View */
/* @var $model backend\models\Stock */

$this->title = $title;
$this->params['breadcrumbs'][] = $this->title; ?>

<div class="stock-create">
    
    <?php
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
                    <div class="stock-movement-form">

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
                        
                        <?= $form->field($model, 'tanggal', [
                                'parts' => [
                                    '{inputClass}' => 'col-lg-7'
                                ],
                            ])->widget(DatePicker::className(), [
                                'pluginOptions' => Yii::$app->params['datepickerOptions'],
                            ]) ?>                                                
                        
                        <?= $form->field($model, 'item_id')->dropDownList(
                                ArrayHelper::map(
                                    Item::find()->where(['not_active' => false])->all(), 
                                    'id', 
                                    function($data) { 
                                        return '(' . $data->id . ') ' . $data->nama_item;                                 
                                    }
                                ), 
                                [
                                    'prompt' => '',
                                    'style' => 'width: 70%'
                                ]
                            ) ?>

                        <?= $form->field($model, 'item_sku_id')->textInput(['maxlength' => 16, 'style' => 'width: 70%']) ?>
                                                
                        <?php                         
                        
                        if ($flow == 'outflow' || $flow == 'transfer' || $flow == 'indelivery') { 
                            echo $form->field($model, 'storage_from')->dropDownList(
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
                                    ]
                                );
                        }                
                        
                        if ($flow == 'outflow' || $flow == 'transfer' || $flow == 'indelivery') { 
                            echo $form->field($model, 'storage_rack_from')->textInput(['maxlength' => 20, 'style' => 'width: 70%']);
                        }
                        
                        if ($flow == 'inflow' || $flow == 'transfer' || $flow == 'inreceive') { 
                            echo $form->field($model, 'storage_to')->dropDownList(
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
                                    ]
                                );
                        }
                        
                        if ($flow == 'inflow' || $flow == 'transfer' || $flow == 'inreceive') { 
                            echo $form->field($model, 'storage_rack_to')->textInput(['maxlength' => 20, 'style' => 'width: 70%']);
                        } 
                        
                        if ($flow == 'indelivery' || $flow == 'inreceive') { 
                            echo $form->field($model, 'branch_id')->dropDownList(
                                    ArrayHelper::map(
                                        Branch::find()->all(), 
                                        'id', 
                                        function($data) { 
                                            return '(' . $data->id . ') ' . $data->nama_branch;                                 
                                        }
                                    ), 
                                    [
                                        'prompt' => '',
                                        'style' => 'width: 70%'
                                    ]
                                );
                        }
                        
                        ?>

                        <?= $form->field($model, 'jumlah', [
                                'parts' => [
                                    '{inputClass}' => 'col-lg-7'
                                ],
                            ])->textInput() ?>
                        
                        <?= $form->field($model, 'keterangan')->textarea(['rows' => 2]) ?>


                        <div class="form-group">
                            <div class="row">
                                <div class="col-lg-3"></div>
                                <div class="col-lg-6">
                                    <?php
                                    $icon = '<i class="fa fa-floppy-o"></i>&nbsp;&nbsp;&nbsp;';
                                    echo Html::submitButton($model->isNewRecord ? $icon . 'Save' : $icon . 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
                                    echo '&nbsp;&nbsp;&nbsp;';
                                    echo Html::a('<i class="fa fa-rotate-left"></i>&nbsp;&nbsp;&nbsp;Cancel', ['site/dashboard'], ['class' => 'btn btn-default']); ?>
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
    $this->registerJsFile(Yii::getAlias('@common-web') . '/js/plugins/iCheck/icheck.min.js');
};

$jscript = '$("#stockmovement-item_id").select2({
                placeholder: "Select Item",
                allowClear: true
            });            
            
            var itemSku = function(datas, initSel) {
                $("#stockmovement-item_sku_id").select2({
                    placeholder: "Select SKU",
                    allowClear: true,
                    query: function(query) {
                        var data = {
                            results: datas
                        };
                        query.callback(data);
                    },
                    initSelection: initSel
                });
            };
            
            itemSku([]);
            
            $("#stockmovement-item_id").on("select2-selecting", function(e) {
                $("#stockmovement-item_sku_id").val("");
                var selected = e;
                $.ajax({
                    dataType: "json",
                    cache: false,
                    url: "' . Yii::$app->urlManager->createUrl('item-sku/get-sku-item') . '?id=" + selected.val,
                    success: function(response) {
                        itemSku(response);
                    }
                });                
            });                        
            
            $("#stockmovement-item_id").on("select2-removed", function(e) {
                $("#stockmovement-item_sku_id").val("");
                itemSku([]);
            });';

if ($status == 'danger') {
    $jscript .= 'var initSelectionSku;';
    
    if (!empty($model->item_sku_id)) {
        $jscript .= 'var initSelectionSku = function (element, callback) {
                         var data = {id: "' . $model->item_sku_id . '", text: "(' . $model->item_sku_id . ') ' . $model->itemSku->nama_sku . '"};
                         callback(data);
                     };';
     }
     
     $jscript .= '$.ajax({
                        dataType: "json",
                        cache: false,
                        url: "' . Yii::$app->urlManager->createUrl('item-sku/get-sku-item') . '?id=' . $model->item_id . '",
                        success: function(response) {
                            itemSku(response, initSelectionSku);
                            $("#stockmovement-item_sku_id").select2("val", "' . $model->item_sku_id . '");
                        }
                    });';
}

if ($flow == 'outflow' || $flow == 'transfer' || $flow == 'indelivery') { 
    
    $jscript .= '$("#stockmovement-storage_from").select2({
                    placeholder: "Select Storage",
                    allowClear: true
                });

                var storageRackFrom = function(datas, initSel) {
                    $("#stockmovement-storage_rack_from").select2({
                        placeholder: "Select Storage Rack",
                        allowClear: true,
                        query: function(query) {
                            var data = {
                                results: datas
                            };
                            query.callback(data);
                        },
                        initSelection: initSel
                    });
                };

                storageRackFrom([]);

                $("#stockmovement-storage_from").on("select2-selecting", function(e) {
                    $("#stockmovement-storage_rack_from").val("");
                    var selected = e;
                    $.ajax({
                        dataType: "json",
                        cache: false,
                        url: "' . Yii::$app->urlManager->createUrl('storage-rack/get-storage-rack') . '?id=" + selected.val,
                        success: function(response) {
                            storageRackFrom(response);
                        }
                    });
                });             

                $("#stockmovement-storage_from").on("select2-removed", function(e) {
                    $("#stockmovement-storage_rack_from").val("");
                    storageRackFrom([]);                
                });
    ';

    if ($status == 'danger') {

        $jscript .= 'var initSelectionStorageRackFrom;';

        if (!empty($model->storage_rack_from)) {
           $jscript .= 'var initSelectionStorageRackFrom = function (element, callback) {
                            var data = {id: "' . $model->storage_rack_from . '", text: "' . $model->storageRackFrom->nama_rak . '"};
                            callback(data);
                        };';
        }

        $jscript .= '$.ajax({
                        dataType: "json",
                        cache: false,
                        url: "' . Yii::$app->urlManager->createUrl('storage-rack/get-storage-rack') . '?id=' . $model->storage_from . '",
                        success: function(response) {
                            storageRackFrom(response, initSelectionStorageRackFrom);
                            $("#stockmovement-storage_rack_from").select2("val", "' . $model->storage_rack_from . '");
                        }
                    });';    
    }
    
}
            
if ($flow == 'inflow' || $flow == 'transfer' || $flow == 'inreceive') {
    
    $jscript .= '$("#stockmovement-storage_to").select2({
                    placeholder: "Select Storage",
                    allowClear: true
                });

                var storageRackTo = function(datas, initSel) {
                    $("#stockmovement-storage_rack_to").select2({
                        placeholder: "Select Storage Rack",
                        allowClear: true,
                        query: function(query) {
                            var data = {
                                results: datas
                            };
                            query.callback(data);
                        },
                        initSelection : initSel
                    });
                };

                storageRackTo([]);

                $("#stockmovement-storage_to").on("select2-selecting", function(e) {    
                    $("#stockmovement-storage_rack_to").val("");
                    var selected = e;
                    $.ajax({
                        dataType: "json",
                        cache: false,
                        url: "' . Yii::$app->urlManager->createUrl('storage-rack/get-storage-rack') . '?id=" + selected.val,
                        success: function(response) {
                            storageRackTo(response);
                        }
                    });
                });             

                $("#stockmovement-storage_to").on("select2-removed", function(e) {
                    $("#stockmovement-storage_rack_to").val("");
                    storageRackTo([]);                
                });
    ';

    if ($status == 'danger') {

        $jscript .= 'var initSelectionStorageRackTo;';       

        if (!empty($model->storage_rack_to)) {
           $jscript .= 'var initSelectionStorageRackTo = function (element, callback) {
                            var data = {id: "' . $model->storage_rack_to . '", text: "' . $model->storageRackTo->nama_rak . '"};
                            callback(data);
                        };';
        }

        $jscript .= '$.ajax({
                        dataType: "json",
                        cache: false,
                        url: "' . Yii::$app->urlManager->createUrl('storage-rack/get-storage-rack') . '?id=' . $model->storage_to . '",
                        success: function(response) {
                            storageRackTo(response, initSelectionStorageRackTo);
                            $("#stockmovement-storage_rack_to").select2("val", "' . $model->storage_rack_to . '");
                        }
                    });
                    ';    
    }
}

if ($flow == 'indelivery' || $flow == 'inreceive') { 
    
    $jscript .= '$("#stockmovement-branch_id").select2({
                    placeholder: "Select Branch",
                    allowClear: true
                });
    ';

    if ($status == 'danger') {

        $jscript .= '$("#stockmovement-branch_id").select2("val", "' . $model->branch_id . '");';   
    }
    
}

$this->registerJs($jscript); ?>