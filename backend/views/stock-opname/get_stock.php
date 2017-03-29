<?php

use yii\helpers\Html;
use backend\components\GridView; ?>


<div class="row stock-index">
    <div class="col-md-1"></div>
    <div class="col-md-10">
        
        <?= GridView::widget([
                'options' => [
                    'id' => 'get-stock-id-' . $keyId
                ],
                'dataProvider' => $dataProvider,
                'condensed' => true,
                'panelHeadingTemplate' => '',
                'panelFooterTemplate' => '',
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    'storage_id',
                    'storage.nama_storage',
                    'storageRack.nama_rak',
                    'jumlah_stok',           
                    [
                        'label' => 'Jumlah Opname',
                        'contentOptions' => [
                            'id' => 'jumlah_opname'
                        ],
                        'format' => 'raw',
                        'value' => function($model, $key, $index, $column) {
                            $row = '<a href="#" id="jumlah-' . $key . '" data-type="text" data-pk="'. $key .'" data-name="jumlah" data-url="' . Yii::$app->urlManager->createUrl('stock-opname/opname-update') . '" data-title="Enter Jumlah">(not set)</a>
                                    <input type="hidden" id="jumlah_stok" value="' . $model->jumlah_stok . '">
                                    <script>
                                        $("a#jumlah-' . $key . '").editable({
                                            params: function(params) {
                                                params.item_id = "' . $model->item_id . '";
                                                params.item_sku_id = "' . $model->item_sku_id . '";
                                                params.storage_id = "' . $model->storage_id . '";
                                                params.storage_rack_id = "' . $model->storage_rack_id . '";
                                                params.jumlah_awal = ' . $model->jumlah_stok . ';
                                                params.jumlah_adjustment = params.value - params.jumlah_awal;
                                                return params;
                                            },
                                            success: function(response, newValue) {
                                                var data = $.parseJSON(response);
                                                if (data.message.length != 0) {
                                                    return data.message;
                                                } else {
                                                    var jumlah_stok = parseFloat($(this).parent().children("input#jumlah_stok").val());
                                                    var adjusment = parseFloat(newValue) - jumlah_stok;
                                                    $(this).parent().parent().children("td#adjusment").html(adjusment);
                                                }
                                            }
                                        });
                                    </script>';
                            return $row;
                        }
                    ],
                    [
                        'label' => 'Adjustment',
                        'contentOptions' => [
                            'id' => 'adjusment'
                        ],
                        'format' => 'raw',
                        'value' => function($model, $key, $index, $column) {
                            $row = '';
                            return $row;
                        }
                    ],
                ],
            ]); ?>
        
    </div>
    <div class="col-md-1"></div>
</div>