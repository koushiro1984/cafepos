<?php

use yii\widgets\ActiveForm; 
use yii\helpers\Html; ?>

<!-- Write Testimoni Modal -->
<div class="popup-modal modal fade" id="testiModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-content">
        <div class="close-modal" data-dismiss="modal">
            <i class="fa fa-times"></i>
        </div>
        <div id="cont-testi">
            <div id="cont-testi-container" class="container">
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <h2 class="section-heading">Write a Testimonial</h2>
                        <h3 class="section-subheading">Berceritalah apa yang kamu lihat, dengar dan rasakan di Koffie Tijd ...</h3>
                    </div>
                </div><!-- /.row -->
                <div class="row">
                    <div class="col-md-6 col-md-offset-3 form-testi">
                        <?php $form = ActiveForm::begin([
                                'action' => Yii::$app->urlManager->createUrl('page/post-voting'),
                                'options' => [

                                ],
                                'fieldConfig' => [
                                    'parts' => [

                                    ],
                                    'template' => '<div class="row">'
                                                    . '<div class="col-lg-3">'
                                                        . '{label}'
                                                    . '</div>'
                                                    . '<div class="col-lg-6">'
                                                        . '{input}'                                                    
                                                    . '</div>'
                                                    . '<div class="col-lg-3">'
                                                        . '{error}'
                                                    . '</div>'
                                                . '</div>', 
                                ]
                        ]); ?>

                        <?= $form->field($modelVoting, 'nama')->textInput(['maxlength' => 32]) ?>

                        <?= $form->field($modelVoting, 'kota')->textInput(['maxlength' => 24]) ?>

                        <?= $form->field($modelVoting, 'email')->textInput(['maxlength' => 32]) ?>

                        <?= $form->field($modelVoting, 'rate')->hiddenInput(['class' => 'star-rating']) ?>

                        <?= $form->field($modelVoting, 'message')->textarea(['rows' => 6]) ?>


                        <div class="row">
                            <div class="col-lg-12 col-md-12">
                                <?= Html::submitButton('Send', ['class' => 'btn btn-xl btn-primary']); ?>  
                            </div>
                        </div>

                        <?php ActiveForm::end(); ?>                    
                    </div>
                </div><!-- /.row -->
            </div>
        </div>
    </div><!-- /.modal-content -->
</section>
<!-- End Write Testimoni Modal -->

<?php
$jscript = '$("#open-testi-modal").click(function(e) {'
                
        . '});'
        . '$(".star-rating").rating({'
            . 'min: 0,'
            . 'max: 5,'
            . 'step: 1,'
            . 'size: "sm",'
            . 'containerClass: "bg-white",'
        . '});';

$this->registerJs($jscript); ?>
            