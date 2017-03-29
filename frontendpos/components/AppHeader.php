<?php

namespace frontendpos\components;

use yii\base\Widget;

class AppHeader extends Widget {

    public function header() {
        return $this->render('appHeader', array(
            
        ));
    }
    
    public function navigation() {
        return $this->render('appNavigation', array(
            
        ));
    }
    
    public function right() {
        return $this->render('appRight', array(
            
        ));
    }
}
