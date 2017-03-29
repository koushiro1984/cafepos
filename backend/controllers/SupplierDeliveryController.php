<?php

namespace backend\controllers;

use Yii;
use backend\models\SupplierDelivery;
use backend\models\search\SupplierDeliverySearch;
use backend\models\Settings;
use backend\models\SupplierDeliveryTrx;
use backend\models\Stock;
use backend\models\StockMovement;
use backend\models\PurchaseOrderTrx;
use backend\controllers\base\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\widgets\ActiveForm;
use kartik\mpdf\Pdf;


/**
 * SupplierDeliveryController implements the CRUD actions for SupplierDelivery model.
 */
class SupplierDeliveryController extends BaseController
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
     * Lists all SupplierDelivery models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SupplierDeliverySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SupplierDelivery model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        $modelSupplierDeliveryTrxs = SupplierDeliveryTrx::find()->joinWith(['item', 'itemSku'])->where(['supplier_delivery_id' => $id])->all();
        
        return $this->render('view', [
            'model' => $this->findModel($id),
            'modelSupplierDeliveryTrxs' => $modelSupplierDeliveryTrxs,
        ]);
    }

    /**
     * Creates a new SupplierDelivery model.
     * If creation is successful, the browser will be redirected to the 'update' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SupplierDelivery();
        $model->date = date('Y-m-d');
        $modelSupplierDeliveryTrxs = [];
        
        if (Yii::$app->request->isPost) {
            if (!empty(Yii::$app->request->post()['SupplierDeliveryTrx'])) {
                foreach (Yii::$app->request->post()['SupplierDeliveryTrx'] as $value) {
                    $modelSupplierDeliveryTrxs[] = new SupplierDeliveryTrx();
                }            
            }
        } else {
            $modelSupplierDeliveryTrxs[] = new SupplierDeliveryTrx();
        }
        
        $loadModelSupplierDelivery = $model->load(Yii::$app->request->post());
        $loadModelSupplierDeliveryTrx = SupplierDeliveryTrx::loadMultiple($modelSupplierDeliveryTrxs, Yii::$app->request->post());
        
        if (Yii::$app->request->isAjax && ($loadModelSupplierDelivery || $loadModelSupplierDeliveryTrx)) {
            Yii::$app->response->format = Response::FORMAT_JSON;            
            return array_merge(ActiveForm::validate($model), ActiveForm::validateMultiple($modelSupplierDeliveryTrxs));
        }       
        
        if ($loadModelSupplierDelivery || $loadModelSupplierDeliveryTrx) {
            
            $transaction = Yii::$app->db->beginTransaction();
            $flag = false;
            $error = '';
            
            if (($model->id = Settings::getTransNumber('no_sd')) !== false) {                
                if (($flag = $model->save())) {
                    if (count($modelSupplierDeliveryTrxs) > 0) {
                        foreach ($modelSupplierDeliveryTrxs as $key => $modelSupplierDeliveryTrx) {                        
                            $attributes = [];
                            foreach ($modelSupplierDeliveryTrx->getAttributes(null, ['purchaseOrderTrx_is_closed']) as $key => $value) {
                                if (!empty($modelSupplierDeliveryTrx->$key) || $key == 'supplier_delivery_id')
                                    $attributes[] = $key;
                            }

                            if (!empty($modelSupplierDeliveryTrx->item_sku_id)) {

                                $modelSupplierDeliveryTrx->supplier_delivery_id = $model->id;
                                if (($flag = $modelSupplierDeliveryTrx->save(true, $attributes))) {
                                    $modelPurchaseOrderTrx = $modelSupplierDeliveryTrx->purchaseOrderTrx;
                                    $modelPurchaseOrderTrx->jumlah_terima += $modelSupplierDeliveryTrx->jumlah_terima;

                                    if (!empty($modelSupplierDeliveryTrx->purchaseOrderTrx_is_closed))
                                        $modelPurchaseOrderTrx->is_closed = $modelSupplierDeliveryTrx->purchaseOrderTrx_is_closed;

                                    if (($flag = $modelPurchaseOrderTrx->save())) {

                                        $flag = Stock::setStock(
                                                $modelSupplierDeliveryTrx->item_id, 
                                                $modelSupplierDeliveryTrx->item_sku_id, 
                                                $modelSupplierDeliveryTrx->storage_id, 
                                                $modelSupplierDeliveryTrx->storage_rack_id, 
                                                $modelSupplierDeliveryTrx->jumlah_terima
                                        );

                                        if ($flag) {
                                            $flag = StockMovement::setInflow(
                                                    'inflow-po', 
                                                    $modelSupplierDeliveryTrx->item_id, 
                                                    $modelSupplierDeliveryTrx->item_sku_id, 
                                                    $modelSupplierDeliveryTrx->storage_id, 
                                                    $modelSupplierDeliveryTrx->storage_rack_id, 
                                                    $modelSupplierDeliveryTrx->jumlah_terima,
                                                    date('Y-m-d'), 
                                                    $modelSupplierDeliveryTrx->supplier_delivery_id
                                            );

                                            if (!$flag) {
                                                break;
                                            }
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
                $modelSupplierDeliveryTrxs[] = new SupplierDeliveryTrx();
                
                Yii::$app->session->setFlash('status', 'danger');
                Yii::$app->session->setFlash('message1', 'Tambah Data Gagal');
                Yii::$app->session->setFlash('message2', 'Proses tambah data gagal. Data gagal disimpan.' . $error);     
                
                $transaction->rollBack();
            }                        
        }
        
        return $this->render('create', [
            'model' => $model,
            'modelSupplierDeliveryTrx' => $modelSupplierDeliveryTrxs[0],
        ]);
    }

    /**
     * Updates an existing SupplierDelivery model.
     * If update is successful, the browser will be redirected to the 'update' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        
        $modelSupplierDeliveryTrx = new SupplierDeliveryTrx();
        $modelSupplierDeliveryTrxs = [];
        $modelSupplierDeliveryTrxsPost = [];
        if (Yii::$app->request->isPost) {
            if (!empty(Yii::$app->request->post()['SupplierDeliveryTrxEdited'])) {
                foreach (Yii::$app->request->post()['SupplierDeliveryTrxEdited'] as $value) {
                    $modelSupplierDeliveryTrxsPost['SupplierDeliveryTrx'][] = $value;
                    $temp = new SupplierDeliveryTrx();
                    $temp->setIsNewRecord(false);
                    $modelSupplierDeliveryTrxs[] = $temp;
                }            
            }
            
            if (!empty(Yii::$app->request->post()['SupplierDeliveryTrx'])) {
                foreach (Yii::$app->request->post()['SupplierDeliveryTrx'] as $value) {
                    $modelSupplierDeliveryTrxsPost['SupplierDeliveryTrx'][] = $value;
                    $temp = new SupplierDeliveryTrx();
                    $temp->setIsNewRecord(true);
                    $modelSupplierDeliveryTrxs[] = $temp;
                }            
            }
            
        } else {
            $modelSupplierDeliveryTrxs = SupplierDeliveryTrx::find()->joinWith(['item', 'itemSku'])->where(['supplier_delivery_id' => $id])->all();
        }                
        
        $loadModelSupplierDelivery = $model->load(Yii::$app->request->post());
        $loadModelSupplierDeliveryTrx = SupplierDeliveryTrx::loadMultiple($modelSupplierDeliveryTrxs, $modelSupplierDeliveryTrxsPost);        
        
        if (Yii::$app->request->isAjax && ($loadModelSupplierDelivery || $loadModelSupplierDeliveryTrx)) {
            Yii::$app->response->format = Response::FORMAT_JSON;            
            return array_merge(ActiveForm::validate($model), ActiveForm::validateMultiple($modelSupplierDeliveryTrxs));
        }
                
        if ($loadModelSupplierDelivery || $loadModelSupplierDeliveryTrx) {

            $transaction = Yii::$app->db->beginTransaction();
            $flag = true;
            $error = '';
            
            if (count($model->supplierDeliveryInvoices) > 0) {
                $flag = false;
                $error = '<br>Penerimaan item ini sudah dibuatkan invoice.';
            } else {
            
                $modelSupplierDeliveryTrxsDelete = [];
                if (!empty(Yii::$app->request->post()['SupplierDeliveryTrxDeleted'])) {
                    foreach (Yii::$app->request->post()['SupplierDeliveryTrxDeleted'] as $value) {
                        $flag = Stock::setStock(
                                $value['item_id'],
                                $value['item_sku_id'], 
                                $value['storage_id'], 
                                $value['storage_rack_id'], 
                                -1 * $value['jumlah_terima']
                        ); 
                        
                        if ($flag) {
                            $flag = StockMovement::setOutflow(
                                    'inflow-po-delete', 
                                    $value['item_id'],
                                    $value['item_sku_id'], 
                                    $value['storage_id'], 
                                    $value['storage_rack_id'], 
                                    $value['jumlah_terima'],
                                    date('Y-m-d'), 
                                    $model->id
                            );
                            
                            if ($flag) {
                                $modelPurchaseOrderTrx = PurchaseOrderTrx::findOne($value['purchase_order_trx_id']);
                                $modelPurchaseOrderTrx->jumlah_terima -= $value['jumlah_terima'];
                                
                                if (!($flag = $modelPurchaseOrderTrx->save())) {                                    
                                    break;
                                }
                            } else {
                                break;
                            }
                        } else {
                            break;
                        }
                        
                        $modelSupplierDeliveryTrxsDelete[] = $value['id'];
                    }

                    if ($flag) {
                        if (SupplierDeliveryTrx::deleteAll(['IN', 'id', $modelSupplierDeliveryTrxsDelete]) == 0) {
                            $flag = false;                    
                        }
                    }
                }

                if ($flag) {
                    if ($model->save()) {
                        foreach ($modelSupplierDeliveryTrxs as $key => $modelSupplierDeliveryTrx) {
                            $attributes = [];
                            foreach ($modelSupplierDeliveryTrx->getAttributes(null, ['purchaseOrderTrx_is_closed']) as $key => $value) {
                                if (!empty($modelSupplierDeliveryTrx->$key) || $key == 'supplier_delivery_id')
                                    $attributes[] = $key;
                            }

                            if (!empty($modelSupplierDeliveryTrx->item_sku_id) && $modelSupplierDeliveryTrx->isNewRecord) {

                                $modelSupplierDeliveryTrx->supplier_delivery_id = $model->id;                                                    
                                if (($flag = $modelSupplierDeliveryTrx->save(true, $attributes))) {
                                    $modelPurchaseOrderTrx = $modelSupplierDeliveryTrx->purchaseOrderTrx;
                                    $modelPurchaseOrderTrx->jumlah_terima += $modelSupplierDeliveryTrx->jumlah_terima;

                                    if (!empty($modelSupplierDeliveryTrx->purchaseOrderTrx_is_closed))
                                        $modelPurchaseOrderTrx->is_closed = $modelSupplierDeliveryTrx->purchaseOrderTrx_is_closed;

                                    if (($flag = $modelPurchaseOrderTrx->save())) {

                                        $flag = Stock::setStock(
                                                $modelSupplierDeliveryTrx->item_id, 
                                                $modelSupplierDeliveryTrx->item_sku_id, 
                                                $modelSupplierDeliveryTrx->storage_id, 
                                                $modelSupplierDeliveryTrx->storage_rack_id, 
                                                $modelSupplierDeliveryTrx->jumlah_terima
                                        );

                                        if ($flag) {
                                            $flag = StockMovement::setInflow(
                                                    'inflow-po', 
                                                    $modelSupplierDeliveryTrx->item_id, 
                                                    $modelSupplierDeliveryTrx->item_sku_id, 
                                                    $modelSupplierDeliveryTrx->storage_id, 
                                                    $modelSupplierDeliveryTrx->storage_rack_id, 
                                                    $modelSupplierDeliveryTrx->jumlah_terima,
                                                    date('Y-m-d'), 
                                                    $modelSupplierDeliveryTrx->supplier_delivery_id
                                            );

                                            if (!$flag) {
                                                break;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    } else {
                        $flag = false;
                    }
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
                Yii::$app->session->setFlash('message2', 'Proses tambah data gagal. Data gagal disimpan.' . $error);                
            }
        }
        
        return $this->render('update', [
            'model' => $model,
            'modelSupplierDeliveryTrx' => $modelSupplierDeliveryTrx,
            'modelSupplierDeliveryTrxs' => $modelSupplierDeliveryTrxs,
        ]);
    }

    /**
     * Deletes an existing SupplierDelivery model.
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
     * Finds the SupplierDelivery model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return SupplierDelivery the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SupplierDelivery::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionPrint($id) {
        $model = $this->findModel($id);
        $modelSupplierDeliveryTrxs = SupplierDeliveryTrx::find()->joinWith(['item', 'itemSku'])->where(['supplier_delivery_id' => $id])->all();
        
        $content = $this->renderPartial('print', [
            'model' => $model,
            'modelSupplierDeliveryTrxs' => $modelSupplierDeliveryTrxs
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
                'SetHeader'=>[Yii::$app->name . ' - Penerimaan Item'], 
                'SetFooter'=>['{PAGENO}'],
            ]
        ]);

        return $pdf->render();
    }
    
    public function actionReportSupplierDelivery()
    {
        if (!empty($post = Yii::$app->request->post()) && !empty($post['tanggalFrom']) && !empty($post['tanggalTo'])) {
            
            $modelSupplierDelivery = SupplierDeliveryTrx::find()
                    ->joinWith([
                        'supplierDelivery',
                        'supplierDelivery.kdSupplier',
                        'item',
                        'itemSku',
                        'storage',
                        'storageRack',
                    ])
                    ->andWhere('supplier_delivery.date BETWEEN "' . $post['tanggalFrom'] . '" AND "' . $post['tanggalTo'] . '"')
                    ->asArray()->all();
            
            $content = '';
            $title = '';
            if ($post['reportType'] == 'detail') {
                $title = ' - Report Penerimaan Barang | Tanggal ' .  Yii::$app->formatter->asDate($post['tanggalFrom']) . ' - ' . Yii::$app->formatter->asDate($post['tanggalTo']);
                $content = $this->renderPartial('_report_supplier_delivery_print', [
                    'modelSupplierDelivery' => $modelSupplierDelivery,
                    'print' => $post['print'],
                ]);
            }            

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
                        'SetHeader'=>[Yii::$app->name . $title], 
                        'SetFooter'=>[$footer],
                    ]
                ]);

                return $pdf->render(); 
            } else if ($post['print'] == 'excel') {
                header('Content-Type:   application/vnd.ms-excel; charset=utf-8');
                header('Content-Disposition: attachment; filename="' . Yii::$app->name . $title .'.xls"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                header('Cache-Control: private',false);
                echo $content;
                exit;
            }
        }
        
        return $this->render('report_supplier_delivery', [
        
        ]);
    }
}
