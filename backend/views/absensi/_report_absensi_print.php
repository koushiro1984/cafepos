<?php

?>

<div class="row">
    <div class="col-lg-12">
        <table class="table">
            <thead>
                <tr style="border:1px solid">
                    <th style="width: 30px">#</th>
                    <th style="width: 100px">Kode Karyawan</th>
                    <th style="width: 200px">Nama</th>
                    <th style="width: 50px">Tanggal</th>                    
                    <th style="width: 90px">Check In</th>
                    <th style="width: 90px">Check Out</th>
                </tr>
            </thead>
            <tbody>

                <?php
                $i = 0;
                foreach ($modelAbsensi as $dataAbsensi):                                                    

                    $i ++; ?>

                    <tr>
                        <td class="line"><?= $i ?></td>
                        <td class="line"><?= $dataAbsensi['kdKaryawan']['kd_karyawan'] ?></td>                   
                        <td class="line"><?= $dataAbsensi['kdKaryawan']['nama'] ?></td>     
                        <td class="line"><?= Yii::$app->formatter->asDate($dataAbsensi['tanggal']) ?></td>
                        <td class="line"><?= $dataAbsensi['check_in'] ?></td>
                        <td class="line"><?= $dataAbsensi['check_out'] ?></td>
                    </tr>

                    <?php

                    
                endforeach; ?>                                    

            </tbody>
            <tfoot>
                <tr style="border:1px solid">
                    <th>&nbsp;</th>
                    <th></th>                   
                    <th></th>
                    <th></th>
                    <th></th>                    
                    <th></th>                                          
                </tr>
            </tfoot>
        </table>
    </div>
</div>           