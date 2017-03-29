<?php
use backend\components\Tools;
use backend\components\NotificationDialog;

if (!empty($modelSaleInvoice)):

    $dataMenu = [];
    $jumlahFaktur = 0;
    $jumlahDiskon = 0;
    $jumlahTotal = 0;
    $jumlahServiceCharge = 0;
    $jumlahPajak = 0;
    $jumlahRefund = 0;
    $jumlahRefundServiceCharge = 0;
    $jumlahRefundPajak = 0;
    $jumlahVoid = 0;
    $jumlahFreeMenu = 0;
    $jumlahGrandTotal = 0;
    $jumlahKembalian = 0;

    $dataPayment = [];
    $paymentJumlahTotal = 0;

    foreach ($modelSaleInvoice as $dataSaleInvoice) {

        $jumlahFaktur ++;
        $jumlahSubtotal = 0;
        $jumlahSubtotalDiskon = 0;
        $jumlahSubtotalRefund = 0;
        $jumlahSubtotalRefundServiceCharge = 0;
        $jumlahSubtotalRefundPajak = 0;
        $jumlahSubtotalVoid = 0;
        $jumlahSubtotalFreeMenu = 0;

        foreach ($dataSaleInvoice['saleInvoiceDetails'] as $dataSaleInvoiceDetail) {
            $keyMenu = $dataSaleInvoiceDetail['menu']['menuCategory']['id'];
            $keyMenu2 = $dataSaleInvoiceDetail['menu']['id'];

            $dataMenu[$keyMenu]['namaCategory'] = $dataSaleInvoiceDetail['menu']['menuCategory']['nama_category'];

            $dataMenu[$keyMenu][$keyMenu2]['nama_menu'] = $dataSaleInvoiceDetail['menu']['nama_menu'];

            if (!empty($dataMenu[$keyMenu][$keyMenu2]['qty']))
                $dataMenu[$keyMenu][$keyMenu2]['qty'] += $dataSaleInvoiceDetail['jumlah'];
            else
                $dataMenu[$keyMenu][$keyMenu2]['qty'] = $dataSaleInvoiceDetail['jumlah'];


            $subtotal = $dataSaleInvoiceDetail['harga'] * $dataSaleInvoiceDetail['jumlah'];
            $discount = 0;
            if ($dataSaleInvoiceDetail['discount_type'] == 'percent') {
                $discount = ($dataSaleInvoiceDetail['harga'] * $dataSaleInvoiceDetail['discount'] / 100) * $dataSaleInvoiceDetail['jumlah'];
            } elseif ($dataSaleInvoiceDetail['discount_type'] == 'value') {
                $discount = $dataSaleInvoiceDetail['discount'] * $dataSaleInvoiceDetail['jumlah']; 
            }

            if ($dataSaleInvoiceDetail['is_void']) {
                $jumlahSubtotalVoid += $dataSaleInvoiceDetail['harga'] * $dataSaleInvoiceDetail['jumlah'];
                $jumlahVoid += $dataSaleInvoiceDetail['harga'] * $dataSaleInvoiceDetail['jumlah'];
            }

            if ($dataSaleInvoiceDetail['is_free_menu']) {
                $jumlahSubtotalFreeMenu += $dataSaleInvoiceDetail['harga'] * $dataSaleInvoiceDetail['jumlah'];
                $jumlahFreeMenu += $dataSaleInvoiceDetail['harga'] * $dataSaleInvoiceDetail['jumlah'];
            }

            if (!empty($dataMenu[$keyMenu][$keyMenu2]['subtotal']))
                $dataMenu[$keyMenu][$keyMenu2]['subtotal'] += $subtotal;
            else
                $dataMenu[$keyMenu][$keyMenu2]['subtotal'] = $subtotal;  


            $subtotalRefund = $dataSaleInvoiceDetail['returSale']['harga'] * $dataSaleInvoiceDetail['returSale']['jumlah'];
            if ($dataSaleInvoiceDetail['returSale']['discount_type'] == 'percent') {
                $subtotalRefund = $subtotalRefund - ($subtotalRefund * $dataSaleInvoiceDetail['returSale']['discount'] / 100);
            } elseif ($dataSaleInvoiceDetail['returSale']['discount_type'] == 'value') {
                $subtotalRefund = $subtotalRefund - ($dataSaleInvoiceDetail['returSale']['discount'] * $dataSaleInvoiceDetail['returSale']['jumlah']);
            }

            $scp = Tools::hitungServiceChargePajak($subtotalRefund, $dataSaleInvoice['service_charge'], $dataSaleInvoice['pajak']);              

            $jumlahRefund += $subtotalRefund;
            $jumlahRefundServiceCharge += $scp['serviceCharge'];
            $jumlahRefundPajak += $scp['pajak'];

            $jumlahSubtotalRefund += $subtotalRefund;
            $jumlahSubtotalRefundServiceCharge += $scp['serviceCharge'];
            $jumlahSubtotalRefundPajak += $scp['pajak'];

            $jumlahDiskon += $discount;
            $jumlahSubtotalDiskon += $discount;
            $jumlahTotal += $subtotal;
            $jumlahSubtotal += $subtotal;                        
        }            

        $scp = Tools::hitungServiceChargePajak($jumlahSubtotal - ($jumlahSubtotalDiskon + $jumlahSubtotalRefund + $jumlahSubtotalVoid + $jumlahSubtotalFreeMenu), $dataSaleInvoice['service_charge'], $dataSaleInvoice['pajak']);                                        
        $serviceCharge = $scp['serviceCharge'];
        $pajak = $scp['pajak']; 
        $grandTotal = ($jumlahSubtotal - ($jumlahSubtotalDiskon + $jumlahSubtotalRefund + $jumlahSubtotalVoid + $jumlahSubtotalFreeMenu)) + $serviceCharge + $pajak;

        $jumlahKembalian += $dataSaleInvoice['jumlah_kembali'];

        $jumlahServiceCharge += $serviceCharge;
        $jumlahPajak += $pajak;
        $jumlahGrandTotal += $grandTotal;

        foreach ($dataSaleInvoice['saleInvoicePayments'] as $dataPaymentMethod) {
            $keyMenu = $dataPaymentMethod['paymentMethod']['id'];

            $dataPayment[$keyMenu]['namaPayment'] = $dataPaymentMethod['paymentMethod']['nama_payment'];
            $dataPayment[$keyMenu]['method'] = $dataPaymentMethod['paymentMethod']['method'];

            if (!empty($dataPayment[$keyMenu]['jumlahBayar']))
                $dataPayment[$keyMenu]['jumlahBayar'] += $dataPaymentMethod['jumlah_bayar'];
            else
                $dataPayment[$keyMenu]['jumlahBayar'] = $dataPaymentMethod['jumlah_bayar'];

            if (!empty($dataPayment[$keyMenu]['count']))
                $dataPayment[$keyMenu]['count'] += 1;
            else
                $dataPayment[$keyMenu]['count'] = 1;

            $paymentJumlahTotal += $dataPaymentMethod['jumlah_bayar'];            
        }
    } 

    $saldoKasirAwal = !empty($modelSaldoKasir['saldo_awal']) ? $modelSaldoKasir['saldo_awal'] : 0; ?>

    <div class="row">
        <div class="col-lg-12">
            <table class="table">
                <tbody>
                    <tr>
                        <td>Petugas</td>
                        <td><?= Yii::$app->session->get('user_data')['employee']['nama'] ?></td>                      
                    </tr>
                </tbody>
            </table>

            <table class="table">
                <tbody>                                
                    <tr>
                        <th style="width: 300px">Menu</th>
                        <th style="width: 80px">Qty</th>    
                        <th class="number" style="width: 150px">Jumlah Harga</th>    
                    </tr>

                    <?php
                    asort($dataMenu);
                    foreach ($dataMenu as $keyC => $menuCategory): ?>

                        <tr>
                            <td colspan="3" style="font-weight: bold">- <?= $menuCategory['namaCategory'] ?> -</td>                        
                        </tr>

                        <?php
                        asort($menuCategory);
                        foreach ($menuCategory as $key => $menu): 
                            if ($key != 'namaCategory'): ?>

                                <tr>
                                    <td><?= $menu['nama_menu'] ?></td>
                                    <td><?= $menu['qty'] ?></td>
                                    <td class="number"><?= Tools::convertToCurrency($menu['subtotal'], ($print == 'pdf')) ?></td>
                                </tr>

                            <?php
                            endif;
                        endforeach; ?>

                        <tr>
                            <td colspan="3"></td>                        
                        </tr>

                    <?php
                    endforeach; ?> 

                </tbody>
            </table>

            <table class="table">
                <tbody>
                    <tr>
                        <td style="width: 100px">Total Faktur</td>
                        <td class="number" style="width: 200px"><?= $jumlahFaktur ?></td>                      
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>                      
                    </tr>
                    <tr>
                        <td>Total Penjualan (Gross)</td>
                        <td class="number"><?= Tools::convertToCurrency($jumlahTotal, ($print == 'pdf')) ?></td>                      
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>                      
                    </tr>
                    <tr>
                        <td>Total Disc Item</td>
                        <td class="number">(<?= Tools::convertToCurrency($jumlahDiskon, ($print == 'pdf')) ?>)</td>                      
                    </tr>
                    <tr>
                        <td>Total Free Menu</td>
                        <td class="number">(<?= Tools::convertToCurrency($jumlahFreeMenu, ($print == 'pdf')) ?>)</td>                      
                    </tr>
                    <tr>
                        <th></th>
                        <th></th>                      
                    </tr>
                    <tr>
                        <td>Total Refund</td>
                        <td class="number">(<?= Tools::convertToCurrency($jumlahRefund, ($print == 'pdf')) ?>)</td>                      
                    </tr>
                    <tr>
                        <td>Total Void</td>
                        <td class="number">(<?= Tools::convertToCurrency($jumlahVoid, ($print == 'pdf')) ?>)</td>                      
                    </tr>
                    <tr>
                        <th></th>
                        <th></th>                      
                    </tr>
                    <tr>
                        <td>Total Penjualan (Netto)</td>
                        <td class="number"><?= Tools::convertToCurrency($jumlahTotal - ($jumlahDiskon + $jumlahFreeMenu + $jumlahRefund + $jumlahVoid), ($print == 'pdf')) ?></td>                      
                    </tr>
                    <tr>
                        <td>Total Service Charge</td>
                        <td class="number"><?= Tools::convertToCurrency($jumlahServiceCharge, ($print == 'pdf')) ?></td>                      
                    </tr>
                    <tr>
                        <td>Total Pajak</td>
                        <td class="number"><?= Tools::convertToCurrency($jumlahPajak, ($print == 'pdf')) ?></td>                      
                    </tr>
                    <tr>
                        <th></th>
                        <th></th>                      
                    </tr>
                    <tr>
                        <td>GRAND TOTAL</td>
                        <td class="number"><?= Tools::convertToCurrency($jumlahGrandTotal, ($print == 'pdf')) ?></td>                      
                    </tr>
                </tbody>
            </table>

            <table class="table">
                <tbody>
                    <tr>
                        <th style="width: 300px">Payment</th> 
                        <th class="number" style="width: 150px">Jumlah</th>    
                    </tr>

                    <?php
                    asort($dataPayment);
                    $cashPayment = 0;
                    foreach ($dataPayment as $payment): 
                        if ($payment['method'] == 'cash')
                            $cashPayment += $payment['jumlahBayar']; ?>

                        <tr>
                            <td><?= '(' . $payment['count'] . ') ' . $payment['namaPayment'] ?></td>
                            <td class="number"><?= Tools::convertToCurrency($payment['jumlahBayar'], ($print == 'pdf')) ?></td>
                        </tr>

                    <?php
                    endforeach; ?> 

                </tbody>
            </table>

            <table class="table">
                <tbody>
                    <tr>
                        <td></td>
                        <td></td>                      
                    </tr>
                    <tr>
                        <td style="width: 100px">Total Kembalian</td>
                        <td class="number" style="width: 200px">(<?= Tools::convertToCurrency($jumlahKembalian, ($print == 'pdf')) ?>)</td>                      
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>                      
                    </tr>
                    <tr>
                        <td>Total Refund</td>
                        <td class="number">(<?= Tools::convertToCurrency($jumlahRefund, ($print == 'pdf')) ?>)</td>                      
                    </tr>
                    <tr>
                        <td>Total Service Charge</td>
                        <td class="number">(<?= Tools::convertToCurrency($jumlahRefundServiceCharge, ($print == 'pdf')) ?>)</td>                      
                    </tr>
                    <tr>
                        <td>Total Void</td>
                        <td class="number">(<?= Tools::convertToCurrency($jumlahRefundPajak, ($print == 'pdf')) ?>)</td>                      
                    </tr>
                    <tr>
                        <th></th>
                        <th></th>                      
                    </tr>
                    <tr>
                        <td>SALDO AWAL</td>
                        <td class="number"><?= Tools::convertToCurrency($saldoKasirAwal, ($print == 'pdf')) ?></td>                      
                    <tr>
                        <th></th>
                        <th></th>                      
                    </tr>
                    <tr>
                        <td>CASH ON HAND</td>
                        <td class="number"><?= Tools::convertToCurrency(($cashPayment - ($jumlahKembalian + $jumlahRefund + $jumlahRefundServiceCharge + $jumlahRefundPajak)) + $saldoKasirAwal, ($print == 'pdf')) ?></td>                      
                    </tr>
                </tbody>
            </table>
        </div>
    </div>   

<?php
else:
    echo 'Tidak ada data';
endif; ?>