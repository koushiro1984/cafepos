<?php

namespace frontend\components;

use yii\base\Widget;
use backend\models\Settings;

class AppHeader extends Widget {

    public function header() {
        
        $modelSlideshowTop = Settings::find()
                ->andWhere(['setting_name' => 'slideshow_top_count'])
                ->asArray()->one();
        
        $modelSlideshowTopValue = Settings::find()
                ->andWhere(['like', 'setting_name', 'slideshow_top_value_'])
                ->limit($modelSlideshowTop['setting_value'])
                ->asArray()->all();                
        
        return $this->render('appHeader', array(
            'modelSlideshowTopValue' => $modelSlideshowTopValue,
        ));
    }
    
    public function navigation() {
        return $this->render('appNavigation', array(
            
        ));
    }
}
