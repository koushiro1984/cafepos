<?php
use backend\components\Tools;
?>

<div class="row">
    <div class="col-lg-12">
        <table class="table">
            <thead>
                <tr style="border:1px solid">
                    <th style="width: 100px">No. Invoice</th>
                    <th style="width: 50px">Tanggal</th>
                    <th style="width: 200px">Supplier</th>
                    <th style="width: 90px">No. SKU</th>
                    <th style="width: 200px">Nama Item</th>
                    <th style="width: 90px" class="number">Qty</th>
                    <th style="width: 90px">Satuan</th>
                    <th style="width: 180px" class="number">Harga</th>
                    <th style="width: 200px" class="number">Total Harga</th>                        
                </tr>
            </thead>
            <tbody>

                <?php
                $jumlahTotal = 0;
                foreach ($modelSupplierDeliveryInvoice as $dataSupplierDeliveryInvoice):                                                    
                    foreach ($dataSupplierDeliveryInvoice['supplierDeliveryInvoiceDetails'] as $dataSupplierDeliveryInvoiceDetails):
                        $jumlahHarga = $dataSupplierDeliveryInvoiceDetails['jumlah_item'] * $dataSupplierDeliveryInvoiceDetails['harga_satuan'];
                        $jumlahTotal += $jumlahHarga; ?>

                        <tr>
                            <td class="line"><?= $dataSupplierDeliveryInvoiceDetails['supplier_delivery_invoice_id'] ?></td>
                            <td class="line"><?= Yii::$app->formatter->asDate($dataSupplierDeliveryInvoice['date']) ?></td>                   
                            <td class="line"><?= $dataSupplierDeliveryInvoice['supplierDelivery']['kdSupplier']['nama'] ?></td>
                            <td class="line"><?= $dataSupplierDeliveryInvoiceDetails['itemSku']['id'] ?></td>
                            <td class="line"><?= $dataSupplierDeliveryInvoiceDetails['item']['nama_item'] ?></td>                    
                            <td class="line number"><?= $dataSupplierDeliveryInvoiceDetails['jumlah_item'] ?></td> 
                            <td class="line"><?= $dataSupplierDeliveryInvoiceDetails['itemSku']['nama_sku'] ?></td>     
                            <td class="line number"><?= Tools::convertToCurrency($dataSupplierDeliveryInvoiceDetails['harga_satuan'], ($print == 'pdf')) ?></td>                                                  
                            <td class="line number"><?= Tools::convertToCurrency($jumlahHarga, ($print == 'pdf')) ?></td>                                                  
                        </tr>

                    <?php
                    endforeach;
                    
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
                    <th style="font-size: 16px">Grand Total</th>                                                  
                    <th class="number" style="font-size: 16px"><?= Tools::convertToCurrency($jumlahTotal, ($print == 'pdf')) ?></th>                                                  
                </tr>
            </tfoot>
        </table>
    </div>
</div>           