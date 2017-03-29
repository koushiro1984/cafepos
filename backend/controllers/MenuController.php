<?php

namespace backend\controllers;

use Yii;
use backend\models\Menu;
use backend\models\search\MenuSearch;
use backend\models\MenuReceipt;
use backend\controllers\base\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\widgets\ActiveForm;
use yii\imagine\Image;


/**
 * MenuController implements the CRUD actions for Menu model.
 */
class MenuController extends BaseController
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
     * Lists all Menu models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MenuSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Menu model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        $modelMenuReceipts = MenuReceipt::find()->joinWith(['item', 'itemSku'])->where(['menu_id' => $id])->all();
        
        return $this->render('view', [
            'model' => $this->findModel($id),
            'modelMenuReceipts' => $modelMenuReceipts,
        ]);
    }

    /**
     * Creates a new Menu model.
     * If creation is successful, the browser will be redirected to the 'update' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Menu();
        $modelMenuReceipts = [];
        
        if (Yii::$app->request->isPost) {
            if (!empty(Yii::$app->request->post()['MenuReceipt'])) {
                foreach (Yii::$app->request->post()['MenuReceipt'] as $value) {
                    $modelMenuReceipts[] = new MenuReceipt();
                }            
            }
        } else {
            $modelMenuReceipts[] = new MenuReceipt();
        }
        
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;            
            return ActiveForm::validate($model);
        }

        $loadModelMenu = $model->load(Yii::$app->request->post());
        $loadModelMenuReceipt = MenuReceipt::loadMultiple($modelMenuReceipts, Yii::$app->request->post());
        if ($loadModelMenu || $loadModelMenuReceipt) {
            
            $transaction = Yii::$app->db->beginTransaction();
            $flag = true;
            
            $image = UploadedFile::getInstance($model, 'image');                                                                   
            if ($image) {                                      
                $flag = $image->saveAs('img/menu/' . $model->id . '.' . $image->extension);
                $model->image = $model->id . '.' . $image->extension;   
                
                if ($flag) {
                    $filename = Yii::getAlias('@backend') . '/web/img/menu/thumb120x120' . $model->image;
                    Image::thumbnail('@backend/web/img/menu/' . $model->image, 120, 120)
                        ->save($filename, ['quality' => 100]);               
                }
            }
            
            if ($model->save() && $flag) {                     
                foreach ($modelMenuReceipts as $key => $modelMenuReceipt) {
                    $modelMenuReceipt->menu_id = $model->id;
                    if (!$modelMenuReceipt->save()) {
                        $flag = false;
                        $transaction->rollBack();
                        break;
                    }
                }
            } else {
                $flag = false;
                $transaction->rollBack();
            }
            
            if ($flag) {
                $transaction->commit();
                
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
            'modelMenuReceipt' => $modelMenuReceipts[0],
        ]);
    }

    /**
     * Updates an existing Menu model.
     * If update is successful, the browser will be redirected to the 'update' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        
        $modelMenuReceipt = new MenuReceipt();
        $modelMenuReceipts = [];
        $modelMenuReceiptsPost = [];
        if (Yii::$app->request->isPost) {
            if (!empty(Yii::$app->request->post()['MenuReceiptEdited'])) {
                foreach (Yii::$app->request->post()['MenuReceiptEdited'] as $value) {
                    $modelMenuReceiptsPost['MenuReceipt'][] = $value;
                    $temp = new MenuReceipt();
                    $temp->setIsNewRecord(false);
                    $modelMenuReceipts[] = $temp;
                }            
            }
            
            if (!empty(Yii::$app->request->post()['MenuReceipt'])) {
                foreach (Yii::$app->request->post()['MenuReceipt'] as $value) {
                    $modelMenuReceiptsPost['MenuReceipt'][] = $value;
                    $temp = new MenuReceipt();
                    $temp->setIsNewRecord(true);
                    $modelMenuReceipts[] = $temp;
                }            
            }
            
        } else {
            $modelMenuReceipts = MenuReceipt::find()->joinWith(['item', 'itemSku'])->where(['menu_id' => $id])->all();
        }                
        
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;            
            return ActiveForm::validate($model);
        }        
        
        
        $loadModelMenu = $model->load(Yii::$app->request->post());
        $loadModelMenuReceipt = MenuReceipt::loadMultiple($modelMenuReceipts, $modelMenuReceiptsPost);
        if ($loadModelMenu || $loadModelMenuReceipt) {

            $transaction = Yii::$app->db->beginTransaction();
            $flag = true;
            
            $modelMenuReceiptsDelete = [];
            if (!empty(Yii::$app->request->post()['MenuReceiptDeleted'])) {
                foreach (Yii::$app->request->post()['MenuReceiptDeleted'] as $value) {
                    $modelMenuReceiptsDelete[] = $value['id'];
                }
                
                if (MenuReceipt::deleteAll(['IN', 'id', $modelMenuReceiptsDelete]) == 0) {
                    $flag = false;                    
                }
            }
            
            $image = UploadedFile::getInstance($model, 'image');                                                                   
            if ($image) {                                      
                $flag = $image->saveAs('img/menu/' . $model->id . '.' . $image->extension);
                $model->image = $model->id . '.' . $image->extension;    
                
                if ($flag) {
                    $filename = Yii::getAlias('@backend') . '/web/img/menu/thumb120x120' . $model->image;
                    Image::thumbnail('@backend/web/img/menu/' . $model->image, 120, 120)
                        ->save($filename, ['quality' => 100]);               
                }
            } else {
                $model->image = $model->oldAttributes['image'];
            }
                        
            if ($flag) {
                if ($model->save()) {                                         
                    foreach ($modelMenuReceipts as $key => $modelMenuReceipt) {
                        $modelMenuReceipt->menu_id = $model->id;                        
                        if (!$modelMenuReceipt->save()) {
                            $flag = false;
                            $transaction->rollBack();                            
                            break;
                        } 
                    }
                } else {
                    $flag = false;
                }
            }
            
            if ($flag) {
                $transaction->commit();
                
                Yii::$app->session->setFlash('status', 'success');
                Yii::$app->session->setFlash('message1', 'Tambah Data Sukses');
                Yii::$app->session->setFlash('message2', 'Proses tambah data sukses. Data telah berhasil disimpan.');
                
                return $this->redirect(['update', 'id' => $model->id]);
            } else {
                $transaction->rollBack();
                
                Yii::$app->session->setFlash('status', 'danger');
                Yii::$app->session->setFlash('message1', 'Tambah Data Gagal');
                Yii::$app->session->setFlash('message2', 'Proses tambah data gagal. Data gagal disimpan.');                
            }                                    
        }                
        
        return $this->render('update', [
            'model' => $model,
            'modelMenuReceipt' => $modelMenuReceipt,
            'modelMenuReceipts' => $modelMenuReceipts,
        ]);
    }

    /**
     * Deletes an existing Menu model.
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
     * Finds the Menu model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Menu the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Menu::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
