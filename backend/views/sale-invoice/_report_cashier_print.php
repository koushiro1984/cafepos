<?php
use backend\components\Tools;


$payment = [];
$invoice = [];
$totalPendapatan = 0;
$totalRetur = 0;

$jumlahTamu = 0;

foreach ($modelPaymentMethod as $dataPaymentMethod) {    
    $key = $dataPaymentMethod['id'];
    
    if (empty($payment[$key]))
        $payment[$key] = [];
    
    if (empty($payment[$key]['nama']))
        $payment[$key]['nama'] = $dataPaymentMethod['nama_payment'];
    
    if (empty($payment[$key]['jumlah']))
        $payment[$key]['jumlah'] = 0;            
}

foreach ($modelSaleInvoice as $dataSaleInvoice) {        
    
    $jumlahTamu += $dataSaleInvoice['mtableSession']['jumlah_guest'];
        
    foreach ($dataSaleInvoice['saleInvoicePayments'] as $dataSaleInvoicePayment) {
        $key = $dataSaleInvoicePayment['payment_method_id'];
        if (!empty($payment[$key])) {
            $payment[$key]['jumlah'] += $dataSaleInvoicePayment['jumlah_bayar'];
        } else {                
            $payment[$key]['jumlah'] = $dataSaleInvoicePayment['jumlah_bayar'];
        }

        $totalPendapatan += $dataSaleInvoicePayment['jumlah_bayar'];                        
    }

    $invoice[$dataSaleInvoicePayment['sale_invoice_id']][] = 1;
    $invoice[$dataSaleInvoicePayment['sale_invoice_id']]['jumlah_kembali'] = $dataSaleInvoice['jumlah_kembali'];
    
    foreach ($dataSaleInvoice['saleInvoiceDetails'] as $dataSaleInvoiceDetail) {
        $subtotal = $dataSaleInvoiceDetail['returSale']['harga'] * $dataSaleInvoiceDetail['returSale']['jumlah'];
        if ($dataSaleInvoiceDetail['returSale']['discount_type'] == 'percent') {
            $subtotal = $subtotal - ($subtotal * $dataSaleInvoiceDetail['returSale']['discount'] / 100);
        } elseif ($dataSaleInvoiceDetail['returSale']['discount_type'] == 'value') {
            $subtotal = $subtotal - ($dataSaleInvoiceDetail['returSale']['discount'] * $dataSaleInvoiceDetail['returSale']['jumlah']);
        } 
        
        $scp = Tools::hitungServiceChargePajak($subtotal, $dataSaleInvoice['service_charge'], $dataSaleInvoice['pajak']);                                        
        $totalRetur += $subtotal + $scp['serviceCharge'] + $scp['pajak'];
    }
}

//print_r($payment);exit;

$jumlahKembali = 0;

foreach ($invoice as $dataInvoice) {
    $jumlahKembali += $dataInvoice['jumlah_kembali'];
} ?>

    <div class="row">
        <div class="col-lg-12">
            <table class="table">                
                <tbody>
                    <tr>
                        <td style="width: 20%"></td>
                        <td>
                            <table class="table">                
                                <tbody>
                                    <?php
                                    foreach ($payment as $dataPayment): ?>

                                        <tr>
                                            <td class="line" style="width: 50%"><?= $dataPayment['nama'] ?></td>
                                            <td class="line number" style="width: 50%"><?= Tools::convertToCurrency($dataPayment['jumlah'], ($print == 'pdf')) ?></td>
                                        </tr>
                                        
                                    <?php
                                    endforeach; ?>
                                    <tr>
                                        <td class="line" style="width: 50%">&nbsp;</td>
                                        <td class="line number" style="width: 50%">&nbsp;</td>
                                    </tr> 
                                    <tr>
                                        <td class="line" style="width: 50%">Total Pembayaran</td>
                                        <td class="line number" style="width: 50%"><?= Tools::convertToCurrency($totalPendapatan, ($print == 'pdf')) ?></td>
                                    </tr>
                                    <tr>
                                        <td class="line" style="width: 50%">Total Kembalian</td>
                                        <td class="line number" style="width: 50%"><?= Tools::convertToCurrency($jumlahKembali, ($print == 'pdf')) ?></td>
                                    </tr>
                                    <tr>
                                        <td class="line" style="width: 50%">Total Refund</td>
                                        <td class="line number" style="width: 50%"><?= Tools::convertToCurrency($totalRetur, ($print == 'pdf')) ?></td>
                                    </tr>
                                        
                                </tbody>
                                <tfoot>
                                    <tr style="border:1px solid">
                                        <th>Total Pendapatan</th>
                                        <th class="number"><?= Tools::convertToCurrency($totalPendapatan - $jumlahKembali - $totalRetur, ($print == 'pdf')) ?></th>
                                    </tr>
                                    <tr>
                                        <th class="line">Jumlah Transaksi</th>
                                        <th class="line number"><?= count($invoice) ?></th>
                                    </tr>
                                    <tr>
                                        <th class="line">Jumlah Tamu</th>
                                        <th class="line number"><?= $jumlahTamu ?></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </td>                   
                        <td style="width: 20%"></td>                        
                    </tr>
                </tbody>
            </table>
        </div>
    </div>   