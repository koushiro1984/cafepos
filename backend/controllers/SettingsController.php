<?php

namespace backend\controllers;

use Yii;
use backend\models\Settings;
use backend\models\search\SettingsSearch;
use backend\controllers\base\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\web\UploadedFile;


/**
 * SettingsController implements the CRUD actions for Settings model.
 */
class SettingsController extends BaseController
{
    public function behaviors()
    {
        return array_merge(
            $this->getAccess(),
            [                
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['post'],
                    ],
                ],
            ]);
    }

    /**
     * Lists all Settings models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SettingsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Settings model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Settings model.
     * If creation is successful, the browser will be redirected to the 'update' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Settings();
        
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;            
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('status', 'success');
                Yii::$app->session->setFlash('message1', 'Tambah Data Sukses');
                Yii::$app->session->setFlash('message2', 'Proses tambah data sukses. Data telah berhasil disimpan.');
                
                return $this->redirect(['update', 'id' => $model->setting_id]);
            } else {
                $model->setIsNewRecord(true);
                
                Yii::$app->session->setFlash('status', 'danger');
                Yii::$app->session->setFlash('message1', 'Tambah Data Gagal');
                Yii::$app->session->setFlash('message2', 'Proses tambah data gagal. Data gagal disimpan.');
            }                        
        }
         
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Settings model.
     * If update is successful, the browser will be redirected to the 'update' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;            
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('status', 'success');
                Yii::$app->session->setFlash('message1', 'Update Sukses');
                Yii::$app->session->setFlash('message2', 'Proses update sukses. Data telah berhasil disimpan.');
                
                return $this->redirect(['update', 'id' => $model->setting_id]);
            } else {
                Yii::$app->session->setFlash('status', 'danger');
                Yii::$app->session->setFlash('message1', 'Update Gagal');
                Yii::$app->session->setFlash('message2', 'Proses update gagal. Data gagal disimpan.');
            }                        
        }
         
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Settings model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if ($this->findModel($id)->delete() !== false) {
            Yii::$app->session->setFlash('status', 'success');
            Yii::$app->session->setFlash('message1', 'Delete Sukses');
            Yii::$app->session->setFlash('message2', 'Proses delete sukses. Data telah berhasil dihapus.');
        } else {
            Yii::$app->session->setFlash('status', 'danger');
            Yii::$app->session->setFlash('message1', 'Delete Gagal');
            Yii::$app->session->setFlash('message2', 'Proses delete gagal. Data gagal dihapus.');
        }

        return $this->redirect(['index']);
    }      
    
    /**
     * Action for update-setting page
     * If update is successful, the browser will be redirected to the 'form_settings_company' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdateSetting($id)
    {
        if ($id == 'company') {            
            return $this->updateSetting([['like', 'setting_name', 'company_']], $id, 'Profile Perusahaan');
        } elseif ($id == 'tax-sc') {
            return $this->updateSetting([['like', 'setting_name', 'tax_amount'], ['like', 'setting_name', 'service_charge_amount']], $id, 'Nilai Pajak dan Service Charge');
        } elseif ($id == 'struk') {
            return $this->updateSetting([['like', 'setting_name', 'struk_']], $id, 'Setting Struk');
        } elseif ($id == 'printer') {
            return $this->updateSetting([['like', 'setting_name', 'printer_']], $id, 'Printer');
        }
    }
    
    /**
     * Updates an existing Settings model.
     * If update is successful, the browser will be redirected to the 'form_settings_company' page.
     * @param string $params
     * @return mixed
     */
    
    protected function updateSetting($params, $id, $judul) {
        $models = Settings::find();
        
        foreach ($params as $param) {
            $models->orFilterWhere($param);
        }

        $models = $models->all();        
        
        if (Settings::loadMultiple($models, Yii::$app->request->post())) { 
            
            $transaction = Yii::$app->db->beginTransaction();
            $flag = true;
            
            foreach ($models as $key => $model) {
                
                if (strpos($model->setting_name, 'file') !== false) {
                    $image = UploadedFile::getInstance($model, '[' . $key . ']setting_value');                                                                   
                    if ($image) {                                      
                        $flag = $image->saveAs('img/company-profile/company.' . $image->extension);
                        $model->setting_value = 'company.' . $image->extension;                        
                    } else {
                        $model->setting_value = $model->oldAttributes['setting_value'];
                    }         
                }
                
                if ($flag) $flag = $model->save();
                
                if (!$flag) {
                    $transaction->rollback();
                    break;
                }                
            }
            
            if ($flag) {
                $transaction->commit();
                
                Yii::$app->session->setFlash('status', 'success');
                Yii::$app->session->setFlash('message1', 'Update Sukses');
                Yii::$app->session->setFlash('message2', 'Proses update sukses. Data telah berhasil disimpan.');
                
                return $this->redirect(['update-setting', 'id' => $id]);
            } else {
                Yii::$app->session->setFlash('status', 'danger');
                Yii::$app->session->setFlash('message1', 'Update Gagal');
                Yii::$app->session->setFlash('message2', 'Proses update gagal. Data gagal disimpan.');
            }                        
        }
        
        return $this->render('form_settings', [
            'models' => $models,
            'judul' => $judul,
        ]);
    }
    
    public function actionSlideshow() {
        
        $models = Settings::find()        
            ->orWhere(['like', 'setting_name', 'slideshow_top_count'])
            ->orWhere(['like', 'setting_name', 'slideshow_bottom_count'])
            ->all();
        
        $modelSlideshowTop = null;
        $modelSlideshowBottom = null;
        
        if (!empty(($post = Yii::$app->request->post()))) {                        

            if (Settings::loadMultiple($models, $post)) {  

                $transaction = Yii::$app->db->beginTransaction();
                $flag = true;

                foreach ($models as $key => $model) {
                    
                    if ($model->setting_name == 'slideshow_top_count')
                        $modelSlideshowTop = $model;
                    elseif ($model->setting_name == 'slideshow_bottom_count')
                        $modelSlideshowBottom = $model;

                    $flag = $model->save();

                    if (!$flag) {
                        $transaction->rollback();
                        break;
                    }                
                }

                if ($flag) {
                    $transaction->commit();
                    
                    if (!empty($post['btnTop'])) {
                        return $this->redirect(['input-slideshow', 'count' => $modelSlideshowTop->setting_value, 'type' => 'top']);
                    } elseif (!empty($post['btnBottom'])) {
                        return $this->redirect(['input-slideshow', 'count' => $modelSlideshowBottom->setting_value, 'type' => 'bottom']);
                    }

                    Yii::$app->session->setFlash('status', 'success');
                    Yii::$app->session->setFlash('message1', 'Update Sukses');
                    Yii::$app->session->setFlash('message2', 'Proses update sukses. Data telah berhasil disimpan.');

                    return $this->redirect(['slideshow']);
                } else {
                    Yii::$app->session->setFlash('status', 'danger');
                    Yii::$app->session->setFlash('message1', 'Update Gagal');
                    Yii::$app->session->setFlash('message2', 'Proses update gagal. Data gagal disimpan.');
                }                        
            }
        }       
        
        foreach ($models as $value) {
            if ($value->setting_name == 'slideshow_top_count')
                $modelSlideshowTop = $value;
            elseif ($value->setting_name == 'slideshow_bottom_count')
                $modelSlideshowBottom = $value;
        }       
        
        return $this->render('form_slideshow', [
            'modelSlideshowTop' => $modelSlideshowTop,
            'modelSlideshowBottom' => $modelSlideshowBottom,
        ]);
    }
    
    public function actionInputSlideshow($count, $type) {
        $models = Settings::find();
        
        if ($type == 'top')
            $models = $models->orWhere(['like', 'setting_name', 'slideshow_top_value_']);
        elseif ($type == 'bottom')
            $models = $models->orWhere(['like', 'setting_name', 'slideshow_bottom_value_']);
            
        $models = $models->limit($count)->all();
        
        if (($count - count($models)) > 0) {
            for ($i = (count($models) + 1); $i <= $count; $i++) {
                $tempModel = new Settings();
                $tempModel->setting_name = 'slideshow_' . $type . '_value_' . $i;
                $tempModel->save();
                
                $models[] = $tempModel;                
            }
        }                
        
        if (!empty(($post = Yii::$app->request->post()))) {
            
            $transaction = Yii::$app->db->beginTransaction();
            $flag = true;
            
            foreach ($models as $key => $model) {
                
                $image = UploadedFile::getInstance($model, '[' . $key . ']setting_value');                                                                   
                if ($image) {                                      
                    $flag = $image->saveAs('img/slideshow/' . $image->name . '.' . $image->extension);
                    $model->setting_value = $image->name . '.' . $image->extension;                        
                } else {
                    $model->setting_value = $model->oldAttributes['setting_value'];
                }         
                
                if ($flag) $flag = $model->save();
                
                if (!$flag) {
                    $transaction->rollback();
                    break;
                }                
            }
            
            if ($flag) {
                $transaction->commit();
                
                Yii::$app->session->setFlash('status', 'success');
                Yii::$app->session->setFlash('message1', 'Update Sukses');
                Yii::$app->session->setFlash('message2', 'Proses update sukses. Data telah berhasil disimpan.');
                
                return $this->redirect(['slideshow']);
            } else {
                Yii::$app->session->setFlash('status', 'danger');
                Yii::$app->session->setFlash('message1', 'Update Gagal');
                Yii::$app->session->setFlash('message2', 'Proses update gagal. Data gagal disimpan.');
            } 
            
        }
        
        return $this->render('form_input_slideshow', [
            'judul' => 'Slideshow',
            'models' => $models,
        ]);
    }
    
    public function actionPrinter() {
        
        $model = Settings::find()        
            ->andWhere(['like', 'setting_name', 'printer_count'])
            ->one();        
        
        if (!empty(($post = Yii::$app->request->post()))) {                        

            if ($model->load($post)) {                  

                if ($model->save()) {  
                    
                    if (!empty($post['btnInput'])) {
                        return $this->redirect(['input-printer', 'count' => $model->setting_value]);
                    }

                    Yii::$app->session->setFlash('status', 'success');
                    Yii::$app->session->setFlash('message1', 'Update Sukses');
                    Yii::$app->session->setFlash('message2', 'Proses update sukses. Data telah berhasil disimpan.');

                    return $this->redirect(['printer']);
                } else {
                    Yii::$app->session->setFlash('status', 'danger');
                    Yii::$app->session->setFlash('message1', 'Update Gagal');
                    Yii::$app->session->setFlash('message2', 'Proses update gagal. Data gagal disimpan.');
                }                        
            }
        }        
        
        return $this->render('form_printer', [
            'model' => $model,
        ]);
    }
    
    public function actionInputPrinter($count) {
        $models = Settings::find()
            ->andWhere(['like', 'setting_name', 'printer_value_'])
            ->limit($count)
            ->all();        
        
        if (($count - count($models)) > 0) {
            for ($i = (count($models) + 1); $i <= $count; $i++) {
                $tempModel = new Settings();
                $tempModel->setting_name = 'printer_value_' . $i;
                $tempModel->save();
                
                $models[] = $tempModel;                
            }
        }                
        
        if (!empty(($post = Yii::$app->request->post())) && Settings::loadMultiple($models, $post)) {
            
            $transaction = Yii::$app->db->beginTransaction();
            $flag = true;
            
            foreach ($models as $key => $model) {                                      
                
                if (!$model->save()) {
                    $transaction->rollback();
                    break;
                }                
            }
            
            if ($flag) {
                $transaction->commit();
                
                Yii::$app->session->setFlash('status', 'success');
                Yii::$app->session->setFlash('message1', 'Update Sukses');
                Yii::$app->session->setFlash('message2', 'Proses update sukses. Data telah berhasil disimpan.');
                
                return $this->redirect(['printer']);
            } else {
                Yii::$app->session->setFlash('status', 'danger');
                Yii::$app->session->setFlash('message1', 'Update Gagal');
                Yii::$app->session->setFlash('message2', 'Proses update gagal. Data gagal disimpan.');
            } 
            
        }
        
        return $this->render('form_input_printer', [
            'models' => $models,
        ]);
    }
    
    public function actionTaxServiceCharge() {
        
        $model = Settings::find()        
            ->andWhere(['like', 'setting_name', 'tax_include_service_charge'])
            ->one();        
        
        if (!empty(($post = Yii::$app->request->post()))) {                        

            
            if ($model->load($post)) {                  

                if ($model->save()) {                      
                    Yii::$app->session->setFlash('status', 'success');
                    Yii::$app->session->setFlash('message1', 'Update Sukses');
                    Yii::$app->session->setFlash('message2', 'Proses update sukses. Data telah berhasil disimpan.');

                    return $this->redirect(['tax-service-charge']);
                } else {
                    Yii::$app->session->setFlash('status', 'danger');
                    Yii::$app->session->setFlash('message1', 'Update Gagal');
                    Yii::$app->session->setFlash('message2', 'Proses update gagal. Data gagal disimpan.');
                }                        
            }
        }        
        
        return $this->render('form_tax_service_charge', [
            'model' => $model,
        ]);
    }
    
    public function actionFullscreen() {
        
        $model = Settings::find()        
            ->andWhere(['like', 'setting_name', 'auto_fullscreen'])
            ->one();        
        
        if (!empty(($post = Yii::$app->request->post()))) {                        

            
            if ($model->load($post)) {                  

                if ($model->save()) {                      
                    Yii::$app->session->setFlash('status', 'success');
                    Yii::$app->session->setFlash('message1', 'Update Sukses');
                    Yii::$app->session->setFlash('message2', 'Proses update sukses. Data telah berhasil disimpan.');

                    return $this->redirect(['fullscreen']);
                } else {
                    Yii::$app->session->setFlash('status', 'danger');
                    Yii::$app->session->setFlash('message1', 'Update Gagal');
                    Yii::$app->session->setFlash('message2', 'Proses update gagal. Data gagal disimpan.');
                }                        
            }
        }        
        
        return $this->render('form_fullscreen', [
            'model' => $model,
        ]);
    }

    /**
     * Finds the Settings model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Settings the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Settings::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    /**
     * Finds the Settings model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Settings the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModels($params)
    {
        if (($model = Settings::find($params)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
