<?php
use yii\helpers\Html;
use backend\components\Tools;


if (count($modelMenu) > 0):
    foreach ($modelMenu as $menuData): ?>

        <div class="col-md-4 col-sm-4 mb">
            <div class="product-panel-2 pn" style="padding: 10px 0">
                <button id="add-menu" class="btn btn-small btn-primary"><i class="fa fa-plus"></i> Add</button> <br>
                <img src="<?= Yii::getAlias('@backend-web') . '/img/menu/thumb120x120' . $menuData['image'] ?>" width="120" class="img-circle" style="margin: 10px 0 10px 0">
                <h5 class="mt" style="color: #000; font-weight: bold"><?= $menuData['nama_menu'] ?></h5>
                <h6><?= Yii::$app->formatter->asCurrency($menuData['harga_jual']) ?></h6>                
                <?= Html::hiddenInput('menu-id', $menuData['id'], ['id' => 'menu-id']) ?>
                <?= Html::hiddenInput('menu-nama', $menuData['nama_menu'], ['id' => 'menu-nama']) ?>
                <?= Html::hiddenInput('menu-harga', $menuData['harga_jual'], ['id' => 'menu-harga']) ?>
                <?= Html::hiddenInput('menu-harga-formatted', Yii::$app->formatter->asCurrency($menuData['harga_jual']), ['id' => 'menu-harga-formatted']) ?>
            </div>
        </div>

<?php
    endforeach; 
else: ?>
    <br><br><br><br>
    No Data Found
    <br><br><br><br><br>

<?php    
endif; ?>

<script>    
    <?= Tools::jsHitungServiceChargePajak(); ?>
        
    $("button#add-menu").on("click", function(event) {                
        var thisParent = $(this).parent();
        var menuId = thisParent.find("input#menu-id");
        var menuNama = thisParent.find("input#menu-nama");
        var menuHarga = thisParent.find("input#menu-harga");
        var menuHargaFormatted = thisParent.find("input#menu-harga-formatted");
        
        var flag = true;
        var harga = parseFloat(menuHarga.val()); 
        $("tbody#tbodyOrderMenu tr#menuRow").find("input#inputMenuQty" + menuId.val()).each(function() {
            var qty = parseFloat($(this).val()) + 1;
            var discount = parseFloat($(this).parent().parent().find("input.inputMenuDiscount").val());      
            harga = harga - (discount * 0.01 * harga);      
            var jmlHarga = harga * qty;
            
            $(this).val(qty);
            $(this).parent().find("span").html($(this).val());
            
            $(this).parent().parent().find("#subtotal span#spanSubtotal").html(jmlHarga);
            $(this).parent().parent().find("#subtotal span#spanSubtotal").currency({<?= Yii::$app->params['currencyOptions'] ?>});
            
            flag = false;
            return;
        });                
        
        $("#total-harga-input").val(harga + parseFloat($("#total-harga-input").val()));
        $("#total-harga").html($("#total-harga-input").val());
        $("#total-harga").currency({<?= Yii::$app->params['currencyOptions'] ?>});  
        
        var scp = hitungServiceChargePajak($("#total-harga-input").val(), $("#serviceChargeAmount").val(), $("#taxAmount").val());                                   
        
        var serviceCharge = scp["serviceCharge"];
        $("#service-charge-amount").html(serviceCharge);
        $("#service-charge-amount").currency({<?= Yii::$app->params['currencyOptions'] ?>});
        
        var pajak = scp["pajak"];
        $("#tax-amount").html(pajak);
        $("#tax-amount").currency({<?= Yii::$app->params['currencyOptions'] ?>});
        
        var grandTotal = parseFloat($("#total-harga-input").val()) + serviceCharge + pajak;
        $("#grand-harga").html(grandTotal);
        $("#grand-harga").currency({<?= Yii::$app->params['currencyOptions'] ?>});
        
        if (!flag) return;
        
        var inputMenuQty = $("<input>").attr("type", "hidden").attr("id", ("inputMenuQty" + menuId.val())).attr("class", "inputMenuQty").attr("name", "menu[" + menuId.val() + "][inputMenuQty]").attr("value", 1);
        var inputMenuId = $("<input>").attr("type", "hidden").attr("id", ("inputMenuId" + menuId.val())).attr("name", "menu[" + menuId.val() + "][inputMenuId]").attr("value", menuId.val());
        var inputHarga = $("<input>").attr("type", "hidden").attr("id", ("inputMenuHarga" + menuId.val())).attr("class", "inputMenuHarga").attr("name", "menu[" + menuId.val() + "][inputMenuHarga]").attr("value", menuHarga.val());
        var inputDiscountType = $("<input>").attr("type", "hidden").attr("id", ("inputMenuDiscountType" + menuId.val())).attr("class", "inputMenuDiscountType").attr("name", "menu[" + menuId.val() + "][inputMenuDiscountType]").attr("value", "percent");  
        var inputDiscount = $("<input>").attr("type", "hidden").attr("id", ("inputMenuDiscount" + menuId.val())).attr("class", "inputMenuDiscount").attr("name", "menu[" + menuId.val() + "][inputMenuDiscount]").attr("value", 0);                
        var inputVoid = $("<input>").attr("type", "hidden").attr("id", ("inputMenuVoid" + menuId.val())).attr("class", "inputMenuVoid").attr("name", "menu[" + menuId.val() + "][inputMenuVoid]").attr("value", 0);                
        var inputFreeMenu = $("<input>").attr("type", "hidden").attr("id", ("inputMenuFreeMenu" + menuId.val())).attr("class", "inputMenuFreeMenu").attr("name", "menu[" + menuId.val() + "][inputMenuFreeMenu]").attr("value", 0);                
        var inputCatatan = $("<input>").attr("type", "hidden").attr("id", ("inputMenuCatatan" + menuId.val())).attr("class", "inputMenuCatatan").attr("name", "menu[" + menuId.val() + "][inputMenuCatatan]").attr("value", "");
        
        var comp = $("#temp").clone();
        comp.children().find("#menu span").html(menuNama.val());
        comp.children().find("#menu").append(inputMenuId).append(inputCatatan);
        comp.children().find("#qty").append(inputMenuQty).append(inputHarga).append(inputDiscount).append(inputVoid).append(inputFreeMenu).append(inputDiscountType);
        comp.children().find("#qty").find("span").html(1);
        comp.children().find("#subtotal span#spanSubtotal").append(menuHargaFormatted.val());                        
        $("tbody#tbodyOrderMenu").append(comp.children().html());   
    });
</script>


