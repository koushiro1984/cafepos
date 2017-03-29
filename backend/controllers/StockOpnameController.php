<?php

namespace backend\controllers;

use Yii;
use backend\models\StockOpname;
use backend\models\Stock;
use backend\models\StockMovement;
use backend\models\search\StockOpnameSearch;
use backend\models\search\ItemSkuSearch;
use backend\controllers\base\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\data\ActiveDataProvider;
use yii\helpers\Json;

/**
 * StockOpnameController implements the CRUD actions for StockOpname model.
 */
class StockOpnameController extends BaseController
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
                        'opname-update' => ['post'],
                        'get-stock' => ['post'],
                        'opname-verify' => ['post'],
                    ],
                ],
            ]);
    }   
    
    /**
     * Lists all StockOpname models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ItemSkuSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);        

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }        

    /**
     * Displays a single StockOpname model.
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
     * Creates a new StockOpname model.
     * If creation is successful, the browser will be redirected to the 'update' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new StockOpname();
        
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;            
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
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
     * Updates an existing StockOpname model.
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
     * Deletes an existing StockOpname model.
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
     * Lists all StockOpname models.
     * @return mixed
     */
    public function actionConfirmation()
    {
        $searchModel = new StockOpnameSearch();
        $dataProvider = '';
        
        if (!empty(Yii::$app->request->post())) {
            $postParams = Yii::$app->request->post();
            if (!empty($postParams['selectedRows'])) {
                
                $transaction = Yii::$app->db->beginTransaction();
                $opnameVerify = '';
                $flag = false;
                $messages = '';
            
                $selectedRows = explode(',', $postParams['selectedRows']);
                foreach ($selectedRows as $value) {
                    $postParams['pk'] = $value;
                    $postParams['value'] = $postParams['action'];
                    $opnameVerify = $this->opnameVerify($postParams);
                    
                    if (!($flag = $opnameVerify['flag'])) break;
                }
                
                $model = $opnameVerify['model'];
                $modelStock = $opnameVerify['modelStock'];
                $flag = $opnameVerify['flag'];
                $messages = $opnameVerify['messages']; 

                if ($flag) {
                    Yii::$app->session->setFlash('status', 'success');
                    Yii::$app->session->setFlash('message1', 'Verifikasi Sukses');
                    Yii::$app->session->setFlash('message2', 'Proses verifikasi sukses. Data telah berhasil disimpan.');
                    
                    $transaction->commit();
                } else {           
                    $model->setIsNewRecord(true);
                    
                    if (empty($model->errors) && (empty($modelStock->errors) && !empty($modelStock))) {
                        $output = 'success';
                    } else {                                       
                        $error = array_merge($model->errors, (!empty($modelStock) ? $modelStock->errors : []));                    
                        foreach ($error as $arr1) {
                            foreach ($arr1 as $value) {
                                $messages .= $value . ' ';
                            }
                        }
                    }
                    
                    Yii::$app->session->setFlash('status', 'danger');
                    Yii::$app->session->setFlash('message1', 'Verifikasi Gagal');
                    Yii::$app->session->setFlash('message2', $messages . '\nData gagal disimpan.');

                    $transaction->rollBack();
                }                                
            }    
            
            return $this->redirect(['confirmation']);
        }
        
        if (!empty(Yii::$app->request->queryParams['StockOpnameSearch']['storage_id'])) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams, true);
        } else {
            $query = StockOpname::find()->where('1=2');
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => array(
                    'pageSize' => 15,
                ),
            ]);
        }

        return $this->render('confirmation', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    /**
     * Get Stock Data.
     * @return mixed
     */
    public function actionGetStock()
    {
        $query = Stock::find();
        $query->joinWith(['storage', 'storageRack']);
        
        $param = !empty(Yii::$app->request->post('expandRowKey')) ? Yii::$app->request->post('expandRowKey') : NULL;
        if ($param) {
            $query->where(['item_sku_id' => $param]);
        }
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => false,
            'pagination' => array(
                'pageSize' => 20,
            ),
        ]);                

        return $this->renderPartial('get_stock', [
            'dataProvider' => $dataProvider,
            'keyId' => $param,
        ]);
    }
    
    /**
     * Update Opname data with ajax.
     * @return mixed
     */
    public function actionOpnameUpdate() {
        $output = '';
        $messages = '';
            
        if (!empty(Yii::$app->request->post())) {            

            $postParams = Yii::$app->request->post();                                        

            $model = new StockOpname();
            $model->item_id = $postParams['item_id'];
            $model->item_sku_id = $postParams['item_sku_id'];
            $model->storage_id = $postParams['storage_id'];
            $model->storage_rack_id = $postParams['storage_rack_id'];
            $model->jumlah = $postParams['value'];
            $model->jumlah_awal = $postParams['jumlah_awal'];
            $model->jumlah_adjustment = $postParams['jumlah_adjustment'];
            
            $dataModels = StockOpname::find()->where([
                'item_id' => $model->item_id,
                'item_sku_id' => $model->item_sku_id,
                'storage_id' => $model->storage_id,
                'storage_rack_id' => $model->storage_rack_id,
                'action' => 'waiting',
            ])->asArray()->all();
            
            if (count($dataModels) > 0) {
                $model->addError('jumlah', 'Other stock opname data still waiting confirmation.');
            } else {            
                $model->save();
            }
            
            if (empty($model->errors['jumlah'])) {
                $output =  $model->jumlah;
            } else {
                foreach ($model->errors['jumlah'] as $value) {
                    $messages .= $value . ' ';
                }
            }                     
        }
        
        return Json::encode(['output' => $output, 'message' => $messages]);
    }
    
    /**
     * Update Opname action verify with ajax.
     * @return mixed
     */
    public function actionOpnameVerify() {
        $output = '';
        $messages = '';
            
        if (!empty(Yii::$app->request->post())) {            

            $postParams = Yii::$app->request->post();    
            
            $transaction = Yii::$app->db->beginTransaction();
            $flag = false;
            
            $opnameVerify = $this->opnameVerify($postParams);                                              
            
            $model = $opnameVerify['model'];
            $modelStock = $opnameVerify['modelStock'];
            $flag = $opnameVerify['flag'];
            $messages = $opnameVerify['messages']; 
                                   
            if ($flag) {
                $output = 'success';
                $transaction->commit();
            } else {                
                if (empty($model->errors) && (empty($modelStock->errors) && !empty($modelStock))) {
                    $output = 'success';
                } else {                                       
                    $error = array_merge($model->errors, (!empty($modelStock) ? $modelStock->errors : []));                    
                    foreach ($error as $arr1) {
                        foreach ($arr1 as $value) {
                            $messages .= $value . ' ';
                        }
                    }
                }
                
                $transaction->rollBack();
            }                     
        }
        
        return Json::encode(['output' => $output, 'message' => $messages]);
    }

    private function opnameVerify($postParams) {
        $flag = false;
        $messages = '';
        
        if (($model = StockOpname::findOne($postParams['pk'])) !== null) {
                
            $model->action = $postParams['value'];
            $model->date_action = date('Y-m-d H:i:s');
            $model->user_action = Yii::$app->user->identity->id;

            if (($flag = $model->save()) && ($model->action == 'approved')) {
                if (($modelStock = Stock::findOne($model->item_id . $model->item_sku_id . $model->storage_id . $model->storage_rack_id)) !== null) {

                    $modelStock->jumlah_stok = $model->jumlah;

                    if (($flag = $modelStock->save())) {

                        $modelStockMovement = new StockMovement();
                        $modelStockMovement->tanggal = date('Y-m-d');
                        $modelStockMovement->type = 'opname';
                        $modelStockMovement->item_id = $model->item_id;
                        $modelStockMovement->item_sku_id = $model->item_sku_id;

                        if ($model->jumlah_adjustment < 0) {
                            $modelStockMovement->jumlah = -1 * $model->jumlah_adjustment;
                            $modelStockMovement->storage_from = $model->storage_id;
                            $modelStockMovement->storage_rack_from = $model->storage_rack_id;
                        } else {
                            $modelStockMovement->jumlah = $model->jumlah_adjustment;
                            $modelStockMovement->storage_to = $model->storage_id;
                            $modelStockMovement->storage_rack_to = $model->storage_rack_id;
                        }
                        
                        $modelStockMovement->reference = $model->id;

                        if (!($flag = $modelStockMovement->save())) {
                            $messages = 'Error update data!';
                        }
                    }                            
                } else {
                    $flag = false;
                    $messages = 'Unavailable stock opname data';
                }
            }                                
        }
        
        return [
            'flag' => $flag, 
            'messages' => $messages, 
            'model' => $model,
            'modelStock' => empty($modelStock) ? NULL : $modelStock
        ];
    }
    /**
     * Finds the StockOpname model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return StockOpname the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = StockOpname::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
