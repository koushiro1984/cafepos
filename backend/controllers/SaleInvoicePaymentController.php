<?php

namespace backend\controllers;

use Yii;
use backend\models\SaleInvoicePayment;
use backend\models\search\SaleInvoicePaymentSearch;
use backend\controllers\base\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\widgets\ActiveForm;
use kartik\mpdf\Pdf;


/**
 * SaleInvoicePaymentController implements the CRUD actions for SaleInvoicePayment model.
 */
class SaleInvoicePaymentController extends BaseController
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
     * Lists all SaleInvoicePayment models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SaleInvoicePaymentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SaleInvoicePayment model.
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
     * Creates a new SaleInvoicePayment model.
     * If creation is successful, the browser will be redirected to the 'update' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SaleInvoicePayment();
        
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
     * Updates an existing SaleInvoicePayment model.
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
     * Deletes an existing SaleInvoicePayment model.
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
     * Creates a new SaleInvoicePayment model.
     * If creation is successful, the browser will be redirected to the 'update' page.
     * @return mixed
     */
    public function actionPay($id)
    {
        $model = $this->findModel($id);
        
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;            
            return ActiveForm::validate($model);
        }

        $modelSaleInvoicePayment = new SaleInvoicePayment();
        if ($modelSaleInvoicePayment->load(Yii::$app->request->post())) {
            $modelSaleInvoicePayment->parent_id = $id;
            $modelSaleInvoicePayment->jumlah_bayar = Yii::$app->request->post()['SaleInvoicePayment']['jumlah_bayar_child'];
            if ($modelSaleInvoicePayment->save()) {
                Yii::$app->session->setFlash('status', 'success');
                Yii::$app->session->setFlash('message1', 'Penerimaan Pembayaran Piutang Sukses');
                Yii::$app->session->setFlash('message2', 'Proses penerimaan pembayaran piutang sukses. Data telah berhasil disimpan.');
                
                return $this->redirect(['index']);
            } else {
                Yii::$app->session->setFlash('status', 'danger');
                Yii::$app->session->setFlash('message1', 'Penerimaan Pembayaran Piutang Gagal');
                Yii::$app->session->setFlash('message2', 'Proses  penerimaan pembayaran piutang gagal. Data gagal disimpan.');                
            }
        }
        
        return $this->render('pay', [
            'model' => $model,
        ]);
    }

    /**
     * Finds the SaleInvoicePayment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return SaleInvoicePayment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SaleInvoicePayment::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionReportPiutang()
    {
        $post = Yii::$app->session->getFlash('post');
        
        $modelSaleInvoicePayment = SaleInvoicePayment::find()
            ->select(['sale_invoice_payment.*', 'child.jumlah_bayar AS jumlah_bayar_child'])
            ->joinWith([
                'saleInvoice',
                'paymentMethod' => function($query){
                    $query->andWhere(['payment_method.method' => 'hutang']);
                },
                'saleInvoicePayments' => function($query) {
                    $query->from('sale_invoice_payment child');
                }
            ])
            ->andWhere('sale_invoice.date BETWEEN "' . $post['tanggalFrom'] . '" AND "' . $post['tanggalTo'] . '"')
            ->andWhere(['IS', 'sale_invoice_payment.parent_id', null])            
            ->asArray()->all();           
            
            
        
        $content = $this->renderPartial('_report_piutang_print', [
            'modelSaleInvoicePayment' => $modelSaleInvoicePayment,
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
                    'SetHeader'=>[Yii::$app->name . ' - Report Piutang | Tanggal ' .  Yii::$app->formatter->asDate($post['tanggalFrom']) . ' - ' . Yii::$app->formatter->asDate($post['tanggalTo'])], 
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
    
    public function actionReportPayPiutang()
    {
        $post = Yii::$app->session->getFlash('post');
        
        $modelSaleInvoicePayment = SaleInvoicePayment::find()
            ->joinWith([
                'saleInvoice',
                'paymentMethod',
                'saleInvoicePayments' => function($query) {
                    $query->from('sale_invoice_payment child');
                },
                'saleInvoicePayments.paymentMethod' => function($query) {
                    $query->from('payment_method child_payment_method');
                },
            ])
            ->andWhere('sale_invoice.date BETWEEN "' . $post['tanggalFrom'] . '" AND "' . $post['tanggalTo'] . '"')
            ->andWhere(['IS', 'sale_invoice_payment.parent_id', null])
            ->andWhere(['payment_method.method' => 'hutang'])
            ->asArray()->all();            
        
        $content = $this->renderPartial('_report_pay_piutang_print', [
            'modelSaleInvoicePayment' => $modelSaleInvoicePayment,
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
                    'SetHeader'=>[Yii::$app->name . ' - Report Pembayaran Piutang | Tanggal ' .  Yii::$app->formatter->asDate($post['tanggalFrom']) . ' - ' . Yii::$app->formatter->asDate($post['tanggalTo'])], 
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
