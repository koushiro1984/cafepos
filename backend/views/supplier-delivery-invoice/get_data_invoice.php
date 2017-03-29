<?php

use yii\helpers\Html; ?>

<table class="table">
    <tr>
        <th>Supplier Delivery Invoice ID</th>
        <td><?= $model['id'] ?></td>
    </tr>
    <tr>
        <th>Date</th>
        <td><?= Yii::$app->formatter->asDate($model['date']) ?></td>
    </tr>
    <tr>
        <th>Supplier Delivery ID</th>
        <td><?= $model['supplier_delivery_id'] ?></td>
    </tr>
    <tr>
        <th>Payment Method</th>
        <td>(<?= $model['payment_method'] ?>) <?= $model['paymentMethod']['nama_payment'] ?></td>
    </tr>
    <tr>
        <th>Jumlah Harga</th>
        <td><?= Yii::$app->formatter->asCurrency($model['jumlah_harga']) ?></td>
    </tr>
    <tr>
        <th>Jumlah Bayar</th>
        <td><?= Yii::$app->formatter->asCurrency($model['jumlah_bayar']) ?></td>
    </tr>
    <tr>
        <th>Jumlah Sisa</th>
        <td><?= Yii::$app->formatter->asCurrency($model['jumlah_harga'] - $model['jumlah_bayar']) ?></td>
    </tr>
    <tr>
        <th></th>
        <td></td>
    </tr>
</table>

<h3 class="box-title">
    Item
</h3>

<table class="table table-striped table-responsive">
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
        $total = 0;
        foreach ($model['supplierDeliveryInvoiceDetails'] as $key => $data): 

            $total += $data['jumlah_item'] * $data['harga_satuan']; ?>

            <tr>
                <td id="item-id">
                    <?= $data['item_id']; ?>
                </td>
                <td id="item-name">
                    <?= $data['item']['nama_item']; ?>
                </td>
                <td id="satuan">
                    <?= $data['itemSku']['nama_sku']; ?>
                </td>
                <td id="jumlah">
                    <?= $data['jumlah_item']; ?>
                </td>
                <td id="subtotal">
                    <?= $data['harga_satuan']; ?>
                </td>
        </tr>

        <?php
        endforeach; ?>
    </tbody>
    <tfoot>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td id="jumlah-subtotal" style="font-weight: bold">
                <span id="jumlah-subtotal-text"><?= $total ?></span>
            </td>
        </tr>
    </tfoot>
</table>

<?= Html::hiddenInput('totalHarga', $total, ['id' => 'totalHarga']); ?>