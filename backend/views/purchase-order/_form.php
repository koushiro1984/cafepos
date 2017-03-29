<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use kartik\money\MaskMoney;
use backend\models\Supplier;
use backend\models\Item;
use backend\components\NotificationDialog;

/* @var $this yii\web\View */
/* @var $model backend\models\PurchaseOrder */
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

endif; 

$form = ActiveForm::begin([
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
                <div class="purchase-order-form">                    
                    
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
                        ])->textInput(['maxlength' => 13, 'readonly' => 'readonly']) ?>

                    <?= $form->field($model, 'date', [
                            'parts' => [
                                '{inputClass}' => 'col-lg-7'
                            ],
                        ])->widget(DatePicker::className(), [
                            'pluginOptions' => Yii::$app->params['datepickerOptions'],
                        ]) ?>

                    <?= $form->field($model, 'kd_supplier')->dropDownList(
                            ArrayHelper::map(
                                Supplier::find()->all(), 
                                'kd_supplier', 
                                function($data) { 
                                    return '(' . $data->kd_supplier . ') ' . $data->nama;                                 
                                }
                            ), 
                            [
                                'prompt' => '',
                                'style' => 'width: 70%'
                            ]) ?>

                    <?= $form->field($model, 'jumlah_item', [
                            'parts' => [
                                '{inputClass}' => 'col-lg-7'
                            ],
                        ])->textInput(['readonly' => 'readonly']) ?>

                    <?= $form->field($model, 'jumlah_harga', [
                            'parts' => [
                                '{inputClass}' => 'col-lg-7'
                            ],
                        ])->widget(MaskMoney::className(), ['readonly' => 'readonly']) ?>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-lg-3"></div>
                            <div class="col-lg-6">
                                <?php
                                $icon = '<i class="fa fa-floppy-o"></i>&nbsp;&nbsp;&nbsp;';
                                echo Html::submitButton($model->isNewRecord ? $icon . 'Save' : $icon . 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
                                echo '&nbsp;&nbsp;&nbsp;';
                                echo Html::a('<i class="fa fa-rotate-left"></i>&nbsp;&nbsp;&nbsp;Cancel', ['index'], ['class' => 'btn btn-default']); 
                                
                                if (!$model->isNewRecord) {
                                  echo '&nbsp;&nbsp;&nbsp;';
                                  echo Html::a('<i class="fa fa-print"></i>&nbsp;&nbsp;&nbsp;Print', ['print', 'id' => $model->id], ['class' => 'btn btn-success']);
                                } ?>
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
                    Item
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
                    <tbody id="tbodyItem">                        
                        <?php
                        $subtotal = 0;
                        $jumlahItem = 0;
                        $indexTrx = 0;
                        
                        if (!empty($modelPurchaseOrderTrxs)):                                                                                                 
                            foreach ($modelPurchaseOrderTrxs as $key => $modelPurchaseOrderTrx):                                                             

                                $total = $modelPurchaseOrderTrx->jumlah_harga;
                                $subtotal += $total; 
                                $jumlahItem += $modelPurchaseOrderTrx->jumlah_order; ?>
                            
                                <tr>
                                    <input name="indexTrx<?= $key ?>" id="indexTrx<?= $key ?>" class="indexTrx" type="hidden" value="indexTrx<?= $key ?>">
                                    
                                    <input id="purchaseordertrxId_edited" class="purchaseordertrxId" type="hidden" name="PurchaseOrderTrxEdited[<?= $key ?>][id]" value="<?= $modelPurchaseOrderTrx->id ?>">
                                    <input id="purchaseordertrxItem_id_edited" class="purchaseordertrxItem_id" type="hidden" name="PurchaseOrderTrxEdited[<?= $key ?>][item_id]" value="<?= $modelPurchaseOrderTrx->item_id ?>">
                                    <input id="purchaseordertrxItem_sku_id_edited" class="purchaseordertrxItem_sku_id" type="hidden" name="PurchaseOrderTrxEdited[<?= $key ?>][item_sku_id]" value="<?= $modelPurchaseOrderTrx->item_sku_id ?>">
                                    <input id="purchaseordertrxJumlah_order_edited" class="purchaseordertrxJumlah_order" type="hidden" name="PurchaseOrderTrxEdited[<?= $key ?>][jumlah_order]" value="<?= $modelPurchaseOrderTrx->jumlah_order ?>">
                                    <input id="purchaseordertrxJumlah_harga_satuan_edited" class="purchaseordertrxJumlah_harga_satuan" type="hidden" name="PurchaseOrderTrxEdited[<?= $key ?>][harga_satuan]" value="<?= $modelPurchaseOrderTrx->harga_satuan ?>">
                                    <input id="purchaseordertrxJumlah_jumlah_harga_edited" class="purchaseordertrxJumlah_jumlah_harga" type="hidden" name="PurchaseOrderTrxEdited[<?= $key ?>][jumlah_harga]" value="<?= $modelPurchaseOrderTrx->jumlah_harga ?>">
                                    
                                    <input id="purchaseordertrxJumlah_subtotal" class="purchaseordertrxJumlah_subtotal" type="hidden" name="purchaseordertrxJumlah_subtotal" value="<?= $total ?>">
                                    <input id="purchaseordertrxJumlah_order" class="purchaseordertrxJumlah_order" type="hidden" name="purchaseordertrxJumlah_subtotal_item" value="<?= $modelPurchaseOrderTrx->jumlah_order ?>">

                                    <td id="item-id"><?= $modelPurchaseOrderTrx->item_id ?></td>
                                    <td id="item-name"><?= $modelPurchaseOrderTrx->item->nama_item ?></td>
                                    <td id="satuan"><?= $modelPurchaseOrderTrx->itemSku->nama_sku ?></td>
                                    <td id="jumlah"><?= $modelPurchaseOrderTrx->jumlah_order ?></td>
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
                                <input id ="jumlah-subtotal-harga" name="jumlah-subtotal" type="hidden" value="<?= $subtotal ?>">
                                <input id ="jumlah-subtotal-item" name="jumlah-item" type="hidden" value="<?= $jumlahItem ?>">
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
                    'id' => 'form-item',
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
                        Add Item
                    </h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-primary btn-sm" data-dismiss="modal">
                            <i class="fa fa-times"></i>
                        </button>

                    </div>
                </div>
                <div class="box-body">                

                    <?= $form->field($modelPurchaseOrderTrx, '[]item_id')->dropDownList(
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

                    <?= $form->field($modelPurchaseOrderTrx, '[]item_sku_id')->textInput(['maxlength' => 16]) ?>
                    
                    <?= $form->field($modelPurchaseOrderTrx, '[]harga_satuan')->widget(MaskMoney::className()) ?>
                    
                    <?= $form->field($modelPurchaseOrderTrx, '[]jumlah_harga', [
                            'template' => '{input}'
                        ])->hiddenInput() ?>

                    <?= $form->field($modelPurchaseOrderTrx, '[]jumlah_order', [
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
    $this->registerJsFile(Yii::getAlias('@common-web') . '/js/plugins/input-mask/jquery.inputmask.js');
    $this->registerJsFile(Yii::getAlias('@common-web') . '/js/plugins/input-mask/jquery.inputmask.date.extensions.js');
    $this->registerJsFile(Yii::getAlias('@common-web') . '/js/plugins/input-mask/jquery.inputmask.extensions.js');
};

$jscript = '$("#purchaseorder-date").inputmask("yyyy-mm-dd", {"placeholder": "yyyy-mm-dd"});
            $("#purchaseorder-kd_supplier").select2({
                placeholder: "Select Supplier",
                allowClear: true
            });

            $("#purchaseordertrx-item_id").select2({
                placeholder: "Select Item",
                allowClear: true
            });

            var itemSkuId = function(remoteData, initSel) {
                $("#purchaseordertrx-item_sku_id").select2({
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

            $("#purchaseordertrx-item_id").on("select2-selecting", function(e) {
                $("#purchaseordertrx-item_sku_id").val("");
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

            $("#purchaseordertrx-item_id").on("select2-removed", function(e) {
                $("#purchaseordertrx-item_sku_id").val("");
                itemSkuId([]);
            });

            $("#purchaseordertrx-item_sku_id").on("select2-selecting", function(e) {
                var harga_satuan = parseFloat(e["object"]["harga_beli"]);
                $("#purchaseordertrx-harga_satuan").val(harga_satuan);
                $("#purchaseordertrx-harga_satuan-disp").maskMoney("mask", harga_satuan);
            });

            $("a#addButton").click(function(event){
                event.preventDefault();
                $("#purchaseordertrx-item_id").select2("val", "");
                $("#purchaseordertrx-item_sku_id").val("");
                itemSkuId([]);                
                $("#purchaseordertrx-harga_satuan").val("");
                $("#purchaseordertrx-harga_satuan-disp").maskMoney("mask", 0);
                $("#purchaseordertrx-jumlah_order").val("");
                if ($(".form-group").hasClass("has-error")) {
                    $(".form-group").removeClass("has-error");
                    $(".help-block").empty();
                }
                if ($(".form-group").hasClass("has-success")) $(".form-group").removeClass("has-success");
                
                $("#modalDialog").find("input#inputState").val("add");                
                $("#modalDialog").modal();                
            });

            var indexInput = ' . $indexTrx . ';

            $("form#form-item").on("beforeSubmit", function(event) {
                if (!$(".form-group").hasClass("has-error")) {
                    var state = $(this).find("input#inputState").val();
                    
                    var item_id = $(this).find("#purchaseordertrx-item_id");
                    var item_sku_id = $(this).find("#purchaseordertrx-item_sku_id");
                    var jumlah = $(this).find("#purchaseordertrx-jumlah_order");
                    var harga_satuan = $(this).find("#purchaseordertrx-harga_satuan");
                    
                    var subtotal = parseFloat(jumlah.val()) * parseFloat(harga_satuan.val());
                    
                    var jumlah_harga = $(this).find("#purchaseordertrx-jumlah_harga");                                        
                    
                    if (state == "add") {                    
                        var inputIndexTrx = $("<input>").attr("type", "hidden").attr("name", "indexTrx" + indexInput).attr("id", "indexTrx" + indexInput).attr("class", "indexTrx").attr("value", "indexTrx" + indexInput);                        

                        var inputItem_id = $("<input>").attr("type", "hidden").attr("name", item_id.attr("name").replace("[]", "[" + indexInput + "]")).attr("class", "purchaseordertrxItem_id").attr("value", item_id.val());                        
                        var inputItem_sku_id = $("<input>").attr("type", "hidden").attr("name", item_sku_id.attr("name").replace("[]", "[" + indexInput + "]")).attr("class", "purchaseordertrxItem_sku_id").attr("value", item_sku_id.val());                       
                        var inputJumlah = $("<input>").attr("type", "hidden").attr("name", jumlah.attr("name").replace("[]", "[" + indexInput + "]")).attr("id", "purchaseordertrxJumlah_order").attr("class", "purchaseordertrxJumlah_order").attr("value", jumlah.val());                        
                        var inputHargaSatuan = $("<input>").attr("type", "hidden").attr("name", harga_satuan.attr("name").replace("[]", "[" + indexInput + "]")).attr("class", "purchaseordertrxJumlah_harga_satuan").attr("value", harga_satuan.val());                                                                
                        var inputSubtotal = $("<input>").attr("type", "hidden").attr("name", "purchaseordertrxJumlah_subtotal").attr("id", "purchaseordertrxJumlah_subtotal").attr("class", "purchaseordertrxJumlah_subtotal").attr("value", subtotal);                       
                        var inputJumlahHarga = $("<input>").attr("type", "hidden").attr("name", jumlah_harga.attr("name").replace("[]", "[" + indexInput + "]")).attr("class", "purchaseordertrxJumlah_jumlah_harga").attr("value", subtotal);
                        
                        var jumlahSubtotal = parseFloat($("td#jumlah-subtotal input#jumlah-subtotal-harga").val());
                        jumlahSubtotal += subtotal;
                        $("td#jumlah-subtotal input#jumlah-subtotal-harga").val(jumlahSubtotal);
                        $("td#jumlah-subtotal span#jumlah-subtotal-text").html(jumlahSubtotal);

                        $("#purchaseorder-jumlah_harga").val(jumlahSubtotal);
                        $("#purchaseorder-jumlah_harga-disp").maskMoney("mask", jumlahSubtotal);

                        var jumlahSubtotalItem = parseFloat($("td#jumlah-subtotal input#jumlah-subtotal-item").val() ? $("td#jumlah-subtotal input#jumlah-subtotal-item").val() : 0);
                        jumlahSubtotalItem += parseFloat(jumlah.val());
                        $("td#jumlah-subtotal input#jumlah-subtotal-item").val(jumlahSubtotalItem);
                        $("#purchaseorder-jumlah_item").val(jumlahSubtotalItem);

                        var comp = $("#temp").clone();
                        comp.children().find("tr").append(inputIndexTrx);
                        comp.children().find("tr").append(inputItem_id).append(inputItem_sku_id).append(inputJumlah);
                        comp.children().find("tr").append(inputSubtotal).append(inputHargaSatuan).append(inputJumlahHarga);
                        
                        comp.children().find("#item-id").append(item_id.val());
                        
                        var data = item_id.select2("data").text;
                        var arr = data.split(")");
                        comp.children().find("#item-name").html(arr[1]);
                        
                        data = item_sku_id.select2("data").text;
                        arr = data.split(")");
                        comp.children().find("#satuan").html(arr[1]);
                        
                        
                        comp.children().find("#jumlah").html(jumlah.val());
                        comp.children().find("#subtotal").html(subtotal);
                        $("#tbodyItem").append(comp.children().html());
                        $("#tbodyItem").find("a#aEdit").tooltip();
                        $("#tbodyItem").find("a#aDelete").tooltip();

                        $("#modalDialog").modal("hide");

                        indexInput++;
                    } else if (state == "edit") {
                        var indexTrx = $(this).find("input#currentIndexTrx").val();
                        var rowObj = $("#tbodyItem").find("input#" + indexTrx).parent();
                        
                        rowObj.find("input.purchaseordertrxItem_id").val(item_id.val());
                        rowObj.find("input.purchaseordertrxItem_sku_id").val(item_sku_id.val());
                        rowObj.find("input.purchaseordertrxJumlah_order").val(jumlah.val());
                        rowObj.find("input.purchaseordertrxJumlah_harga_satuan").val(harga_satuan.val());
                        rowObj.find("input.purchaseordertrxJumlah_subtotal").val(subtotal);
                        rowObj.find("input.purchaseordertrxJumlah_jumlah_harga").val(subtotal);
                        
                        rowObj.find("#item-id").html(item_id.val());
                        
                        var data = item_id.select2("data").text;
                        var arr = data.split(")");
                        rowObj.find("#item-name").html(arr[1]);
                        
                        data = $(this).find("#purchaseordertrx-item_sku_id").select2("data").text;
                        arr = data.split(")");
                        rowObj.find("#satuan").html(arr[1]);
                                                
                        rowObj.find("#jumlah").html(jumlah.val());
                        rowObj.find("#subtotal").html(subtotal);
                        
                        var totalItem = 0;
                        var totalHarga = 0;
                        $("#tbodyItem tr").each(function() {
                            totalItem += parseFloat($(this).find("input.purchaseordertrxJumlah_order").val());
                            totalHarga += parseFloat($(this).find("input.purchaseordertrxJumlah_order").val()) * parseFloat($(this).find("input.purchaseordertrxJumlah_harga_satuan").val());                            
                        });
                        
                        $("td#jumlah-subtotal input#jumlah-subtotal-item").val(totalItem);
                        
                        $("td#jumlah-subtotal input#jumlah-subtotal-harga").val(totalHarga);
                        $("td#jumlah-subtotal span#jumlah-subtotal-text").html(totalHarga);
                        
                        $("#purchaseorder-jumlah_item").val(totalItem);
                        
                        $("#purchaseorder-jumlah_harga").val(totalHarga);
                        $("#purchaseorder-jumlah_harga-disp").maskMoney("mask", totalHarga);
                        
                        $("#modalDialog").modal("hide");
                    }
                }
                return false;
            });

            $(document).on("click", "a#aDelete", function(event){
                event.preventDefault();
                var remove = true;
                $(this).parent().parent().find("input").each(function(i, val) {
                    if ($(val).attr("id") == "purchaseordertrxJumlah_subtotal") {
                        var jumlahSubtotal = parseFloat($("td#jumlah-subtotal input#jumlah-subtotal-harga").val());
                        jumlahSubtotal -= parseFloat($(val).val());
                        $("#purchaseorder-jumlah_harga").val(jumlahSubtotal);
                        $("#purchaseorder-jumlah_harga-disp").maskMoney("mask", jumlahSubtotal);
                        $("td#jumlah-subtotal input#jumlah-subtotal-harga").val(jumlahSubtotal);
                        $("td#jumlah-subtotal span#jumlah-subtotal-text").html(jumlahSubtotal);                                                
                    }
                    
                    if ($(val).attr("id") == "purchaseordertrxJumlah_order") {
                        var jumlahSubtotalItem = parseFloat($("td#jumlah-subtotal input#jumlah-subtotal-item").val() ? $("td#jumlah-subtotal input#jumlah-subtotal-item").val() : 0);
                        jumlahSubtotalItem -= parseFloat($(val).val());
                        $("#purchaseorder-jumlah_item").val(jumlahSubtotalItem);
                        $("td#jumlah-subtotal input#jumlah-subtotal-item").val(jumlahSubtotalItem);
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
                
                $("#purchaseordertrx-item_id").select2("val", thisObj.find("input.purchaseordertrxItem_id").val());
                
                var initSelection = function (element, callback) {
                    var data = {id: thisObj.find("input.purchaseordertrxItem_sku_id").val(), text: "(" + thisObj.find("input.purchaseordertrxItem_sku_id").val() + ") " + thisObj.find("td#satuan").text()};
                    callback(data);
                };
                
                $.ajax({
                    dataType: "json",
                    cache: false,
                    url: "' . Yii::$app->urlManager->createUrl('item-sku/get-sku-item') . '?id=" + thisObj.find("input.purchaseordertrxItem_id").val(),
                    success: function(response) {
                        itemSkuId(response, initSelection);
                        $("#purchaseordertrx-item_sku_id").select2("val", thisObj.find("input.purchaseordertrxItem_sku_id").val());
                    }
                });                
                                
                $("#purchaseordertrx-harga_satuan").val(thisObj.find("input.purchaseordertrxJumlah_harga_satuan").val());
                $("#purchaseordertrx-harga_satuan-disp").maskMoney("mask", parseFloat(thisObj.find("input.purchaseordertrxJumlah_harga_satuan").val()));
                $("#purchaseordertrx-jumlah_order").val(thisObj.find("input.purchaseordertrxJumlah_order").val());
                if ($(".form-group").hasClass("has-error")) {
                    $(".form-group").removeClass("has-error");
                    $(".help-block").empty();
                }
                if ($(".form-group").hasClass("has-success")) $(".form-group").removeClass("has-success");
                
                $("#modalDialog").find("input#inputState").val("edit");
                $("#modalDialog").find("input#currentIndexTrx").val(thisObj.find("input.indexTrx").val());
                $("#modalDialog").modal();
            });
            
            $("#purchaseorder-jumlah_harga-disp").off("keypress");
            $("#purchaseorder-jumlah_harga-disp").off("keyup");
';   

$jscript .= '$(\'[data-toggle="tooltip"]\').tooltip();';

$this->registerJs($jscript); ?>
