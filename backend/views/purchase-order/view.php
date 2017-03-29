<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use backend\components\ModalDialog;

/* @var $this yii\web\View */
/* @var $model backend\models\PurchaseOrder */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Purchase Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title; ?>

<div class="purchase-order-view">
    
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
        <div class="col-sm-2"></div>
        <div class="col-sm-8">
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
                            </tr>
                        </thead>                    
                        <tbody id="tbodyItem">                        
                            <?php
                            $subtotal = 0;

                            if (!empty($modelPurchaseOrderTrxs)):                                                                                                 
                                foreach ($modelPurchaseOrderTrxs as $key => $modelPurchaseOrderTrx): 

                                    $total = $modelPurchaseOrderTrx->jumlah_harga;
                                    $subtotal += $total; ?>

                                    <tr>
                                        <td id="item-id"><?= $modelPurchaseOrderTrx->item_id ?></td>
                                        <td id="item-name"><?= $modelPurchaseOrderTrx->item->nama_item ?></td>
                                        <td id="satuan"><?= $modelPurchaseOrderTrx->itemSku->nama_sku ?></td>
                                        <td id="jumlah"><?= $modelPurchaseOrderTrx->jumlah_order ?></td>
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
    
?>