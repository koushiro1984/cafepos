<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\widgets\PjaxAsset;
use kartik\date\DatePicker;
use kartik\money\MaskMoney;
use backend\models\Supplier;
use backend\models\Storage;
use backend\components\NotificationDialog;

/* @var $this yii\web\View */
/* @var $model backend\models\SupplierDelivery */
/* @var $form yii\widgets\ActiveForm */

PjaxAsset::register($this);

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
                <div class="supplier-delivery-form">                   
                    
                    <div class="form-group">
                        <div class="row">
                            <div class="col-lg-6">
                                <?php
                                if (!$model->isNewRecord)
                                    echo Html::a('<i class="fa fa-upload"></i>&nbsp;&nbsp;&nbsp;' . 'Create', ['create'], ['class' => 'btn btn-success btnLink']); ?>
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
                                  echo Html::a('<i class="fa fa-print"></i>&nbsp;&nbsp;&nbsp;Print', ['print', 'id' => $model->id], ['class' => 'btn btn-success btnLink']);
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
    <div class="col-sm-12">
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
                            <th>No. PO</th>
                            <th>Item ID</th>
                            <th>Nama Item</th>
                            <th>Satuan</th>
                            <th>Jumlah</th>
                            <th>Sub Total</th>
                            <th>Storage</th>
                            <th>Rak</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>                    
                    <tbody id="tbodyItem">                        
                        <?php
                        $subtotal = 0;
                        $jumlahItem = 0;
                        $indexTrx = 0;
                        
                        if (!empty($modelSupplierDeliveryTrxs)):                                                                                                 
                            foreach ($modelSupplierDeliveryTrxs as $key => $modelSupplierDeliveryTrx): 

                                $total = $modelSupplierDeliveryTrx->jumlah_harga;
                                $subtotal += $total; 
                                $jumlahItem += $modelSupplierDeliveryTrx->jumlah_terima; ?>
                            
                                <tr>  
                                    <input name="indexTrx<?= $key ?>" id="indexTrx<?= $key ?>" class="indexTrx" type="hidden" value="indexTrx<?= $key ?>">
                                    
                                    <input id="supplierdeliverytrxId_edited" class="supplierdeliverytrxId" type="hidden" name="SupplierDeliveryTrxEdited[<?= $key ?>][id]" value="<?= $modelSupplierDeliveryTrx->id ?>">
                                    <input id="supplierdeliverytrxItem_id_edited" class="supplierdeliverytrxItem_id" type="hidden" name="SupplierDeliveryTrxEdited[<?= $key ?>][item_id]" value="<?= $modelSupplierDeliveryTrx->item_id ?>">
                                    <input id="supplierdeliverytrxItem_sku_id_edited" class="supplierdeliverytrxItem_sku_id" type="hidden" name="SupplierDeliveryTrxEdited[<?= $key ?>][item_sku_id]" value="<?= $modelSupplierDeliveryTrx->item_sku_id ?>">
                                    <input id="supplierdeliverytrxJumlah_terima_edited" class="supplierdeliverytrxJumlah_terima" type="hidden" name="SupplierDeliveryTrxEdited[<?= $key ?>][jumlah_terima]" value="<?= $modelSupplierDeliveryTrx->jumlah_terima ?>">
                                    <input id="supplierdeliverytrxJumlah_order_edited" class="supplierdeliverytrxJumlah_order" type="hidden" name="SupplierDeliveryTrxEdited[<?= $key ?>][jumlah_order]" value="<?= $modelSupplierDeliveryTrx->jumlah_order ?>">
                                    <input id="supplierdeliverytrxJumlah_harga_satuan_edited" class="supplierdeliverytrxJumlah_harga_satuan" type="hidden" name="SupplierDeliveryTrxEdited[<?= $key ?>][harga_satuan]" value="<?= $modelSupplierDeliveryTrx->harga_satuan ?>">
                                    <input id="supplierdeliverytrxJumlah_jumlah_harga_edited" class="supplierdeliverytrxJumlah_jumlah_harga" type="hidden" name="SupplierDeliveryTrxEdited[<?= $key ?>][jumlah_harga]" value="<?= $modelSupplierDeliveryTrx->jumlah_harga ?>">                                    
                                    <input id="supplierdeliverytrxStorage_id_edited" class="supplierdeliverytrxStorage_id" type="hidden" name="SupplierDeliveryTrxEdited[<?= $key ?>][storage_id]" value="<?= $modelSupplierDeliveryTrx->storage_id ?>">                                    
                                    <input id="supplierdeliverytrxStorage_rack_id_edited" class="supplierdeliverytrxStorage_rack_id" type="hidden" name="SupplierDeliveryTrxEdited[<?= $key ?>][storage_rack_id]" value="<?= $modelSupplierDeliveryTrx->storage_rack_id ?>">                                    
                                    <input id="supplierdeliverytrxStorage_rack_nama_edited" class="supplierdeliverytrxStorage_rack_nama" type="hidden" name="SupplierDeliveryTrxEdited[<?= $key ?>][storageRack][nama_rak]" value="<?= !empty($modelSupplierDeliveryTrx->storageRack) ? $modelSupplierDeliveryTrx->storageRack->nama_rak : '' ?>">                                    
                                    <input id="supplierdeliverytrxPurchase_order_id_edited" class="supplierdeliverytrxPurchase_order_id" type="hidden" name="SupplierDeliveryTrxEdited[<?= $key ?>][purchase_order_id]" value="<?= $modelSupplierDeliveryTrx->purchase_order_id ?>">                                                                        
                                    <input id="supplierdeliverytrxPurchase_order_trx_id_edited" class="supplierdeliverytrxPurchase_order_trx_id" type="hidden" name="SupplierDeliveryTrxEdited[<?= $key ?>][purchase_order_trx_id]" value="<?= $modelSupplierDeliveryTrx->purchase_order_trx_id ?>">                                    
                                    
                                    <input id="supplierdeliverytrxJumlah_subtotal" class="supplierdeliverytrxJumlah_subtotal" type="hidden" name="supplierdeliverytrxJumlah_subtotal" value="<?= $total ?>">
                                    <input id="supplierdeliverytrxJumlah_terima" class="supplierdeliverytrxJumlah_terima" type="hidden" name="supplierdeliverytrxJumlah_subtotal_item" value="<?= $modelSupplierDeliveryTrx->jumlah_terima ?>">
                                    
                                    <td id="purchase-order-id"><?= $modelSupplierDeliveryTrx->purchase_order_id ?></td>
                                    <td id="item-id"><?= $modelSupplierDeliveryTrx->item_id ?></td>
                                    <td id="item-name"><?= $modelSupplierDeliveryTrx->item->nama_item ?></td>
                                    <td id="satuan"><?= $modelSupplierDeliveryTrx->itemSku->nama_sku ?></td>
                                    <td id="jumlah"><?= $modelSupplierDeliveryTrx->jumlah_terima ?></td>
                                    <td id="subtotal"><?= $total ?></td>
                                    <td id="storage"><?= '(' . $modelSupplierDeliveryTrx->storage_id . ') ' . $modelSupplierDeliveryTrx->storage->nama_storage ?></td>
                                    <td id="rack"><?= !empty($modelSupplierDeliveryTrx->storageRack) ? $modelSupplierDeliveryTrx->storageRack->nama_rak : '' ?></td>
                                    <td>
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
                            <td></td>
                            <td id="jumlah-subtotal" style="font-weight: bold">
                                <span id="jumlah-subtotal-text"><?= $subtotal ?></span>
                                <input id ="jumlah-subtotal-harga" name="jumlah-subtotal" type="hidden" value="<?= $subtotal ?>">
                                <input id ="jumlah-subtotal-item" name="jumlah-item" type="hidden" value="<?= $jumlahItem ?>">
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
                
            </div>
        </div>
    </div>
