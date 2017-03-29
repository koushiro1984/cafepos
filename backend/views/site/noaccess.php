<?php

use backend\assets\AppAsset;
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this); ?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <!-- Favicon -->
        <link rel="icon" href="<?= Yii::$app->request->baseUrl . '/favicon.png' ?>" type="image/x-icon">
        <link rel="shortcut icon" href="<?= Yii::$app->request->baseUrl . '/favicon.png' ?>" type="image/x-icon">
        <link rel="apple-touch-icon" href="<?= Yii::$app->request->baseUrl . '/favicon.png' ?>">
        
        <title><?= Html::encode(Yii::$app->name) ?></title>
        <?php 
        $this->head();
        $this->registerCssFile(Yii::getAlias('@common-web') . '/css/font-awesome.min.css');
        $this->registerCssFile(Yii::getAlias('@common-web') . '/css/ionicons.min.css'); 
        $this->registerCssFile(Yii::getAlias('@backend-web') . '/css/AdminLTE.css');
        $this->registerCssFile(Yii::getAlias('@backend-web') . '/css/site.css');?>
        
    </head>
    <body>
        <?php $this->beginBody() ?>
        
        <div class="wrap" style="background-color: rgb(100, 50, 70)">
            <div class="container">
                <div class="logo" style="width: 240px; height: 180px">
                    
               </div>
               <div class="form-box" id="login-box">
                    <div class="header"><?= Html::encode(Yii::$app->name) ?></b>
                    </div>
                   
                   <?= Html::beginForm() ?>
                   
                    <div class="body bg-white">
                        <div class="form-group field-loginform-username required">
                            <label class="control-label" for="kdRegistrasi">Kode Registrasi</label>
                            <input type="text" id="kdRegistrasi" class="form-control" name="license[kdRegistrasi]" value="<?= $kdRegistrasi ?>" readonly="readonly" style="font-size: 11px">
                            <p class="help-block help-block-error"></p>
                        </div>
                        <div class="form-group field-loginform-username required">
                            <label class="control-label" for="kdAktivasi">Kode Aktivasi</label>
                            <input type="text" id="kdAktivasi" class="form-control" name="license[kdAktivasi]" value="<?= $kdAktivasi ?>" readonly="readonly" style="font-size: 11px">
                            <p class="help-block help-block-error"></p>
                        </div>
                        <div class="form-group field-loginform-username required">
                            <label class="control-label" for="kdLisensi">Kode Lisensi</label>
                            <input type="text" id="kdLisensi" class="form-control" name="license[kdLisensi]" style="font-size: 8px">
                            <p class="help-block help-block-error"></p>
                        </div>
                    </div>
                    <div class="footer">                                                               
                        <?= Html::submitButton('Submit', ['class' => 'btn bg-red btn-block', 'name' => 'license[submit]']) ?>                                          
                    </div>
                   
                   <?= Html::endForm() ?>
                   
               </div>
            </div>
        </div>       
            
        <?php $this->endBody(); ?>
    </body>
</html>
<?php $this->endPage() ?>
