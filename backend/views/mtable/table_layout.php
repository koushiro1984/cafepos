<?php
use yii\helpers\Html;
use backend\components\NotificationDialog;

$status = Yii::$app->session->getFlash('status');
$message1 = Yii::$app->session->getFlash('message1');
$message2 = Yii::$app->session->getFlash('message2');

if ($status !== null) : 
    $notif = new NotificationDialog([
        'status' => $status,
        'message1' => $message1,
        'message2' => $message2,
    ]);

    $notif->theScript();
    echo $notif->renderDialog();

endif;

$this->title = 'Table Layout';
$this->params['breadcrumbs'][] = ['label' => 'Table', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;  ?>


<div class="row">
    <div class="col-lg-12">
        <?= Html::beginForm('', 'post', ['id' => 'formSaveLayout']) ?>
        <?= Html::submitButton('<i class="fa fa-floppy-o"></i>&nbsp;&nbsp;&nbsp;Save', ['class' => 'btn btn-success btn-lg', 'id'=> 'btnSave']) ?>
        <?= Html::endForm() ?>
    </div>
</div>

<div style="margin: 10px"></div>

<div class="row">
    
    <div class="col-lg-12">
        Keterangan warna pada kategori meja :
    </div>
    
</div>

<div style="margin: 10px"></div>

<div class="row">
    <?php
    foreach ($model as $value): 
        $color = !empty($value->color) ? $value->color : '#000'; ?>
    
        <div class="col-lg-2">
            <?= Html::a($value->nama_category, Yii::$app->urlManager->createUrl(['mtable/table-layout', 'catid' => $value['id']]), ['class' => 'btn btn-block', 'style' => 'margin-top: 5px; color: white; background: ' . $color]) ?>
        </div>
    
    <?php
    endforeach; ?>
    
</div>

<div style="margin: 10px"></div>

<div class="row">
    <div class="col-lg-3">
        <div style="width: 100%; height: 600px; background: rgb(235, 235 ,235); padding: 10px; overflow-y: scroll">
            
            <?php
            $jscriptTemp = 'var drawingTemp;';
            
            foreach ($model as $value):
                if ($catid == $value['id']):
                    foreach ($value->mtables as $dataTable):

                        $color = !empty($value->color) ? $value->color : '#000'; 
                        if (!empty($dataTable->shape)) {
                            if ($dataTable->shape == 'circle')
                                $jscriptTemp .= 'drawingTemp = $("canvas#tableLayout").drawArc(drawingArc("' . $dataTable->id . '", ' . $dataTable->layout_x . ', ' . $dataTable->layout_y . ', "' . $value->color . '"));'; 
                            else if ($dataTable->shape == 'rectangle')
                                $jscriptTemp .= 'drawingTemp = $("canvas#tableLayout").drawRect(drawingRect("' . $dataTable->id . '", ' . $dataTable->layout_x . ', ' . $dataTable->layout_y . ', "' . $value->color . '"));';

                            $jscriptTemp .= 'drawingTemp.drawText(drawingText("' . $dataTable->id . '", ' . $dataTable->layout_x . ', ' . $dataTable->layout_y . '));';
                        } ?>

                        <div style="margin: 5px">
                            <?= Html::a('(' . $dataTable->id . ') ' . $dataTable->nama_meja, '#', ['class' => 'btn btn-block', 'id' => 'table', 'style' => 'color: white; background: ' . $color]) ?>
                            <?= Html::hiddenInput('color', $value->color, ['id' => 'color']) ?>
                            <?= Html::hiddenInput('shape', $dataTable->shape, ['id' => 'shape']) ?>
                            <?= Html::hiddenInput('idTable', $dataTable->id, ['id' => 'idTable']) ?>
                        </div>

                    <?php
                    endforeach;
                endif;
            endforeach; ?>
            
        </div>
    </div>
    <div class="col-lg-9">
        <canvas id="tableLayout" width="750" height="600" style="background: rgb(235, 235 ,235)">
    
        </canvas>   
    </div>
</div>

<!-- Modal Custom -->
<div class="modal fade" id="modalShape" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-inverse">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="modalShapeTitle">Shape ?</h4>
            </div>           
            <div class="modal-footer">
                <button id="circle" type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-check-circle"></i> &nbsp; Circle</button>
                <button id="rectangle" type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-check-square"></i> &nbsp; Rectangle</button>
            </div>
        </div>
    </div>
</div>

<?php
$this->params['regJsFile'][] = function() {
    $this->registerJsFile(Yii::getAlias('@common-web') . '/js/plugins/jcanvas/jcanvas.min.js');
};

$jscript = '
    var nearest = function (number, n) {
        return Math.round(number / n) * n;
    }
      
    var gridSize = 20;
    
    var drawingArc = function(idTable, posX, posY, color) {
        return {
            draggable: true,
            layer: true,
            name: idTable,
            fillStyle: color,
            x: posX, 
            y: posY,
            radius: 25,
            data: {
                "shape": "circle",
                "idTable": idTable
            },
            drag: function(layer) {
                layer.x = nearest(layer.x, gridSize);
                layer.y = nearest(layer.y, gridSize);

                $("canvas#tableLayout").setLayer(idTable + "text", {
                    x: layer.x, 
                    y: layer.y,
                });
            }
        };
    };
    
    var drawingRect = function(idTable, posX, posY, color) {
        return {
            draggable: true,
            layer: true,
            name: idTable,
            fillStyle: color,
            x: posX, 
            y: posY,
            width: 50,
            height: 50,
            data: {
                "shape": "rectangle",
                "idTable": idTable
            },
            drag: function(layer) {
                layer.x = nearest(layer.x, gridSize);
                layer.y = nearest(layer.y, gridSize);

                $("canvas#tableLayout").setLayer(idTable + "text", {
                    x: layer.x, 
                    y: layer.y,
                });
            }
        };
    };
    
    var drawingText = function(idTable, posX, posY) {
        return {
            layer: true,
            name: idTable + "text",
            fillStyle: "#fff",
            fontStyle: "bold",
            fontSize: "10pt",
            fontFamily: "Trebuchet MS, sans-serif",
            x: posX, 
            y: posY,
            text: idTable
        };
    };
    
    $("a#table").on("click", function(event) {
        event.preventDefault();
        
        var thisObj = $(this).parent();
        var color = thisObj.find("input#color").val();
        var shape = thisObj.find("input#shape").val();
        var idTable = thisObj.find("input#idTable").val();
        
        $("#modalShape").modal();                
        
        $("#modalShape").find("button").off("click").on("click", function(event) {
        
            var drawing;
        
            if ($(this).attr("id") == "circle") {
                drawing = $("canvas#tableLayout").drawArc(drawingArc(idTable, 25, 25, color));
            } else if ($(this).attr("id") == "rectangle") {
                drawing = $("canvas#tableLayout").drawRect(drawingRect(idTable, 25, 25, color));
            }
            
            drawing.drawText(drawingText(idTable, 25, 25));
        });
    });
    
    $("button#btnSave").on("click", function(event) {
        var i = 0;
        $("canvas#tableLayout").getLayers(function(layer) {
            if (layer.name.indexOf("text") == -1) {
                var inputId = $("<input>").attr("type", "hidden").attr("name", "mtable[" + i + "][id]").val(layer.data.idTable);
                var inputX = $("<input>").attr("type", "hidden").attr("name", "mtable[" + i + "][layout_x]").val(layer.x);
                var inputY = $("<input>").attr("type", "hidden").attr("name", "mtable[" + i + "][layout_y]").val(layer.y);
                var inputShape = $("<input>").attr("type", "hidden").attr("name", "mtable[" + i + "][shape]").val(layer.data.shape);
                $("form#formSaveLayout").append(inputId).append(inputX).append(inputY).append(inputShape);
                
                i++;
            }
          });
    });
';

$this->registerJs($jscript . $jscriptTemp); ?>