</div>

<?php 
ActiveForm::end(); ?>

<table id="temp" style="display: none">
    <tr>
        <td id="purchase-order-id"></td>
        <td id="item-id"></td>
        <td id="item-name"></td>
        <td id="satuan"></td>
        <td id="jumlah"></td>
        <td id="subtotal"></td>
        <td id="storage"></td>
        <td id="rack"></td>
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
    <div class="modal-dialog" style="width: 50%">    
        <?php $form = ActiveForm::begin([
                    'id' => 'form-purchase-order',
                    
            ]); 
            
            echo Html::hiddenInput('inputState', null, ['id' => 'inputState']);
            echo Html::hiddenInput('currentIndexTrx', null, ['id' => 'currentIndexTrx']); ?>
        
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">
                        Add Item From Purchase Order
                    </h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-primary btn-sm" data-dismiss="modal">
                            <i class="fa fa-times"></i>
                        </button>

                    </div>
                </div>
                <div class="box-body">      
                    <div id="purchase-order-trx-data" style="margin-bottom: 20px"></div>
                    
                    <div class="row">
                        <div class="col-md-12">                                                        

                            <div class="row">
                                <div class="col-md-4">
                                    <?= $form->field($modelSupplierDeliveryTrx, '[]item_id')->textInput(['maxlength' => 16, 'readonly' => 'readonly']) ?>
                                </div>
                                <div class="col-md-4">
                                    <?= $form->field($modelSupplierDeliveryTrx, '[]item_sku_id', ['enableAjaxValidation' => true])->textInput(['maxlength' => 16, 'readonly' => 'readonly']) ?>
                                </div>
                                <div class="col-md-4">
                                    <?= $form->field($modelSupplierDeliveryTrx, '[]harga_satuan')->widget(MaskMoney::className(), ['readonly' => 'readonly']) ?>
                                </div>
                            </div>
                            
                            <div class="row">                                
                                <div class="col-md-4">
                                    <?= $form->field($modelSupplierDeliveryTrx, '[]jumlah_terima')->textInput() ?>
                                </div>
                                <div class="col-md-2">
                                    <?= $form->field($modelSupplierDeliveryTrx, '[]purchaseOrderTrx_is_closed')->checkbox([], false) ?>
                                </div>
                                <div class="col-md-4">
                                    <?= $form->field($modelSupplierDeliveryTrx, '[]jumlah_order')->textInput(['readonly' => 'readonly']) ?>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <?= $form->field($modelSupplierDeliveryTrx, '[]storage_id')->dropDownList(
                                        ArrayHelper::map(
                                            Storage::find()->all(), 
                                            'id', 
                                            function($data) { 
                                                return '(' . $data->id . ') ' . $data->nama_storage;                                 
                                            }
                                        ), 
                                        [
                                            'prompt' => '',
                                        ]
                                    ); ?>
                                </div>
                                <div class="col-md-6">
                                    <?= $form->field($modelSupplierDeliveryTrx, '[]storage_rack_id')->textInput(); ?>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <?= $form->field($modelSupplierDeliveryTrx, '[]purchase_order_id')->textInput(['maxlength' => 13, 'readonly' => 'readonly']) ?>
                                </div>
                                <div class="col-md-6">
                                    <?= $form->field($modelSupplierDeliveryTrx, '[]purchase_order_trx_id')->textInput(['maxlength' => 13, 'readonly' => 'readonly']) ?>                                
                                </div>
                            </div>

                            <?= $form->field($modelSupplierDeliveryTrx, '[]jumlah_harga', [
                                    'template' => '{input}'
                                ])->hiddenInput() ?>    
                            
                            <?= Html::hiddenInput('nama_item', '', ['id' => 'supplierdeliverytrx-item_id_nama']) ?>
                            <?= Html::hiddenInput('nama_sku', '', ['id' => 'supplierdeliverytrx-item_sku_id_nama']) ?>
                            
                        </div>
                    </div>
                </div>
                <div class="box-footer" style="text-align: right">
                    <?= Html::submitButton('<i class="fa fa-check"></i>&nbsp;&nbsp;&nbsp;Add', ['id' => 'aYes', 'class' => 'btn btn-primary']); ?>
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        <i class="fa fa-rotate-left"></i>&nbsp;&nbsp;&nbsp;
                        Close
                    </button>
                </div> 
                <div class="overlay"></div>
                <div class="loading-img"></div>
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
    $this->registerJsFile(Yii::getAlias('@common-web') . '/js/plugins/input-mask/jquery.inputmask.js');
    $this->registerJsFile(Yii::getAlias('@common-web') . '/js/plugins/input-mask/jquery.inputmask.date.extensions.js');
    $this->registerJsFile(Yii::getAlias('@common-web') . '/js/plugins/input-mask/jquery.inputmask.extensions.js');
};

