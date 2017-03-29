<?php

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use yii\web\View;
use backend\components\AppMenu;

/* @var $this View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <?= Html::csrfMetaTags() ?>
        
        <!-- Favicon -->
        <link rel="icon" href="<?= Yii::$app->request->baseUrl . '/favicon.png' ?>" type="image/x-icon">
        <link rel="shortcut icon" href="<?= Yii::$app->request->baseUrl . '/favicon.png' ?>" type="image/x-icon">
        <link rel="apple-touch-icon" href="<?= Yii::$app->request->baseUrl . '/favicon.png' ?>">

        <title><?= Html::encode(Yii::$app->name) . ' - ' . Html::encode($this->title) ?></title>
        <?php 
        $this->head();
        
        if (isset($this->params['regCssFile'])) {
            foreach ($this->params['regCssFile'] as $value) {
                $value();
            }
        } ?>
    </head>
    <body class="skin-meriz fixed">
        <?php $this->beginBody() ?>
        
        <?php 
        $menu = new AppMenu(); 
        echo $menu->header() ?>
        
        <div class="wrapper row-offcanvas row-offcanvas-left">
            
            <?= $menu->sideMenu() ?>
            
            <aside class="right-side">
                <section class="content-header">
                    <h1><?= Html::encode($this->title ) . (isset($this->params['titleH1']) ? $this->params['titleH1'] : '') ?></h1>
                    
                    <?= 
                    Breadcrumbs::widget([
                        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                    ]) ?>
                    
                </section>
                
                <section class="content">
                    
                    <?= $content ?>
                    
                </section>
            
            </aside>
        
        </div>


        <?php 
        $this->endBody();       
        
        if (isset($this->params['regJsFile'])) {
            foreach ($this->params['regJsFile'] as $value) {
                $value();
            }
        } ?>       
    </body>
</html>
<?php $this->endPage() ?>
