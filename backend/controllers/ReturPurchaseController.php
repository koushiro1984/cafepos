<?php

namespace backend\controllers;

use Yii;
use backend\models\ReturPurchase;
use backend\models\search\ReturPurchaseSearch;
use backend\models\Settings;
use backend\models\ReturPurchaseTrx;
use backend\models\Stock;
use backend\models\StockMovement;
use backend\controllers\base\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\widgets\ActiveForm;
use kartik\mpdf\Pdf;


/**
 * ReturPurchaseController implements the CRUD actions for ReturPurchase model.
 */
class ReturPurchaseController extends BaseController
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
     * Lists all ReturPurchase models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ReturPurchaseSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ReturPurchase model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        $modelReturPurchaseTrxs = ReturPurchaseTrx::find()->joinWith(['item', 'itemSku'])->where(['retur_purchase_id' => $id])->all();
        
        return $this->render('view', [
            'model' => $this->findModel($id),
            'modelReturPurchaseTrxs' => $modelReturPurchaseTrxs,
        ]);
    }

    /**
     * Creates a new ReturPurchase model.
     * If creation is successful, the browser will be redirected to the 'update' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ReturPurchase();
        $model->date = date('Y-m-d');
        $modelReturPurchaseTrxs = [];
        
        if (Yii::$app->request->isPost) {
            if (!empty(Yii::$app->request->post()['ReturPurchaseTrx'])) {
                foreach (Yii::$app->request->post()['ReturPurchaseTrx'] as $value) {
                    $modelReturPurchaseTrxs[] = new ReturPurchaseTrx();
                }            
            }
        } else {
            $modelReturPurchaseTrxs[] = new ReturPurchaseTrx();
        }
        
        $loadModelReturPurchase = $model->load(Yii::$app->request->post());
        $loadModelReturPurchaseTrx = ReturPurchaseTrx::loadMultiple($modelReturPurchaseTrxs, Yii::$app->request->post());
        
        if (Yii::$app->request->isAjax && ($loadModelReturPurchase || $loadModelReturPurchaseTrx)) {
            Yii::$app->response->format = Response::FORMAT_JSON;            
            return array_merge(ActiveForm::validate($model), ActiveForm::validateMultiple($modelReturPurchaseTrxs));
        }       
        
        if ($loadModelReturPurchase || $loadModelReturPurchaseTrx) {
            
            $transaction = Yii::$app->db->beginTransaction();
            $flag = false;
            
            if (($model->id = Settings::getTransNumber('no_rp')) !== false) {                
                if (($flag = $model->save())) {
                    if (count($modelReturPurchaseTrxs) > 0) {
                        foreach ($modelReturPurchaseTrxs as $key => $modelReturPurchaseTrx) {
                            if (!empty($modelReturPurchaseTrx->item_sku_id)) {

                                $modelReturPurchaseTrx->retur_purchase_id = $model->id;                                                    
                                if (($flag = $modelReturPurchaseTrx->save())) {

                                    $flag = Stock::setStock(
                                            $modelReturPurchaseTrx->item_id, 
                                            $modelReturPurchaseTrx->item_sku_id, 
                                            $modelReturPurchaseTrx->storage_id, 
                                            $modelReturPurchaseTrx->storage_rack_id, 
                                            -1 * $modelReturPurchaseTrx->jumlah_item
                                    );

                                    if ($flag) {
                                        $flag = StockMovement::setOutflow(
                                                'outflow-rp', 
                                                $modelReturPurchaseTrx->item_id, 
                                                $modelReturPurchaseTrx->item_sku_id, 
                                                $modelReturPurchaseTrx->storage_id, 
                                                $modelReturPurchaseTrx->storage_rack_id, 
                                                $modelReturPurchaseTrx->jumlah_item,
                                                date('Y-m-d'), 
                                                $modelReturPurchaseTrx->retur_purchase_id
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
                $modelReturPurchaseTrxs[] = new ReturPurchaseTrx();
                
                Yii::$app->session->setFlash('status', 'danger');
                Yii::$app->session->setFlash('message1', 'Tambah Data Gagal');
                Yii::$app->session->setFlash('message2', 'Proses tambah data gagal. Data gagal disimpan.' . $error);     
                
                $transaction->rollBack();
            }                        
        }
        
        return $this->render('create', [
            'model' => $model,
            'modelReturPurchaseTrx' => $modelReturPurchaseTrxs[0],
        ]);
    }

    /**
     * Updates an existing ReturPurchase model.
     * If update is successful, the browser will be redirected to the 'update' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        
        $modelReturPurchaseTrx = new ReturPurchaseTrx();
        $modelReturPurchaseTrxs = [];
        $modelReturPurchaseTrxsPost = [];
        if (Yii::$app->request->isPost) {
            if (!empty(Yii::$app->request->post()['ReturPurchaseTrxEdited'])) {
                foreach (Yii::$app->request->post()['ReturPurchaseTrxEdited'] as $value) {
                    $modelReturPurchaseTrxsPost['ReturPurchaseTrx'][] = $value;
                    $temp = new ReturPurchaseTrx();
                    $temp->setIsNewRecord(false);
                    $modelReturPurchaseTrxs[] = $temp;
                }            
            }
            
            if (!empty(Yii::$app->request->post()['ReturPurchaseTrx'])) {
                foreach (Yii::$app->request->post()['ReturPurchaseTrx'] as $value) {
                    $modelReturPurchaseTrxsPost['ReturPurchaseTrx'][] = $value;
                    $temp = new ReturPurchaseTrx();
                    $temp->setIsNewRecord(true);
                    $modelReturPurchaseTrxs[] = $temp;
                }            
            }
            
        } else {
            $modelReturPurchaseTrxs = ReturPurchaseTrx::find()->joinWith(['item', 'itemSku'])->where(['retur_purchase_id' => $id])->all();
        }
        
        $loadModelReturPurchase = $model->load(Yii::$app->request->post());
        $loadModelReturPurchaseTrx = ReturPurchaseTrx::loadMultiple($modelReturPurchaseTrxs, $modelReturPurchaseTrxsPost);
        
        if (Yii::$app->request->isAjax && ($loadModelReturPurchase || $loadModelReturPurchaseTrx)) {
            Yii::$app->response->format = Response::FORMAT_JSON;            
            return array_merge(ActiveForm::validate($model), ActiveForm::validateMultiple($modelReturPurchaseTrxs));
        }
        
        if ($loadModelReturPurchase || $loadModelReturPurchaseTrx) {

            $transaction = Yii::$app->db->beginTransaction();
            $flag = true;
            
            $modelReturPurchaseTrxsDelete = [];
            if (!empty(Yii::$app->request->post()['ReturPurchaseTrxDeleted'])) {
                foreach (Yii::$app->request->post()['ReturPurchaseTrxDeleted'] as $value) {
                    $flag = Stock::setStock(
                            $value['item_id'],
                            $value['item_sku_id'], 
                            $value['storage_id'], 
                            $value['storage_rack_id'], 
                            $value['jumlah_item']
                    ); 

                    if ($flag) {
                        $flag = StockMovement::setOutflow(
                                'outflow-rp-delete', 
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
                    
                    $modelReturPurchaseTrxsDelete[] = $value['id'];
                }
                
                if (ReturPurchaseTrx::deleteAll(['IN', 'id', $modelReturPurchaseTrxsDelete]) == 0) {
                    $flag = false;                    
                }
            }
            
            if ($flag) {
                if ($model->save()) {                                         
                    foreach ($modelReturPurchaseTrxs as $key => $modelReturPurchaseTrx) {
                                            
                        if (!empty($modelReturPurchaseTrx->item_sku_id) && $modelReturPurchaseTrx->isNewRecord) {
                        
                            $modelReturPurchaseTrx->retur_purchase_id = $model->id;                                                    
                            if (($flag = $modelReturPurchaseTrx->save())) {

                                $flag = Stock::setStock(
                                        $modelReturPurchaseTrx->item_id, 
                                        $modelReturPurchaseTrx->item_sku_id, 
                                        $modelReturPurchaseTrx->storage_id, 
                                        $modelReturPurchaseTrx->storage_rack_id, 
                                        -1 * $modelReturPurchaseTrx->jumlah_item
                                );

                                if ($flag) {
                                    $flag = StockMovement::setInflow(
                                            'outflow-rp', 
                                            $modelReturPurchaseTrx->item_id, 
                                            $modelReturPurchaseTrx->item_sku_id, 
                                            $modelReturPurchaseTrx->storage_id, 
                                            $modelReturPurchaseTrx->storage_rack_id, 
                                            $modelReturPurchaseTrx->jumlah_item,
                                            date('Y-m-d'), 
                                            $modelReturPurchaseTrx->retur_purchase_id
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
            'modelReturPurchaseTrx' => $modelReturPurchaseTrx,
            'modelReturPurchaseTrxs' => $modelReturPurchaseTrxs,
        ]);
    }

    /**
     * Deletes an existing ReturPurchase model.
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
     * Finds the ReturPurchase model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return ReturPurchase the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ReturPurchase::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionPrint($id) {
        $model = $this->findModel($id);
        $modelReturPurchaseTrxs = ReturPurchaseTrx::find()->joinWith(['item', 'itemSku'])->where(['retur_purchase_id' => $id])->all();
        
        $content = $this->renderPartial('print', [
            'model' => $model,
            'modelReturPurchaseTrxs' => $modelReturPurchaseTrxs
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
                'SetHeader'=>[Yii::$app->name . ' - Retur Pembelian'], 
                'SetFooter'=>['{PAGENO}'],
            ]
        ]);

        return $pdf->render();
    }
    
    public function actionReportReturPurchase()
    {
        if (!empty($post = Yii::$app->request->post()) && !empty($post['tanggalFrom']) && !empty($post['tanggalTo'])) {
            
            $modelReturPurchase = ReturPurchaseTrx::find()
                    ->joinWith([
                        'returPurchase',
                        'returPurchase.kdSupplier',
                        'item',
                        'itemSku',
                        'storage',
                        'storageRack',
                    ])
                    ->andWhere('retur_purchase.date BETWEEN "' . $post['tanggalFrom'] . '" AND "' . $post['tanggalTo'] . '"')
                    ->asArray()->all();
            
            $content = '';
            $title = '';
            if ($post['reportType'] == 'detail') {
                $title = ' - Report Retur Pembelian | Tanggal ' .  Yii::$app->formatter->asDate($post['tanggalFrom']) . ' - ' . Yii::$app->formatter->asDate($post['tanggalTo']);
                $content = $this->renderPartial('_report_retur_purchase_print', [
                    'modelReturPurchase' => $modelReturPurchase,
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
        
        return $this->render('report_retur_purchase', [
        
        ]);
    }
}
