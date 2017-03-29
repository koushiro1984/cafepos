<?php
use backend\components\Tools;
?>

<div class="row">
    <div class="col-lg-12">
        <table class="table">
            <thead>
                <tr style="border:1px solid">
                    <th style="width: 100px">No. RETUR</th>
                    <th style="width: 50px">Tanggal</th>
                    <th style="width: 150px">Supplier</th>
                    <th style="width: 90px">No. SKU</th>
                    <th style="width: 250px">Nama Item</th>
                    <th style="width: 200px">Gudang</th>
                    <th style="width: 100px">Rak Gudang</th>
                    <th style="width: 90px" class="number">Qty</th>
                    <th style="width: 90px">Satuan</th>
                    <th style="width: 120px" class="number">Harga</th>
                    <th style="width: 160px" class="number">Total Harga</th>                        
                </tr>
            </thead>
            <tbody>

                <?php
                $jumlahTotal = 0;
                foreach ($modelReturPurchase as $dataReturPurchase):                                                    

                    $jumlahTotal += $dataReturPurchase['jumlah_harga']; ?>

                    <tr>
                        <td class="line"><?= $dataReturPurchase['retur_purchase_id'] ?></td>
                        <td class="line"><?= Yii::$app->formatter->asDate($dataReturPurchase['returPurchase']['date']) ?></td>                   
                        <td class="line"><?= $dataReturPurchase['returPurchase']['kdSupplier']['nama'] ?></td>
                        <td class="line"><?= $dataReturPurchase['itemSku']['id'] ?></td>
                        <td class="line"><?= $dataReturPurchase['item']['nama_item'] ?></td>                    
                        <td class="line"><?= '(' . $dataReturPurchase['storage']['id'] . ') ' . $dataReturPurchase['storage']['nama_storage'] ?></td>
                        <td class="line"><?= $dataReturPurchase['storageRack']['nama_rak'] ?></td>
                        <td class="line number"><?= $dataReturPurchase['jumlah_item'] ?></td> 
                        <td class="line"><?= $dataReturPurchase['itemSku']['nama_sku'] ?></td>     
                        <td class="line number"><?= Tools::convertToCurrency($dataReturPurchase['harga_satuan'], ($print == 'pdf')) ?></td>                                                  
                        <td class="line number"><?= Tools::convertToCurrency($dataReturPurchase['jumlah_harga'], ($print == 'pdf')) ?></td>                                                  
                    </tr>

                    <?php

                    
                endforeach; ?>                                    

            </tbody>
            <tfoot>
                <tr style="border:1px solid">
                    <th></th>
                    <th></th>                   
                    <th></th>
                    <th></th>
                    <th></th>                    
                    <th></th> 
                    <th></th>     
                    <th></th>     
                    <th></th>     
                    <th style="font-size: 16px">Grand Total</th>                                                  
                    <th class="number" style="font-size: 16px"><?= Tools::convertToCurrency($jumlahTotal, ($print == 'pdf')) ?></th>                                                  
                </tr>
            </tfoot>
        </table>
    </div>
</div>           