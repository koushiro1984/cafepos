<?php
use backend\components\Tools;
?>

<div class="row">
    <div class="col-lg-12">
        <table class="table">
            <thead>
                <tr style="border:1px solid">
                    <th style="width: 10px">#</th>
                    <th style="width: 50px">Tanggal</th>
                    <th style="width: 180px">Menu</th>
                    <th style="width: 80px" class="number">Qty</th>                                     
                    <th style="width: 150px" class="number">Harga</th>                                     
                    <th style="width: 150px" class="number">Jumlah</th>                                 
                    <th style="width: 80px">Meja</th>
                    <th style="width: 120px">User</th>                                         
                    <th style="width: 200px">Catatan</th>                                           
                </tr>
            </thead>
            <tbody>
                <?php 
                $i = 0;
                $jumlahTotal = 0;
                foreach ($modelMtableOrder as $dataMtableOrder): 
                    $i++; 
                    $jumlah = $dataMtableOrder['jumlah'] * $dataMtableOrder['harga_satuan']; 
                    $jumlahTotal += $jumlah; ?>

                    <tr>
                        <td class="line"><?= $i ?></td>
                        <td class="line"><?= Yii::$app->formatter->asDate($dataMtableOrder['mtableSession']['opened_at']) ?></td>
                        <td class="line"><?= $dataMtableOrder['menu']['nama_menu'] ?></td>                   
                        <td class="line number"><?= $dataMtableOrder['jumlah'] ?></td>    
                        <td class="line number"><?= Tools::convertToCurrency($dataMtableOrder['harga_satuan'], ($print == 'pdf')) ?></td>  
                        <td class="line number"><?= Tools::convertToCurrency($jumlah, ($print == 'pdf')) ?></td>
                        <td class="line"><?= $dataMtableOrder['mtableSession']['mtable_id'] ?></td>           
                        <td class="line"><?= $dataMtableOrder['userVoid']['kdKaryawan']['nama'] ?></td>  
                        <td class="line"><?= $dataMtableOrder['catatan'] ?></td>
                    </tr>

                <?php
                endforeach; ?>
                    
                <tr>
                    <td class="line"></td>
                    <td class="line"></td>
                    <td class="line"></td>                   
                    <td class="line number"></td>    
                    <td class="line number" style="font-weight: bold">JUMLAH TOTAL</td>  
                    <td class="line number" style="font-weight: bold"><?= Tools::convertToCurrency($jumlahTotal, ($print == 'pdf')) ?></td>
                    <td class="line"></td>           
                    <td class="line"></td>  
                    <td class="line"></td>  
                    <td class="line"></td>
                </tr>
            </tbody>
            <tfoot>
                
            </tfoot>
        </table>
    </div>
</div>        