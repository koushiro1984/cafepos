<?php

namespace backend\controllers\base;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;

class BaseController extends Controller {
    
    public function getAccess()
    {        
        return [
            'access' => [
                'class' => AccessControl::className(),
//                'denyCallback' => function ($rule, $action) {   
//                    return true;
//                },
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {   
            
                            if (Yii::$app->session->get('user_data')['user_level']['is_super_admin'])
                                return true;
                            
                            $userAkses = Yii::$app->session->get('user_data')['user_level']['userAkses'];                            
                            $temp = explode('\\', $action->controller->module->controllerNamespace);
                            $subProgram = $temp[0];
                            
                            foreach ($userAkses as $value) {
                                if (
                                        ($value['userAppModule']['nama_module'] === $action->controller->id 
                                        && $value['userAppModule']['module_action'] === $action->id 
                                        && $value['userAppModule']['sub_program'] === $subProgram) 
                                    ) {

                                    return true;
                                }
                            }
                            
                            if ($action->controller->id === 'site' && ($action->id === 'error' || $action->id === 'logout')) {
                                return true;
                            }
                            
                            return false;
                        }
                    ],
                    [
                        'allow' => true,
                        'roles' => ['?'],
                        'matchCallback' => function ($rule, $action) {   
                            if ($action->controller->id === 'site' && ($action->id === 'login' || $action->id === 'absensi' || $action->id === 'error'))
                                return true;
                            else 
                                return false;
                        }
                    ],
                ],
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeAction($action) {
        if (parent::beforeAction($action)) {
            if (Yii::$app->session->get('company_settings_profile') === null) {
                $settings = \backend\models\Settings::find()->andWhere('setting_name LIKE "company%"')->all();
                foreach ($settings as $value) {
                    $data[$value->setting_name] = $value->setting_value;
                }

                Yii::$app->session->set('company_settings_profile', $data);
            }
            
            return true;  
        } else {
            return false;
        }
    }

}
