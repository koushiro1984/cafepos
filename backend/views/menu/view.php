<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use backend\components\ModalDialog;

/* @var $this yii\web\View */
/* @var $model backend\models\Menu */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Menus', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title; ?>

<div class="menu-view">
    
    <div class="row">
        <div class="col-sm-2"></div>
        <div class="col-sm-8">
            <div class="box box-danger">
                <div class="box-header">
                    <h3 class="box-title">
                        <?= Html::a('<i class="glyphicon glyphicon-pencil"></i>&nbsp;&nbsp;&nbsp;' . 'Edit', 
                            ['update', 'id' => $model->id], 
                            [
                                'class' => 'btn btn-primary',
                                'style' => 'color:white'
                            ]) ?>
                            
                        <?= Html::a('<i class="glyphicon glyphicon-trash"></i>&nbsp;&nbsp;&nbsp;' . 'Delete', 
                            ['delete', 'id' => $model->id], 
                            [
                                'id' => 'delete',
                                'class' => 'btn btn-danger',
                                'style' => 'color:white',
                                'model-id' => $model->id,
                                'model-name' => $model->nama_menu,
                            ]) ?>                            
                        
                        <?= Html::a('<i class="fa fa-rotate-left"></i>&nbsp;&nbsp;&nbsp;' . 'Cancel', 
                            ['index'], 
                            [
                                'class' => 'btn btn-default',
                            ]) ?>
                    </h3>
                </div>
                
                <?= DetailView::widget([
                    'model' => $model,
                    'options' => [
                        'class' => 'table'
                    ],
                    'attributes' => [
                        'id',
                        'nama_menu',
                        'menuCategory.nama_category',
                        'menuSatuan.nama_satuan',
                        'keterangan:ntext',
                        [
                            'attribute' => 'not_active',
                            'format' => 'raw',
                            'value' => Html::checkbox('not_active[]', $model->not_active, ['value' => $model->id, 'disabled' => 'disabled']),
                        ],
                        'harga_pokok:currency',
                        'biaya_lain:currency',
                        'harga_jual:currency',
                        [
                            'attribute' => 'image',
                            'format' => 'raw',
                            'value' => Html::img(Yii::$app->request->baseUrl . '/img/menu/' . $model->image, ['class'=>'img-thumbnail file-preview-image']),
                        ],
                    ],
                ]) ?>
                        
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-sm-2"></div>
        <div class="col-sm-8">
            <div class="box box-danger">
                <div class="box-header">
                    <h3 class="box-title">
                        Menu Recipe
                    </h3>
                    <div class="box-tools">
                        
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
                            </tr>
                        </thead>                    
                        <tbody id="tbodyRecipe">                        
                            <?php
                            $subtotal = 0;

                            if (!empty($modelMenuReceipts)):                            
                                foreach ($modelMenuReceipts as $key => $modelMenuReceipt): 
                                    $total = $modelMenuReceipt->itemSku->harga_beli * $modelMenuReceipt->jumlah;
                                    $subtotal += $total; ?>

                                    <tr>                                        
                                        <td id="item-id"><?= $modelMenuReceipt->item_id ?></td>
                                        <td id="item-name"><?= $modelMenuReceipt->item->nama_item ?></td>
                                        <td id="satuan"><?= $modelMenuReceipt->itemSku->nama_sku ?></td>
                                        <td id="jumlah"><?= $modelMenuReceipt->jumlah ?></td>
                                        <td id="subtotal"><?= $total ?></td>                                        
                                    </tr>

                                <?php
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
                                    <input name="jumlah-subtotal" type="hidden" value="<?= $subtotal ?>">
                                </td>
                            </tr>
                        </tfoot>
                    </table>

                </div>
            </div>
        </div>
        <div class="col-sm-2"></div>
    </div>

</div>

<?php
    
$modalDialog = new ModalDialog([
    'clickedComponent' => 'a#delete',
    'modelAttributeId' => 'model-id',
    'modelAttributeName' => 'model-name',
]);

$modalDialog->theScript();

echo $modalDialog->renderDialog();

$jscript = Yii::$app->params['checkbox-radio-script']()
        . '$(".iCheck-helper").parent().removeClass("disabled");';

$this->registerJs($jscript);

$this->params['regJsFile'][] = function() {
    $this->registerJsFile(Yii::getAlias('@common-web') . '/js/plugins/iCheck/icheck.min.js');    
};
    
?>