<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
$this->title = 'Koffie Tijd !';
?>

<!-- Introduction Section -->
<section id="intro" class="wow zoomIn">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <h2 class="section-heading">Salam Hangat Dari Koffie Tijd</h2>
                <span class="section-divider">M</span>
                <h3 class="section-subheading text-muted">Koffietijd lahir sebagai cafe yang memiliki diferensiasi tersendiri di bandingkan dengan cafe<i class="sans">-</i>cafe lain yang ada di bandung Nama Koffietijd berasal dari bahasa belanda yang artinya<br><span>... waktunya ngopi.</span></h3>
                <a href="#menus" class="page-scroll">
                    <i class="fa fa-angle-double-down animated"></i>
                </a>
            </div>
        </div><!-- /.row -->
    </div><!-- /.container -->
</section>
<!-- End Introduction Section -->

<!-- Menus Section -->
<section id="menus" style="background-image:url('<?= Yii::$app->request->baseUrl ?>/img/images/menu_bg.jpg')">        
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <h2 class="section-heading wow fadeInDown">We are big on choice.</h2>
                <h3 class="section-subheading wow fadeInDown">Choose a menu</h3>
                <ul class="wow fadeInUp">
                    <?php
                    foreach ($dataModelMenuCategory as $key => $menuCategoryData): 
                        if ($key > 0): ?>
                    
                            <hr style="width: 40%">                            
                
                        <?php
                        endif;
                        
                        if (count($menuCategoryData['menuCategories']) > 0): ?>
                            <li class="menu-head"><?= $menuCategoryData['nama_category'] ?></li>   
                            
                                <?php
                                foreach ($menuCategoryData['menuCategories'] as $menuCategoryChildData): ?>

                                    <li>
                                        <span>
                                            <a id="modalMenu" href="#menuModal"><?= $menuCategoryChildData['nama_category'] ?></a>                                            
                                        </span>
                                        <?= Html::hiddenInput('categoryId', $menuCategoryChildData['id'], ['id' => 'categoryId']) ?>
                                        <?= Html::hiddenInput('categoryKeterangan', $menuCategoryChildData['keterangan'], ['id' => 'categoryKeterangan']) ?>
                                    </li>

                                <?php
                                endforeach; ?>
                                    
                        <?php
                        else: ?>

                            <li>
                                <span>
                                    <a id="modalMenu" href="#menuModal"><?= $menuCategoryData['nama_category'] ?></a>
                                </span>
                                <?= Html::hiddenInput('categoryId', $menuCategoryData['id'], ['id' => 'categoryId']) ?>
                                <?= Html::hiddenInput('categoryKeterangan', $menuCategoryData['keterangan'], ['id' => 'categoryKeterangan']) ?>
                            </li> 

                        <?php
                        endif; ?>

                    <?php
                    endforeach; ?>

                </ul>
            </div>
            <div class="col-sm-12">
                <a href="#unique" class="page-scroll">
                    <i class="fa fa-angle-double-down animated"></i>
                </a>
            </div>
        </div><!-- /.row -->
    </div><!-- /.container -->
</section>
<!-- End Menus Section -->

<!-- Unique Chain Section -->
<section id="unique">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <span class="section-icon">x</span>
                <h2 class="section-heading  wow slideInLeft">Koffie Tijd<i class="sans">!</i> Lebih dari sekedar F & B.</h2>
                <span class="section-divider">N</span>
                <h3 class="section-subheading  wow slideInRight">Koffie tijd memberikan nilai tambah kepada tamu tamu yang berharga dengan memberikan kejutan <i class="sans">-</i> kejutan dari taglink kami,<br><span><i class="sans">"</i>Good Things Inside...<i class="sans">"</i></span>.</h3>
                <a href="#testimonial" class="page-scroll">
                    <i class="fa fa-angle-double-down animated"></i>
                </a>
            </div>
        </div><!-- /.row -->
    </div><!-- /.container -->
</section>
<!-- End Unique Chain Section -->

<!-- Testimonial Section -->
<section id="testimonial">        
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <h3 class="section-subheading wow fadeInDown">What Our Customers Say</h3>
            </div>
        </div><!-- /.row-->
        <div class="row wow fadeInUp">
            
            <?php
            foreach ($dataModelVoting as $value): ?>
            
                <div class="testimonial col-md-4 col-sm-6">
                    <div class="author-info">
                        <?= $value['nama'] ?> <span>From</span> <?= $value['kota'] ?>
                    </div>

                    <div class="testimonial-bubble">                                        
                        <blockquote>
                            <!-- Quote -->
                            <p class="quote">                                
                                <?php
                                $char = str_split($value['message']);
                                //print_r($char);
                                for ($i = 0; $i < 160; $i++): 
                                    if (empty($char[$i])) 
                                        echo "&nbsp; ";
                                    else
                                        echo $char[$i];
                                endfor; ?>
                            </p>
                            <input class="rate" type="hidden" value="<?= $value['rate'] ?>">
                        </blockquote>
                        <div class="sprite arrow-speech-bubble"></div>
                    </div>
                </div>
            
            <?php
            endforeach; ?>
            
        </div><!-- /.row -->
        
        <div class="row wow fadeInRight">
            <div class="col-lg-12" style="text-align: center"> 
                <a id="open-testi-modal" class="btn btn-primary" href="#testiModal" data-toggle="modal">Write</a>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a class="btn btn-primary" href="#">Read More</a>
            </div>
        </div>
        
        <div class="row">
            <div class="col-lg-12">      
                <a href="#gallery" class="page-scroll">
                    <i class="fa fa-angle-double-down animated"></i>
                </a>
            </div>
        </div><!-- /.row -->
    </div><!-- /.container -->