$jscript = '
    var disableKdSupplier = function() {
        if ($("#tbodyItem").find("tr").length > 0) {
            $("#supplierdelivery-kd_supplier").select2("readonly", true);
        } else {
            $("#supplierdelivery-kd_supplier").select2("readonly", false);
        }
    };
    
    $("#supplierdelivery-date").inputmask("yyyy-mm-dd", {"placeholder": "yyyy-mm-dd"});
    $("#supplierdelivery-kd_supplier").select2({
        placeholder: "Select Supplier",
        allowClear: true
    });            

    var loadPurchaseOrderTrx = function(value) {
        $.ajax({
            type: "GET",
            data: {
                "kd_supplier": value
            },                    
            url: "' . Yii::$app->urlManager->createUrl('purchase-order-trx/get-purchase-order-trx') . '",
            success: function(response) {
                $("div#purchase-order-trx-data").html(response);
            }
        });
    };           

    loadPurchaseOrderTrx($("#supplierdelivery-kd_supplier").val());
    
    $("#supplierdeliverytrx-storage_id").select2({
        placeholder: "Select Storage",
        allowClear: true
    });

    var storageRack = function(datas, initSel) {
        $("#supplierdeliverytrx-storage_rack_id").select2({
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

    storageRack([]);

    $("#supplierdeliverytrx-storage_id").on("select2-selecting", function(e) {    
        $("#supplierdeliverytrx-storage_rack_id").val("");
        var selected = e;
        $.ajax({
            dataType: "json",
            cache: false,
            url: "' . Yii::$app->urlManager->createUrl('storage-rack/get-storage-rack') . '?id=" + selected.val,
            success: function(response) {
                storageRack(response);
            }
        });
    });             

    $("#supplierdeliverytrx-storage_id").on("select2-removed", function(e) {
        $("#supplierdeliverytrx-storage_rack_id").val("");
        storageRack([]);                
    });

    var clearPurchaseOrder = function() {
        $("#supplierdeliverytrx-purchase_order_id").val("");
        $("#supplierdeliverytrx-purchase_order_trx_id").val("");
        $("#supplierdeliverytrx-item_id").val("");
        $("#supplierdeliverytrx-item_sku_id").val("");
        $("#supplierdeliverytrx-storage_id").select2("val", "");
        $("#supplierdeliverytrx-storage_rack_id").val("");
        storageRack([]);
        $("#supplierdeliverytrx-harga_satuan").val("");
        $("#supplierdeliverytrx-harga_satuan-disp").maskMoney("mask", 0);
        $("#supplierdeliverytrx-jumlah_terima").val("");
        $("#supplierdeliverytrx-jumlah_order").val("");
        if ($(".form-group").hasClass("has-error")) {
            $(".form-group").removeClass("has-error");
            $(".help-block").empty();
        }
        if ($(".form-group").hasClass("has-success")) $(".form-group").removeClass("has-success");
    };

    $("a#addButton").click(function(event){
        event.preventDefault();
        clearPurchaseOrder();
        loadPurchaseOrderTrx($("#supplierdelivery-kd_supplier").val());
        
        $("#modalDialog").find("input#inputState").val("add");
        $("#modalDialog").modal();
    });            

    var indexInput = ' . $indexTrx . ';

    $("form#form-purchase-order").on("beforeSubmit", function(event) {
        if (!$(".form-group").hasClass("has-error")) {
        
            var state = $(this).find("input#inputState").val();
        
            var namaItem = $(this).find("#supplierdeliverytrx-item_id_nama").val();
            var namaItemSku = $(this).find("#supplierdeliverytrx-item_sku_id_nama").val();
            
            var purchase_order_id = $(this).find("#supplierdeliverytrx-purchase_order_id");
            var purchase_order_trx_id = $(this).find("#supplierdeliverytrx-purchase_order_trx_id");
            var is_closed = $(this).find("#supplierdeliverytrx-purchaseordertrx_is_closed");
            var is_close_val = (is_closed.is(":checked")) ? 1 : 0;
            var item_id = $(this).find("#supplierdeliverytrx-item_id");
            var item_sku_id = $(this).find("#supplierdeliverytrx-item_sku_id");
            var storage_id = $(this).find("#supplierdeliverytrx-storage_id");
            var storage_rack_id = $(this).find("#supplierdeliverytrx-storage_rack_id");
            var jumlah = $(this).find("#supplierdeliverytrx-jumlah_terima");
            var jumlah_order = $(this).find("#supplierdeliverytrx-jumlah_order");
            var harga_satuan = $(this).find("#supplierdeliverytrx-harga_satuan");
            var subtotal = parseFloat(jumlah.val()) * parseFloat(harga_satuan.val());
            var jumlah_harga = $(this).find("#supplierdeliverytrx-jumlah_harga");
        
            if (state == "add") {
                var inputIndexTrx = $("<input>").attr("type", "hidden").attr("name", "indexTrx" + indexInput).attr("id", "indexTrx" + indexInput).attr("class", "indexTrx").attr("value", "indexTrx" + indexInput);                        
                
                var inputPurchase_order_id = $("<input>").attr("type", "hidden").attr("name", purchase_order_id.attr("name").replace("[]", "[" + indexInput + "]")).attr("class", "supplierdeliverytrxPurchase_order_id").attr("value", purchase_order_id.val());           
                var inputPurchase_order_trx_id = $("<input>").attr("type", "hidden").attr("name", purchase_order_trx_id.attr("name").replace("[]", "[" + indexInput + "]")).attr("class", "supplierdeliverytrxPurchase_order_trx_id").attr("value", purchase_order_trx_id.val());            
                var inputIs_closed = $("<input>").attr("type", "hidden").attr("name", is_closed.attr("name").replace("[]", "[" + indexInput + "]")).attr("value", is_close_val);            
                var inputItem_id = $("<input>").attr("type", "hidden").attr("name", item_id.attr("name").replace("[]", "[" + indexInput + "]")).attr("class", "supplierdeliverytrxItem_id").attr("value", item_id.val());            
                var inputItem_sku_id = $("<input>").attr("type", "hidden").attr("name", item_sku_id.attr("name").replace("[]", "[" + indexInput + "]")).attr("class", "supplierdeliverytrxItem_sku_id").attr("value", item_sku_id.val());            
                var inputStorage_id = $("<input>").attr("type", "hidden").attr("name", storage_id.attr("name").replace("[]", "[" + indexInput + "]")).attr("class", "supplierdeliverytrxStorage_id").attr("value", storage_id.val());            
                var inputStorage_rack_id = $("<input>").attr("type", "hidden").attr("name", storage_rack_id.attr("name").replace("[]", "[" + indexInput + "]")).attr("class", "supplierdeliverytrxStorage_rack_id").attr("value", storage_rack_id.val());            
                
                var inputStorage_rack_nama = $("<input>").attr("type", "hidden").attr("name", "storage_rack_nama").attr("class", "supplierdeliverytrxStorage_rack_nama");            
                if (storage_rack_id.val() != "") 
                    inputStorage_rack_nama = inputStorage_rack_nama.attr("value", storage_rack_id.select2("data").text);            
                else
                    inputStorage_rack_nama = inputStorage_rack_nama.attr("value", "");  
                
                var inputJumlah = $("<input>").attr("type", "hidden").attr("name", jumlah.attr("name").replace("[]", "[" + indexInput + "]")).attr("id", "supplierdeliverytrxJumlah_terima").attr("class", "supplierdeliverytrxJumlah_terima").attr("value", jumlah.val());           
                var inputJumlahOrder = $("<input>").attr("type", "hidden").attr("name", jumlah_order.attr("name").replace("[]", "[" + indexInput + "]")).attr("class", "supplierdeliverytrxJumlah_order").attr("value", jumlah_order.val());            
                var inputHargaSatuan = $("<input>").attr("type", "hidden").attr("name", harga_satuan.attr("name").replace("[]", "[" + indexInput + "]")).attr("class", "supplierdeliverytrxJumlah_harga_satuan").attr("value", harga_satuan.val());                                                    
                var inputSubtotal = $("<input>").attr("type", "hidden").attr("name", "supplierdeliverytrxJumlah_subtotal").attr("id", "supplierdeliverytrxJumlah_subtotal").attr("class", "supplierdeliverytrxJumlah_subtotal").attr("value", subtotal);            
                var inputJumlahHarga = $("<input>").attr("type", "hidden").attr("name", jumlah_harga.attr("name").replace("[]", "[" + indexInput + "]")).attr("class", "supplierdeliverytrxJumlah_jumlah_harga").attr("value", subtotal);

                var jumlahSubtotal = parseFloat($("td#jumlah-subtotal input#jumlah-subtotal-harga").val());
                jumlahSubtotal += subtotal;
                $("td#jumlah-subtotal input#jumlah-subtotal-harga").val(jumlahSubtotal);
                $("td#jumlah-subtotal span#jumlah-subtotal-text").html(jumlahSubtotal);

                $("#supplierdelivery-jumlah_harga").val(jumlahSubtotal);
                $("#supplierdelivery-jumlah_harga-disp").maskMoney("mask", jumlahSubtotal);

                var jumlahSubtotalItem = parseFloat($("td#jumlah-subtotal input#jumlah-subtotal-item").val() ? $("td#jumlah-subtotal input#jumlah-subtotal-item").val() : 0);
                jumlahSubtotalItem += parseFloat(jumlah.val());
                $("td#jumlah-subtotal input#jumlah-subtotal-item").val(jumlahSubtotalItem);
                $("#supplierdelivery-jumlah_item").val(jumlahSubtotalItem);
                $("#supplierdelivery-jumlah_item-disp").maskMoney("mask", jumlahSubtotalItem);

                var comp = $("#temp").clone();
                comp.children().find("tr").append(inputIndexTrx);
                comp.children().find("tr").append(inputPurchase_order_id).append(inputPurchase_order_trx_id).append(inputIs_closed).append(inputItem_id);
                comp.children().find("tr").append(inputItem_sku_id).append(inputStorage_id).append(inputStorage_rack_id).append(inputStorage_rack_nama).append(inputJumlah).append(inputJumlahOrder);
                comp.children().find("tr").append(inputSubtotal).append(inputHargaSatuan).append(inputJumlahHarga);
                
                comp.children().find("#purchase-order-id").append(purchase_order_id.val());
                comp.children().find("#item-id").append(item_id.val());
                comp.children().find("#item-name").html(namaItem);
                comp.children().find("#satuan").append(namaItemSku);
                comp.children().find("#jumlah").append(jumlah.val());
                comp.children().find("#subtotal").append(subtotal);
                
                var data = storage_id.select2("data").text;
                comp.children().find("#storage").append(data);

                if (storage_rack_id.val() != "")
                    data = storage_rack_id.select2("data").text;
                else
                    data = "";
                    
                comp.children().find("#rack").append(data);                                                                
                
                $("#tbodyItem").append(comp.children().html());
                $("#tbodyItem").find("a#aEdit").tooltip();  
                $("#tbodyItem").find("a#aDelete").tooltip();  

                disableKdSupplier();
                clearPurchaseOrder();

                indexInput++;
            } else if (state == "edit") {
            
                var indexTrx = $(this).find("input#currentIndexTrx").val();
                var rowObj = $("#tbodyItem").find("input#" + indexTrx).parent();                                

                rowObj.find("input.supplierdeliverytrxItem_id").val(item_id.val());
                rowObj.find("input.supplierdeliverytrxItem_sku_id").val(item_sku_id.val());
                rowObj.find("input.supplierdeliverytrxJumlah_harga_satuan").val(harga_satuan.val());
                rowObj.find("input.supplierdeliverytrxJumlah_terima").val(jumlah.val());
                rowObj.find("input.supplierdeliverytrxJumlah_order").val(jumlah_order.val());
                rowObj.find("input.supplierdeliverytrxStorage_id").val(storage_id.val());                
                rowObj.find("input.supplierdeliverytrxStorage_rack_id").val(storage_rack_id.val());    
                
                var data = "";
                if (storage_rack_id.val() != "") {
                    data = storage_rack_id.select2("data").text;                        
                }
                
                rowObj.find("input.supplierdeliverytrxStorage_rack_nama").val(data);
                
                rowObj.find("input.supplierdeliverytrxPurchase_order_id").val(purchase_order_id.val());
                rowObj.find("input.supplierdeliverytrxPurchase_order_trx_id").val(purchase_order_trx_id.val());
                
                rowObj.find("#purchase-order-id").html(purchase_order_id.val());
                rowObj.find("#item-name").html(namaItem);
                rowObj.find("#satuan").html(namaItemSku);
                rowObj.find("#jumlah").html(jumlah.val());
                rowObj.find("#subtotal").html(subtotal);
                
                data = storage_id.select2("data").text;
                rowObj.find("#storage").html(data);

                if (storage_rack_id.val() != "")
                    data = storage_rack_id.select2("data").text;
                else
                    data = "";
                    
                rowObj.find("#rack").html(data);

                var totalItem = 0;
                var totalHarga = 0;
                $("#tbodyItem tr").each(function() {
                    totalItem += parseFloat($(this).find("input.supplierdeliverytrxJumlah_terima").val());
                    totalHarga += parseFloat($(this).find("input.supplierdeliverytrxJumlah_terima").val()) * parseFloat($(this).find("input.supplierdeliverytrxJumlah_harga_satuan").val());                            
                });

                $("td#jumlah-subtotal input#jumlah-subtotal-item").val(totalItem);

                $("td#jumlah-subtotal input#jumlah-subtotal-harga").val(totalHarga);
                $("td#jumlah-subtotal span#jumlah-subtotal-text").html(totalHarga);

                $("#supplierdelivery-jumlah_item").val(totalItem);

                $("#supplierdelivery-jumlah_harga").val(totalHarga);
                $("#supplierdelivery-jumlah_harga-disp").maskMoney("mask", totalHarga);

                $("#modalDialog").modal("hide");
            }
        }
        
        return false;
    });

    $(document).on("click", "a#aDelete", function(event){
        event.preventDefault();
        var remove = true;
        $(this).parent().parent().find("input").each(function(i, val) {
            if ($(val).attr("id") == "supplierdeliverytrxJumlah_subtotal") {
                var jumlahSubtotal = parseFloat($("td#jumlah-subtotal input#jumlah-subtotal-harga").val());
                jumlahSubtotal -= parseFloat($(val).val());
                $("#supplierdelivery-jumlah_harga").val(jumlahSubtotal);
                $("#supplierdelivery-jumlah_harga-disp").maskMoney("mask", jumlahSubtotal);
                $("td#jumlah-subtotal input#jumlah-subtotal-harga").val(jumlahSubtotal);
                $("td#jumlah-subtotal span#jumlah-subtotal-text").html(jumlahSubtotal);                                                
            }

            if ($(val).attr("id") == "supplierdeliverytrxJumlah_terima") {
                var jumlahSubtotalItem = parseFloat($("td#jumlah-subtotal input#jumlah-subtotal-item").val() ? $("td#jumlah-subtotal input#jumlah-subtotal-item").val() : 0);
                jumlahSubtotalItem -= parseFloat($(val).val());
                $("#supplierdelivery-jumlah_item").val(jumlahSubtotalItem);
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
                
            disableKdSupplier();
        });
                
    });
    
    $(document).on("click", "a#aEdit", function(event){
        event.preventDefault();

        var thisObj = $(this).parent().parent();                

        $("#supplierdeliverytrx-item_id").val(thisObj.find("input.supplierdeliverytrxItem_id").val());     
        $("#supplierdeliverytrx-item_sku_id").val(thisObj.find("input.supplierdeliverytrxItem_sku_id").val());
        $("#supplierdeliverytrx-harga_satuan").val(thisObj.find("input.supplierdeliverytrxJumlah_harga_satuan").val());
        $("#supplierdeliverytrx-harga_satuan-disp").maskMoney("mask", parseFloat(thisObj.find("input.supplierdeliverytrxJumlah_harga_satuan").val()));
        $("#supplierdeliverytrx-jumlah_terima").val(thisObj.find("input.supplierdeliverytrxJumlah_terima").val());
        $("#supplierdeliverytrx-jumlah_order").val(thisObj.find("input.supplierdeliverytrxJumlah_order").val());
        $("#supplierdeliverytrx-storage_id").select2("val", thisObj.find("input.supplierdeliverytrxStorage_id").val());
                
        var initSelection;
        
        if (thisObj.find("input.supplierdeliverytrxStorage_rack_id").val() != "") {
            initSelection = function (element, callback) {
                var data = {id: thisObj.find("input.supplierdeliverytrxStorage_rack_id").val(), text: thisObj.find("input.supplierdeliverytrxStorage_rack_nama").val()};
                callback(data);
            };
        }

        $.ajax({
            dataType: "json",
            cache: false,
            url: "' . Yii::$app->urlManager->createUrl('storage-rack/get-storage-rack') . '?id=" + thisObj.find("input.supplierdeliverytrxStorage_id").val(),
            success: function(response) {
                storageRack(response, initSelection);
                
                if (initSelection !== undefined)
                    $("#supplierdeliverytrx-storage_rack_id").select2("val", thisObj.find("input.supplierdeliverytrxStorage_rack_id").val());
                    
            }
        });
            
        $("#supplierdeliverytrx-purchase_order_id").val(thisObj.find("input.supplierdeliverytrxPurchase_order_id").val());
        $("#supplierdeliverytrx-purchase_order_trx_id").val(thisObj.find("input.supplierdeliverytrxPurchase_order_trx_id").val());
        
        if ($(".form-group").hasClass("has-error")) {
            $(".form-group").removeClass("has-error");
            $(".help-block").empty();
        }
        if ($(".form-group").hasClass("has-success")) $(".form-group").removeClass("has-success");

        $("#modalDialog").find("input#inputState").val("edit");
        $("#modalDialog").find("input#currentIndexTrx").val(thisObj.find("input.indexTrx").val());
        $("#modalDialog").modal();
    });

    $("#supplierdelivery-jumlah_harga-disp").off("keypress");
    $("#supplierdelivery-jumlah_harga-disp").off("keyup");

    $("#supplierdeliverytrx-harga_satuan-disp").off("keypress");
    $("#supplierdeliverytrx-harga_satuan-disp").off("keyup");
            
    $("a.btnLink").on("click", function() {
        $(location).attr("href",$(this).attr("href"));
    });
';

if (!$model->isNewRecord) {
    $jscript .= '
        disableKdSupplier();
    ';
}

$jscript .= '
    $(\'[data-toggle="tooltip"]\').tooltip()
    $(".overlay").hide();
    $(".loading-img").hide();
';

$this->registerJs($jscript . Yii::$app->params['checkbox-radio-script']()); ?>
