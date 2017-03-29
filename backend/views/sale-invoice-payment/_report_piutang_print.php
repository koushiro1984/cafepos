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
                    <th style="width: 130px" class="number">Total Piutang Invoice</th>
                    <th style="width: 130px" class="number">Total Bayar</th>
                    <th style="width: 130px" class="number">Sisa</th>                      
                </tr>
            </thead>
            <tbody>

                <?php
                $jumlahSisa = 0;
                foreach ($modelSaleInvoicePayment as $dataSaleInvoicePayment):                                                    

                    $sisa = $dataSaleInvoicePayment['jumlah_bayar']; 
                    
                    foreach ($dataSaleInvoicePayment['saleInvoicePayments'] as $dataSaleInvoicePaymentChild) {
                        $sisa -= $dataSaleInvoicePaymentChild['jumlah_bayar'];
                        $jumlahSisa += $sisa; 
                    }?>

                    <tr>
                        <td class="line"><?= $dataSaleInvoicePayment['sale_invoice_id'] ?></td>
                        <td class="line"><?= Yii::$app->formatter->asDate($dataSaleInvoicePayment['saleInvoice']['date']) ?></td>                                    
                        <td class="line number"><?= Tools::convertToCurrency($dataSaleInvoicePayment['jumlah_bayar'], ($print == 'pdf')) ?></td>   
                        <td class="line number"><?= Tools::convertToCurrency($dataSaleInvoicePayment['jumlah_bayar_child'], ($print == 'pdf')) ?></td>                                                  
                        <td class="line number"><?= Tools::convertToCurrency($sisa, ($print == 'pdf')) ?></td>                                                  
                    </tr>

                    <?php

                    
                endforeach; ?>                                    

            </tbody>
            <tfoot>
                <tr style="border:1px solid">
                    <th></th>
                    <th></th>                   
                    <th></th>    
                    <th style="font-size: 16px">Grand Total</th>                                                  
                    <th class="number" style="font-size: 16px"><?= Tools::convertToCurrency($jumlahSisa, ($print == 'pdf')) ?></th>                                                  
                </tr>
            </tfoot>
        </table>
    </div>
</div>    

