<div class="row">
    <div class="col-lg-12">
        <table class="table">
            <thead>
                <tr style="border:1px solid">
                    <th style="width: 10px">#</th>
                    <th style="width: 50px">Tanggal</th>
                    <th style="width: 80px">No. SKU</th>
                    <th style="width: 200px">Nama Item</th>
                    <th style="width: 90px">Satuan</th>                                     
                    <th style="width: 180px">Gudang Asal</th>
                    <th style="width: 80px">Rak</th>
                    <th style="width: 180px">Gudang Tujuan</th>
                    <th style="width: 80px">Rak</th>
                    <th style="width: 50px" class="number">Qty</th>                                            
                </tr>
            </thead>
            <tbody>
                <?php 
                $i = 0;
                foreach ($modelStock as $dataStock): 
                    $i++; ?>

                    <tr>
                        <td class="line"><?= $i ?></td>
                        <td class="line"><?= Yii::$app->formatter->asDate($dataStock['tanggal']) ?></td>
                        <td class="line"><?= $dataStock['item_sku_id'] ?></td>                   
                        <td class="line"><?= $dataStock['item']['nama_item'] ?></td>    
                        <td class="line"><?= $dataStock['itemSku']['nama_sku'] ?></td>  
                        <td class="line"><?= '(' . $dataStock['storage_from'] . ') ' . $dataStock['storageFrom']['nama_storage'] ?></td>
                        <td class="line"><?= $dataStock['storageRackFrom']['nama_rak'] ?></td>
                        <td class="line"><?= '(' . $dataStock['storage_to'] . ') ' . $dataStock['storageTo']['nama_storage'] ?></td>
                        <td class="line"><?= $dataStock['storageRackTo']['nama_rak'] ?></td>
                        <td class="line number"><?= $dataStock['jumlah'] ?></td>                                                                  
                    </tr>

                <?php
                endforeach; ?>

            </tbody>
        </table>
    </div>
</div>        