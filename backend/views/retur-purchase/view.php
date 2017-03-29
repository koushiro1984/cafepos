<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use backend\components\ModalDialog;

/* @var $this yii\web\View */
/* @var $model backend\models\ReturPurchase */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Retur Purchases', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title; ?>

<div class="retur-purchase-view">
    
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
                                'model-name' => '',
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
                        'date:date',
                        'kd_supplier',
                        'kdSupplier.nama',
                        'jumlah_item',
                        'jumlah_harga:currency',
                    ],
                ]) ?>
                        
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-sm-1"></div>
        <div class="col-sm-10">
            <div class="box box-danger">
                <div class="box-header">
                    <h3 class="box-title">
                        Item
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
                                <th>Storage</th>
                                <th>Rak</th>
                            </tr>
                        </thead>                    
                        <tbody id="tbodyItem">                        
                            <?php
                            $subtotal = 0;

                            if (!empty($modelReturPurchaseTrxs)):                                                                                                 
                                foreach ($modelReturPurchaseTrxs as $key => $modelReturPurchaseTrx): 

                                    $total = $modelReturPurchaseTrx->jumlah_harga;
                                    $subtotal += $total; ?>

                                    <tr>
                                        <td id="item-id"><?= $modelReturPurchaseTrx->item_id ?></td>
                                        <td id="item-name"><?= $modelReturPurchaseTrx->item->nama_item ?></td>
                                        <td id="satuan"><?= $modelReturPurchaseTrx->itemSku->nama_sku ?></td>
                                        <td id="jumlah"><?= $modelReturPurchaseTrx->jumlah_item ?></td>
                                        <td id="subtotal"><?= $total ?></td>
                                        <td id="storage"><?= '(' . $modelReturPurchaseTrx->storage_id . ') ' . $modelReturPurchaseTrx->storage->nama_storage ?></td>
                                        <td id="rack"><?= !empty($modelReturPurchaseTrx->storageRack) ? $modelReturPurchaseTrx->storageRack->nama_rak : '' ?></td>
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
                                </td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>

                </div>
            </div>
        </div>
        <div class="col-sm-1"></div>
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
    
?>