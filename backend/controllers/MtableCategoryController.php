<?php

namespace backend\controllers;

use Yii;
use backend\models\MtableCategory;
use backend\models\search\MtableCategorySearch;
use backend\controllers\base\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\imagine\Image;


/**
 * MtableCategoryController implements the CRUD actions for MtableCategory model.
 */
class MtableCategoryController extends BaseController
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
     * Lists all MtableCategory models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MtableCategorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MtableCategory model.
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
     * Creates a new MtableCategory model.
     * If creation is successful, the browser will be redirected to the 'update' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new MtableCategory();
        
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;            
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
            $flag = true;
            $image = UploadedFile::getInstance($model, 'image'); 
            $namaFile = time();
            if ($image) {                                      
                $flag = $image->saveAs('img/mtable-category/' . $namaFile . '.' . $image->extension);
                $model->image = $namaFile . '.' . $image->extension;    
                
                if ($flag) {
                    $filename = Yii::getAlias('@backend') . '/web/img/mtable-category/thumb120x120' . $model->image;
                    Image::thumbnail('@backend/web/img/mtable-category/' . $model->image, 120, 120)
                        ->save($filename, ['quality' => 100]);               
                }
            }
            
            if ($model->save() && $flag) {
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
     * Updates an existing MtableCategory model.
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
            $flag = false;
            $image = UploadedFile::getInstance($model, 'image');      
            $namaFile = time();
            if ($image) {                                      
                $flag = $image->saveAs('img/mtable-category/' . $namaFile . '.' . $image->extension);
                $model->image = $namaFile . '.' . $image->extension;                
                
                if ($flag) {
                    $filename = Yii::getAlias('@backend') . '/web/img/mtable-category/thumb120x120' . $model->image;
                    Image::thumbnail('@backend/web/img/mtable-category/' . $model->image, 120, 120)
                        ->save($filename, ['quality' => 100]);               
                }
            } else {
                $flag = true;
                $model->image = $model->oldAttributes['image'];
            }
            
            if ($model->save() &&$flag) {
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
     * Deletes an existing MtableCategory model.
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
     * Finds the MtableCategory model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return MtableCategory the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MtableCategory::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
