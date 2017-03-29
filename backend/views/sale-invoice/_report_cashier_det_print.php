<?php
use backend\components\Tools; ?>


<div class="row">
    <div class="col-lg-12">
        <table class="table">
            <thead>
                <tr style="border:1px solid">
                    <th style="width: 15px">#</th>
                    <th style="width: 100px">No. Invoice</th>
                    <th style="width: 50px">Tanggal</th>
                    <th style="width: 100px">Payment Method</th>
                    <th style="width: 130px" class="number">Total</th>
                    <th style="width: 130px" class="number">Total Kembalian</th>
                    <th style="width: 130px" class="number">Total Refund</th>
                    <th style="width: 130px" class="number">Total Pendapatan</th>                                         
                </tr>
            </thead>
            <tbody>

                <?php
                
                $grandTotal = 0;
                $grandPayment = 0;
                $grandKembalian = 0;
                $grandRefund = 0;
                
                foreach ($modelSaleInvoice as $key => $dataSaleInvoice):    
                    
                    $total = 0;
                    $jumlahPayment = 0;
                    $jumlahRefund = 0;
                
                    foreach ($dataSaleInvoice['saleInvoicePayments'] as $dataSaleInvoicePayment): 
                        $jumlahPayment += $dataSaleInvoicePayment['jumlah_bayar']; ?>
                
                        <tr>
                            <td class="line"></td>
                            <td class="line"></td>
                            <td class="line"></td>                   
                            <td class="line"><?= $dataSaleInvoicePayment['paymentMethod']['nama_payment'] ?></td>
                            <td class="line number"><?= Tools::convertToCurrency($dataSaleInvoicePayment['jumlah_bayar'], ($print == 'pdf')) ?></td>           
                            <td class="line"></td> 
                            <td class="line"></td>                                                  
                            <td class="line"></td>                                                  
                        </tr>
                    
                    <?php
                    endforeach; 
                    
                    foreach ($dataSaleInvoice['saleInvoiceDetails'] as $dataSaleInvoiceDetail) {
                        $subtotal = $dataSaleInvoiceDetail['returSale']['harga'] * $dataSaleInvoiceDetail['returSale']['jumlah'];
                        if ($dataSaleInvoiceDetail['returSale']['discount_type'] == 'percent') {
                            $subtotal = $subtotal - ($subtotal * $dataSaleInvoiceDetail['returSale']['discount'] / 100);
                        } elseif ($dataSaleInvoiceDetail['returSale']['discount_type'] == 'value') {
                            $subtotal = $subtotal - ($dataSaleInvoiceDetail['returSale']['discount'] * $dataSaleInvoiceDetail['returSale']['jumlah']);
                        } 
                        
                        $scp = Tools::hitungServiceChargePajak($subtotal, $dataSaleInvoice['service_charge'], $dataSaleInvoice['pajak']);                                        
                        $jumlahRefund += $subtotal + $scp['serviceCharge'] + $scp['pajak'];
                    } 
                    
                    $total += $jumlahPayment - $dataSaleInvoice['jumlah_kembali'] - $jumlahRefund;                    
                    
                    $grandTotal += $total; 
                    $grandPayment += $jumlahPayment;
                    $grandKembalian += $dataSaleInvoice['jumlah_kembali'];
                    $grandRefund += $jumlahRefund; ?>
                     
                    <tr>
                        <th class="line"><?= $key + 1 ?></th>
                        <th class="line"><?= $dataSaleInvoice['id'] ?></th>
                        <th class="line"><?= Yii::$app->formatter->asDate($dataSaleInvoice['date']) ?></th>                   
                        <th class="line"></th>
                        <th class="line number"><?= Tools::convertToCurrency($jumlahPayment, ($print == 'pdf')) ?></th>           
                        <th class="line number"><?= Tools::convertToCurrency($dataSaleInvoice['jumlah_kembali'], ($print == 'pdf')) ?></th> 
                        <th class="line number"><?= Tools::convertToCurrency($jumlahRefund, ($print == 'pdf')) ?></th>                                                  
                        <th class="line number"><?= Tools::convertToCurrency($total, ($print == 'pdf')) ?></th>                                                  
                    </tr>
                    
                    <tr>
                        <td class="line">&nbsp;</td>
                        <td class="line">&nbsp;</td>
                        <td class="line">&nbsp;</td>                   
                        <td class="line">&nbsp;</td>
                        <td class="line">&nbsp;</td>           
                        <td class="line">&nbsp;</td> 
                        <td class="line">&nbsp;</td>                                                  
                        <td class="line">&nbsp;</td>                                                  
                    </tr>
                                         

                    <?php

                    
                endforeach; ?>                                    

            </tbody>
            <tfoot>
                <tr style="border:1px solid">                   
                    <th style="font-size: 16px">Grand Total</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th class="number" style="font-size: 16px"><?= Tools::convertToCurrency($grandPayment, ($print == 'pdf')) ?></th>
                    <th class="number" style="font-size: 16px"><?= Tools::convertToCurrency($grandKembalian, ($print == 'pdf')) ?></th>
                    <th class="number" style="font-size: 16px"><?= Tools::convertToCurrency($grandRefund, ($print == 'pdf')) ?></th>                                                                     
                    <th class="number" style="font-size: 16px"><?= Tools::convertToCurrency($grandTotal, ($print == 'pdf')) ?></th>                                                  
                </tr>
            </tfoot>
        </table>
    </div>
</div>           