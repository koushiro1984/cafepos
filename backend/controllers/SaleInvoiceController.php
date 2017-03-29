<?php

namespace backend\controllers;

use Yii;
use backend\models\SaleInvoice;
use backend\models\search\SaleInvoiceSearch;
use backend\models\PaymentMethod;
use backend\models\SaldoKasir;
use backend\models\User;
use backend\controllers\base\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\widgets\ActiveForm;
use kartik\mpdf\Pdf;


/**
 * SaleInvoiceController implements the CRUD actions for SaleInvoice model.
 */
class SaleInvoiceController extends BaseController
{
    private $params = [];
    
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
     * Lists all SaleInvoice models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SaleInvoiceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SaleInvoice model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        $modelSaleInvoice = SaleInvoice::find()
                ->joinWith(['saleInvoiceDetails', 'saleInvoiceDetails.returSale', 'saleInvoiceDetails.menu'])
                ->where(['sale_invoice.id' => $id])
                ->one();       
        
        return $this->render('view', [
            'model' => $modelSaleInvoice,
        ]);
    }

    /**
     * Creates a new SaleInvoice model.
     * If creation is successful, the browser will be redirected to the 'update' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SaleInvoice();
        
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
     * Updates an existing SaleInvoice model.
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
     * Deletes an existing SaleInvoice model.
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
     * Finds the SaleInvoice model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return SaleInvoice the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SaleInvoice::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionReportSale()
    {
        if (!empty($post = Yii::$app->request->post()) && !empty($post['tanggalFrom']) && !empty($post['tanggalTo'])) {
            
            $modelSaleInvoice = null;
                    
            $modelSaleInvoice = SaleInvoice::find()
                    ->joinWith([
                        'saleInvoiceDetails' => function($query) {
                            $query->andWhere(['sale_invoice_detail.is_free_menu' => false])
                                ->andWhere(['sale_invoice_detail.is_void' => false]);
                        },
                        'saleInvoiceDetails.menu',
                        'saleInvoicePayments',
                        'saleInvoicePayments.paymentMethod',
                    ])
                    ->andWhere('sale_invoice.date BETWEEN "' . $post['tanggalFrom'] . '" AND "' . $post['tanggalTo'] . '"')
                    ->asArray()->all();
            
            $content = '';
            $title = '';
            if ($post['reportType'] == 'detail') {
                $title = ' - Report Penjualan Detail | Tanggal ' .  Yii::$app->formatter->asDate($post['tanggalFrom']) . ' - ' . Yii::$app->formatter->asDate($post['tanggalTo']);
                $content = $this->renderPartial('_report_sale_print', [
                    'modelSaleInvoice' => $modelSaleInvoice,
                    'print' => $post['print'],
                ]);
            } elseif ($post['reportType'] == 'summary') {
                $title = ' - Report Penjualan Summary | Tanggal ' .  Yii::$app->formatter->asDate($post['tanggalFrom']) . ' - ' . Yii::$app->formatter->asDate($post['tanggalTo']);
                $content = $this->renderPartial('_report_sale_print_sum', [
                    'modelSaleInvoice' => $modelSaleInvoice,
                    'print' => $post['print'],
                ]);
            } elseif ($post['reportType'] == 'terlaris') {                                     
                
                $title = ' - Report Penjualan Terlaris | Tanggal ' .  Yii::$app->formatter->asDate($post['tanggalFrom']) . ' - ' . Yii::$app->formatter->asDate($post['tanggalTo']);
                $content = $this->renderPartial('_report_sale_print_terlaris', [
                    'modelSaleInvoice' => $modelSaleInvoice,
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
                    'orientation' => Pdf::ORIENT_PORTRAIT, 
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
        
        return $this->render('report_sale', [
        
        ]);
    }
    
    public function actionReportCashier()
    {
        if (!empty($post = Yii::$app->request->post()) && !empty($post['tanggalFrom']) && !empty($post['tanggalTo'])) {                        
            
            $content = '';
            $title = '';
            if ($post['reportType'] == 'detail') {                
                
                $modelSaleInvoice = SaleInvoice::find()
                    ->joinWith([                        
                        'saleInvoiceDetails.returSale',
                        'saleInvoicePayments',
                        'saleInvoicePayments.paymentMethod',
                    ])
                    ->andWhere('sale_invoice.date BETWEEN "' . $post['tanggalFrom'] . '" AND "' . $post['tanggalTo'] . '"')
                    //->andWhere('payment_method.type="sale"')
                    ->andWhere('sale_invoice_payment.parent_id IS NULL')
                    ->orderBy('sale_invoice.id ASC')
                    ->asArray()->all();
                
                $title = ' - Report Penjualan Detail | Tanggal ' .  Yii::$app->formatter->asDate($post['tanggalFrom']) . ' - ' . Yii::$app->formatter->asDate($post['tanggalTo']);
                $orientation = Pdf::ORIENT_LANDSCAPE;

                $content = $this->renderPartial('_report_cashier_det_print', [
                    'modelSaleInvoice' => $modelSaleInvoice,
                    'print' => $post['print'],
                ]);
            } elseif ($post['reportType'] == 'summary') {                
                
                $modelPaymentMethod = PaymentMethod::find()  
                        ->andWhere('payment_method.type="sale"')
                        ->orderBy('payment_method.nama_payment')
                        ->asArray()->all();
                            
                $modelSaleInvoice = SaleInvoice::find()
                    ->joinWith([
                        'mtableSession',
                        'saleInvoiceDetails.returSale',
                        'saleInvoicePayments',
                        'saleInvoicePayments.paymentMethod',
                    ])
                    ->andWhere('sale_invoice.date BETWEEN "' . $post['tanggalFrom'] . '" AND "' . $post['tanggalTo'] . '"')
                    //->andWhere('payment_method.type="sale"')
                    ->andWhere('sale_invoice_payment.parent_id IS NULL')
                    ->orderBy('sale_invoice.id ASC')
                    ->asArray()->all();
                
                $title = ' - Report Penjualan Detail | Tanggal ' .  Yii::$app->formatter->asDate($post['tanggalFrom']) . ' - ' . Yii::$app->formatter->asDate($post['tanggalTo']);
                $orientation = Pdf::ORIENT_PORTRAIT;
                                
                $content = $this->renderPartial('_report_cashier_print', [
                    'modelPaymentMethod' => $modelPaymentMethod,
                    'modelSaleInvoice' => $modelSaleInvoice,
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
                    'orientation' => $orientation, 
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
        
        return $this->render('report_cashier', [
        
        ]);
    }
    
    public function actionReportCashierDaily()
    {
        $modelSaleInvoice = null;
        $modelSaldoKasir = null;
        
        if (!empty($post = Yii::$app->request->post()) && !empty($post['tanggalFrom']) && !empty($post['tanggalTo'])) {                        

            $modelSaleInvoice = SaleInvoice::find()
                ->joinWith([
                    'saleInvoiceDetails.menu',
                    'saleInvoiceDetails.menu.menuCategory',
                    'saleInvoiceDetails.returSale',
                    'saleInvoicePayments',
                    'saleInvoicePayments.paymentMethod',
                ])
                ->andWhere('sale_invoice.date BETWEEN "' . $post['tanggalFrom'] . '" AND "' . $post['tanggalTo'] . '"')
                //->andWhere('payment_method.type="sale"')
                ->orderBy('menu.nama_menu')
                ->asArray()->all();           
            
            $modelSaldoKasir = SaldoKasir::find()
                    ->joinWith([
                        'shift'
                    ])
                    ->andWhere(['saldo_kasir.date' => $post['tanggalFrom']])
                    ->andWhere('"' . date('H:i:s') . '" BETWEEN shift.start_time AND shift.end_time')
                    ->asArray()->one();   
            
            if ($post['print'] != 'print') {
                
                $content = $this->renderPartial('_report_cashier_daily_print', [
                    'modelSaleInvoice' => $modelSaleInvoice,
                    'modelSaldoKasir' => $modelSaldoKasir,
                    'tanggal' => !empty($post['tanggalFrom']) ? $post['tanggalFrom'] : '',
                    'print' => $post['print'],
                ]);
                
                $title = ' - Report Pendapatan Kasir Harian | Tanggal ' .  Yii::$app->formatter->asDate($post['tanggalFrom']) . ' - ' . Yii::$app->formatter->asDate($post['tanggalTo']);
                
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
        }        
        
        return $this->render('report_cashier_daily', [
            'modelSaleInvoice' => $modelSaleInvoice,
            'modelSaldoKasir' => $modelSaldoKasir,
            'tanggal' => !empty($post['tanggalFrom']) ? $post['tanggalFrom'] : '',
            'print' => !empty($post['print']) ? $post['print'] : '',
        ]);
    }
    
    public function actionReportRekapDaily()
    {
        $modelSaleInvoice = null;
        
        if (!empty($post = Yii::$app->request->post()) && !empty($post['tanggalFrom']) && !empty($post['tanggalTo'])) {                        

            $modelSaleInvoice = SaleInvoice::find()
                ->joinWith([
                    'saleInvoiceDetails.menu',
                    'saleInvoiceDetails.menu.menuCategory',
                    'saleInvoiceDetails.menu.menuCategory.parentCategory' => function($query) {
                        $query->from('menu_category parentCategory');
                    },
                    'saleInvoiceDetails.returSale',
                    'saleInvoicePayments',
                    'saleInvoicePayments.paymentMethod',
                    'mtableSession',
                ])
                ->andWhere('sale_invoice.date BETWEEN "' . $post['tanggalFrom'] . '" AND "' . $post['tanggalTo'] . '"')
                //->andWhere('payment_method.type="sale"')
                ->orderBy('parentCategory.nama_category')
                ->asArray()->all();   
                    
            if ($post['print'] != 'print') {
                
                $content = $this->renderPartial('_report_rekap_daily_print', [
                    'modelSaleInvoice' => $modelSaleInvoice,
                    'tanggal' => !empty($post['tanggalFrom']) ? $post['tanggalFrom'] : '',
                    'print' => $post['print'],
                ]);
                
                $title = ' - Report Rekap Penjualan Harian | Tanggal ' .  Yii::$app->formatter->asDate($post['tanggalFrom']) . ' - ' . Yii::$app->formatter->asDate($post['tanggalTo']);
                
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
        }       
        
        return $this->render('report_rekap_daily', [
            'modelSaleInvoice' => $modelSaleInvoice,
            'tanggal' => !empty($post['tanggalFrom']) ? $post['tanggalFrom'] : '',
            'print' => !empty($post['print']) ? $post['print'] : '',
        ]);
    }
    
    public function actionReportInvoiceDaily()
    {
        $modelSaleInvoice = null;
        $modelSaldoKasir = null;
        $modelUser = null;
        $kasirAll = true;
        
        if (!empty($post = Yii::$app->request->post()) && !empty($post['tanggalFrom']) && !empty($post['tanggalTo'])) {                        

            $modelSaleInvoice = SaleInvoice::find()
                ->joinWith([
                    'saleInvoiceDetails.menu',
                    'saleInvoiceDetails.returSale',
                    'saleInvoicePayments',
                    'saleInvoicePayments.paymentMethod',
                ])
                ->andWhere('sale_invoice.date BETWEEN "' . $post['tanggalFrom'] . '" AND "' . $post['tanggalTo'] . '"');
                //->andWhere('payment_method.type="sale"')
                    
            if (empty($post['kasirAll'])) {
                $modelSaleInvoice->andWhere(['user_operator' => $post['kasir']]);
                $modelUser = User::findOne($post['kasir']);
                $kasirAll = false;
            }
            
            $modelSaleInvoice = $modelSaleInvoice->asArray()->all();           
            
            $modelSaldoKasir = SaldoKasir::find()
                    ->joinWith([
                        'shift'
                    ])
                    ->andWhere(['saldo_kasir.date' => $post['tanggalFrom']])
                    ->andWhere('"' . date('H:i:s') . '" BETWEEN shift.start_time AND shift.end_time')
                    ->asArray()->one();     
            
            if ($post['print'] != 'print') {
                
                $content = $this->renderPartial('_report_invoice_daily_print', [
                    'modelSaleInvoice' => $modelSaleInvoice,
                    'modelSaldoKasir' => $modelSaldoKasir,
                    'modelUser' => $modelUser,
                    'tanggal' => !empty($post['tanggalFrom']) ? $post['tanggalFrom'] : '',
                    'kasirAll' => $kasirAll,
                    'print' => !empty($post['print']) ? $post['print'] : '',
                ]);
                
                $title = ' - Report Faktur Kasir Harian | Tanggal ' .  Yii::$app->formatter->asDate($post['tanggalFrom']) . ' - ' . Yii::$app->formatter->asDate($post['tanggalTo']);
                
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
        }        
        
        return $this->render('report_invoice_daily', [
            'modelSaleInvoice' => $modelSaleInvoice,
            'modelSaldoKasir' => $modelSaldoKasir,
            'modelUser' => $modelUser,
            'tanggal' => !empty($post['tanggalFrom']) ? $post['tanggalFrom'] : '',
            'kasirAll' => $kasirAll,
            'print' => !empty($post['print']) ? $post['print'] : '',
        ]);
    }
        
}
