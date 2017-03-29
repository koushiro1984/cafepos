<?php

use yii\helpers\Html;
use backend\components\GridView; ?>


<div id="pjax-container">
    <div class="row get-purchase-order-trx">
        <div class="col-md-12">

            <?= GridView::widget([
                    'options' => [
                        'id' => 'get-purchase-order-trx'
                    ],
                    'dataProvider' => $dataProvider,
                    'condensed' => true,
                    'panelHeadingTemplate' => '',
                    'panelFooterTemplate' => '',
                    'columns' => [
                        [
                            'class' => 'yii\grid\SerialColumn',
                        ],

                        'purchase_order_id',
                        'item.nama_item',
                        'itemSku.nama_sku',
                        'jumlah_order',                               
                        'jumlah_terima',
                        'harga_satuan:currency',

                        [
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{check}',
                            'buttons' => [
                                'check' =>  function($url, $model, $key) {
                                    $str = Html::hiddenInput('purchase_order_id', $model->purchase_order_id, ['id' => 'purchase_order_id']) .
                                            Html::hiddenInput('purchase_order_trx_id', $model->id, ['id' => 'purchase_order_trx_id']) .
                                            Html::hiddenInput('item_id', $model->item_id, ['id' => 'item_id']) .
                                            Html::hiddenInput('item_id-nama', $model->item->nama_item, ['id' => 'item_id-nama']) .
                                            Html::hiddenInput('item_sku_id', $model->item_sku_id, ['id' => 'item_sku_id']) .
                                            Html::hiddenInput('item_sku_id-nama', $model->itemSku->nama_sku, ['id' => 'item_sku_id-nama']) .
                                            Html::hiddenInput('jumlah_order', $model->jumlah_order, ['id' => 'jumlah_order']) .
                                            Html::hiddenInput('jumlah_terima', $model->jumlah_terima, ['id' => 'jumlah_terima']) .
                                            Html::hiddenInput('harga_satuan', $model->harga_satuan, ['id' => 'harga_satuan']);

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
    })
      $(document).on('pjax:complete', function() {
        $(".overlay").hide();
        $(".loading-img").hide();
    })
    
    $("a#check").tooltip();
            
    $("a#check").click(function(event) {
        event.preventDefault();
        parent = $(this).parent().parent();
        $("#supplierdeliverytrx-purchase_order_id").val(parent.find("input#purchase_order_id").val());
        $("#supplierdeliverytrx-purchase_order_trx_id").val(parent.find("input#purchase_order_trx_id").val());
        $("#supplierdeliverytrx-item_id").val(parent.find("input#item_id").val());
        $("#supplierdeliverytrx-item_id_nama").val(parent.find("input#item_id-nama").val());
        $("#supplierdeliverytrx-item_sku_id").val(parent.find("input#item_sku_id").val());
        $("#supplierdeliverytrx-item_sku_id_nama").val(parent.find("input#item_sku_id-nama").val());
        $("#supplierdeliverytrx-harga_satuan").val(parent.find("input#harga_satuan").val());
        $("#supplierdeliverytrx-harga_satuan-disp").maskMoney("mask", parseFloat(parent.find("input#harga_satuan").val()));
        //$("#supplierdeliverytrx-jumlah_terima").val(parent.find("input#jumlah_terima").val());
        $("#supplierdeliverytrx-jumlah_terima").val(0);
        $("#supplierdeliverytrx-jumlah_order").val(parent.find("input#jumlah_order").val());
    })
</script>
