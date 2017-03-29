<?php

namespace backend\controllers;

use Yii;
use backend\models\DirectPurchase;
use backend\models\search\DirectPurchaseSearch;
use backend\models\Settings;
use backend\models\DirectPurchaseTrx;
use backend\models\Stock;
use backend\models\StockMovement;
use backend\controllers\base\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\widgets\ActiveForm;
use kartik\mpdf\Pdf;


/**
 * DirectPurchaseController implements the CRUD actions for DirectPurchase model.
 */
class DirectPurchaseController extends BaseController
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
     * Lists all DirectPurchase models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DirectPurchaseSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DirectPurchase model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        $modelDirectPurchaseTrxs = DirectPurchaseTrx::find()->joinWith(['item', 'itemSku'])->where(['direct_purchase_id' => $id])->all();
        
        return $this->render('view', [
            'model' => $this->findModel($id),
            'modelDirectPurchaseTrxs' => $modelDirectPurchaseTrxs,
        ]);
    }

    /**
     * Creates a new DirectPurchase model.
     * If creation is successful, the browser will be redirected to the 'update' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new DirectPurchase();
        $model->date = date('Y-m-d');
        $modelDirectPurchaseTrxs = [];
        
        if (Yii::$app->request->isPost) {
            if (!empty(Yii::$app->request->post()['DirectPurchaseTrx'])) {
                foreach (Yii::$app->request->post()['DirectPurchaseTrx'] as $value) {
                    $modelDirectPurchaseTrxs[] = new DirectPurchaseTrx();
                }            
            }
        } else {
            $modelDirectPurchaseTrxs[] = new DirectPurchaseTrx();
        }
        
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;            
            return ActiveForm::validate($model);
        }

        $loadModelDirectPurchase = $model->load(Yii::$app->request->post());
        $loadModelDirectPurchaseTrx = DirectPurchaseTrx::loadMultiple($modelDirectPurchaseTrxs, Yii::$app->request->post());
        if ($loadModelDirectPurchase || $loadModelDirectPurchaseTrx) {
            
            $transaction = Yii::$app->db->beginTransaction();
            $flag = false;
            $error = '';
            
            if (($model->id = Settings::getTransNumber('no_dp')) !== false) {                
                if (($flag = $model->save())) {
                    if (count($modelDirectPurchaseTrxs) > 0) {
                        foreach ($modelDirectPurchaseTrxs as $key => $modelDirectPurchaseTrx) {
                            if (!empty($modelDirectPurchaseTrx->item_sku_id)) {

                                $modelDirectPurchaseTrx->direct_purchase_id = $model->id;                                                    
                                if (($flag = $modelDirectPurchaseTrx->save())) {

                                    $flag = Stock::setStock(
                                            $modelDirectPurchaseTrx->item_id, 
                                            $modelDirectPurchaseTrx->item_sku_id, 
                                            $modelDirectPurchaseTrx->storage_id, 
                                            $modelDirectPurchaseTrx->storage_rack_id, 
                                            $modelDirectPurchaseTrx->jumlah_item
                                    );

                                    if ($flag) {
                                        $flag = StockMovement::setInflow(
                                                'inflow-dp', 
                                                $modelDirectPurchaseTrx->item_id, 
                                                $modelDirectPurchaseTrx->item_sku_id, 
                                                $modelDirectPurchaseTrx->storage_id, 
                                                $modelDirectPurchaseTrx->storage_rack_id, 
                                                $modelDirectPurchaseTrx->jumlah_item,
                                                date('Y-m-d'), 
                                                $modelDirectPurchaseTrx->direct_purchase_id
                                        );

                                        if (!$flag) {
                                            break;
                                        }
                                    }
                                }
                            }
                        }
                    } else {                                                
                        $flag = false;
                        $error = '<br>Data item tidak boleh kosong.';
                    }
                }
            }
            
            if ($flag) {
                Yii::$app->session->setFlash('status', 'success');
                Yii::$app->session->setFlash('message1', 'Tambah Data Sukses');
                Yii::$app->session->setFlash('message2', 'Proses tambah data sukses. Data telah berhasil disimpan.');
                
                $transaction->commit();
                
                return $this->redirect(['update', 'id' => $model->id]);
            } else {
                $model->setIsNewRecord(true);
                $model->id = null;
                $modelDirectPurchaseTrxs[] = new DirectPurchaseTrx();
                
                Yii::$app->session->setFlash('status', 'danger');
                Yii::$app->session->setFlash('message1', 'Tambah Data Gagal');
                Yii::$app->session->setFlash('message2', 'Proses tambah data gagal. Data gagal disimpan.' . $error);     
                
                $transaction->rollBack();
            }                        
        }
        
        return $this->render('create', [
            'model' => $model,
            'modelDirectPurchaseTrx' => $modelDirectPurchaseTrxs[0],
        ]);
    }

    /**
     * Updates an existing DirectPurchase model.
     * If update is successful, the browser will be redirected to the 'update' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        
        $modelDirectPurchaseTrx = new DirectPurchaseTrx();
        $modelDirectPurchaseTrxs = [];
        $modelDirectPurchaseTrxsPost = [];
        if (Yii::$app->request->isPost) {
            if (!empty(Yii::$app->request->post()['DirectPurchaseTrxEdited'])) {
                foreach (Yii::$app->request->post()['DirectPurchaseTrxEdited'] as $value) {
                    $modelDirectPurchaseTrxsPost['DirectPurchaseTrx'][] = $value;
                    $temp = new DirectPurchaseTrx();
                    $temp->setIsNewRecord(false);
                    $modelDirectPurchaseTrxs[] = $temp;
                }
            }
            
            if (!empty(Yii::$app->request->post()['DirectPurchaseTrx'])) {
                foreach (Yii::$app->request->post()['DirectPurchaseTrx'] as $value) {
                    $modelDirectPurchaseTrxsPost['DirectPurchaseTrx'][] = $value;
                    $temp = new DirectPurchaseTrx();
                    $temp->setIsNewRecord(true);
                    $modelDirectPurchaseTrxs[] = $temp;
                }
            }
        } else {
            $modelDirectPurchaseTrxs = DirectPurchaseTrx::find()->joinWith(['item', 'itemSku'])->where(['direct_purchase_id' => $id])->all();
        }
        
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;            
            return ActiveForm::validate($model);
        }
        
        $loadModelDirectPurchase = $model->load(Yii::$app->request->post());
        $loadModelDirectPurchaseTrx = DirectPurchaseTrx::loadMultiple($modelDirectPurchaseTrxs, $modelDirectPurchaseTrxsPost);
        if ($loadModelDirectPurchase || $loadModelDirectPurchaseTrx) {

            $transaction = Yii::$app->db->beginTransaction();
            $flag = true;
            
            $modelDirectPurchaseTrxsDelete = [];
            if (!empty(Yii::$app->request->post()['DirectPurchaseTrxDeleted'])) {
                foreach (Yii::$app->request->post()['DirectPurchaseTrxDeleted'] as $value) {
                    $flag = Stock::setStock(
                            $value['item_id'],
                            $value['item_sku_id'], 
                            $value['storage_id'], 
                            $value['storage_rack_id'], 
                            -1 * $value['jumlah_item']
                    ); 

                    if ($flag) {
                        $flag = StockMovement::setOutflow(
                                'inflow-dp-delete', 
                                $value['item_id'],
                                $value['item_sku_id'], 
                                $value['storage_id'], 
                                $value['storage_rack_id'], 
                                $value['jumlah_item'],
                                date('Y-m-d'), 
                                $model->id
                        );

                        if (!$flag) {                            
                            break;
                        }
                    } else {
                        break;
                    }

                    $modelDirectPurchaseTrxsDelete[] = $value['id'];
                }
                
                if (DirectPurchaseTrx::deleteAll(['IN', 'id', $modelDirectPurchaseTrxsDelete]) == 0) {
                    $flag = false;                    
                }
            }
            
            if ($flag) {
                if ($model->save()) {                                         
                    foreach ($modelDirectPurchaseTrxs as $key => $modelDirectPurchaseTrx) {
                        $modelDirectPurchaseTrx->direct_purchase_id = $model->id;                        
                        if (!empty($modelDirectPurchaseTrx->item_sku_id) && $modelDirectPurchaseTrx->isNewRecord) {
                        
                            $modelDirectPurchaseTrx->direct_purchase_id = $model->id;                                                    
                            if (($flag = $modelDirectPurchaseTrx->save())) {

                                $flag = Stock::setStock(
                                        $modelDirectPurchaseTrx->item_id, 
                                        $modelDirectPurchaseTrx->item_sku_id, 
                                        $modelDirectPurchaseTrx->storage_id, 
                                        $modelDirectPurchaseTrx->storage_rack_id, 
                                        $modelDirectPurchaseTrx->jumlah_item
                                );

                                if ($flag) {
                                    $flag = StockMovement::setInflow(
                                            'inflow-dp', 
                                            $modelDirectPurchaseTrx->item_id, 
                                            $modelDirectPurchaseTrx->item_sku_id, 
                                            $modelDirectPurchaseTrx->storage_id, 
                                            $modelDirectPurchaseTrx->storage_rack_id, 
                                            $modelDirectPurchaseTrx->jumlah_item,
                                            date('Y-m-d'), 
                                            $modelDirectPurchaseTrx->direct_purchase_id
                                    );

                                    if (!$flag) {
                                        break;
                                    }
                                }
                            }
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
            'modelDirectPurchaseTrx' => $modelDirectPurchaseTrx,
            'modelDirectPurchaseTrxs' => $modelDirectPurchaseTrxs,
        ]);
    }

    /**
     * Deletes an existing DirectPurchase model.
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
     * Finds the DirectPurchase model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return DirectPurchase the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DirectPurchase::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionPrint($id) {
        $model = $this->findModel($id);
        $modelDirectPurchaseTrxs = DirectPurchaseTrx::find()->joinWith(['item', 'itemSku'])->where(['direct_purchase_id' => $id])->all();
        
        $content = $this->renderPartial('print', [
            'model' => $model,
            'modelDirectPurchaseTrxs' => $modelDirectPurchaseTrxs
        ]);
        
        $pdf = new Pdf([
            'mode' => Pdf::MODE_BLANK, 
            'format' => Pdf::FORMAT_A4, 
            'orientation' => Pdf::ORIENT_PORTRAIT, 
            'destination' => Pdf::DEST_BROWSER, 
            'content' => $content,  
            'cssFile' => '@vendor/yii2-krajee-mpdf/assets/kv-mpdf-bootstrap.min.css',
            'cssInline' => file_get_contents(Yii::getAlias('@backend/web/css/report.css')), 
            'options' => ['title' => Yii::$app->name],
            'methods' => [ 
                'SetHeader'=>[Yii::$app->name . ' - Pembelian Langsung'], 
                'SetFooter'=>['{PAGENO}'],
            ]
        ]);

        return $pdf->render();
    }
}
