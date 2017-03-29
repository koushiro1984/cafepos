<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use backend\components\ModalDialog;

/* @var $this yii\web\View */
/* @var $model backend\models\SupplierDelivery */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Supplier Deliveries', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title; ?>

<div class="supplier-delivery-view">
    
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
                        'jumlah_item',
                        'jumlah_harga:currency',
                    ],
                ]) ?>
                        
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-sm-12">
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
                                <th>No. PO</th>
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

                            if (!empty($modelSupplierDeliveryTrxs)):                                                                                                 
                                foreach ($modelSupplierDeliveryTrxs as $key => $modelSupplierDeliveryTrx): 

                                    $total = $modelSupplierDeliveryTrx->jumlah_harga;
                                    $subtotal += $total; ?>

                                    <tr>
                                        <td id="purchase-order-id"><?= $modelSupplierDeliveryTrx->purchase_order_id ?></td>
                                        <td id="item-id"><?= $modelSupplierDeliveryTrx->item_id ?></td>
                                        <td id="item-name"><?= $modelSupplierDeliveryTrx->item->nama_item ?></td>
                                        <td id="satuan"><?= $modelSupplierDeliveryTrx->itemSku->nama_sku ?></td>
                                        <td id="jumlah"><?= $modelSupplierDeliveryTrx->jumlah_terima ?></td>
                                        <td id="subtotal"><?= $total ?></td>
                                        <td id="storage"><?= '(' . $modelSupplierDeliveryTrx->storage_id . ') ' . $modelSupplierDeliveryTrx->storage->nama_storage ?></td>
                                        <td id="rack"><?= !empty($modelSupplierDeliveryTrx->storageRack) ? $modelSupplierDeliveryTrx->storageRack->nama_rak : '' ?></td>
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