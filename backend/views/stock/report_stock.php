<?php

use yii\helpers\Html;
use kartik\date\DatePicker;


/* @var $this yii\web\View */
/* @var $model backend\models\SaleInvoice */

$this->title = 'Laporan Stok';
$this->params['breadcrumbs'][] = $this->title; ?>

<div class="sale-invoice-report">

    <div class="row">
        <div class="col-sm-2"></div>
        <div class="col-sm-8">
            <div class="box box-danger">
                <div class="box-body">
                    <div class="sale-invoice-form">    
                        
                        <?= Html::beginForm() ?>
                                                    
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-3"></div>
                                    <div class="col-lg-6">
                                        <?php
                                        $icon = '<i class="fa fa-print"></i>&nbsp;&nbsp;&nbsp;';
                                        echo Html::submitButton($icon . 'PDF', ['class' => 'btn btn-success', 'name' => 'print', 'value' => 'pdf']); 
                                        echo '&nbsp;&nbsp;&nbsp;&nbsp;';
                                        echo Html::submitButton($icon . 'Excel', ['class' => 'btn btn-primary', 'name' => 'print', 'value' => 'excel']); ?>

                                    </div>
                                </div>
                            </div>
                        
                        <?= Html::endForm() ?>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-2"></div>
    </div><!-- /.row -->
</div>