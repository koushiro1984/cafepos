<?php

namespace backend\controllers;

use Yii;
use backend\models\SupplierDeliveryInvoice;
use backend\models\search\SupplierDeliveryInvoiceSearch;
use backend\models\SupplierDeliveryInvoiceDetail;
use backend\models\Settings;
use backend\controllers\base\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\widgets\ActiveForm;
use kartik\mpdf\Pdf;


/**
 * SupplierDeliveryInvoiceController implements the CRUD actions for SupplierDeliveryInvoice model.
 */
class SupplierDeliveryInvoiceController extends BaseController
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
                        'get-data-invoice' => ['post'],
                    ],
                ],
            ]);
    }

    /**
     * Lists all SupplierDeliveryInvoice models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SupplierDeliveryInvoiceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SupplierDeliveryInvoice model.
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
     * Creates a new SupplierDeliveryInvoice model.
     * If creation is successful, the browser will be redirected to the 'update' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SupplierDeliveryInvoice();
        $model->date = date('Y-m-d');
        
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;            
            return ActiveForm::validate($model);
        }
        
        if (($post = Yii::$app->request->post())) {
            
            if ($model->load($post)) {
                
                $transaction = Yii::$app->db->beginTransaction();
                $flag = false;
            
                if (($model->id = Settings::getTransNumber('no_sdinv')) !== false) {
                    
                    if (($flag = $model->save())) {

                        foreach ($post['trx'] as $dataTrx) {                            
                            $modelSupplierDeliveryInvoiceDetail = new SupplierDeliveryInvoiceDetail();
                            $modelSupplierDeliveryInvoiceDetail->supplier_delivery_invoice_id = $model->id;
                            $modelSupplierDeliveryInvoiceDetail->item_id = $dataTrx['item_id'];
                            $modelSupplierDeliveryInvoiceDetail->item_sku_id = $dataTrx['item_sku_id'];
                            $modelSupplierDeliveryInvoiceDetail->jumlah_item = $dataTrx['jumlah_terima'];
                            $modelSupplierDeliveryInvoiceDetail->harga_satuan = $dataTrx['harga_satuan'];
                            
                            if (!($flag = $modelSupplierDeliveryInvoiceDetail->save())) {
                                break;
                            }
                        }
                        
                        if (!empty($post['retur'])) {
                            foreach ($post['retur'] as $dataRetur) {                            
                                $modelSupplierDeliveryInvoiceDetail = new SupplierDeliveryInvoiceDetail();
                                $modelSupplierDeliveryInvoiceDetail->supplier_delivery_invoice_id = $model->id;
                                $modelSupplierDeliveryInvoiceDetail->item_id = $dataRetur['item_id'];
                                $modelSupplierDeliveryInvoiceDetail->item_sku_id = $dataRetur['item_sku_id'];
                                $modelSupplierDeliveryInvoiceDetail->jumlah_item = -1 * $dataRetur['jumlah_item'];
                                $modelSupplierDeliveryInvoiceDetail->harga_satuan = $dataRetur['harga_satuan'];

                                if (!($flag = $modelSupplierDeliveryInvoiceDetail->save())) {
                                    break;
                                }
                            }
                        }
                    }
                }
                
                if ($flag) {
                    Yii::$app->session->setFlash('status', 'success');
                    Yii::$app->session->setFlash('message1', 'Tambah Data Sukses');
                    Yii::$app->session->setFlash('message2', 'Proses tambah data sukses. Data telah berhasil disimpan.');
                    
                    $transaction->commit();

                    return $this->redirect(['index']);
                } else {
                    $model->setIsNewRecord(true);
                    
                    Yii::$app->session->setFlash('status', 'danger');
                    Yii::$app->session->setFlash('message1', 'Tambah Data Gagal');
                    Yii::$app->session->setFlash('message2', 'Proses tambah data gagal. Data gagal disimpan.');            
                    
                    $transaction->rollBack();
                }                        
            }
        }
        
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing SupplierDeliveryInvoice model.
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
     * Deletes an existing SupplierDeliveryInvoice model.
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
    
    public function actionGetDataInvoice($id) 
    {
        $model = SupplierDeliveryInvoice::find()
                ->joinWith([
                    'paymentMethod',
                    'supplierDeliveryInvoiceDetails',
                    'supplierDeliveryInvoiceDetails.item', 
                    'supplierDeliveryInvoiceDetails.itemSku'
                ])
                ->where(['supplier_delivery_invoice.id' => $id])
                ->asArray()->all();
        
        return $this->renderPartial('get_data_invoice', [
            'model' => $model[0],
        ]);
    }

    /**
     * Finds the SupplierDeliveryInvoice model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return SupplierDeliveryInvoice the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SupplierDeliveryInvoice::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionReportHutang()
    {
        $post = Yii::$app->session->getFlash('post');
            
        $modelSupplierDeliveryInvoice = SupplierDeliveryInvoice::find()
                ->joinWith([
                    'supplierDelivery',
                    'supplierDelivery.kdSupplier',
                    'supplierDeliveryInvoiceDetails',
                    'supplierDeliveryInvoiceDetails.item',
                    'supplierDeliveryInvoiceDetails.itemSku',
                    'paymentMethod',
                ])
                ->andWhere('supplier_delivery_invoice.date BETWEEN "' . $post['tanggalFrom'] . '" AND "' . $post['tanggalTo'] . '"')
                ->andWhere('payment_method.type="purchase" AND payment_method.method="hutang"')
                ->asArray()->all();
        
        $content = $this->renderPartial('_report_hutang_print', [
            'modelSupplierDeliveryInvoice' => $modelSupplierDeliveryInvoice,
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
                    'SetHeader'=>[Yii::$app->name . ' - Report Hutang | Tanggal ' .  Yii::$app->formatter->asDate($post['tanggalFrom']) . ' - ' . Yii::$app->formatter->asDate($post['tanggalTo'])], 
                    'SetFooter'=>[$footer],
                ]
            ]);

            return $pdf->render(); 
        } else if ($post['print'] == 'excel') {
            header('Content-Type:   application/vnd.ms-excel; charset=utf-8');
            header('Content-Disposition: attachment; filename="' . Yii::$app->name . ' - Report Hutang | Tanggal ' .  Yii::$app->formatter->asDate($post['tanggalFrom']) . ' - ' . Yii::$app->formatter->asDate($post['tanggalTo']) .'.xls"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Cache-Control: private',false);
            echo $content;
            exit;
        }  
    }
    
}

