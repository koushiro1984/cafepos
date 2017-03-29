<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
use kartik\money\MaskMoney;
use backend\components\NotificationDialog;
use backend\models\MenuCategory;
use backend\models\MenuSatuan;
use backend\models\Item;

/* @var $this yii\web\View */
/* @var $model backend\models\Menu */
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

<?php 
$form = ActiveForm::begin([
            'options' => [
                'enctype' => 'multipart/form-data'
            ],
            'fieldConfig' => [
                'parts' => [
                    '{inputClass}' => 'col-lg-12',
                    '{imageFile}' => '',
                ],
                'template' => '<div class="row">'
                                . '<div class="col-lg-3">'
                                    . '{label}'
                                . '</div>'
                                . '<div class="col-lg-6">'
                                    . '<div class="{inputClass}">'
                                        . '{imageFile}'
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
                <div class="menu-form">                    
                    
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

                    <?= $form->field($model, 'nama_menu')->textInput(['maxlength' => 128]) ?>

                    <?= $form->field($model, 'menu_category_id')->dropDownList(
                            ArrayHelper::map(
                                MenuCategory::find()->where(['not_active' => false])->andWhere(['IS NOT', 'parent_category_id', NULL])->all(), 
                                'id', 
                                function($data) { 
                                    return '(' . $data->id . ') ' . $data->nama_category;                                 
                                }
                            ), 
                            [
                                'prompt' => '',
                                'style' => 'width: 70%'
                            ]) ?>

                    <?= $form->field($model, 'menu_satuan_id')->dropDownList(
                            ArrayHelper::map(
                                MenuSatuan::find()->where(['!=', 'id', ''])->all(), 
                                'id', 
                                function($data) { 
                                    return '(' . $data->id . ') ' . $data->nama_satuan;                                 
                                }
                            ), 
                            [
                                'prompt' => '',
                                'style' => 'width: 70%'
                            ]) ?>
                    
                    <?= $form->field($model, 'keterangan')->textarea(['rows' => 2]) ?>
                    
                    <?php
                    $temp = [
                        'template' => '{label}&nbsp;&nbsp;&nbsp;&nbsp;{input}'
                    ]; ?>
                                        
                    <?= $form->field($model, 'not_active')->checkbox(['value' => true], false) ?>
                    

                    <?= $form->field($model, 'harga_pokok', [
                            'parts' => [
                                '{inputClass}' => 'col-lg-7'
                            ],
                        ])->widget(MaskMoney::className()) ?>

                    <?= $form->field($model, 'biaya_lain', [
                            'parts' => [
                                '{inputClass}' => 'col-lg-7'
                            ],
                        ])->widget(MaskMoney::className()) ?>

                    <?= $form->field($model, 'harga_jual', [
                            'parts' => [
                                '{inputClass}' => 'col-lg-7'
                            ],
                        ])->widget(MaskMoney::className()) ?>                                        
                    
                    <?= $form->field($model, 'image')->widget(FileInput::classname(), [
                            'options' => [
                                'accept' => 'image/*'
                            ],
                            'pluginOptions' => [
                                'initialPreview' => [
                                    Html::img(Yii::$app->request->baseUrl . '/img/menu/' . $model->image, ['class'=>'file-preview-image']),
                                ],
                                'showRemove' => false,
                                'showUpload' => false,
                            ]
                        ]); ?>

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

                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-2"></div>
</div><!-- /.row -->


<div class="row">
    <div class="col-sm-2"></div>
    <div class="col-sm-8">
        <div class="box box-danger">
            <div class="box-header">
                <h3 class="box-title">
                    Menu Recipe
                </h3>
                <div class="box-tools">
                    <div class="input-group">
                        <a id="addButton" class="btn btn-primary" style="color: white">
                            <i class="fa fa-plus"></i>&nbsp;&nbsp;&nbsp;Add
                        </a>
                    </div>
                </div>
            </div>
            <div class="box-body table-responsive no-padding">
                
                <table id="menu-receipts" class="table table-striped table-responsive">
                    <thead>
                        <tr>
                            <th>Item ID</th>
                            <th>Nama Item</th>
                            <th>Satuan</th>
                            <th>Jumlah</th>
                            <th>Sub Total</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>                    
                    <tbody id="tbodyRecipe">                        
                        <?php
                        $subtotal = 0;
                        $indexTrx = 0;
                        
                        if (!empty($modelMenuReceipts)):                            
                            foreach ($modelMenuReceipts as $key => $modelMenuReceipt): 
                                $total = $modelMenuReceipt->itemSku->harga_beli * $modelMenuReceipt->jumlah;
                                $subtotal += $total; ?>
                            
                                <tr>
                                    <input name="indexTrx<?= $key ?>" id="indexTrx<?= $key ?>" class="indexTrx" type="hidden" value="indexTrx<?= $key ?>">                                    
                                    
                                    <input id="menureceiptId_edited" class="menureceiptId" type="hidden" name="MenuReceiptEdited[<?= $key ?>][id]" value="<?= $modelMenuReceipt->id ?>">
                                    <input id="menureceiptItem_id_edited" class="menureceiptItem_id" type="hidden" name="MenuReceiptEdited[<?= $key ?>][item_id]" value="<?= $modelMenuReceipt->item_id ?>">
                                    <input id="menureceiptItem_sku_id_edited" class="menureceiptItem_sku_id" type="hidden" name="MenuReceiptEdited[<?= $key ?>][item_sku_id]" value="<?= $modelMenuReceipt->item_sku_id ?>">
                                    <input id="menureceiptJumlah_edited" class="menureceiptJumlah" type="hidden" name="MenuReceiptEdited[<?= $key ?>][jumlah]" value="<?= $modelMenuReceipt->jumlah ?>">
                                    <input id="menureceiptJumlah_harga_satuan_edited" class="menureceiptJumlah_harga_satuan" type="hidden" name="harga_beli" value="<?= $modelMenuReceipt->itemSku->harga_beli ?>">
                                    
                                    <input id="menureceiptJumlah_subtotal" class="menureceiptJumlah_subtotal" type="hidden" name="menureceiptJumlah_subtotal" value="<?= $total ?>">

                                    <td id="item-id"><?= $modelMenuReceipt->item_id ?></td>
                                    <td id="item-name"><?= $modelMenuReceipt->item->nama_item ?></td>
                                    <td id="satuan"><?= $modelMenuReceipt->itemSku->nama_sku ?></td>
                                    <td id="jumlah"><?= $modelMenuReceipt->jumlah ?></td>
                                    <td id="subtotal"><?= $total ?></td>
                                    <td>
                                        <a id="aEdit" href="" class="btn btn-sm btn-success" data-toggle="tooltip" data-placement="top" title="Edit">
                                            <i class="glyphicon glyphicon-pencil"></i>
                                        </a>
                                        <a id="aDelete" href="" class="btn btn-sm btn-primary" data-toggle="tooltip" data-placement="top" title="Delete">
                                            <i class="glyphicon glyphicon-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                
                                <?php
                                $indexTrx = $key + 1;
                                
                            endforeach;
                        endif; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td id="jumlah-subtotal" style="font-weight: bold">
                                <span id="jumlah-subtotal-text"><?= $subtotal ?></span>
                                <input name="jumlah-subtotal" id="jumlah-subtotal" type="hidden" value="<?= $subtotal ?>">
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
                
            </div>
        </div>
    </div>
    <div class="col-sm-2"></div>
</div>

<?php
ActiveForm::end(); ?>

<table id="temp" style="display: none">
    <tr>
        <td id="item-id"></td>
        <td id="item-name"></td>
        <td id="satuan"></td>
        <td id="jumlah"></td>
        <td id="subtotal"></td>
        <td>
            <a id="aEdit" href="" class="btn btn-sm btn-success" data-toggle="tooltip" data-placement="top" title="Edit">
                <i class="glyphicon glyphicon-pencil"></i>
            </a>
            <a id="aDelete" href="" class="btn btn-sm btn-primary" data-toggle="tooltip" data-placement="top" title="Delete">
                <i class="glyphicon glyphicon-trash"></i>
            </a>
        </td>
    </tr>
</table>

<div class="modal fade" id="modalDialog" tabindex="-1" role="dialog">
    <div class="modal-dialog">    
        <?php $form = ActiveForm::begin([
                    'id' => 'form-receipt',
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
            ]); 
            
            echo Html::hiddenInput('inputState', null, ['id' => 'inputState']);
            echo Html::hiddenInput('currentIndexTrx', null, ['id' => 'currentIndexTrx']); ?>
        
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">
                        Add Recipe
                    </h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-primary btn-sm" data-dismiss="modal">
                            <i class="fa fa-times"></i>
                        </button>

                    </div>
                </div>
                <div class="box-body">                

                    <?= $form->field($modelMenuReceipt, '[]item_id')->dropDownList(
                            ArrayHelper::map(
                                Item::find()->where(['not_active' => false])->all(), 
                                'id', 
                                function($data) { 
                                    return '(' . $data->id . ') ' . $data->nama_item;                                 
                                }
                            ), 
                            [
                                'prompt' => '',
                            ]) ?>

                    <?= $form->field($modelMenuReceipt, '[]item_sku_id')->textInput(['maxlength' => 16]) ?>
                    <input id="harga_beli" type="hidden" value="">

                    <?= $form->field($modelMenuReceipt, '[]jumlah', [
                            'parts' => [
                                '{inputClass}' => 'col-lg-7'
                            ],
                        ])->textInput() ?>                    

                </div>
                <div class="box-footer" style="text-align: right">
                    <?= Html::submitButton('<i class="fa fa-check"></i>&nbsp;&nbsp;&nbsp;Add', ['id' => 'aYes', 'class' => 'btn btn-primary']); ?>
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        <i class="fa fa-rotate-left"></i>&nbsp;&nbsp;&nbsp;
                        Cancel
                    </button>
                </div> 
            </div>
        <?php ActiveForm::end(); ?>
    </div>    
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

$jscript = '$("#menu-menu_category_id").select2({
                placeholder: "Select Menu Category",
                allowClear: true
            });

            $("#menu-menu_satuan_id").select2({
                placeholder: "Select Satuan",
                allowClear: true
            });

            $("#menureceipt-item_id").select2({
                placeholder: "Select Item",
                allowClear: true                
            });            

            var itemSkuId = function(remoteData, initSel) {
                $("#menureceipt-item_sku_id").select2({
                    placeholder: "Select SKU",
                    allowClear: true,
                    query: function(query) {
                        var data = {
                            results: remoteData
                        };
                        query.callback(data);
                    },
                    initSelection: initSel,
                });
            };

            itemSkuId([]);

            $("#menureceipt-item_id").on("select2-selecting", function(e) {
                $("#menureceipt-item_sku_id").val("");
                var selected = e;
                $.ajax({
                    dataType: "json",
                    cache: false,
                    url: "' . Yii::$app->urlManager->createUrl('item-sku/get-sku-item') . '?id=" + selected.val,
                    success: function(response) {
                        itemSkuId(response);
                    }
                });
            });                                

            $("#menureceipt-item_id").on("select2-removed", function(e) {
                $("#menureceipt-item_sku_id").val("");
                itemSkuId([]);
            });

            $("#menureceipt-item_sku_id").on("select2-selecting", function(e) {
                var harga_beli = e["object"]["harga_beli"];
                $("input#harga_beli").val(harga_beli);
            });

            $("a#addButton").click(function(event){
                event.preventDefault();
                $("#menureceipt-item_id").select2("val", "");
                $("#menureceipt-item_sku_id").val("");
                itemSkuId([]);                
                $("#menureceipt-jumlah").val("");
                if ($(".form-group").hasClass("has-error")) {
                    $(".form-group").removeClass("has-error");
                    $(".help-block").empty();
                }
                if ($(".form-group").hasClass("has-success")) $(".form-group").removeClass("has-success");
                
                $("#modalDialog").find("input#inputState").val("add");
                $("#modalDialog").modal();
            });

            var indexInput = ' . $indexTrx . ';  

            $("form#form-receipt").on("beforeSubmit", function(event) {
                if (!$(".form-group").hasClass("has-error")) {                                        
                    
                    var state = $(this).find("input#inputState").val();
                    
                    var item_id = $(this).find("#menureceipt-item_id");
                    var item_sku_id = $(this).find("#menureceipt-item_sku_id");
                    var jumlah = $(this).find("#menureceipt-jumlah");
                    var harga_satuan = $(this).find("#harga_beli");
                    
                    var subtotal = parseFloat(jumlah.val()) * parseFloat(harga_satuan.val());                                                 
                    
                    if (state == "add") {                    
                        var inputIndexTrx = $("<input>").attr("type", "hidden").attr("name", "indexTrx" + indexInput).attr("id", "indexTrx" + indexInput).attr("class", "indexTrx").attr("value", "indexTrx" + indexInput);                        

                        var inputItem_id = $("<input>").attr("type", "hidden").attr("name", item_id.attr("name").replace("[]", "[" + indexInput + "]")).attr("class", "menureceiptItem_id").attr("value", item_id.val());                        
                        var inputItem_sku_id = $("<input>").attr("type", "hidden").attr("name", item_sku_id.attr("name").replace("[]", "[" + indexInput + "]")).attr("class", "menureceiptItem_sku_id").attr("value", item_sku_id.val());                       
                        var inputJumlah = $("<input>").attr("type", "hidden").attr("name", jumlah.attr("name").replace("[]", "[" + indexInput + "]")).attr("id", "menureceiptJumlah").attr("class", "menureceiptJumlah").attr("value", jumlah.val());                        
                        var inputHargaSatuan = $("<input>").attr("type", "hidden").attr("name", "harga_beli").attr("class", "menureceiptJumlah_harga_satuan").attr("value", harga_satuan.val());                                                                
                        
                        var jumlahSubtotal = parseFloat($("td#jumlah-subtotal input#jumlah-subtotal").val());
                        jumlahSubtotal += subtotal;
                        $("td#jumlah-subtotal input#jumlah-subtotal").val(jumlahSubtotal);
                        $("td#jumlah-subtotal span#jumlah-subtotal-text").html(jumlahSubtotal);

                        $("#menu-harga_pokok").val(jumlahSubtotal);
                        $("#menu-harga_pokok-disp").maskMoney("mask", jumlahSubtotal);                        

                        var comp = $("#temp").clone();
                        comp.children().find("tr").append(inputIndexTrx);
                        comp.children().find("tr").append(inputItem_id).append(inputItem_sku_id).append(inputJumlah);
                        comp.children().find("tr").append(inputHargaSatuan);
                        
                        comp.children().find("#item-id").append(item_id.val());
                        
                        var data = item_id.select2("data").text;
                        var arr = data.split(")");
                        comp.children().find("#item-name").html(arr[1]);
                        
                        data = item_sku_id.select2("data").text;
                        arr = data.split(")");
                        comp.children().find("#satuan").html(arr[1]);
                        
                        
                        comp.children().find("#jumlah").html(jumlah.val());
                        comp.children().find("#subtotal").html(subtotal);
                        $("#tbodyRecipe").append(comp.children().html());
                        $("#tbodyRecipe").find("a#aEdit").tooltip();
                        $("#tbodyRecipe").find("a#aDelete").tooltip();

                        $("#modalDialog").modal("hide");

                        indexInput++;
                    } else if (state == "edit") {
                        var indexTrx = $(this).find("input#currentIndexTrx").val();
                        var rowObj = $("#tbodyRecipe").find("input#" + indexTrx).parent();
                        
                        rowObj.find("input.menureceiptItem_id").val(item_id.val());
                        rowObj.find("input.menureceiptItem_sku_id").val(item_sku_id.val());
                        rowObj.find("input.menureceiptJumlah").val(jumlah.val());
                        rowObj.find("input.menureceiptJumlah_harga_satuan").val(harga_satuan.val());
                        rowObj.find("input.menureceiptJumlah_subtotal").val(subtotal);
                        
                        rowObj.find("#item-id").html(item_id.val());
                        
                        var data = item_id.select2("data").text;
                        var arr = data.split(")");
                        rowObj.find("#item-name").html(arr[1]);
                        
                        data = $(this).find("#menureceipt-item_sku_id").select2("data").text;
                        arr = data.split(")");
                        rowObj.find("#satuan").html(arr[1]);
                                                
                        rowObj.find("#jumlah").html(jumlah.val());
                        rowObj.find("#subtotal").html(subtotal);
                        
                        var totalHarga = 0;
                        $("#tbodyRecipe tr").each(function() {                        
                            totalHarga += parseFloat($(this).find("input.menureceiptJumlah").val()) * parseFloat($(this).find("input.menureceiptJumlah_harga_satuan").val());                            
                        });
                        
                        $("td#jumlah-subtotal input#jumlah-subtotal").val(totalHarga);
                        $("td#jumlah-subtotal span#jumlah-subtotal-text").html(totalHarga);
                        
                        $("#menu-harga_pokok").val(jumlahSubtotal);
                        $("#menu-harga_pokok-disp").maskMoney("mask", totalHarga);   
                        
                        $("#modalDialog").modal("hide");
                    }
                }
                return false;
            });                

            $(document).on("click", "a#aDelete", function(event){
                event.preventDefault();
                var remove = true;
                $(this).parent().parent().find("input").each(function(i, val) {
                    if ($(val).attr("id") == "menureceiptJumlah_subtotal") {
                        var jumlahSubtotal = parseFloat($("td#jumlah-subtotal input").val());
                        jumlahSubtotal -= parseFloat($(val).val());
                        $("#menu-harga_pokok").val(jumlahSubtotal);
                        $("#menu-harga_pokok-disp").maskMoney("mask", jumlahSubtotal);
                        $("td#jumlah-subtotal input").val(jumlahSubtotal);
                        $("td#jumlah-subtotal span#jumlah-subtotal-text").html(jumlahSubtotal);
                    }

                    $(val).attr("name", $(val).attr("name").replace("Edited", "Deleted"));

                    if ($(val).attr("name").indexOf("Deleted") > -1) {
                        remove = false;
                    }
                });
        
                $(this).parent().parent().fadeOut(500, function() {
                    if (remove)
                        $(this).remove();
                });
            });
            
            $(document).on("click", "a#aEdit", function(event){
                event.preventDefault();
                
                var thisObj = $(this).parent().parent();                
                
                $("#menureceipt-item_id").select2("val", thisObj.find("input.menureceiptItem_id").val());
                
                var initSelection = function (element, callback) {
                    var data = {id: thisObj.find("input.menureceiptItem_sku_id").val(), text: "(" + thisObj.find("input.menureceiptItem_sku_id").val() + ") " + thisObj.find("td#satuan").text()};
                    callback(data);
                };
                
                $.ajax({
                    dataType: "json",
                    cache: false,
                    url: "' . Yii::$app->urlManager->createUrl('item-sku/get-sku-item') . '?id=" + thisObj.find("input.menureceiptItem_id").val(),
                    success: function(response) {
                        itemSkuId(response, initSelection);
                        $("#menureceipt-item_sku_id").select2("val", thisObj.find("input.menureceiptItem_sku_id").val());
                    }
                });                
                                
                $("#harga_beli").val(thisObj.find("input.menureceiptJumlah_harga_satuan").val());                
                $("#menureceipt-jumlah").val(thisObj.find("input.menureceiptJumlah").val());
                if ($(".form-group").hasClass("has-error")) {
                    $(".form-group").removeClass("has-error");
                    $(".help-block").empty();
                }
                if ($(".form-group").hasClass("has-success")) $(".form-group").removeClass("has-success");
                
                $("#modalDialog").find("input#inputState").val("edit");
                $("#modalDialog").find("input#currentIndexTrx").val(thisObj.find("input.indexTrx").val());
                $("#modalDialog").modal();
            });
            
';    

$jscript .= '$(\'[data-toggle="tooltip"]\').tooltip();';

$this->registerJs($jscript . Yii::$app->params['checkbox-radio-script']()); ?>
