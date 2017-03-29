<?php

namespace backend\controllers;

use Yii;
use backend\models\Mtable;
use backend\models\search\MtableSearch;
use backend\models\MtableCategory;
use backend\controllers\base\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\imagine\Image;


/**
 * MtableController implements the CRUD actions for Mtable model.
 */
class MtableController extends BaseController
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
     * Lists all Mtable models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MtableSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Mtable model.
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
     * Creates a new Mtable model.
     * If creation is successful, the browser will be redirected to the 'update' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Mtable();
        
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;            
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
            $flag = true;
            $image = UploadedFile::getInstance($model, 'image');                                                                   
            if ($image) {                                      
                $flag = $image->saveAs('img/mtable/' . $model->id . '.' . $image->extension);
                $model->image = $model->id . '.' . $image->extension;    
                
                if ($flag) {
                    $filename = Yii::getAlias('@backend') . '/web/img/mtable/thumb120x120' . $model->image;
                    Image::thumbnail('@backend/web/img/mtable/' . $model->image, 120, 120)
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
                
                return $this->redirect(['create']);
            }                        
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Mtable model.
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
            if ($image) {                                      
                $flag = $image->saveAs('img/mtable/' . $model->id . '.' . $image->extension);
                $model->image = $model->id . '.' . $image->extension;     
                
                if ($flag) {
                    $filename = Yii::getAlias('@backend') . '/web/img/mtable/thumb120x120' . $model->image;
                    Image::thumbnail('@backend/web/img/mtable/' . $model->image, 120, 120)
                        ->save($filename, ['quality' => 100]);               
                }
            } else {
                $flag = true;
                $model->image = $model->oldAttributes['image'];
            }
            
            if ($model->save() && $flag) {
                Yii::$app->session->setFlash('status', 'success');
                Yii::$app->session->setFlash('message1', 'Update Sukses');
                Yii::$app->session->setFlash('message2', 'Proses update sukses. Data telah berhasil disimpan.');
            } else {
                Yii::$app->session->setFlash('status', 'danger');
                Yii::$app->session->setFlash('message1', 'Update Gagal');
                Yii::$app->session->setFlash('message2', 'Proses update gagal. Data gagal disimpan.');
            }
            
            return $this->redirect(['update', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Mtable model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if (($model = $this->findModel($id)) !== false) {
                        
            $flag = false;
            $error = '';
            
            try {
                $flag = $model->delete();
            } catch (yii\db\Exception $exc) {
                $error = Yii::$app->params['errMysql'][$exc->errorInfo[1]];
            }
        }
        
        if ($flag) {
            Yii::$app->session->setFlash('status', 'success');
            Yii::$app->session->setFlash('message1', 'Delete Sukses');
            Yii::$app->session->setFlash('message2', 'Proses delete sukses. Data telah berhasil dihapus.');            
        } else {
            Yii::$app->session->setFlash('status', 'danger');
            Yii::$app->session->setFlash('message1', 'Delete Gagal');
            Yii::$app->session->setFlash('message2', 'Proses delete gagal. Data gagal dihapus.' . $error);
        }

        return $this->redirect(['index']);
    }
    
    public function actionTableLayout($catid = null)
    {
        if (($post = Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            $flag = true;
            
            foreach ($post['mtable'] as $mtable) {
                $modelMtable = $this->findModel($mtable['id']);
                $modelMtable->layout_x = $mtable['layout_x'];
                $modelMtable->layout_y = $mtable['layout_y'];
                $modelMtable->shape = $mtable['shape'];
                
                if (!($flag = $modelMtable->save()))
                    break;
            }
            
            if ($flag) {
                Yii::$app->session->setFlash('status', 'success');
                Yii::$app->session->setFlash('message1', 'Update Sukses');
                Yii::$app->session->setFlash('message2', 'Proses update sukses. Data telah berhasil disimpan.');
                
                $transaction->commit();
            } else {
                Yii::$app->session->setFlash('status', 'danger');
                Yii::$app->session->setFlash('message1', 'Update Gagal');
                Yii::$app->session->setFlash('message2', 'Proses update gagal. Data gagal disimpan.');
                
                $transaction->rollBack();
            }
            
            return $this->redirect(['table-layout', 'catid' => $catid]); 
        }
        
        $model = MtableCategory::find()->all();
        
        if (empty($catid)) {
            
            if (empty($model))
                throw new NotFoundHttpException('The requested page does not exist.');
            
            $catid = $model[0]->id;
        }
        
        return $this->render('table_layout', [
            'model' => $model,
            'catid' => $catid,
        ]);
    }

    /**
     * Finds the Mtable model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Mtable the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Mtable::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
