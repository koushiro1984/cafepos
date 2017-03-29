<?php

$settings_company_profile = Yii::$app->session->get('company_settings_profile'); ?>

<!-- Left side column. contains the logo and sidebar -->
<aside class="left-side sidebar-offcanvas">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            
            <img src="<?= Yii::$app->request->baseUrl . '/img/company-profile/' . $settings_company_profile['company_image_file'] ?>">
            
            <div class="pull-left info">
                <p>
                    <?= $settings_company_profile['company_name'] ?><br><br>
                    <?= $settings_company_profile['company_address'] ?><br>
                    <?= $settings_company_profile['company_city'] . ' ' . $settings_company_profile['company_postal_code'] ?><br><br>
                    <?= $settings_company_profile['company_phone'] ?><br>
                </p>
            </div>
        </div>                    
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
            <li class="active">
                <a href="<?= Yii::$app->urlManager->createUrl('site/dashboard'); ?>">
                    <i class="fa fa-dashboard text-green"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-th text-aqua"></i> 
                    <span>Master</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a href="<?= Yii::$app->urlManager->createUrl('employee'); ?>">
                            <i class="fa fa-angle-double-right"></i> Karyawan
                        </a>
                    </li>
                    <li>
                        <a href="<?= Yii::$app->urlManager->createUrl('shift'); ?>">
                            <i class="fa fa-angle-double-right"></i> Shift
                        </a>
                    </li>
                    <li>
                        <a href="<?= Yii::$app->urlManager->createUrl('saldo-kasir'); ?>">
                            <i class="fa fa-angle-double-right"></i> Saldo Kasir
                        </a>
                    </li>
                    <li>
                        <a href="<?= Yii::$app->urlManager->createUrl('supplier'); ?>">
                            <i class="fa fa-angle-double-right"></i> Supplier
                        </a>
                    </li>
                    <!--
                    <li><a href="#"><i class="fa fa-angle-double-right"></i> Customer</a></li>
                    <li><a href="#"><i class="fa fa-angle-double-right"></i> Point</a></li>
                    -->
                    <li>
                        <a href="<?= Yii::$app->urlManager->createUrl('storage'); ?>">
                            <i class="fa fa-angle-double-right"></i> Gudang
                        </a>
                    </li>
                    <li>
                        <a href="<?= Yii::$app->urlManager->createUrl('storage-rack'); ?>">
                            <i class="fa fa-angle-double-right"></i> Rak
                        </a>
                    </li>
                    <li>
                        <a href="<?= Yii::$app->urlManager->createUrl('menu-category'); ?>">
                            <i class="fa fa-angle-double-right"></i> Kategori Menu
                        </a>
                    </li>
                    <li>
                        <a href="<?= Yii::$app->urlManager->createUrl('menu-satuan'); ?>">
                            <i class="fa fa-angle-double-right"></i> Satuan Menu
                        </a>
                    </li>
                    <li>
                        <a href="<?= Yii::$app->urlManager->createUrl('menu'); ?>">
                            <i class="fa fa-angle-double-right"></i> Menu
                        </a>
                    </li>
                    <li>
                        <a href="<?= Yii::$app->urlManager->createUrl('payment-method'); ?>">
                            <i class="fa fa-angle-double-right"></i> Payment Method
                        </a>
                    </li>
                    <li>
                        <a href="<?= Yii::$app->urlManager->createUrl('branch'); ?>">
                            <i class="fa fa-angle-double-right"></i> Branch
                        </a>
                    </li>
                    <!--
                    <li>
                        <a href="<?= Yii::$app->urlManager->createUrl('menu-discount'); ?>">
                            <i class="fa fa-angle-double-right"></i> Diskon
                        </a>
                    </li>
                    -->
                    <li>
                        <a href="<?= Yii::$app->urlManager->createUrl('voucher'); ?>">
                            <i class="fa fa-angle-double-right"></i> Voucher
                        </a>
                    </li>                    
                    <li>
                        <a href="<?= Yii::$app->urlManager->createUrl('mtable'); ?>">
                            <i class="fa fa-angle-double-right"></i>Ruangan dan Meja</a>
                    </li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-cubes text-red"></i>
                    <span>Stock</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a href="<?= Yii::$app->urlManager->createUrl('item-category'); ?>">
                            <i class="fa fa-angle-double-right"></i> Kategori Item
                        </a>
                    </li>
                    <li>
                        <a href="<?= Yii::$app->urlManager->createUrl('item'); ?>">
                            <i class="fa fa-angle-double-right"></i> Item
                        </a>
                    </li>                    
                    <li>
                        <a href="<?= Yii::$app->urlManager->createUrl('stock/stock-flow?flow=in'); ?>">
                            <i class="fa fa-angle-double-right"></i> Stock Masuk
                        </a>
                    </li>
                    <li>
                        <a href="<?= Yii::$app->urlManager->createUrl('stock/stock-flow?flow=out'); ?>">
                            <i class="fa fa-angle-double-right"></i> Stock Keluar
                        </a>
                    </li>
                    <li>
                        <a href="<?= Yii::$app->urlManager->createUrl('stock/stock-flow?flow=trnsfr'); ?>">
                            <i class="fa fa-angle-double-right"></i> Stock Transfer
                        </a>
                    </li>
					<li>
                        <a href="<?= Yii::$app->urlManager->createUrl('stock-opname'); ?>">
                            <i class="fa fa-angle-double-right"></i> Stock Opname
                        </a>
                    </li>
                    <li>
                        <a href="<?= Yii::$app->urlManager->createUrl('stock-opname/confirmation'); ?>">
                            <i class="fa fa-angle-double-right"></i> Verifikasi Stock Opname
                        </a>
                    </li>
                    <li>
                        <a href="<?= Yii::$app->urlManager->createUrl('stock/stock-flow?flow=indeliv'); ?>">
                            <i class="fa fa-angle-double-right"></i> Internal Delivery
                        </a>
                    </li>
                    <li>
                        <a href="<?= Yii::$app->urlManager->createUrl('stock/stock-flow?flow=inrecev'); ?>">
                            <i class="fa fa-angle-double-right"></i> Internal Receiving
                        </a>
                    </li>
                    <li>
                        <a href="<?= Yii::$app->urlManager->createUrl('stock-movement'); ?>">
                            <i class="fa fa-angle-double-right"></i> Stock Movement
                        </a>
                    </li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-truck text-navy"></i>
                    <span>Pembelian</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a href="<?= Yii::$app->urlManager->createUrl('purchase-order'); ?>">
                            <i class="fa fa-angle-double-right"></i> Purchase Order
                        </a>
                    </li>
                    <li>
                        <a href="<?= Yii::$app->urlManager->createUrl('supplier-delivery'); ?>">
                            <i class="fa fa-angle-double-right"></i> Penerimaan Item
                        </a>
                    </li>
                    <li>
                        <a href="<?= Yii::$app->urlManager->createUrl('direct-purchase'); ?>">
                            <i class="fa fa-angle-double-right"></i> Pembelian Langsung
                        </a>
                    </li>
                    <li>
                        <a href="<?= Yii::$app->urlManager->createUrl('retur-purchase'); ?>">
                            <i class="fa fa-angle-double-right"></i> Retur Pembelian
                        </a>
                    </li>                    
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-shopping-cart text-red"></i>
                    <span>Penjualan</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a href="<?= Yii::getAlias('@frontendpos-web'); ?>">
                            <i class="fa fa-angle-double-right"></i> Point Of Sales
                        </a>
                    </li>
                    <li>
                        <a href="<?= Yii::$app->urlManager->createUrl('sale-invoice'); ?>">
                            <i class="fa fa-angle-double-right"></i> Refund
                        </a>
                    </li>
                    <li><a href="<?= Yii::getAlias('@frontendpos-web/index.php/page/menu-queue'); ?>"><i class="fa fa-angle-double-right"></i> Antrian Dapur</a></li>
                    <li>
                        <a href="<?= Yii::$app->urlManager->createUrl('booking'); ?>">
                            <i class="fa fa-angle-double-right"></i> Booking
                        </a>
                    </li>
                    <!--<li><a href="#"><i class="fa fa-angle-double-right"></i> Konfirmasi Booking</a></li>-->
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-money text-green"></i>
                    <span>Keuangan</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a href="<?= Yii::$app->urlManager->createUrl('kode-transaksi'); ?>">
                            <i class="fa fa-angle-double-right"></i> Kode Transaksi
                        </a>
                    </li>
                    <li>
                        <a href="<?= Yii::$app->urlManager->createUrl('transaksi-keuangan'); ?>">
                            <i class="fa fa-angle-double-right"></i> Cash In &AMP; Cash Out
                        </a>
                    </li>
                    <li>
                        <a href="<?= Yii::$app->urlManager->createUrl('supplier-delivery-invoice'); ?>">
                            <i class="fa fa-angle-double-right"></i> Invoice Penerimaan Item
                        </a>
                    </li>
                    <li>
                        <a href="<?= Yii::$app->urlManager->createUrl('supplier-delivery-invoice-payment'); ?>">
                            <i class="fa fa-angle-double-right"></i> Bayar Pembelian (PO)
                        </a>
                    </li>
                    <li>
                        <a href="<?= Yii::$app->urlManager->createUrl('sale-invoice-payment'); ?>">
                            <i class="fa fa-angle-double-right"></i> List Piutang/AR
                        </a>
                    </li>
                </ul>
            </li>                        
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-file text-orange"></i> 
                    <span>Laporan</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a href="<?= Yii::$app->urlManager->createUrl('sale-invoice/report-sale'); ?>">
                            <i class="fa fa-angle-double-right"></i> Penjualan
                        </a>
                    </li>
                    <li>
                        <a href="<?= Yii::$app->urlManager->createUrl('sale-invoice/report-cashier'); ?>">
                            <i class="fa fa-angle-double-right"></i> Pendapatan Kasir
                        </a>
                    </li>
                    <li>
                        <a href="<?= Yii::$app->urlManager->createUrl('sale-invoice/report-cashier-daily'); ?>">
                            <i class="fa fa-angle-double-right"></i> Pendapatan By Kategori</a></li>
                    <li>
                        <a href="<?= Yii::$app->urlManager->createUrl('sale-invoice/report-invoice-daily'); ?>">
                            <i class="fa fa-angle-double-right"></i> Faktur Kasir 
                        </a>
                    </li>
                    <li>
                        <a href="<?= Yii::$app->urlManager->createUrl('sale-invoice/report-rekap-daily'); ?>">
                            <i class="fa fa-angle-double-right"></i> Rekap Penjualan 
                        </a>
                    </li>
                    <li>
                        <a href="<?= Yii::$app->urlManager->createUrl('retur-sale/report-retur-sale'); ?>">
                            <i class="fa fa-angle-double-right"></i> Retur Penjualan
                        </a>
                    </li>
                    <li>
                        <a href="<?= Yii::$app->urlManager->createUrl('purchase-order/report-purchase-order'); ?>">
                            <i class="fa fa-angle-double-right"></i> Pembelian (PO)
                        </a>
                    </li>
                    <li>
                        <a href="<?= Yii::$app->urlManager->createUrl('supplier-delivery/report-supplier-delivery'); ?>">
                            <i class="fa fa-angle-double-right"></i> Penerimaan Barang
                        </a>
                    </li>
                    <li>
                        <a href="<?= Yii::$app->urlManager->createUrl('retur-purchase/report-retur-purchase'); ?>">
                            <i class="fa fa-angle-double-right"></i> Retur Pembelian
                        </a>
                    </li>
                    <li>
                        <a href="<?= Yii::$app->urlManager->createUrl('site/report-hutang-piutang'); ?>">
                            <i class="fa fa-angle-double-right"></i> AR/AP
                        </a>
                    </li>
                    <li>
                        <a href="<?= Yii::$app->urlManager->createUrl('site/report-pay-hutang-piutang'); ?>">
                            <i class="fa fa-angle-double-right"></i> Pembayaran AR/AP
                        </a>
                    </li>
                    <li>
                        <a href="<?= Yii::$app->urlManager->createUrl('stock/report-stock'); ?>">
                            <i class="fa fa-angle-double-right"></i> Stock
                        </a>
                    </li>  
                    <li>
                        <a href="<?= Yii::$app->urlManager->createUrl('stock/report-stock-inflow'); ?>">
                            <i class="fa fa-angle-double-right"></i> Stock Masuk/Inflow
                        </a>
                    </li>
                    <li>
                        <a href="<?= Yii::$app->urlManager->createUrl('stock/report-stock-outflow'); ?>">
                            <i class="fa fa-angle-double-right"></i> Stock Keluar/Outflow
                        </a>
                    </li>
                    <li>
                        <a href="<?= Yii::$app->urlManager->createUrl('stock/report-stock-transfer'); ?>">
                            <i class="fa fa-angle-double-right"></i> Stock Transfer
                        </a>
                    </li>
                    <li>
                        <a href="<?= Yii::$app->urlManager->createUrl('stock/report-stock-internal'); ?>">
                            <i class="fa fa-angle-double-right"></i> Stock  Delivery/Receiving
                        </a>
                    </li>
                    <li>
                        <a href="<?= Yii::$app->urlManager->createUrl('sale-invoice-detail/report-free-menu'); ?>">
                            <i class="fa fa-angle-double-right"></i> Free Menu (Invoice)
                        </a>
                    </li>
                    <li>
                        <a href="<?= Yii::$app->urlManager->createUrl('mtable-order/report-delete-order'); ?>">
                            <i class="fa fa-angle-double-right"></i>  Void Menu dan Close Meja
                        </a>
                    </li>
                    <li>
                        <a href="<?= Yii::$app->urlManager->createUrl('sale-invoice-detail/report-delete-order'); ?>">
                            <i class="fa fa-angle-double-right"></i>  Void Menu 
                        </a>
                    </li>
                    <li>
                        <a href="<?= Yii::$app->urlManager->createUrl('site/report-laba-rugi'); ?>">
                            <i class="fa fa-angle-double-right"></i> Aktivitas Keuangan
                        </a>
                    </li>
                    <li>
                        <a href="<?= Yii::$app->urlManager->createUrl('absensi/report-absensi'); ?>">
                            <i class="fa fa-angle-double-right"></i> Absensi
                        </a>
                    </li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-users text-fuchsia"></i>
                    <span>Manajemen User</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a href="<?= Yii::$app->urlManager->createUrl('user'); ?>">
                            <i class="fa fa-angle-double-right"></i> Data User
                        </a>
                    </li>
                    <li>
                        <a href="<?= Yii::$app->urlManager->createUrl('user-level'); ?>">
                            <i class="fa fa-angle-double-right"></i> Data User Level
                        </a>
                    </li>
                    <li>
                        <a href="<?= Yii::$app->urlManager->createUrl('user-app-module'); ?>">
                            <i class="fa fa-angle-double-right"></i> Application Module
                        </a>
                    </li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-star text-yellow"></i>
                    <span>Fitur</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a href="<?= Yii::$app->urlManager->createUrl('voting'); ?>">
                            <i class="fa fa-angle-double-right"></i> Voting
                        </a>
                    </li>
                    <li>
                        <a href="<?= Yii::$app->urlManager->createUrl(['site/absensi']); ?>">
                            <i class="fa fa-angle-double-right"></i> Absensi
                        </a>
                    </li>
                    <li>
                        <a href="<?= Yii::getAlias('@frontend-web'); ?>">
                            <i class="fa fa-angle-double-right"></i>Display Menu</a>
                    </li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-gears text-navy"></i>
                    <span>Setting</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a href="<?= Yii::$app->urlManager->createUrl(['settings/update-setting', 'id' => 'company']); ?>">
                            <i class="fa fa-angle-double-right"></i> Profile Perusahaan
                        </a>
                    </li>
                    <li>
                        <a href="<?= Yii::$app->urlManager->createUrl(['settings/update-setting', 'id' => 'tax-sc']); ?>">
                            <i class="fa fa-angle-double-right"></i> Nilai Pajak &AMP; Service Charge
                        </a>
                    </li>
                    <li>
                        <a href="<?= Yii::$app->urlManager->createUrl(['settings/tax-service-charge']); ?>">
                            <i class="fa fa-angle-double-right"></i> Pajak Include Service Charge
                        </a>
                    </li>
                    <li>
                        <a href="<?= Yii::$app->urlManager->createUrl(['settings/update-setting', 'id' => 'struk']); ?>">
                            <i class="fa fa-angle-double-right"></i> Setting Struk
                        </a>
                    </li>  
                    <li>
                        <a href="<?= Yii::$app->urlManager->createUrl(['printer']); ?>">
                            <i class="fa fa-angle-double-right"></i> Printer
                        </a>
                    </li>
                    <li>
                        <a href="<?= Yii::$app->urlManager->createUrl(['settings/slideshow']); ?>">
                            <i class="fa fa-angle-double-right"></i> Slideshow
                        </a>
                    </li>
                    <li>
                        <a href="<?= Yii::$app->urlManager->createUrl(['settings/fullscreen']); ?>">
                            <i class="fa fa-angle-double-right"></i> Fullscreen
                        </a>
                    </li>
                </ul>
            </li>
            <!--
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-gears text-navy"></i>
                    <span>Administrator</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a href="<?= Yii::$app->urlManager->createUrl(['settings/update-database']); ?>">
                            <i class="fa fa-database"></i> Update Database
                        </a>
                    </li>                    
                </ul>
            </li>
            -->
        </ul>                    
    </section>
    <!-- /.sidebar -->
</aside>