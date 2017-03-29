<?php

use yii\helpers\Html;
use backend\components\GridView; ?>


<div id="pjax-container">
    <div class="row">
        <div class="col-md-12">

            <?= GridView::widget([
                    'options' => [
                        'id' => 'get-supplier-delivery-trx'
                    ],
                    'dataProvider' => $dataProvider,
                    'condensed' => true,
                    'panelHeadingTemplate' => '',
                    'panelFooterTemplate' => '',
                    'columns' => [
                        [
                            'class' => 'yii\grid\SerialColumn',
                        ],

                        'supplier_delivery_id',
                        'item.nama_item',
                        'itemSku.nama_sku',                               
                        'jumlah_terima',
                        [
                            'attribute' => 'jumlah_retur',
                            'format' => 'raw',
                            'value' => function ($model, $index, $widget) {  
                                $jumlah = 0;
                                foreach ($model->returPurchaseTrxes as $dataReturPurchaseTrx) {
                                    //if ($dataReturPurchaseTrx->item_id == $model->item_id && $dataReturPurchaseTrx->item_sku_id == $model->item_sku_id)
                                    $jumlah += $dataReturPurchaseTrx->jumlah_item;
                                }
                                return $jumlah;
                            },
                        ],
                        'harga_satuan:currency',
                        'storage_id',
                        'storageRack.nama_rak',

                        [
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{check}',
                            'buttons' => [
                                'check' =>  function($url, $model, $key) {
                                    $str = Html::hiddenInput('supplier_delivery_id', $model->supplier_delivery_id, ['id' => 'supplier_delivery_id']) .
                                            Html::hiddenInput('supplier_delivery_trx_id', $model->id, ['id' => 'supplier_delivery_trx_id']) .
                                            Html::hiddenInput('item_id', $model->item_id, ['id' => 'item_id']) .
                                            Html::hiddenInput('item_id-nama', $model->item->nama_item, ['id' => 'item_id-nama']) .
                                            Html::hiddenInput('item_sku_id', $model->item_sku_id, ['id' => 'item_sku_id']) .
                                            Html::hiddenInput('item_sku_id-nama', $model->itemSku->nama_sku, ['id' => 'item_sku_id-nama']) .
                                            Html::hiddenInput('jumlah_order', $model->jumlah_order, ['id' => 'jumlah_order']) .
                                            Html::hiddenInput('jumlah_terima', $model->jumlah_terima, ['id' => 'jumlah_terima']) .
                                            Html::hiddenInput('harga_satuan', $model->harga_satuan, ['id' => 'harga_satuan']) .
                                            Html::hiddenInput('storage_id', $model->storage_id, ['id' => 'storage_id']) .
                                            Html::hiddenInput('storage_nama', $model->storage->nama_storage, ['id' => 'storage_nama']) .
                                            Html::hiddenInput('storage_rack_id', $model->storage_rack_id, ['id' => 'storage_rack_id']) .
                                            Html::hiddenInput('storage_rack_nama', !empty($model->storageRack) ? $model->storageRack->nama_rak : '', ['id' => 'storage_rack_nama']);

                                    return $str . 
                                            '<div class="btn-group btn-group-xs" role="group" style="width: 75px">' .
                                                Html::a('<i class="fa fa-check"></i>', $url, [
                                                    'id' => 'check',
                                                    'class' => 'btn btn-success',
                                                    'data-toggle' => 'tooltip',
                                                    'data-placement' => 'left',
                                                    'title' => 'Select'
                                                ]) . 
                                            '</div>';
                                },
                            ]
                        ],
                    ],
                    'pager' => [
                        'firstPageLabel' => '<i class="fa fa-angle-double-left"></i>',
                        'prevPageLabel' => '<i class="fa fa-angle-left"></i>',
                        'lastPageLabel' => '<i class="fa fa-angle-double-right"></i>',
                        'nextPageLabel' => '<i class="fa fa-angle-right"></i>',
                    ],
                ]); ?>

        </div>
    </div>
</div>


<script>
    $(document).pjax('a', '#pjax-container', {
        timeout: 25000
    });
    
    $(document).on('pjax:send', function() {
        $(".overlay").show();
        $(".loading-img").show();
    });
    
    $(document).on('pjax:complete', function() {
        $(".overlay").hide();
        $(".loading-img").hide();
    });
    
    $("a#check").tooltip();
            
    $("a#check").click(function(event) {
        event.preventDefault();
        parent = $(this).parent().parent();
        $("#returpurchasetrx-supplier_delivery_id").val(parent.find("input#supplier_delivery_id").val());
        $("#returpurchasetrx-supplier_delivery_trx_id").val(parent.find("input#supplier_delivery_trx_id").val());
        $("#returpurchasetrx-item_id").val(parent.find("input#item_id").val());
        $("#returpurchasetrx-item_id_nama").val(parent.find("input#item_id-nama").val());
        $("#returpurchasetrx-item_sku_id").val(parent.find("input#item_sku_id").val());
        $("#returpurchasetrx-item_sku_id_nama").val(parent.find("input#item_sku_id-nama").val());
        $("#returpurchasetrx-harga_satuan").val(parent.find("input#harga_satuan").val());
        $("#returpurchasetrx-harga_satuan-disp").maskMoney("mask", parseFloat(parent.find("input#harga_satuan").val()));
        $("#returpurchasetrx-jumlah_item").val(0);
        
        $("#returpurchasetrx-storage_id").select2("val", parent.find("input#storage_id").val());

        var initSelection;

        if (parent.find("input#storage_rack_id").val() != "") {
            initSel = function (element, callback) {
                var data = {id: parent.find("input#storage_rack_id").val(), text: parent.find("input#storage_rack_nama").val()};
                callback(data);
            };
        }

        $.ajax({
            dataType: "json",
            cache: false,
            url: "<?= Yii::$app->urlManager->createUrl('storage-rack/get-storage-rack') ?>?id=" + parent.find("input#storage_id").val(),
            success: function(response) {
                $("#returpurchasetrx-storage_rack_id").select2({
                    placeholder: "Select Storage Rack",
                    allowClear: true,
                    query: function(query) {
                        var data = {
                            results: response
                        };
                        query.callback(data);
                    },
                    initSelection : initSel
                });

                if (initSel !== undefined)
                    $("#returpurchasetrx-storage_rack_id").select2("val", parent.find("input#storage_rack_id").val());

            }
        });
    })
</script>