</section>
<!-- End Testimonial Section -->

<!-- Gallery Section -->
<div id="gallery" class="wow fadeIn">
    <div id="gallery-carousel" class="carousel slide" data-ride="carousel">
        <!-- Indicators -->
        <ol class="carousel-indicators">
            <?php
            foreach ($modelSlideshowBottomValue as $key => $dataSlideshowBottomValue): ?>
            
                <li data-target="#gallery-carousel" data-slide-to="<?= $key ?>" class="<?= ($key == 0) ? 'active' : '' ?>"></li>
                
            <?php
            endforeach; ?>
        </ol><!-- /.carousel-indicators -->

        <!-- Wrapper for slides -->
        <div class="carousel-inner">
            
            <?php
            foreach ($modelSlideshowBottomValue as $key => $dataSlideshowBottomValue): ?>

                <!-- Start slide -->
                <div class="item <?= ($key == 0) ? 'active' : '' ?>">
                    <img src="<?= Yii::getAlias('@backend-web') . '/img/slideshow/' . $dataSlideshowBottomValue['setting_value'] ?>" data-no-retina>
                </div>
                <!-- End slide -->

            <?php
            endforeach; ?>
            
        </div><!-- /.carousel-inner -->

        <!-- Controls -->
        <a class="left carousel-control" href="#gallery-carousel" role="button" data-slide="prev">
            <span class="glyphicon glyphicon-chevron-left"></span>
        </a>
        <a class="right carousel-control" href="#gallery-carousel" role="button" data-slide="next">
            <span class="glyphicon glyphicon-chevron-right"></span>
        </a>

    </div><!-- /#hero-carousel -->
</div>
<!-- End Gallery Section -->

<!-- Contact section -->
<section id="contact">
    <div class="container text-center">
        <div class="row">
            <div class="col-md-12">
                <h2 class="section-heading wow fadeInDown" >Contact Koffie Tijd<i class="sans">!</i></h2>
            </div>
            <div class="col-md-4 contact-details wow fadeInLeft">
                <h3 class="section-subheading">Location <span>u</span></h3>
                <p>Jalan Flores No. 8<br>Bandung<br>40117</p>
            </div>
            <div class="col-md-4 contact-details wow fadeInUp">
                <h3 class="section-subheading">Contact Info <span>V</span></h3>
                <p>022 - 70 8888 12</p>
                <a href="#" class="btn btn-primary">Email us</a>
                <ul class="list-inline">
                    <li><a href="#"><i class="fa fa-facebook fa-fw fa-2x"></i></a></li>
                    <li><a href="#"><i class="fa fa-twitter fa-fw fa-2x"></i></a></li>
                </ul><!-- /.list-inline -->
            </div>
            <div class="col-md-4 contact-details wow fadeInRight">
                <h3 class="section-subheading">Opening Hours<span>X</span></h3>
                <p><em>Weekdays</em>&nbsp;&nbsp;<strong>10:00 &ndash; 23:00</strong></p>
                <p><em>Weekend</em>&nbsp;&nbsp;<strong>10:00 &ndash; 24:00</strong></p>
            </div>
        </div><!-- /.row -->
    </div><!-- /.container -->
</section>
<!-- End Contact Section -->

<!-- Footer Section -->
<footer class="footer">
    <div class="container text-center">
        <div class="row copyright">
            <div class="col-lg-12">
                <p class="small">&copy; Copyright 2015. <span>Crafted by <a href="#" target="">Syncfactory IT Point</a> in Bandung</span></p>
            </div>
        </div><!-- /.copyright -->
    </div><!-- /.container -->
</footer>
<!-- End Footer Section -->

<?= $this->render('_menu-modal', [
    
]) ?>

<?= $this->render('_testimoni-modal', [
    'modelVoting' => $modelVoting,   
]) ?>

<?php

$this->params['regCssFile'][] = function() {
    $this->registerCssFile(Yii::getAlias('@common-web') . '/css/bootstrap-star-rating/star-rating.css');
}; 

$this->params['regJsFile'][] = function() {
    $this->registerJsFile(Yii::getAlias('@common-web') . '/js/plugins/bootstrap-star-rating/star-rating.min.js');        
}; 

$jscript = '
    $("a#modalMenu").on("click", function(event) {
        var thisObj = $(this);
        var modalId = thisObj.attr("href");
        $(modalId + " #modalMenuTitle").html(thisObj.html());
        $(modalId + " #modalMenuKeterangan").html(thisObj.parent().parent().find("input#categoryKeterangan").val());
        $(modalId).modal();
        
        $.ajax({
            cache: false,
            type: "POST",
            data: {
                "id": thisObj.parent().parent().find("input#categoryId").val()
            },
            url: "' . Yii::$app->urlManager->createUrl('page/get-menu') . '",
            beforeSend: function(xhr) {
                $("#menuOverlay").show();
                $("#menuLoadingImg").show();
            },
            success: function(response) {
                $(modalId + " #modalMenuContent").html(response);
                $("#menuOverlay").hide();
                $("#menuLoadingImg").hide();
            }
        });
    });
    
    $(".rate").rating("refresh", {
        disabled: true,
        showClear: false,
        showCaption: false,
        size: "xs",
    });
';

$this->registerJs($jscript); ?>