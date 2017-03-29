<?php
use backend\components\Tools;


$dataMenu = [];
$jumlahDiskon = 0;
$jumlahTotal = 0;
$jumlahServiceCharge = 0;
$jumlahPajak = 0;
$jumlahGrandTotal = 0;
foreach ($modelSaleInvoice as $dataSaleInvoice) {
    
    $jumlahSubtotal = 0;
    
    foreach ($dataSaleInvoice['saleInvoiceDetails'] as $dataSaleInvoiceDetail) {
        $keyMenu = $dataSaleInvoiceDetail['menu']['id'];
        
        $dataMenu[$keyMenu]['nama_menu'] = $dataSaleInvoiceDetail['menu']['nama_menu'];
        
        if (!empty($dataMenu[$keyMenu]['qty']))
            $dataMenu[$keyMenu]['qty'] += $dataSaleInvoiceDetail['jumlah'];
        else
            $dataMenu[$keyMenu]['qty'] = $dataSaleInvoiceDetail['jumlah'];
        
        
        $subtotal = $dataSaleInvoiceDetail['harga'] * $dataSaleInvoiceDetail['jumlah'];
        $discount = 0;
        if ($dataSaleInvoiceDetail['discount_type'] == 'percent') {
            $discount = ($dataSaleInvoiceDetail['harga'] * $dataSaleInvoiceDetail['discount'] / 100) * $dataSaleInvoiceDetail['jumlah'];
            $subtotal = $subtotal - $discount;
        } elseif ($dataSaleInvoiceDetail['discount_type'] == 'value') {
            $discount = $dataSaleInvoiceDetail['discount'] * $dataSaleInvoiceDetail['jumlah']; 
            $subtotal = $subtotal - $discount;
        }
        
        if (!empty($dataMenu[$keyMenu]['subtotal']))
            $dataMenu[$keyMenu]['subtotal'] += $subtotal;
        else
            $dataMenu[$keyMenu]['subtotal'] = $subtotal;   
        
        $jumlahDiskon += $discount;
        $jumlahTotal += $subtotal;
        $jumlahSubtotal += $subtotal;
    }    
    
    $scp = Tools::hitungServiceChargePajak($jumlahSubtotal, $dataSaleInvoice['service_charge'], $dataSaleInvoice['pajak']);                                        
    $serviceCharge = $scp['serviceCharge'];
    $pajak = $scp['pajak']; 
    $grandTotal = $jumlahSubtotal + $serviceCharge + $pajak;
    
    $jumlahServiceCharge += $serviceCharge;
    $jumlahPajak += $pajak;
    $jumlahGrandTotal += $grandTotal;
} ?>

<div class="mb">    

    <div class="row">
        <div class="col-lg-12">
            <table class="table">
                <thead>
                    <tr style="border:1px solid">
                        <th style="width: 10px">#</th>
                        <th style="width: 370px">Menu Pesanan</th>
                        <th style="width: 90px">Qty</th>
                        <th style="width: 180px" class="number">Subtotal</th>                               
                    </tr>
                </thead>
                <tbody>                   
                    <?php
                    
                    $i = 0;
                    foreach ($dataMenu as $value): 
                        $i++; ?>

                        <tr>
                            <td class="line"><?= $i ?></td>
                            <td class="line"><?= $value['nama_menu'] ?></td>                   
                            <td class="line"><?= $value['qty'] ?></td>                    
                            <td class="line number"><?= Tools::convertToCurrency($value['subtotal'], ($print == 'pdf')) ?></td>                                                  
                        </tr>

                    <?php
                    endforeach; ?>
                        
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-12">                
            <table class="table" style="border: none">
                <tbody style="border: none">
                    <tr>
                        <td style="width: 480px; padding: 0">

                            <table class="table" style="width: 300px">
                                <tbody>
                                    <tr>
                                        <td style="width: 140px">Total Invoice</td>
                                        <td style="width: 10px">:</td>       
                                        <td class="number" style="width: 120px"><?= count($modelSaleInvoice) ?></td>       
                                    </tr>
                                    <tr>
                                        <td>Total Diskon Item</td>
                                        <td>:</td>       
                                        <td class="number"><?= Tools::convertToCurrency($jumlahDiskon, ($print == 'pdf')) ?></td>       
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
                                        <td>Total Service Charge</td>
                                        <td>:</td>       
                                        <td class="number"><?= Tools::convertToCurrency($jumlahServiceCharge, ($print == 'pdf')) ?></td>       
                                    </tr>
                                    <tr>
                                        <td>Total Pajak</td>
                                        <td>:</td>       
                                        <td class="number"><?= Tools::convertToCurrency($jumlahPajak, ($print == 'pdf')) ?></td>       
                                    </tr>
                                    <tr>
                                        <td>Grand Total</td>
                                        <td>:</td>       
                                        <td class="number"><?= Tools::convertToCurrency($jumlahGrandTotal, ($print == 'pdf')) ?></td>       
                                    </tr>
                                </tbody>
                            </table>

                        </td>       
                    </tr>
                </tbody>
            </table>

        </div>
    </div>
    
</div>