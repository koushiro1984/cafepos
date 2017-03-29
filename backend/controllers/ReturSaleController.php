<?php

namespace backend\controllers;

use Yii;
use backend\models\ReturSale;
use backend\models\search\ReturSaleSearch;
use backend\controllers\base\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\helpers\Json;
use kartik\mpdf\Pdf;


/**
 * ReturSaleController implements the CRUD actions for ReturSale model.
 */
class ReturSaleController extends BaseController
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
                        'update-jumlah' => ['post'],
                    ],
                ],
            ]);
    }

    /**
     * Lists all ReturSale models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ReturSaleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ReturSale model.
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
     * Creates a new ReturSale model.
     * If creation is successful, the browser will be redirected to the 'update' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ReturSale();
        
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
     * Updates an existing ReturSale model.
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
     * Deletes an existing ReturSale model.
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
     * Update jumlah retur sale.
     * @param string $id
     * @return mixed
     */
    public function actionUpdateJumlah()
    {
        $output = '';
        $messages = '';
            
        if (!empty(($post = Yii::$app->request->post()))) {
            
            if ($post['returSaleId'] == 0) {
                $modelReturSale = new ReturSale();
                $modelReturSale->date = date('Y-m-d');
                $modelReturSale->sale_invoice_detail_id = $post['saleInvoiceDetailId'];                
            } else {
                $modelReturSale = ReturSale::findOne($post['returSaleId']);
            }
                            
            $modelReturSale->menu_id = $post['menuId'];
            $modelReturSale->jumlah = $post['value']['jumlah'];
            $modelReturSale->discount_type = $post['discountType'];
            $modelReturSale->discount = $post['discount'];
            $modelReturSale->harga = $post['harga'];
            $modelReturSale->keterangan = $post['value']['keterangan'];
            
            if ($post['value']['jumlah'] > $post['jumlah']) {
                $modelReturSale->addError('jumlah', 'Jumlah retur tidak boleh melebihi jumlah penjualan');
            } else {            
                $modelReturSale->save();
            }
            
            if (empty($modelReturSale->errors['jumlah'])) {
                $output =  $modelReturSale->jumlah;
            } else {
                foreach ($modelReturSale->errors['jumlah'] as $value) {
                    $messages .= $value . ' ';
                }
            }
        }
        
        return Json::encode(['output' => $output, 'message' => $messages]);
    }

    /**
     * Finds the ReturSale model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return ReturSale the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ReturSale::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionReportReturSale()
    {
        if (!empty($post = Yii::$app->request->post()) && !empty($post['tanggalFrom']) && !empty($post['tanggalTo'])) {
            
            $modelReturSale = ReturSale::find()
                    ->joinWith([
                        'menu',
                        'saleInvoiceDetail',
                        'saleInvoiceDetail.saleInvoice',
                        'saleInvoiceDetail.saleInvoice.mtableSession',
                        'saleInvoiceDetail.saleInvoice.mtableSession.mtable',
                    ])
                    ->andWhere('sale_invoice.date BETWEEN "' . $post['tanggalFrom'] . '" AND "' . $post['tanggalTo'] . '"')
                    ->asArray()->all();
            
            $content = '';
            $title = '';
            if ($post['reportType'] == 'detail') {
                $title = ' - Report Retur Penjualan Detail | Tanggal ' .  Yii::$app->formatter->asDate($post['tanggalFrom']) . ' - ' . Yii::$app->formatter->asDate($post['tanggalTo']);
                $content = $this->renderPartial('_report_retur_sale_print', [
                    'modelReturSale' => $modelReturSale,
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
        
        return $this->render('report_retur_sale', [
        
        ]);
    }
}
