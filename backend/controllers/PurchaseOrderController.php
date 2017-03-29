<?php

namespace backend\controllers;

use Yii;
use backend\models\PurchaseOrder;
use backend\models\search\PurchaseOrderSearch;
use backend\models\Settings;
use backend\models\PurchaseOrderTrx;
use backend\controllers\base\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\widgets\ActiveForm;
use kartik\mpdf\Pdf;


/**
 * PurchaseOrderController implements the CRUD actions for PurchaseOrder model.
 */
class PurchaseOrderController extends BaseController
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
     * Lists all PurchaseOrder models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PurchaseOrderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PurchaseOrder model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        $modelPurchaseOrderTrxs = PurchaseOrderTrx::find()->joinWith(['item', 'itemSku'])->where(['purchase_order_id' => $id])->all();
        
        return $this->render('view', [
            'model' => $this->findModel($id),
            'modelPurchaseOrderTrxs' => $modelPurchaseOrderTrxs,
        ]);
    }

    /**
     * Creates a new PurchaseOrder model.
     * If creation is successful, the browser will be redirected to the 'update' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PurchaseOrder();
        $model->date = date('Y-m-d');
        $modelPurchaseOrderTrxs = [];        
        
        if (Yii::$app->request->isPost) {
            if (!empty(Yii::$app->request->post()['PurchaseOrderTrx'])) {
                foreach (Yii::$app->request->post()['PurchaseOrderTrx'] as $value) {
                    $modelPurchaseOrderTrxs[] = new PurchaseOrderTrx();
                }            
            }
        } else {
            $modelPurchaseOrderTrxs[] = new PurchaseOrderTrx();
        }
        
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;            
            return ActiveForm::validate($model);
        }

        $loadModelPurchaseOrder = $model->load(Yii::$app->request->post());
        $loadModelPurchaseOrderTrx = PurchaseOrderTrx::loadMultiple($modelPurchaseOrderTrxs, Yii::$app->request->post());
        if ($loadModelPurchaseOrder || $loadModelPurchaseOrderTrx) {
            
            $transaction = Yii::$app->db->beginTransaction();
            $flag = false;
            $error = '';
            
            if (($model->id = Settings::getTransNumber('no_po')) !== false) {                
                if (($flag = $model->save())) {
                    if (count($modelPurchaseOrderTrxs) > 0) {
                        foreach ($modelPurchaseOrderTrxs as $key => $modelPurchaseOrderTrx) {
                            $modelPurchaseOrderTrx->purchase_order_id = $model->id;
                            if (!($flag = $modelPurchaseOrderTrx->save())) {
                                break;
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
                $modelPurchaseOrderTrxs[] = new PurchaseOrderTrx();
                
                Yii::$app->session->setFlash('status', 'danger');
                Yii::$app->session->setFlash('message1', 'Tambah Data Gagal');
                Yii::$app->session->setFlash('message2', 'Proses tambah data gagal. Data gagal disimpan.' . $error);     
                
                $transaction->rollBack();
            }                        
        }
        
        return $this->render('create', [
            'model' => $model,
            'modelPurchaseOrderTrx' => $modelPurchaseOrderTrxs[0],
        ]);
    }

    /**
     * Updates an existing PurchaseOrder model.
     * If update is successful, the browser will be redirected to the 'update' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        
        $modelPurchaseOrderTrx = new PurchaseOrderTrx();
        $modelPurchaseOrderTrxs = [];
        $modelPurchaseOrderTrxsPost = [];
        if (Yii::$app->request->isPost) {
            if (!empty(Yii::$app->request->post()['PurchaseOrderTrxEdited'])) {
                foreach (Yii::$app->request->post()['PurchaseOrderTrxEdited'] as $value) {
                    $modelPurchaseOrderTrxsPost['PurchaseOrderTrx'][] = $value;
                    $temp = new PurchaseOrderTrx();
                    $temp->id = $value['id'] ;
                    $temp->setIsNewRecord(false);
                    $modelPurchaseOrderTrxs[] = $temp;
                }            
            }
            
            
            
            if (!empty(Yii::$app->request->post()['PurchaseOrderTrx'])) {
                foreach (Yii::$app->request->post()['PurchaseOrderTrx'] as $value) {
                    $modelPurchaseOrderTrxsPost['PurchaseOrderTrx'][] = $value;
                    $temp = new PurchaseOrderTrx();
                    $temp->setIsNewRecord(true);
                    $modelPurchaseOrderTrxs[] = $temp;
                }            
            }
            
        } else {
            $modelPurchaseOrderTrxs = PurchaseOrderTrx::find()->joinWith(['item', 'itemSku'])->where(['purchase_order_id' => $id])->all();
        }
        
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;            
            return ActiveForm::validate($model);
        }
        
        $loadModelPurchaseOrder = $model->load(Yii::$app->request->post());
        $loadModelPurchaseOrderTrx = PurchaseOrderTrx::loadMultiple($modelPurchaseOrderTrxs, $modelPurchaseOrderTrxsPost);
        if ($loadModelPurchaseOrder || $loadModelPurchaseOrderTrx) {

            $transaction = Yii::$app->db->beginTransaction();      
            $flag = true;
            $error = '';
            
            if (count($model->purchaseOrderTrxes) > 0) {
                $flag = false;
                $error = '<br>Purchase order ini sudah dibuatkan penerimaan barang.';                
            } else {                      

                $modelPurchaseOrderTrxsDelete = [];
                if (!empty(Yii::$app->request->post()['PurchaseOrderTrxDeleted'])) {
                    foreach (Yii::$app->request->post()['PurchaseOrderTrxDeleted'] as $value) {
                        $modelPurchaseOrderTrxsDelete[] = $value['id'];
                    }

                    if (PurchaseOrderTrx::deleteAll(['IN', 'id', $modelPurchaseOrderTrxsDelete]) == 0) {
                        $flag = false;                    
                    }
                }

                if ($flag) {
                    if ($model->save()) {                                         
                        foreach ($modelPurchaseOrderTrxs as $key => $modelPurchaseOrderTrx) {

                            $modelPurchaseOrderTrx->purchase_order_id = $model->id;                        
                            if (!$modelPurchaseOrderTrx->save()) {
                                $flag = false;
                                $transaction->rollBack();                            
                                break;
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
                Yii::$app->session->setFlash('message2', 'Proses update data sukses. Data telah berhasil disimpan.');
                
                return $this->redirect(['update', 'id' => $model->id]);
            } else {
                $transaction->rollBack();
                
                Yii::$app->session->setFlash('status', 'danger');
                Yii::$app->session->setFlash('message1', 'Tambah Data Gagal');
                Yii::$app->session->setFlash('message2', 'Proses update data gagal. Data gagal disimpan.' . $error);      
            }
        }
        
        return $this->render('update', [
            'model' => $model,
            'modelPurchaseOrderTrx' => $modelPurchaseOrderTrx,
            'modelPurchaseOrderTrxs' => $modelPurchaseOrderTrxs,
        ]);
    }

    /**
     * Deletes an existing PurchaseOrder model.
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
     * Finds the PurchaseOrder model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return PurchaseOrder the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PurchaseOrder::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionPrint($id) {
        $model = $this->findModel($id);
        $modelPurchaseOrderTrxs = PurchaseOrderTrx::find()->joinWith(['item', 'itemSku'])->where(['purchase_order_id' => $id])->all();
        
        $content = $this->renderPartial('print', [
            'model' => $model,
            'modelPurchaseOrderTrxs' => $modelPurchaseOrderTrxs
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
                'SetHeader'=>[Yii::$app->name . ' - Purchase Order'], 
                'SetFooter'=>['{PAGENO}'],
            ]
        ]);

        return $pdf->render();
    }
    
    public function actionReportPurchaseOrder()
    {
        if (!empty($post = Yii::$app->request->post()) && !empty($post['tanggalFrom']) && !empty($post['tanggalTo'])) {
            
            $modelPurchaseOrder = PurchaseOrderTrx::find()
                    ->joinWith([
                        'purchaseOrder',
                        'purchaseOrder.kdSupplier',
                        'item',
                        'itemSku',
                    ])
                    ->andWhere('purchase_order.date BETWEEN "' . $post['tanggalFrom'] . '" AND "' . $post['tanggalTo'] . '"')
                    ->asArray()->all();
            
            $content = '';
            $title = '';
            if ($post['reportType'] == 'detail') {
                $title = ' - Report Pembelian (PO) | Tanggal ' .  Yii::$app->formatter->asDate($post['tanggalFrom']) . ' - ' . Yii::$app->formatter->asDate($post['tanggalTo']);
                $content = $this->renderPartial('_report_purchase_order_print', [
                    'modelPurchaseOrder' => $modelPurchaseOrder,
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
        
        return $this->render('report_purchase_order', [
        
        ]);
    }
}
