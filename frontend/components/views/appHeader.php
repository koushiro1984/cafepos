<!-- Main Slider -->
<header id="header" class="intro">
    <div id="hero-carousel" class="carousel slide" data-ride="carousel">

        <!-- Wrapper for slides -->
        <div class="carousel-inner">
            
            <?php
            foreach ($modelSlideshowTopValue as $key => $dataSlideshowTopValue): ?>

                <!-- Start slide -->
                <div class="item <?= ($key == 0) ? 'active' : '' ?>">
                    <div class="fill" style="background-image: url('<?= Yii::getAlias('@backend-web') . '/img/slideshow/' . $dataSlideshowTopValue['setting_value'] ?>');"></div>
                </div>
                <!-- End slide -->

            <?php
            endforeach; ?>

        </div><!-- /.carousel-inner -->

        <a href="#nav" class="btn btn-circle page-scroll header-scroll">
            <i class="fa fa-angle-double-down animated"></i>
        </a>

        <!-- Controls -->
        <a class="left carousel-control" href="#hero-carousel" role="button" data-slide="prev" style="z-index: 998">
            <span class="glyphicon glyphicon-chevron-left"></span>
        </a>
        <a class="right carousel-control" href="#hero-carousel" role="button" data-slide="next" style="z-index: 998">
            <span class="glyphicon glyphicon-chevron-right"></span>
        </a>

    </div><!-- /#hero-carousel -->
    
    <div class="single-page-header-text">
        <!-- Pre-slogan -->
        <div class="tilt-left">
            <h3 class="header-text-pre-slogan">Come on in<em>&hellip;</em></h3>
        </div>
        
        <!-- Slogan Rotator -->
        <div class="tlt">
            <ul class="header-texts">
                <li>Dine with us!</li>
                <li>Try the Coffee!</li>
                <li>Bring the Family!</li>
                <li>Enjoy our Food!</li>
                <li>Have a Great Time!</li>
            </ul>
        </div>
        
        <!-- Divider -->
        <div class="header-text-divider"></div>
    </div>
</header><!-- /.intro -->
<!-- End Main Slider -->

<?php
$jscript = '$(".tlt").textillate({'
            . 'selector: ".header-texts",'
            . 'loop: true,'
            . 'minDisplayTime: 6e3,'
            . 'initialDelay: 0,'
            . 'autoStart: true,'
            . 'in: {'
                . 'effect: "flipInX",'
                . 'delayScale: 1.8,'
                . 'delay: 45,'
                . 'sync: false,'
                . 'shuffle: false,'
                . 'reverse: false'
            . '},'
            . 'out: {'
                . 'effect: "bounceOut",'
                . 'delayScale: 1.8,'
                . 'delay: 45,'
                . 'sync: false,'
                . 'shuffle: false,'
                . 'reverse:true'
            . '}'
        . '});';

$this->registerJs($jscript); ?>