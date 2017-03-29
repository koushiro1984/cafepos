<?php

namespace backend\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\db\Query;
use yii\web\Response;
use kartik\mpdf\Pdf;
use common\models\LoginForm;
use backend\controllers\base\BaseController;
use backend\models\Absensi;
use backend\models\Employee;
use backend\models\SaleInvoice;

/**
 * Site controller
 */
class SiteController extends BaseController {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return array_merge(
            $this->getAccess(),
            [            
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'logout' => ['post'],
                    ],
                ],
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function actions() {
        $this->layout = 'zero';

        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
                'view' => 'error',
            ],
        ];
    }

    public function actionIndex() {
        return $this->render('index');
    }

    public function actionLogin() {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            
            return $this->redirect(Yii::$app->session->get('user_data')['user_level']['default_action']);
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }        

    public function actionLogout() {
        Yii::$app->user->logout();

        return $this->goHome();
    }
    
    public function actionAbsensi() {
        
        if (($post = Yii::$app->request->post())) {
            
            $idAbsen = explode('|', $post['idAbsen']);
            $kdKaryawan = !empty($idAbsen[0]) ? $idAbsen[0] : '';
            $password = !empty($idAbsen[1]) ? $idAbsen[1] : '';
            
            if (($employee = Employee::findOne(['kd_karyawan' => $kdKaryawan, 'password_absen' => $password])) !== null) {
                
                if (!$employee->not_active) {

                    if (!empty($post['checkin'])) {                

                        $absensi = Absensi::findOne(['id' => date('Ymd') . $kdKaryawan]);

                        if ($absensi === null) {
                            $absensi = new Absensi();
                            $absensi->id = date('Ymd') . $kdKaryawan;
                            $absensi->kd_karyawan = $kdKaryawan;
                            $absensi->tanggal = date('Y-m-d');
                            $absensi->check_in = date('H:i:s');

                            if ($absensi->save()) {
                                Yii::$app->session->setFlash('status', 'success');
                                Yii::$app->session->setFlash('message1', 'Rekam Absensi Check In Sukses');
                                Yii::$app->session->setFlash('message2', 'Proses rekam absensi sukses. Data telah berhasil disimpan.');                        
                            }
                        } else {
                            Yii::$app->session->setFlash('status', 'danger');
                            Yii::$app->session->setFlash('message1', 'Rekam Absensi Check In Gagal');
                            Yii::$app->session->setFlash('message2', 'Proses rekam absensi gagal. Data absen check in anda telah terekam sebelumnya.');
                        }
                    } elseif (!empty($post['checkout'])) {

                        $absensi = Absensi::findOne(['id' => date('Ymd') . $kdKaryawan]);

                        if ($absensi !== null) {
                            if (empty($absensi->check_out)) {
                                $absensi->check_out = date('H:i:s');

                                if ($absensi->save()) {
                                    Yii::$app->session->setFlash('status', 'success');
                                    Yii::$app->session->setFlash('message1', 'Rekam Absensi Check Out Sukses');
                                    Yii::$app->session->setFlash('message2', 'Proses rekam absensi sukses. Data telah berhasil disimpan.');                            
                                }
                            } else {
                                Yii::$app->session->setFlash('status', 'danger');
                                Yii::$app->session->setFlash('message1', 'Rekam Absensi Check Out Gagal');
                                Yii::$app->session->setFlash('message2', 'Proses rekam absensi gagal. Data absen check out anda telah terekam sebelumnya.');
                            }
                        } else {
                            Yii::$app->session->setFlash('status', 'danger');
                            Yii::$app->session->setFlash('message1', 'Rekam Absensi Check Out Gagal');
                            Yii::$app->session->setFlash('message2', 'Proses rekam absensi gagal. Data absen check in anda belum terekam sebelumnya.');
                        }
                    }
                } else {
                    Yii::$app->session->setFlash('status', 'danger');
                    Yii::$app->session->setFlash('message1', 'Rekam Absensi Gagal');
                    Yii::$app->session->setFlash('message2', 'Proses rekam absensi gagal. Karyawan ini sudah non aktif.');
                }
            } else {
                Yii::$app->session->setFlash('status', 'danger');
                Yii::$app->session->setFlash('message1', 'Rekam Absensi Gagal');
                Yii::$app->session->setFlash('message2', 'Proses rekam absensi gagal. Data yang diinputkan tidak valid.');
            }
            
            return $this->redirect(['absensi']);
        }
        
        return $this->render('absensi', [
            
        ]);
    }
    
    public function actionDashboard() {
        $this->layout = 'main';
        
        $jumlahTamu = Yii::$app->db->createCommand('
                SELECT SUM(jumlah_guest) AS jumlah_guest
                FROM mtable_session
            ')->queryOne();
        
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
                    ->andWhere('sale_invoice.date BETWEEN "' . date('Y-m-01', time() - (2419200 * 4)) . '" AND "' . date('Y-m-t') . '"')
                    ->asArray()->all();
        
        $sumPenjualan = Yii::$app->db->createCommand('
                SELECT SUM(a.`jumlah`) AS jumlah
                FROM `sale_invoice_detail` a
                    LEFT JOIN `sale_invoice` b ON a.sale_invoice_id = b.id
                WHERE DATE_FORMAT(b.date, "%Y") = "' . date('Y') . '" AND a.is_void = FALSE AND a.is_free_menu = FALSE
            ')->queryOne();
        
        $countStokKritis = Yii::$app->db->createCommand('
                SELECT COUNT(`item_sku`.id) as count
                FROM `item_sku` 
                        LEFT JOIN `stock` ON `item_sku`.`id` = `stock`.`item_sku_id` 
                WHERE stock.jumlah_stok IS NULL OR stock.jumlah_stok <= item_sku.stok_minimal OR stock.jumlah_stok <= 0
            ')->queryOne();       
        
        $topMenuRawData = Yii::$app->db->createCommand('
                SELECT DATE_FORMAT(b.date, "%Y-%m-01") AS grupBulan, DATE_FORMAT(b.date, "%Y") AS tahun, DATE_FORMAT(b.date, "%m") AS bulan,
                    b.date, SUM(a.`jumlah`) AS jumlah,
                    a.menu_id, c.nama_menu
                FROM `sale_invoice_detail` a
                    LEFT JOIN `sale_invoice` b ON a.sale_invoice_id = b.id
                    LEFT JOIN `menu` c ON a.menu_id = c.id
                WHERE DATE_FORMAT(b.date, "%Y-%m") = DATE_FORMAT(NOW(), "%Y-%m") AND a.is_void = FALSE
                GROUP BY grupBulan, a.menu_id
                ORDER BY grupBulan DESC, jumlah DESC
            ')->queryAll();                
        
        $topMenu = [];
        foreach ($topMenuRawData as $value) {
            $topMenu[$value['grupBulan']][] = $value;
        }
        
        return $this->render('dashboard', [
            'jumlahTamu' => $jumlahTamu,
            'modelSaleInvoice' => $modelSaleInvoice,
            'sumPenjualan' => $sumPenjualan,
            'countStokKritis' => $countStokKritis,
            'topMenu' => $topMenu,
        ]);
    }
    
    public function actionDefault() {
        return $this->redirect(Yii::$app->session->get('user_data')['user_level']['default_action']);
    }
    
    public function actionReportHutangPiutang()
    {                
        if (!empty($post = Yii::$app->request->post()) && !empty($post['tanggalFrom']) && !empty($post['tanggalTo'])) {
            if ($post['reportType'] == 'hutang') {
                Yii::$app->session->setFlash('post', $post);
                $this->redirect(['supplier-delivery-invoice/report-hutang']);                       
            } elseif ($post['reportType'] == 'piutang') {
                Yii::$app->session->setFlash('post', $post);
                $this->redirect(['sale-invoice-payment/report-piutang']);                       
            }
        }
        
        $this->layout = 'main';
        
        return $this->render('report_hutang_piutang', [
        
        ]);
    }
    
    public function actionReportPayHutangPiutang()
    {                
        if (!empty($post = Yii::$app->request->post()) && !empty($post['tanggalFrom']) && !empty($post['tanggalTo'])) {
            if ($post['reportType'] == 'hutang') {
                Yii::$app->session->setFlash('post', $post);
                $this->redirect(['supplier-delivery-invoice-payment/report-pay-hutang']);                       
            } elseif ($post['reportType'] == 'piutang') {
                Yii::$app->session->setFlash('post', $post);
                $this->redirect(['sale-invoice-payment/report-pay-piutang']);                       
            }
        }
        
        $this->layout = 'main';
        
        return $this->render('report_pay_hutang_piutang', [
        
        ]);
    }
    
    public function actionReportLabaRugi()
    {
        if (!empty($post = Yii::$app->request->post()) && !empty($post['tanggalFrom']) && !empty($post['tanggalTo'])) {
        
            
            $query = new Query();            
            $rows = $query->select('SUM(jumlah_harga) as total_penjualan')
                ->from('sale_invoice')
                ->andWhere('sale_invoice.date BETWEEN "' . $post['tanggalFrom'] . '" AND "' . $post['tanggalTo'] . '"')
                ->all();
            
            $penjualan = $rows[0]['total_penjualan'];
            
            $query = new Query();
            $rows = $query->select('SUM(jumlah) as total_cash_in')
                ->from('transaksi_keuangan')
                ->join('LEFT JOIN', 'kode_transaksi', 'transaksi_keuangan.account_id=kode_transaksi.account_id')
                ->andWhere('transaksi_keuangan.date BETWEEN "' . $post['tanggalFrom'] . '" AND "' . $post['tanggalTo'] . '"')
                ->andWhere('kode_transaksi.account_type="cash-in"')
                ->all();
            
            $cashIn = $rows[0]['total_cash_in'];
            
            $query = new Query();
            $rows = $query->select('SUM(jumlah_bayar) as total_pembelian')
                ->from('supplier_delivery_invoice')
                ->andWhere('supplier_delivery_invoice.date BETWEEN "' . $post['tanggalFrom'] . '" AND "' . $post['tanggalTo'] . '"')
                ->all();
            
            $pembelian = $rows[0]['total_pembelian'];  
            
            $query = new Query();
            $rows = $query->select('SUM(jumlah) as total_cash_out')
                ->from('transaksi_keuangan')
                ->join('LEFT JOIN', 'kode_transaksi', 'transaksi_keuangan.account_id=kode_transaksi.account_id')
                ->andWhere('transaksi_keuangan.date BETWEEN "' . $post['tanggalFrom'] . '" AND "' . $post['tanggalTo'] . '"')
                ->andWhere('kode_transaksi.account_type="cash-out"')
                ->all();
            
            $cashOut = $rows[0]['total_cash_out'];

            $content = $this->renderPartial('_report_laba_rugi_print', [
                'penjualan' => $penjualan,
                'pembelian' => $pembelian,
                'cashIn' => $cashIn,
                'cashOut' => $cashOut,
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
                        'SetHeader'=>[Yii::$app->name . ' - Report Aktivitas Keuangan | Tanggal ' .  Yii::$app->formatter->asDate($post['tanggalFrom']) . ' - ' . Yii::$app->formatter->asDate($post['tanggalTo'])], 
                        'SetFooter'=>[$footer],
                    ]
                ]);

                return $pdf->render(); 
            } else if ($post['print'] == 'excel') {
                header('Content-Type:   application/vnd.ms-excel; charset=utf-8');
                header('Content-Disposition: attachment; filename="' . Yii::$app->name . ' - Report Aktivitas Keuangan | Tanggal ' .  Yii::$app->formatter->asDate($post['tanggalFrom']) . ' - ' . Yii::$app->formatter->asDate($post['tanggalTo']) .'.xls"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                header('Cache-Control: private',false);
                echo $content;
                exit;
            } 
        }
        
        $this->layout = 'main';
        
        return $this->render('report_laba_rugi', [
        
        ]);
    }
	
	public function actionGetDatetime() {
        $datetime = [];
        $datetime['date'] = date('l, d F Y');
        $datetime['time'] = date('H:i');
                
        Yii::$app->response->format = Response::FORMAT_JSON;

        return $datetime;
    }
        

//    public function actionGenerate($row = 10, $iterate = 1) {
//        $start = microtime(true);
//        $faker = Factory::create();
//        $datas = [];        
//        
//        for ($j = 1; $j <= $iterate; $j++) {
//            for ($i = 1; $i <= $row; $i++) {
//                $datas[$i] = [($j . $i), $faker->name, $faker->streetAddress];
//            }
//            Yii::$app->db->createCommand()->batchInsert('storage', ['id', 'nama_storage', 'keterangan'], $datas)->execute();
//        }
//
//        $time_elapsed_us = microtime(true) - $start;
//        echo ($row * $iterate) . ' = ' . $time_elapsed_us . ' <br>';
//    }
}
