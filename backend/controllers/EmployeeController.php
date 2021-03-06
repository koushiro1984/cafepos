<?php

namespace backend\controllers;

use Yii;
use backend\models\Employee;
use backend\models\search\EmployeeSearch;
use backend\controllers\base\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\imagine\Image;

/**
 * EmployeeController implements the CRUD actions for Employee model.
 */
class EmployeeController extends BaseController
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
                        'update-limit-officer' => ['post'],
                    ],
                ],
            ]);
    }

    /**
     * Lists all Employee models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new EmployeeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Employee model.
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
     * Creates a new Employee model.
     * If creation is successful, the browser will be redirected to the 'update' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Employee();
        
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;            
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
            $flag = true;
            $image = UploadedFile::getInstance($model, 'image');                                                                   
            if ($image) {                                      
                $flag = $image->saveAs('img/employee/' . $model->kd_karyawan . '.' . $image->extension);
                $model->image = $model->kd_karyawan . '.' . $image->extension;
                
                if ($flag) {
                    $filename = Yii::getAlias('@backend') . '/web/img/employee/thumb100x100' . $model->image;
                    Image::thumbnail('@backend/web/img/employee/' . $model->image, 100, 100)
                        ->save($filename, ['quality' => 100]);               
                }
            }
            
            if ($model->save() && $flag) {
                Yii::$app->session->setFlash('status', 'success');
                Yii::$app->session->setFlash('message1', 'Tambah Data Sukses');
                Yii::$app->session->setFlash('message2', 'Proses tambah data sukses. Data telah berhasil disimpan.');
                
                return $this->redirect(['update', 'id' => $model->kd_karyawan]);
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
     * Updates an existing Employee model.
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
                $flag = $image->saveAs('img/employee/' . $model->kd_karyawan . '.' . $image->extension);
                $model->image = $model->kd_karyawan . '.' . $image->extension;        
                
                if ($flag) {
                    $filename = Yii::getAlias('@backend') . '/web/img/employee/thumb100x100' . $model->image;
                    Image::thumbnail('@backend/web/img/employee/' . $model->image, 100, 100)
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
                
                return $this->redirect(['update', 'id' => $model->kd_karyawan]);
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
    
    public function actionUpdateLimitOfficer($id)
    {
        $transaction = Yii::$app->db->beginTransaction();
        $flag = false;
        
        if ($id == 'all') {
            $models = Employee::find()->andWhere(['not_active' => false])->all();
            
            foreach ($models as $model) {
                $model->sisa = $model->limit_officer;
                
                if (!($flag = $model->save()))
                    break;
            }
        } else {
            $model = $this->findModel($id);
            $model->sisa = $model->limit_officer;
            $flag = $model->save();           
        }
        
        if ($flag) {
            $transaction->commit();
            
            Yii::$app->session->setFlash('status', 'success');
            Yii::$app->session->setFlash('message1', 'Update Sukses');
            Yii::$app->session->setFlash('message2', 'Proses update sukses. Data telah berhasil disimpan.');                                
        } else {
            $transaction->rollBack();
            
            Yii::$app->session->setFlash('status', 'danger');
            Yii::$app->session->setFlash('message1', 'Update Gagal');
            Yii::$app->session->setFlash('message2', 'Proses update gagal. Data gagal disimpan.');
        }
        
        return $this->redirect(['index']);
    }

    /**
     * Deletes an existing Employee model.
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

    /**
     * Finds the Employee model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Employee the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Employee::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
