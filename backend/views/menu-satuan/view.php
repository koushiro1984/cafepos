<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use backend\components\ModalDialog;

/* @var $this yii\web\View */
/* @var $model backend\models\MenuSatuan */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Menu Satuans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title; ?>

<div class="menu-satuan-view">
    
    <div class="row">
        <div class="col-sm-2"></div>
        <div class="col-sm-8">
            <div class="box box-danger">
                <div class="box-header">
                    <h3 class="box-title">
                        <?= Html::a('<i class="glyphicon glyphicon-pencil"></i>&nbsp;&nbsp;&nbsp;' . 'Edit', 
                            ['update', 'id' => $model->id], 
                            [
                                'class' => 'btn btn-primary',
                                'style' => 'color:white'
                            ]) ?>
                            
                        <?= Html::a('<i class="glyphicon glyphicon-trash"></i>&nbsp;&nbsp;&nbsp;' . 'Delete', 
                            ['delete', 'id' => $model->id], 
                            [
                                'id' => 'delete',
                                'class' => 'btn btn-danger',
                                'style' => 'color:white',
                                'model-id' => $model->id,
                                'model-name' => $model->nama_satuan,
                            ]) ?>                            
                        
                        <?= Html::a('<i class="fa fa-rotate-left"></i>&nbsp;&nbsp;&nbsp;' . 'Cancel', 
                            ['index'], 
                            [
                                'class' => 'btn btn-default',
                            ]) ?>
                    </h3>
                </div>
                
                <?= DetailView::widget([
                    'model' => $model,
                    'options' => [
                        'class' => 'table'
                    ],
                    'attributes' => [
                        'id',
                        'nama_satuan',
                    ],
                ]) ?>
                        
                </div>
            </div>
        </div>
    </div>

</div>

<?php
    
$modalDialog = new ModalDialog([
    'clickedComponent' => 'a#delete',
    'modelAttributeId' => 'model-id',
    'modelAttributeName' => 'model-name',
]);

$modalDialog->theScript();

echo $modalDialog->renderDialog();
    
?>