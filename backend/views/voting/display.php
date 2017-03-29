<?php

$this->title = 'Voting Result And Testimonial';
$this->params['breadcrumbs'][] = $this->title; ?>

<!-- Small boxes (Stat box) -->
<div class="row">
    <div class="col-lg-4">
        <!-- small box -->
        <div class="small-box bg-green">
            <div class="inner">
                <h3 style="font-size: 80px"><?= $point ?> <span style="font-size: 64px">Point</span></h3>
                <input class="rate" type="hidden" value="<?= $point ?>">
                <p style="padding-top: 3px">
                    <i class="ion ion-android-person" style="font-size: 20px"></i>&nbsp;&nbsp;&nbsp;
                    total &nbsp;&nbsp; <?= $count ?>
                </p>
            </div>
            <div class="icon">
                <i class="ion ion-star"></i><i class="ion ion-star"></i><i class="ion ion-star"></i>
            </div>
            <div class="small-box-footer">
                &nbsp;
            </div>
        </div>
    </div><!-- ./col -->
    <div class="col-lg-6">                            
        <div class="chart-container" id="chart-penjualan" style="position: relative; height: 400px;"></div>                                
    </div>    
</div><!-- /.row -->

<script>
<?php 
print_r($groupRate);
ob_start(); ?>
    
    var chart_penjualan = new Highcharts.Chart({
        chart: {
            renderTo: "chart-penjualan",
            type: "column"
        },
        title: {
            text: "Rating Point"
        },
        legend: {
            enabled: false
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Rate'
            }
        },
        series: [
            {
                name: "Point",
                data: [<?= count($groupRate[1]) . ',' . count($groupRate[2]) . ',' . count($groupRate[3]) . ',' . count($groupRate[4]) . ',' .count($groupRate[5]) . ',' ?>]
            }
        ],
        plotOptions: {
            series: {
                borderWidth: 0,
                dataLabels: {
                    enabled: true,
                }
            }
        },
        colors: ['#F56954'],
        xAxis: {
            categories: [
                '\uf005 1', '\uf005 2', '\uf005 3', '\uf005 4', '\uf005 5'
            ]
        },
        credits: {
            enabled: false
        }
    });
<?php $this->params['chart'] = ob_get_clean(); ?>
</script>

<?php

$this->params['regCssFile'][] = function() {
    $this->registerCssFile(Yii::getAlias('@common-web') . '/css/bootstrap-star-rating/star-rating.css');
}; 

$this->params['regJsFile'][] = function() {
    $this->registerJsFile(Yii::getAlias('@common-web') . '/js/plugins/highchart/highcharts.js');
    $this->registerJsFile(Yii::getAlias('@common-web') . '/js/plugins/highchart/themes/dark-unica.js');
    $this->registerJsFile(Yii::getAlias('@common-web') . '/js/plugins/highchart/plugins/grouped-categories.js');
    $this->registerJsFile(Yii::getAlias('@common-web') . '/js/plugins/bootstrap-star-rating/star-rating.min.js');
    
    $jscript = '$(".rate").rating("refresh", {'
                . 'disabled: true,'
                . 'showClear: false,'
                . 'showCaption: false,'
                . 'step: 0.1,'
                . 'size: "xs"'
            . '});'
            . $this->params['chart'];

    $this->registerJs($jscript);
}; ?>