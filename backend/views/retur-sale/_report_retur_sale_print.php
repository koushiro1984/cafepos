<?php
use backend\components\Tools;

$temp = $modelReturSale;
$modelReturSale = [];
foreach ($temp as $dataReturSale) {
    $key = $dataReturSale['saleInvoiceDetail']['sale_invoice_id'];
    $modelReturSale[$key][] = $dataReturSale;
}

$jumlahGrandTotal = 0;

foreach ($modelReturSale as $dataReturSale): ?>
    
    <div class="mb">
        <div class="row">
            <div class="col-lg-12 fs14">
                No. Invoice: <?= $dataReturSale[0]['saleInvoiceDetail']['saleInvoice']['id'] ?> &nbsp; &nbsp; &nbsp; Tanggal: <?= Yii::$app->formatter->asDate($dataReturSale[0]['saleInvoiceDetail']['saleInvoice']['date']) ?>
            </div>           
        </div>

        <div class="row">
            <div class="col-lg-12">
                <table class="table">
                    <thead>
                        <tr style="border:1px solid">
                            <th style="width: 10px">#</th>
                            <th style="width: 300px">Menu Pesanan</th>
                            <th style="width: 150px" class="number">Harga</th>
                            <th style="width: 90px" class="number">Qty</th>                    
                            <th style="width: 150px" class="number">Diskon</th>       
                            <th style="width: 150px" class="number">Subtotal</th>                               
                            <th style="width: 250px">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $jumlahTotal = 0;
                        
                        foreach ($dataReturSale as $key => $dataReturSaleDetail): 
                            $subtotal = $dataReturSaleDetail['harga'] * $dataReturSaleDetail['jumlah'];                             

                            $discount = '';
                            if ($dataReturSaleDetail['discount_type'] == 'percent') {
                                $discount = Tools::convertToCurrency($dataReturSaleDetail['harga'] * $dataReturSaleDetail['discount'] / 100, ($print == 'pdf'));
                                $subtotal = $subtotal - ($subtotal * $dataReturSaleDetail['discount'] / 100);
                            } elseif ($dataReturSaleDetail['discount_type'] == 'value') {
                                $discount = Tools::convertToCurrency($dataReturSaleDetail['discount'], ($print == 'pdf')); 
                                $subtotal = $subtotal - ($dataReturSaleDetail['discount'] * $dataReturSaleDetail['jumlah']);
                            }                             
                            
                            $jumlahTotal += $subtotal; ?>

                            <tr>
                                <td class="line"><?= $key + 1 ?></td>
                                <td class="line"><?= $dataReturSaleDetail['menu']['nama_menu'] ?></td>                   
                                <td class="line number"><?= Tools::convertToCurrency($dataReturSaleDetail['harga'], ($print == 'pdf')) ?></td>
                                <td class="line number"><?= $dataReturSaleDetail['jumlah'] ?></td>
                                <td class="line number"><?= $discount ?></td>                    
                                <td class="line number"><?= Tools::convertToCurrency($subtotal, ($print == 'pdf')) ?></td>                                                  
                                <td class="line"><?= $dataReturSaleDetail['keterangan'] ?></td>
                            </tr>

                        <?php
                        endforeach; 
                        
                        $serviceCharge = round($jumlahTotal * $dataReturSaleDetail['saleInvoiceDetail']['saleInvoice']['service_charge'] / 100);
                        $pajak = round(($jumlahTotal + $serviceCharge) * $dataReturSaleDetail['saleInvoiceDetail']['saleInvoice']['pajak'] / 100); 
                        $grandTotal = $jumlahTotal + $serviceCharge + $pajak; 
                        
                        $jumlahGrandTotal += $grandTotal; ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">                
            <table class="table" style="border: none">
                <tbody style="border: none">
                    <tr>
                        <td style="width: 480px">                               
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td style="width: 70px">No Meja</td>
                                        <td style="width: 10px">:</td>       
                                        <td style="width: 150px">
                                            <?= '(' . $dataReturSaleDetail['saleInvoiceDetail']['saleInvoice']['mtableSession']['mtable_id'] . ') ' . $dataReturSaleDetail['saleInvoiceDetail']['saleInvoice']['mtableSession']['mtable']['nama_meja'] ?>
                                        </td>       
                                    </tr>                                        
                                    <tr>
                                        <td>Waktu Retur</td>
                                        <td>:</td>       
                                        <td><?= Yii::$app->formatter->asDate($dataReturSaleDetail['date']) ?></td>       
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                        <td style="width: 370px; padding: 0">

                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td style="width: 170px">Total</td>
                                        <td style="width: 10px">:</td>       
                                        <td class="number" style="width: 150px"><?= Tools::convertToCurrency($jumlahTotal, ($print == 'pdf')) ?></td>       
                                    </tr>                                        
                                    <tr>
                                        <td>Service Charge <?= '(' . $dataReturSaleDetail['saleInvoiceDetail']['saleInvoice']['service_charge'] . ' %)' ?></td>
                                        <td>:</td>       
                                        <td class="number"><?= Tools::convertToCurrency($serviceCharge, ($print == 'pdf')) ?></td>       
                                    </tr>
                                    <tr>
                                        <td>Pajak <?= '(' . $dataReturSaleDetail['saleInvoiceDetail']['saleInvoice']['pajak'] . ' %)' ?></td>
                                        <td>:</td>       
                                        <td class="number"><?= Tools::convertToCurrency($pajak, ($print == 'pdf')) ?></td>       
                                    </tr>
                                    <tr>
                                        <td>Grand Total</td>
                                        <td>:</td>       
                                        <td class="number"><?= Tools::convertToCurrency($grandTotal, ($print == 'pdf')) ?></td>       
                                    </tr>
                                </tbody>
                            </table>

                        </td>       
                    </tr>
                </tbody>
            </table>

        </div>
    </div>

<?php
endforeach; ?>

<div class="row">
    <div class="col-lg-12">                
        <table class="table">
            <tbody>
                <tr style="border: 2px solid">
                    <th style="width: 480px; font-size: 16px">                               
                        GRAND TOTAL
                    </th>
                    <th style="width: 370px; font-size: 16px">                        
                        <?= Tools::convertToCurrency($jumlahGrandTotal, ($print == 'pdf')) ?>
                    </th>       
                </tr>
            </tbody>
        </table>

    </div>
</div>