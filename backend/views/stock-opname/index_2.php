<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\popover\PopoverXAsset;
use kartik\editable\EditableAsset;
use kartik\editable\EditablePjaxAsset;
use yii\widgets\ActiveFormAsset;
use backend\models\Storage;
use backend\models\StorageRack;
use backend\components\GridView;
use backend\components\ModalDialog;
use backend\components\NotificationDialog;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\StockOpnameSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['queryParams'] = $queryParams;

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

$this->title = 'Stock Opnames';
$this->params['breadcrumbs'][] = $this->title; ?>

<div class="stock-opname-index">

    <?php 
    $storage_id = !empty(Yii::$app->request->get('StockOpnameSearch')['storage_id']) ? Yii::$app->request->get('StockOpnameSearch')['storage_id'] : NULL;
    $storage_rack_id = !empty(Yii::$app->request->get('StockOpnameSearch')['storage_rack_id']) && !empty($storage_id) ? Yii::$app->request->get('StockOpnameSearch')['storage_rack_id'] : NULL;
    
    $modalDialog = new ModalDialog([
        'clickedComponent' => 'a#delete',
        'modelAttributeId' => 'model-id',
        'modelAttributeName' => 'model-name',
    ]);
    
    $jscript = '$(\'[data-toggle="tooltip"]\').tooltip();'
            . $modalDialog->getScript()
            
            . '$("#storage_id").select2({'
                . 'placeholder: "Select Storage",'
                . 'allowClear: true'
            . '});'
            
            . '$("#storage_id").on("select2-removed", function(e) {'
                . '$("#storage_rack_id").select2("val", "");'
            . '});'
                                 
            . 'var remoteData= [];';
    
    if (!empty($storage_id)) {
        $jscript .= '$.ajax({'
                        . 'dataType: "json",'
                        . 'cache: false,'
                        . 'url: "' . Yii::$app->urlManager->createUrl('storage-rack/get-storage-rack') . '?id=' . $storage_id . '",'
                        . 'success: function(response) {'
                            . 'remoteData = response;'
                        . '}'
                    . '});';
    }
    
    $initSelection = '';
    if (!empty($storage_rack_id) && !empty($storage_id)) {
        $initSelection = 'initSelection: function (element, callback) {'                        
                            . 'var data = {id: "' . $storage_rack_id . '", text: "' . StorageRack::findOne($storage_rack_id)->nama_rak . '"};'
                            . 'callback(data);'
                        . '},';
    }
    
    $jscript .= '$("#storage_rack_id").select2({'
                    . 'placeholder: "Select Storage Rack",'
                    . 'allowClear: true,'
                    . 'query: function(query) {'
                        . 'var data = {'
                            . 'results: remoteData'
                        . '};'
                        . 'query.callback(data);'
                    . '},'
                    . $initSelection
                . '});';
    
    if (!empty($storage_rack_id) && !empty($storage_id)) {        
        $jscript .= '$("#storage_rack_id").select2("val", "' . $storage_rack_id . '");';
    }
            
    $this->registerJs($jscript);
    
    $jscript = '<script>' . $jscript . '</script>'; ?>    

    <?= GridView::widget([
        'options' => [
            'id' => 'stock-opname-grid'
        ],
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
        ],
        'toolbar' => [
            [
                'content' => Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['index'], [
                            'class' => 'btn btn-success', 
                            'data-placement' => 'top',
                            'data-toggle' => 'tooltip',
                            'title' => 'Refresh'
                ]),                
            ],
        ],
        'filterSelector' => '#storage_id, #storage_rack_id',
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'class' => 'kartik\grid\ExpandRowColumn',
                'value' => function ($model, $key, $index, $column) {
                    return GridView::ROW_COLLAPSED;
                },
//                'detail' => function ($model, $key, $index, $column) {
//                    return $model->id;
//                },
                'detailUrl' => Yii::$app->urlManager->createUrl('index'),
                //'onDetailLoaded' => 'asdasd'
            ],
            'id',
            'item.nama_item',
            'nama_sku',            
            //'stocks.storage_id',
            //'stocks.storage_rack_id',
//            [
//                'class' => 'kartik\grid\EditableColumn',
//                'attribute' => 'jumlah',                 
//                'editableOptions' => function ($model, $key, $index) {
//                    return [
//                        'size' => 'sm',
//                        'header' => 'Jumlah Opname',
//                        'inputType' => 'textInput',
//                        'afterInput' => function ($form, $widget) use ($model, $index) {
//                            $params = $this->params['queryParams'];
//                            $afterInput = Html::input('hidden', 'storage_id', $params['StockOpnameSearch']['storage_id'])
//                                        . Html::input('hidden', 'storage_rack_id', $params['StockOpnameSearch']['storage_rack_id']);
//                            return $afterInput;
//                        }
//                    ];
//                }
//            ],
//            'jumlah_awal',
            // 'jumlah_adjusment',
            // 'is_approved',
            // 'date_approved',
            // 'user_approver',
            // 'created_at',
            // 'user_created',
            // 'updated_at',
            // 'user_updated',            
        ],
        'pager' => [
            'firstPageLabel' => '<i class="fa fa-angle-double-left"></i>',
            'prevPageLabel' => '<i class="fa fa-angle-left"></i>',
            'lastPageLabel' => '<i class="fa fa-angle-double-right"></i>',
            'nextPageLabel' => '<i class="fa fa-angle-right"></i>',
        ],
    ]); ?>
    

</div>

<?= $modalDialog->renderDialog() ?>

<?php

$this->params['regCssFile'][] = function() {
    $this->registerCssFile(Yii::getAlias('@common-web') . '/css/select2/select2.css');
    $this->registerCssFile(Yii::getAlias('@common-web') . '/css/select2/select2-bootstrap.css');
}; 

$this->params['regJsFile'][] = function() {
    $this->registerJsFile(Yii::getAlias('@common-web') . '/js/plugins/select2/select2.min.js');        
}; ?>  

<?php
PopoverXAsset::register($this);
ActiveFormAsset::register($this);
EditablePjaxAsset::register($this);
EditableAsset::register($this); ?>
