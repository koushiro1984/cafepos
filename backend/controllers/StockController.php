<?php

namespace backend\controllers;

use Yii;
use backend\models\Stock;
use backend\models\StockMovement;
use backend\models\search\StockSearch;
use backend\models\ItemSku;
use backend\models\PurchaseOrder;
use backend\models\PurchaseOrderTrx;
use backend\models\Settings;
use backend\controllers\base\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\widgets\ActiveForm;
use kartik\mpdf\Pdf;


/**
 * StockController implements the CRUD actions for Stock model.
 */
class StockController extends BaseController
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
     * Lists all Stock models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new StockSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Stock model.
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
     * Creates a new Stock model.
     * If creation is successful, the browser will be redirected to the 'update' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Stock();
        
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
     * Updates an existing Stock model.
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
     * Deletes an existing Stock model.
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
     * Updates an Stock Flow.
     *
     * @return mixed
     */
    public function actionStockFlow($flow)
    {        
        if ($flow == 'in') {
            return $this->stockInflow();
        } elseif ($flow == 'out') {
            return $this->stockOutflow();
        } elseif ($flow == 'trnsfr') {
            return $this->stockTransfer();
        } elseif ($flow == 'indeliv') {
            return $this->stockInternalDelivery();
        } elseif ($flow == 'inrecev') {
            return $this->stockInternalReceive();
        }
    }
    
    /**
     * Updates an Stock model for Inflow.
     *
     * @return mixed
     */
    private function stockInflow()
    {
        $model = new StockMovement();
        $model->tanggal = date('Y-m-d');
        
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;            
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
            
            $transaction = Yii::$app->db->beginTransaction();
            $flag = false;                                   
                
            $flag = Stock::setStock(
                    $model->item_id, 
                    $model->item_sku_id, 
                    $model->storage_to, 
                    $model->storage_rack_to, 
                    $model->jumlah
            );

            if ($flag) {                
                $model->type = 'inflow';
                
                $flag = $model->save();
            }                       
            
            
            if ($flag) {
                Yii::$app->session->setFlash('status', 'success');
                Yii::$app->session->setFlash('message1', 'Update Data Stok Masuk Sukses');
                Yii::$app->session->setFlash('message2', 'Data telah berhasil disimpan.');
                
                $transaction->commit();
                
                return $this->redirect(['stock-flow', 'flow' => 'in']);
            } else {
                $model->setIsNewRecord(true);
                
                if (empty(Yii::$app->session->getFlash('status'))) {
                    Yii::$app->session->setFlash('status', 'danger');
                    Yii::$app->session->setFlash('message1', 'Update Data Stok Masuk Gagal');
                    Yii::$app->session->setFlash('message2', 'Data gagal disimpan.');  
                    
                    $transaction->rollBack();
                }
            }                        
        }
        
        return $this->render('stock_flow', [
            'model' => $model,
            'flow' => 'inflow',
            'title' => 'Stok Inflow',
        ]);
    }
    
    /**
     * Updates an Stock model for Outflow.
     *
     * @return mixed
     */
    private function stockOutflow()
    {
        $model = new StockMovement();
        $model->tanggal = date('Y-m-d');
        
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;            
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
            
            $transaction = Yii::$app->db->beginTransaction();
            $flag = false;            
            
            $flag = Stock::setStock(
                    $model->item_id, 
                    $model->item_sku_id, 
                    $model->storage_from, 
                    $model->storage_rack_from, 
                    -1 * $model->jumlah
            );
            
            if ($flag) {
                                        
                $model->type = 'outflow';

                $flag = $model->save();
            }
                        
            
            if ($flag) {
                Yii::$app->session->setFlash('status', 'success');
                Yii::$app->session->setFlash('message1', 'Update Data Stok Masuk Sukses');
                Yii::$app->session->setFlash('message2', 'Data telah berhasil disimpan.');
                
                $transaction->commit();
                
                return $this->redirect(['stock-flow', 'flow' => 'out']);
            } else {
                $model->setIsNewRecord(true);
                
                if (empty(Yii::$app->session->getFlash('status'))) {
                    Yii::$app->session->setFlash('status', 'danger');
                    Yii::$app->session->setFlash('message1', 'Update Data Stok Masuk Gagal');
                    Yii::$app->session->setFlash('message2', 'Data gagal disimpan.');                                          
                }
                
                $transaction->rollBack();
            }                        
        }
        
        return $this->render('stock_flow', [
            'model' => $model,
            'flow' => 'outflow',
            'title' => 'Stok Outflow',
        ]);
    }
    
    /**
     * Updates an Stock model for Outflow.
     *
     * @return mixed
     */
    private function stockTransfer()
    {
        $model = new StockMovement();
        $model->tanggal = date('Y-m-d');
        
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;            
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
            
            $transaction = Yii::$app->db->beginTransaction();
            $flag = false;
            
            $flag = Stock::setStock(
                $model->item_id, 
                $model->item_sku_id, 
                $model->storage_from, 
                $model->storage_rack_from, 
                -1 * $model->jumlah
            );
            
            if ($flag) {
                $flag = Stock::setStock(
                    $model->item_id, 
                    $model->item_sku_id, 
                    $model->storage_to, 
                    $model->storage_rack_to, 
                    $model->jumlah
                );
                
                if ($flag) {
                                        
                    $model->type = 'transfer';

                    $flag = $model->save();
                }
            }
                        
            
            if ($flag) {
                Yii::$app->session->setFlash('status', 'success');
                Yii::$app->session->setFlash('message1', 'Update Data Stok Masuk Sukses');
                Yii::$app->session->setFlash('message2', 'Data telah berhasil disimpan.');
                
                $transaction->commit();
                
                return $this->redirect(['stock-flow', 'flow' => 'trnsfr']);
            } else {
                $model->setIsNewRecord(true);
                
                if (empty(Yii::$app->session->getFlash('status'))) {
                    Yii::$app->session->setFlash('status', 'danger');
                    Yii::$app->session->setFlash('message1', 'Update Data Stok Masuk Gagal');
                    Yii::$app->session->setFlash('message2', 'Data gagal disimpan.');                                          
                }
                
                $transaction->rollBack();
            }
        }
        
        return $this->render('stock_flow', [
            'model' => $model,
            'flow' => 'transfer',
            'title' => 'Stok Transfer',
        ]);
    }
    
    /**
     * Updates an Stock model for Internal Delivery.
     *
     * @return mixed
     */
    private function stockInternalDelivery()
    {
        $model = new StockMovement();
        $model->tanggal = date('Y-m-d');
        
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;            
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
            
            $transaction = Yii::$app->db->beginTransaction();
            $flag = false;             
            
            $flag = Stock::setStock(
                    $model->item_id, 
                    $model->item_sku_id, 
                    $model->storage_from, 
                    $model->storage_rack_from, 
                    -1 * $model->jumlah
            );
            
            if ($flag) {
                                        
                $model->type = 'outflow-indelivery';

                $flag = $model->save();
            }            
            
            if ($flag) {
                Yii::$app->session->setFlash('status', 'success');
                Yii::$app->session->setFlash('message1', 'Update Data Stok Masuk Sukses');
                Yii::$app->session->setFlash('message2', 'Data telah berhasil disimpan.');
                
                $transaction->commit();
                
                return $this->redirect(['stock-flow', 'flow' => 'indeliv']);
            } else {
                $model->setIsNewRecord(true);
                
                if (empty(Yii::$app->session->getFlash('status'))) {
                    Yii::$app->session->setFlash('status', 'danger');
                    Yii::$app->session->setFlash('message1', 'Update Data Stok Masuk Gagal');
                    Yii::$app->session->setFlash('message2', 'Data gagal disimpan.');                                          
                }
                
                $transaction->rollBack();
            }                        
        }
        
        return $this->render('stock_flow', [
            'model' => $model,
            'flow' => 'indelivery',
            'title' => 'Internal Delivery',
        ]);
    }
    
    /**
     * Updates an Stock model for Internal Receive.
     *
     * @return mixed
     */
    private function stockInternalReceive()
    {
        $model = new StockMovement();
        $model->tanggal = date('Y-m-d');
        
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;            
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
            
            $transaction = Yii::$app->db->beginTransaction();
            $flag = false;
            
            $flag = Stock::setStock(
                    $model->item_id, 
                    $model->item_sku_id, 
                    $model->storage_to, 
                    $model->storage_rack_to, 
                    $model->jumlah
            );
            
            if ($flag) {
                                        
                $model->type = 'inflow-inreceive';

                $flag = $model->save();
            }                          
            
            if ($flag) {
                Yii::$app->session->setFlash('status', 'success');
                Yii::$app->session->setFlash('message1', 'Update Data Stok Masuk Sukses');
                Yii::$app->session->setFlash('message2', 'Data telah berhasil disimpan.');
                
                $transaction->commit();
                
                return $this->redirect(['stock-flow', 'flow' => 'inrecev']);
            } else {
                $model->setIsNewRecord(true);
                
                if (empty(Yii::$app->session->getFlash('status'))) {
                    Yii::$app->session->setFlash('status', 'danger');
                    Yii::$app->session->setFlash('message1', 'Update Data Stok Masuk Gagal');
                    Yii::$app->session->setFlash('message2', 'Data gagal disimpan.');                                          
                }
                
                $transaction->rollBack();
            }                        
        }
        
        return $this->render('stock_flow', [
            'model' => $model,
            'flow' => 'inreceive',
            'title' => 'Internal Receiving',
        ]);
    }
    
    public function actionStockKritis() {
        
        $modelPurchaseOrder = new PurchaseOrder();
        $modelPurchaseOrderTrx = new PurchaseOrderTrx();
        
        if (!empty($post = Yii::$app->request->post())) {
            $loadModelPurchaseOrder = $modelPurchaseOrder->load($post);
            $loadModelPurchaseOrderTrx = $modelPurchaseOrderTrx->load($post);
            
            if ($loadModelPurchaseOrder || $loadModelPurchaseOrderTrx) {

                $transaction = Yii::$app->db->beginTransaction();
                $flag = false;

                if (($modelPurchaseOrder->id = Settings::getTransNumber('no_po')) !== false) { 
                    $modelPurchaseOrder->date = date('Y-m-d');
                    $modelPurchaseOrder->jumlah_item = $modelPurchaseOrderTrx->jumlah_order;
                    $modelPurchaseOrder->jumlah_harga = $modelPurchaseOrderTrx->jumlah_order * $modelPurchaseOrderTrx->harga_satuan;
                    
                    if (($flag = $modelPurchaseOrder->save())) {

                        $modelPurchaseOrderTrx->purchase_order_id = $modelPurchaseOrder->id;
                        $modelPurchaseOrderTrx->jumlah_harga = $modelPurchaseOrderTrx->jumlah_order * $modelPurchaseOrderTrx->harga_satuan;
                        
                        $flag = $modelPurchaseOrderTrx->save();
                    }
                }

                if ($flag) {
                    Yii::$app->session->setFlash('status', 'success');
                    Yii::$app->session->setFlash('message1', 'Tambah Data Sukses');
                    Yii::$app->session->setFlash('message2', 'Proses tambah data sukses. Data telah berhasil disimpan.');

                    $transaction->commit();

                    return $this->redirect(['purchase-order/update', 'id' => $modelPurchaseOrder->id]);
                } else {
                    Yii::$app->session->setFlash('status', 'danger');
                    Yii::$app->session->setFlash('message1', 'Tambah Data Gagal');
                    Yii::$app->session->setFlash('message2', 'Proses tambah data gagal. Data gagal disimpan.');     

                    $transaction->rollBack();
                }                        
            }
        }
        
        $modelItemSku = ItemSku::find()
                ->joinWith([
                    'item',                    
                    'stocks',
                    'stocks.storage',
                    'stocks.storageRack',
                ])
                ->andWhere('stock.jumlah_stok IS NULL OR stock.jumlah_stok <= item_sku.stok_minimal OR stock.jumlah_stok <= 0')
                ->asArray()->all();
        
        return $this->render('stock_kritis', [
            'modelItemSku' => $modelItemSku,
            'modelPurchaseOrder' => $modelPurchaseOrder,
            'modelPurchaseOrderTrx' => $modelPurchaseOrderTrx,
        ]);
    }
    
    /**
     * Finds the Stock model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Stock the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Stock::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionReportStock()
    {
        if (!empty($post = Yii::$app->request->post())) {
        
            $modelStock = Stock::find()
                ->joinWith([
                    'item',
                    'itemSku',
                    'storage',
                    'storageRack',
                ])
                ->asArray()->all();            

            $content = $this->renderPartial('_report_stock_print', [
                'modelStock' => $modelStock,
                'print' => $post['print'],
            ]);                    

            if ($post['print'] == 'pdf') {
                $footer = '
                    <table style="width:100%">
                        <tr>
                            <td style="width:50%">' . date('d-m-Y H:m:s') . ' - ' . Yii::$app->session->get('user_data')['employee']['nama'] . '</td>
                            <td style="width:50%; text-align:right">{PAGENO}</td>
                        </tr>
                    </table>
                ';

                $pdf = new Pdf([
                    'mode' => Pdf::MODE_BLANK, 
                    'format' => Pdf::FORMAT_A4, 
                    'orientation' => Pdf::ORIENT_LANDSCAPE, 
                    'destination' => Pdf::DEST_BROWSER, 
                    'content' => $content,  
                    'cssFile' => '@vendor/yii2-krajee-mpdf/assets/kv-mpdf-bootstrap.min.css',
                    'cssInline' => file_get_contents(Yii::getAlias('@backend/web/css/report.css')), 
                    'options' => ['title' => Yii::$app->name],
                    'methods' => [ 
                        'SetHeader'=>[Yii::$app->name . ' - Report Stok'], 
                        'SetFooter'=>[$footer],
                    ]
                ]);

                return $pdf->render(); 
            } else if ($post['print'] == 'excel') {
                header('Content-Type:   application/vnd.ms-excel; charset=utf-8');
                header('Content-Disposition: attachment; filename="' . Yii::$app->name . ' - Report Stok' .'.xls"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                header('Cache-Control: private',false);
                echo $content;
                exit;
            }  
        }
        
        return $this->render('report_stock', [
        
        ]);
    }
    
    public function actionReportStockOutflow()
    {
        if (!empty($post = Yii::$app->request->post()) && !empty($post['tanggalFrom']) && !empty($post['tanggalTo'])) {      
        
            $modelStock = StockMovement::find()
                ->joinWith([
                    'item',
                    'itemSku',
                    'storageFrom',
                    'storageRackFrom',
                ])
                ->andWhere('stock_movement.tanggal BETWEEN "' . $post['tanggalFrom'] . '" AND "' . $post['tanggalTo'] . '"')
                ->andWhere('stock_movement.type="outflow"')
                ->asArray()->all();            

            $content = $this->renderPartial('_report_stock_outflow_print', [
                'modelStock' => $modelStock,
                'print' => $post['print'],
            ]);                    

            if ($post['print'] == 'pdf') {
                $footer = '
                    <table style="width:100%">
                        <tr>
                            <td style="width:50%">' . date('d-m-Y H:m:s') . ' - ' . Yii::$app->session->get('user_data')['employee']['nama'] . '</td>
                            <td style="width:50%; text-align:right">{PAGENO}</td>
                        </tr>
                    </table>
                ';
                
                $pdf = new Pdf([
                    'mode' => Pdf::MODE_BLANK, 
                    'format' => Pdf::FORMAT_A4, 
                    'orientation' => Pdf::ORIENT_LANDSCAPE, 
                    'destination' => Pdf::DEST_BROWSER, 
                    'content' => $content,  
                    'cssFile' => '@vendor/yii2-krajee-mpdf/assets/kv-mpdf-bootstrap.min.css',
                    'cssInline' => file_get_contents(Yii::getAlias('@backend/web/css/report.css')), 
                    'options' => ['title' => Yii::$app->name],
                    'methods' => [ 
                        'SetHeader'=>[Yii::$app->name . ' - Report Stok Outflow | Tanggal ' . Yii::$app->formatter->asDate($post['tanggalFrom']) . ' - ' . Yii::$app->formatter->asDate($post['tanggalTo'])], 
                        'SetFooter'=>[$footer],
                    ]
                ]);

                return $pdf->render(); 
            } else if ($post['print'] == 'excel') {
                header('Content-Type:   application/vnd.ms-excel; charset=utf-8');
                header('Content-Disposition: attachment; filename="' . Yii::$app->name . ' - Report Stok Keluar | Tanggal ' . Yii::$app->formatter->asDate($post['tanggalFrom']) . ' - ' . Yii::$app->formatter->asDate($post['tanggalTo']) .'.xls"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                header('Cache-Control: private',false);
                echo $content;
                exit;
            }  
        }
        
        return $this->render('report_stock_outflow', [
        
        ]);
    }
    
    public function actionReportStockInflow()
    {
        if (!empty($post = Yii::$app->request->post()) && !empty($post['tanggalFrom']) && !empty($post['tanggalTo'])) {      
        
            $modelStock = StockMovement::find()
                ->joinWith([
                    'item',
                    'itemSku',
                    'storageTo',
                    'storageRackTo',
                ])
                ->andWhere('stock_movement.tanggal BETWEEN "' . $post['tanggalFrom'] . '" AND "' . $post['tanggalTo'] . '"')
                ->andWhere('stock_movement.type="inflow"')
                ->asArray()->all();            

            $content = $this->renderPartial('_report_stock_inflow_print', [
                'modelStock' => $modelStock,
                'print' => $post['print'],
            ]);                    

            if ($post['print'] == 'pdf') {
                $footer = '
                    <table style="width:100%">
                        <tr>
                            <td style="width:50%">' . date('d-m-Y H:m:s') . ' - ' . Yii::$app->session->get('user_data')['employee']['nama'] . '</td>
                            <td style="width:50%; text-align:right">{PAGENO}</td>
                        </tr>
                    </table>
                ';
                
                $pdf = new Pdf([
                    'mode' => Pdf::MODE_BLANK, 
                    'format' => Pdf::FORMAT_A4, 
                    'orientation' => Pdf::ORIENT_LANDSCAPE, 
                    'destination' => Pdf::DEST_BROWSER, 
                    'content' => $content,  
                    'cssFile' => '@vendor/yii2-krajee-mpdf/assets/kv-mpdf-bootstrap.min.css',
                    'cssInline' => file_get_contents(Yii::getAlias('@backend/web/css/report.css')), 
                    'options' => ['title' => Yii::$app->name],
                    'methods' => [ 
                        'SetHeader'=>[Yii::$app->name . ' - Report Stok Inflow | Tanggal ' . Yii::$app->formatter->asDate($post['tanggalFrom']) . ' - ' . Yii::$app->formatter->asDate($post['tanggalTo'])], 
                        'SetFooter'=>[$footer],
                    ]
                ]);

                return $pdf->render(); 
            } else if ($post['print'] == 'excel') {
                header('Content-Type:   application/vnd.ms-excel; charset=utf-8');
                header('Content-Disposition: attachment; filename="' . Yii::$app->name . ' - Report Stok Keluar | Tanggal ' . Yii::$app->formatter->asDate($post['tanggalFrom']) . ' - ' . Yii::$app->formatter->asDate($post['tanggalTo']) .'.xls"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                header('Cache-Control: private',false);
                echo $content;
                exit;
            }  
        }
        
        return $this->render('report_stock_inflow', [
        
        ]);
    }
    
    public function actionReportStockTransfer()
    {
        if (!empty($post = Yii::$app->request->post()) && !empty($post['tanggalFrom']) && !empty($post['tanggalTo'])) {      
        
            $modelStock = StockMovement::find()
                ->joinWith([
                    'item',
                    'itemSku',
                    'storageFrom' => function($query) {
                        $query->from('storage storageFrom');
                    },
                    'storageRackFrom' => function($query) {
                        $query->from('storage_rack storageRackFrom');
                    },
                    'storageTo' => function($query) {
                        $query->from('storage storageTo');
                    },
                    'storageRackTo' => function($query) {
                        $query->from('storage_rack storageRackTo');
                    },
                ])
                ->andWhere('stock_movement.tanggal BETWEEN "' . $post['tanggalFrom'] . '" AND "' . $post['tanggalTo'] . '"')
                ->andWhere('stock_movement.type="transfer"')
                ->asArray()->all();            

            $content = $this->renderPartial('_report_stock_transfer_print', [
                'modelStock' => $modelStock,
                'print' => $post['print'],
            ]);                    

            if ($post['print'] == 'pdf') {
                $footer = '
                    <table style="width:100%">
                        <tr>
                            <td style="width:50%">' . date('d-m-Y H:m:s') . ' - ' . Yii::$app->session->get('user_data')['employee']['nama'] . '</td>
                            <td style="width:50%; text-align:right">{PAGENO}</td>
                        </tr>
                    </table>
                ';
                
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
                        'SetHeader'=>[Yii::$app->name . ' - Report Stok Transfer | Tanggal ' .  Yii::$app->formatter->asDate($post['tanggalFrom']) . ' - ' . Yii::$app->formatter->asDate($post['tanggalTo'])], 
                        'SetFooter'=>[$footer],
                    ]
                ]);

                return $pdf->render(); 
            } else if ($post['print'] == 'excel') {
                header('Content-Type:   application/vnd.ms-excel; charset=utf-8');
                header('Content-Disposition: attachment; filename="' . Yii::$app->name . ' - Report Stok Keluar | Tanggal ' . Yii::$app->formatter->asDate($post['tanggalFrom']) . ' - ' . Yii::$app->formatter->asDate($post['tanggalTo']) .'.xls"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                header('Cache-Control: private',false);
                echo $content;
                exit;
            }  
        }
        
        return $this->render('report_stock_outflow', [
        
        ]);
    }
    
     public function actionReportStockInternal()
    {
        if (!empty($post = Yii::$app->request->post()) && !empty($post['tanggalFrom']) && !empty($post['tanggalTo'])) {      
            
            $storage = '';
            $storageRack = '';
            if ($post['type'] == 'outflow-indelivery') {
                $storage = 'storageFrom';
                $storageRack = 'storageRackFrom';
            } else if ($post['type'] == 'inflow-inreceive') {
                $storage = 'storageTo';
                $storageRack = 'storageRackTo';
            }
        
            $modelStock = StockMovement::find()
                ->joinWith([
                    'item',
                    'itemSku',
                    $storage,
                    $storageRack,
                ])
                ->andWhere('stock_movement.tanggal BETWEEN "' . $post['tanggalFrom'] . '" AND "' . $post['tanggalTo'] . '"')
                ->andWhere('stock_movement.type="' . $post['type'] . '"')
                ->asArray()->all();            

            $content = $this->renderPartial('_report_stock_internal_print', [
                'modelStock' => $modelStock,
                'type' => $post['type'],
                'storage' => $storage,
                'storageRack' => $storageRack,
                'print' => $post['print'],
            ]);                    

            if ($post['print'] == 'pdf') {
                $footer = '
                    <table style="width:100%">
                        <tr>
                            <td style="width:50%">' . date('d-m-Y H:m:s') . ' - ' . Yii::$app->session->get('user_data')['employee']['nama'] . '</td>
                            <td style="width:50%; text-align:right">{PAGENO}</td>
                        </tr>
                    </table>
                ';
                
                $pdf = new Pdf([
                    'mode' => Pdf::MODE_BLANK, 
                    'format' => Pdf::FORMAT_A4, 
                    'orientation' => Pdf::ORIENT_LANDSCAPE, 
                    'destination' => Pdf::DEST_BROWSER, 
                    'content' => $content,  
                    'cssFile' => '@vendor/yii2-krajee-mpdf/assets/kv-mpdf-bootstrap.min.css',
                    'cssInline' => file_get_contents(Yii::getAlias('@backend/web/css/report.css')), 
                    'options' => ['title' => Yii::$app->name],
                    'methods' => [ 
                        'SetHeader'=>[Yii::$app->name . ' - Report Stok Inflow | Tanggal ' . Yii::$app->formatter->asDate($post['tanggalFrom']) . ' - ' . Yii::$app->formatter->asDate($post['tanggalTo'])], 
                        'SetFooter'=>[$footer],
                    ]
                ]);

                return $pdf->render(); 
            } else if ($post['print'] == 'excel') {
                header('Content-Type:   application/vnd.ms-excel; charset=utf-8');
                header('Content-Disposition: attachment; filename="' . Yii::$app->name . ' - Report Stok Keluar | Tanggal ' . Yii::$app->formatter->asDate($post['tanggalFrom']) . ' - ' . Yii::$app->formatter->asDate($post['tanggalTo']) .'.xls"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                header('Cache-Control: private',false);
                echo $content;
                exit;
            }  
        }
        
        return $this->render('report_stock_internal', [
        
        ]);
    }
}
