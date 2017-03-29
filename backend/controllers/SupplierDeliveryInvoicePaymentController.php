<?php

namespace backend\controllers;

use Yii;
use backend\models\SupplierDeliveryInvoicePayment;
use backend\models\search\SupplierDeliveryInvoicePaymentSearch;
use backend\models\SupplierDeliveryInvoice;
use backend\controllers\base\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\widgets\ActiveForm;
use kartik\mpdf\Pdf;


/**
 * SupplierDeliveryInvoicePaymentController implements the CRUD actions for SupplierDeliveryInvoicePayment model.
 */
class SupplierDeliveryInvoicePaymentController extends BaseController
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
     * Lists all SupplierDeliveryInvoicePayment models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SupplierDeliveryInvoicePaymentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SupplierDeliveryInvoicePayment model.
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
     * Creates a new SupplierDeliveryInvoicePayment model.
     * If creation is successful, the browser will be redirected to the 'update' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SupplierDeliveryInvoicePayment();
        
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;            
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            $flag = false;
            
            if (($flag = $model->save())) {
                $modelSupplierDeliveryInvoice = SupplierDeliveryInvoice::findOne($model->supplier_delivery_invoice_id);
                $modelSupplierDeliveryInvoice->jumlah_bayar = $modelSupplierDeliveryInvoice->jumlah_bayar + $model->jumlah_bayar;
                $flag = $modelSupplierDeliveryInvoice->save();
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
        
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing SupplierDeliveryInvoicePayment model.
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
     * Deletes an existing SupplierDeliveryInvoicePayment model.
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
     * Finds the SupplierDeliveryInvoicePayment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return SupplierDeliveryInvoicePayment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SupplierDeliveryInvoicePayment::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionReportPayHutang()
    {
        $post = Yii::$app->session->getFlash('post');
            
        $modelSupplierDeliveryInvoicePayment = SupplierDeliveryInvoicePayment::find()
                ->joinWith([
                    'supplierDeliveryInvoice',
                    'supplierDeliveryInvoice.supplierDelivery.kdSupplier',
                    'supplierDeliveryInvoice.paymentMethod',
                ])
                ->andWhere('supplier_delivery_invoice.date BETWEEN "' . $post['tanggalFrom'] . '" AND "' . $post['tanggalTo'] . '"')
                ->andWhere('payment_method.type="purchase" AND payment_method.method="hutang"')
                ->asArray()->all();
        
        $content = $this->renderPartial('_report_hutang_pay_print', [
            'modelSupplierDeliveryInvoicePayment' => $modelSupplierDeliveryInvoicePayment,
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
                    'SetHeader'=>[Yii::$app->name . ' - Report Pembayaran Hutang | Tanggal ' .  Yii::$app->formatter->asDate($post['tanggalFrom']) . ' - ' . Yii::$app->formatter->asDate($post['tanggalTo'])], 
                    'SetFooter'=>[$footer],
                ]
            ]);

            return $pdf->render(); 
        } else if ($post['print'] == 'excel') {
            header('Content-Type:   application/vnd.ms-excel; charset=utf-8');
            header('Content-Disposition: attachment; filename="' . Yii::$app->name . ' - Report Piutang | Tanggal ' .  Yii::$app->formatter->asDate($post['tanggalFrom']) . ' - ' . Yii::$app->formatter->asDate($post['tanggalTo']) .'.xls"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Cache-Control: private',false);
            echo $content;
            exit;
        }
    }
}
