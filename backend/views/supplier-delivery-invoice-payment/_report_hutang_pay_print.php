<?php
$temp = $modelSupplierDeliveryInvoicePayment;
$modelSupplierDeliveryInvoicePayment = [];

foreach ($temp as $dataTemp) {
    $key = $dataTemp['supplier_delivery_invoice_id'];
    $modelSupplierDeliveryInvoicePayment[$key][] = $dataTemp;    
}

foreach ($modelSupplierDeliveryInvoicePayment as $dataSupplierDeliveryInvoicePayment): 
    
    $jumlahTotal = 0;
    $jumlahSisa = $dataSupplierDeliveryInvoicePayment[0]['supplierDeliveryInvoice']['jumlah_harga']; ?>
    
    <div class="mb">
        <div class="row">
            <div class="col-lg-12" style="font-weight: bold; font-size: 16px">
                No. Invoice: <?= $dataSupplierDeliveryInvoicePayment[0]['supplier_delivery_invoice_id'] ?> &nbsp; &nbsp; &nbsp; Tanggal: <?= Yii::$app->formatter->asDate($dataSupplierDeliveryInvoicePayment[0]['supplierDeliveryInvoice']['supplierDelivery']['date']) ?>
            </div>  
            <div class="col-lg-12" style="font-weight: bold; font-size: 16px">
                Jumlah Hutang: <?= Yii::$app->formatter->asCurrency($jumlahSisa) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <table class="table">
                    <thead>
                        <tr style="border:1px solid">
                            <th style="width: 10px">#</th>
                            <th style="width: 90px">Tanggal</th>
                            <th style="width: 150px" class="number">Jumlah Bayar</th>
                            <th style="width: 150px" class="number">Jumlah Sisa</th>                                            
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $i = 0;                                                
                        foreach ($dataSupplierDeliveryInvoicePayment as $data):   
                            
                            $i++;
                            $jumlahSisa -= $data['jumlah_bayar'];
                            $jumlahTotal += $data['jumlah_bayar']; ?>

                            <tr>
                                <td class="line"><?= $i ?></td>
                                <td class="line"><?= Yii::$app->formatter->asDate($data['created_at']) ?></td>                   
                                <td class="line number"><?= Yii::$app->formatter->asCurrency($data['jumlah_bayar']) ?></td>
                                <td class="line number"><?= Yii::$app->formatter->asCurrency($jumlahSisa) ?></td>                                               
                            </tr>

                        <?php
                        endforeach; 
                        
                        ?>

                    </tbody>
                    <tfoot>
                        <tr style="border:1px solid">                    
                            <th></th> 
                            <th style="font-size: 16px">Total Bayar</th>                                                  
                            <th class="number" style="font-size: 16px"><?= Yii::$app->formatter->asCurrency($jumlahTotal) ?></th> 
                            <th></th>                                                                                  
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
                                            

<?php
endforeach; ?>

