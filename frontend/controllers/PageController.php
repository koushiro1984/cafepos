<?php

namespace frontend\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use backend\models\Voting;
use backend\models\MenuCategory;
use backend\models\Menu;
use backend\models\Settings;

/**
 * Page controller
 */
class PageController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                    'get-menu' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex() {
        $modelVoting = new Voting();
        $dataModelVoting = Voting::find()->andWhere(['is_publish' => true])->orderBy(['id' => SORT_DESC])->limit(6)->asArray()->all();        
        
        $dataModelMenuCategory = MenuCategory::find()
                    ->joinWith([
                        'menuCategories' => function($query) {
                            $query->from('menu_category child');
                        }
                    ])
                    ->andWhere(['IS', 'menu_category.parent_category_id', NULL])
                    ->asArray()->all();
                    
        $modelSlideshowBottom = Settings::find()
                ->andWhere(['setting_name' => 'slideshow_bottom_count'])
                ->asArray()->one();
        
        $modelSlideshowBottomValue = Settings::find()
                ->andWhere(['like', 'setting_name', 'slideshow_bottom_value_'])
                ->limit($modelSlideshowBottom['setting_value'])
                ->asArray()->all();        
        
        return $this->render('index', [
            'modelVoting' => $modelVoting,
            'dataModelVoting' => $dataModelVoting,
            'dataModelMenuCategory' => $dataModelMenuCategory,  
            'modelSlideshowBottomValue' => $modelSlideshowBottomValue,
        ]);
    }        
    
    public function actionPostVoting() {
        $model = new Voting();
        
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;            
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);            
        }
    }
    
    public function actionGetMenu() {
        
        $modelMenu = Menu::find()
                ->andWhere(['menu_category_id' => Yii::$app->request->post()['id']])
                ->andWhere(['IS NOT', 'harga_jual', NULL])
                ->andWhere(['not_active' => false])
                ->asArray()->all();
        
        return $this->renderPartial('get_menu', [
            'modelMenu' => $modelMenu,
        ]);
    }
}
