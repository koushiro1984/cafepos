<?php

namespace backend\controllers;

use Yii;
use backend\models\Booking;
use backend\models\Settings;
use backend\models\search\BookingSearch;
use backend\controllers\base\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\widgets\ActiveForm;


/**
 * BookingController implements the CRUD actions for Booking model.
 */
class BookingController extends BaseController
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
     * Lists all Booking models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BookingSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Booking model.
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
     * Creates a new Booking model.
     * If creation is successful, the browser will be redirected to the 'update' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Booking();
        
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;            
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
            $flag = false;
            if (($model->id = Settings::getTransNumber('no_booking')) !== false) {
                $flag = $model->save();
            }
            
            if ($flag) {
                Yii::$app->session->setFlash('status', 'success');
                Yii::$app->session->setFlash('message1', 'Tambah Data Sukses');
                Yii::$app->session->setFlash('message2', 'Proses tambah data sukses. Data telah berhasil disimpan.');
                
                return $this->redirect(['update', 'id' => $model->id]);
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
     * Updates an existing Booking model.
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
                
                return $this->redirect(['update', 'id' => $model->id]);
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
     * Deletes an existing Booking model.
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
     * Finds the Booking model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Booking the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Booking::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
