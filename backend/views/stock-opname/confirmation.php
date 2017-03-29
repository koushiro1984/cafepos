 <?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use backend\models\Storage;
use backend\models\StorageRack;
use backend\components\GridView;
use backend\components\NotificationDialog;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\StockOpnameSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

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

$this->title = 'Stock Opname Confirmation';
$this->params['breadcrumbs'][] = $this->title; ?>

<div class="stock-opname-index">

    <?php 
    
    $storage_id = !empty(Yii::$app->request->get('StockOpnameSearch')['storage_id']) ? Yii::$app->request->get('StockOpnameSearch')['storage_id'] : NULL;
    $storage_rack_id = !empty(Yii::$app->request->get('StockOpnameSearch')['storage_rack_id']) && !empty($storage_id) ? Yii::$app->request->get('StockOpnameSearch')['storage_rack_id'] : NULL;   
    
    $jscript = Yii::$app->params['checkbox-radio-script']() .
            
                '$("button#submitSelection").on("click", function(event) {
                    $("input#selectedRows").val($("#w0").yiiGridView("getSelectedRows"));
                });
                
                $("#storage_id").select2({
                    placeholder: "Select Storage",
                    allowClear: true
                });
            
                $("#storage_id").on("select2-removed", function(e) {
                    $("#storage_rack_id").val("");
                });
                                 
                var remoteData= [];';
    
    if (!empty($storage_id)) {
        $jscript .= '$.ajax({
                        dataType: "json",
                        cache: false,
                        url: "' . Yii::$app->urlManager->createUrl('storage-rack/get-storage-rack') . '?id=' . $storage_id . '",
                        success: function(response) {
                            remoteData = response;
                        }
                    });';
    }
    
    $initSelection = '';
    if (!empty($storage_rack_id) && !empty($storage_id)) {
        $initSelection = 'initSelection: function (element, callback) {
                            var data = {id: "' . $storage_rack_id . '", text: "' . StorageRack::findOne($storage_rack_id)->nama_rak . '"};
                            callback(data);
                        },';
    }
    
    $jscript .= '$("#storage_rack_id").select2({
                    placeholder: "Select Storage Rack",
                    allowClear: true,
                    query: function(query) {
                        var data = {
                            results: remoteData
                        };
                        query.callback(data);
                    },' .
                    $initSelection . '
                });';
    
    if (!empty($storage_rack_id) && !empty($storage_id)) {        
        $jscript .= '$("#storage_rack_id").select2("val", "' . $storage_rack_id . '");';
    }
    
    /*$jscript .= '$("#tes").on("click", function(event) {
                    $.pjax.reload({container:"#w0-pjax"});
                });';*/
            
    $this->registerJs($jscript);
    
    $jscript = '<script>' . $jscript . '</script>'; ?>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'pjax' => true,
        'scriptAfterPjax' => $jscript,
        'bordered' => false,
        'floatHeader' => true,
        'panelHeadingTemplate' => '<div class="kv-panel-pager pull-right" style="text-align:right">'
                                    . '{pager}{summary}'
                                . '</div>'                                
                                . '<div class="clearfix"></div>'
        ,
        'panelFooterTemplate' => '<div class="kv-panel-pager pull-right" style="text-align:right">'
                                    . '{summary}{pager}'
                                . '</div>'
                                . '{footer}'
                                . '<div class="clearfix"></div>'
        ,
        'panel' => [
            'before' => '<div class="pull-left" style="width: 60%">'
                            . '<div class="pull-left" style="width: 35%; margin-right: 15px">'
                                . Html::dropDownList('StockOpnameSearch[storage_id]', $storage_id, 
                                    ArrayHelper::map(
                                        Storage::find()->all(), 
                                        'id', 
                                        function($data) { 
                                            return '(' . $data->id . ') ' . $data->nama_storage;                                 
                                        }
                                    ), [
                                        'prompt' => '',
                                        'id' => 'storage_id',
                                        'class' => 'form-control',
                                        'style' => 'width: 100%'
                                    ]
                                )
                            . '</div>'
                            . '<div class="pull-left" style="width: 25%">'
                                . Html::textInput('StockOpnameSearch[storage_rack_id]', NULL, [
                                    'id' => 'storage_rack_id',
                                    'class' => 'form-control',
                                    'style' => 'width: 100%'
                                ])
                            . '</div>'
                        . '</div>'
            ,
            'after' => Html::beginForm().           
                            '<div class="form-inline form-group">' .
                                Html::dropDownList('action', null, ['waiting' => 'Waiting', 'approved' => 'Approved', 'rejected' => 'Rejected'], ['class' => 'form-control']) .
                                '&nbsp; &nbsp; &nbsp;' .
                                Html::input('hidden', 'selectedRows', null, ['id' => 'selectedRows']) .
                                Html::submitButton('<i class="fa fa-floppy-o"></i>&nbsp; &nbsp;Submit', ['class' => 'btn btn-primary', 'id' => 'submitSelection']) .
                            '</div>' .
                        Html::endForm()
        ],
        'toolbar' => [
            [
                'content' => Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['confirmation'], [
                                'data-pjax' => false, 
                                'class' => 'btn btn-success', 
                                'data-placement' => 'top',
                                'data-toggle' => 'tooltip',
                                'title' => 'Refresh'
                            ]) /*.
                            Html::button('TEsss', [
                                'id' => 'tes'
                            ])*/
            ],
        ],                                            
        'filterModel' => $searchModel,
        'filterSelector' => '#storage_id, #storage_rack_id',
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            
            [
                'class' => 'yii\grid\CheckboxColumn',
                'multiple' => false,
                'checkboxOptions' => function($model, $key, $index, $column) {
                    return ['value' => $model->id];
                }
            ],
            
            'item_id',
            'item.nama_item',
            'item_sku_id',
            'itemSku.nama_sku',
            'jumlah',
            'jumlah_awal',
            'jumlah_adjustment',
            [
                'label' => 'Action',
                'contentOptions' => [
                    'id' => 'jumlah_opname'
                ],
                'format' => 'raw',
                'value' => function($model, $key, $index, $column) {
                    $row = '<a href="#" id="action-' . $key . '" data-type="select" data-pk="'. $key .'" data-name="action" data-url="' . Yii::$app->urlManager->createUrl('stock-opname/opname-verify') . '" data-title="Enter Action">' . ucfirst($model->action) . '</a>';
                    $jscript = '
                                $("a#action-' . $key . '").editable({
                                    placement: "left",
                                    value: "' . $model->action . '",
                                    source: [
                                        {value: "waiting", text: "Waiting"},
                                        {value: "approved", text: "Approved"},
                                        {value: "rejected", text: "Rejected"}
                                    ],
                                    params: function(params) {
                                        return params;
                                    },
                                    success: function(response, newValue) {
                                        var data = $.parseJSON(response);
                                        if (data.message.length != 0) {
                                            return data.message;
                                        }
                                    }
                                });';
                    
                    $this->registerJs($jscript);
                    
                    return $row . '<script>' . $jscript . '</script>';
                }
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

<?php

$this->params['regCssFile'][] = function() {
    $this->registerCssFile(Yii::getAlias('@common-web') . '/css/select2/select2.css');
    $this->registerCssFile(Yii::getAlias('@common-web') . '/css/select2/select2-bootstrap.css');
    $this->registerCssFile(Yii::getAlias('@common-web') . '/css/x-editable/bootstrap-editable.css');
}; 

$this->params['regJsFile'][] = function() {
    $this->registerJsFile(Yii::getAlias('@common-web') . '/js/plugins/select2/select2.min.js'); 
    $this->registerJsFile(Yii::getAlias('@common-web') . '/js/plugins/x-editable/bootstrap-editable.js');
    $this->registerJsFile(Yii::getAlias('@common-web') . '/js/plugins/iCheck/icheck.min.js');    
}; ?>  