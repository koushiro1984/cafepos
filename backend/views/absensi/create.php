<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Absensi */

$this->title = 'Create Absensi';
$this->params['breadcrumbs'][] = ['label' => 'Absensis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="absensi-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
