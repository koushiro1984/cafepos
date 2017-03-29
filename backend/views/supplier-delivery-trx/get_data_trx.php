<?php

use yii\helpers\Html; ?>

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
        foreach ($model as $key => $data): 

            $total += $data['jumlah_terima'] * $data['harga_satuan']; ?>

            <tr>
                <td id="item-id">
                    <?= Html::hiddenInput('trx[' . $key . '][item_id]', $data['item_id']) ?>
                    <?= $data['item_id']; ?>
                </td>
                <td id="item-name">
                    <?= $data['item']['nama_item']; ?>
                </td>
                <td id="satuan">
                    <?= Html::hiddenInput('trx[' . $key . '][item_sku_id]', $data['item_sku_id']) ?>
                    <?= $data['itemSku']['nama_sku']; ?>
                </td>
                <td id="jumlah">
                    <?= Html::hiddenInput('trx[' . $key . '][jumlah_terima]', $data['jumlah_terima']) ?>
                    <?= $data['jumlah_terima']; ?>
                </td>
                <td id="subtotal">
                    <?= Html::hiddenInput('trx[' . $key . '][harga_satuan]', $data['harga_satuan']) ?>
                    <?= $data['jumlah_terima'] * $data['harga_satuan']; ?>
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