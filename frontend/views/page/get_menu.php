<?php

use yii\helpers\Html; 

foreach ($modelMenu as $key => $value): ?>
   
    <img class="img-responsive img-centered" src="<?= Yii::getAlias('@backend-web') . '/img/menu/' . $value['image'] ?>" alt="" data-no-retina>

    <h4 class="section-subsubheading"><?= $value['nama_menu'] ?></h4>

    <p>
        <?= $value['keterangan'] ?>
        <br>
        <strong><?= Yii::$app->formatter->asCurrency($value['harga_jual']) ?></strong>
</p>

<?php
endforeach; ?>