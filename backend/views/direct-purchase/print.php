<?php
use backend\components\Tools;
?>

<div class="row">
    <div class="col-lg-12">
        <table class="table">
            <tbody>
                <tr>
                    <td style="font-size: 30px; font-weight: bold; text-align: center" colspan="2">PEMBELIAN LANGSUNG</td>
                </tr>
                <tr>
                    <td style="width: 350px">
                        
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td>Reference: <?= $model->reference ?></td>
                                </tr>
                                <tr>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                        
                    </td>
                    <td style="width: 250px">
                        
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td>No. Pembelian</td>
                                    <td>:<?= $model->id ?></td>
                                </tr>
                                <tr>
                                    <td>Tanggal</td>
                                    <td>: <?= Yii::$app->formatter->asDate($model->date) ?></td>
                                </tr>
                                <tr>
                                    <td>Print At</td>
                                    <td>: <?= date('d-m-Y H:i:s') ?></td>
                                </tr>
                                <tr>
                                    <td>Print By</td>
                                    <td>: <?= Yii::$app->session->get('user_data')['employee']['nama'] ?></td>
                                </tr>
                            </tbody>
                        </table>
                        
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        
                        <table class="table" style="font-size: 12px">
                            <tbody>
                                <tr style="border: 1px solid">
                                    <th>Item ID</th>
                                    <th>Nama Item</th>
                                    <th>Satuan</th>
                                    <th class="number">Jumlah</th>
                                    <th class="number">Subtotal</th>
                                </tr>
                                
                                <?php
                                foreach ($modelDirectPurchaseTrxs as $dataDirectPurchaseTrx): ?>
                                
                                    <tr>
                                        <td><?= $dataDirectPurchaseTrx->item->id ?></td>
                                        <td><?= $dataDirectPurchaseTrx->item->nama_item ?></td>
                                        <td><?= $dataDirectPurchaseTrx->itemSku->nama_sku ?></td>
                                        <td class="number"><?= $dataDirectPurchaseTrx->jumlah_item ?></td>
                                        <td class="number"><?= Tools::convertToCurrency($dataDirectPurchaseTrx->jumlah_harga) ?></td>
                                    </tr>
                                
                                <?php
                                endforeach;?>                            
                                
                                <tr style="border: 1px solid">
                                    <th></th>
                                    <th></th>
                                    <th>TOTAL</th>
                                    <th class="number"><?= $model->jumlah_item ?></th>
                                    <th class="number"><?= Tools::convertToCurrency($model->jumlah_harga) ?></th>
                                </tr>    
                            </tbody>
                        </table>
                        
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>