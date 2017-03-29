<?php

namespace backend\models;

use Yii;
use yii\base\NotSupportedException;
use yii\web\IdentityInterface;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "user".
 *
 * @property string $id
 * @property string $kd_karyawan
 * @property string $password
 * @property string $user_level_id
 * @property integer $not_active
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Absensi[] $absensis
 * @property Booking[] $bookings
 * @property Branch[] $branches
 * @property DirectPurchase[] $directPurchases
 * @property DirectPurchaseTrx[] $directPurchaseTrxes
 * @property Employee[] $employees
 * @property HistoryCud[] $historyCuds
 * @property Item[] $items
 * @property ItemCategory[] $itemCategories
 * @property ItemSku[] $itemSkus
 * @property KodeTransaksi[] $kodeTransaksis
 * @property Menu[] $menus
 * @property MenuCategory[] $menuCategories
 * @property MenuDiscount[] $menuDiscounts
 * @property MenuQueue[] $menuQueues
 * @property MenuReceipt[] $menuReceipts
 * @property MenuSatuan[] $menuSatuans
 * @property Mtable[] $mtables
 * @property MtableCategory[] $mtableCategories
 * @property MtableOrder[] $mtableOrders
 * @property MtableSession[] $mtableSessions
 * @property PaymentMethod[] $paymentMethods
 * @property PurchaseOrder[] $purchaseOrders
 * @property PurchaseOrderTrx[] $purchaseOrderTrxes
 * @property ReturPurchase[] $returPurchases
 * @property ReturPurchaseTrx[] $returPurchaseTrxes
 * @property ReturSale[] $returSales
 * @property SaldoKasir[] $saldoKasirs
 * @property SaleInvoice[] $saleInvoices
 * @property SaleInvoiceDetail[] $saleInvoiceDetails
 * @property SaleInvoicePayment[] $saleInvoicePayments
 * @property Settings[] $settings
 * @property Shift[] $shifts
 * @property Stock[] $stocks
 * @property StockMovement[] $stockMovements
 * @property StockOpname[] $stockOpnames
 * @property Storage[] $storages
 * @property StorageRack[] $storageRacks
 * @property Supplier[] $suppliers
 * @property SupplierDelivery[] $supplierDeliveries
 * @property SupplierDeliveryInvoice[] $supplierDeliveryInvoices
 * @property SupplierDeliveryInvoiceDetail[] $supplierDeliveryInvoiceDetails
 * @property SupplierDeliveryInvoicePayment[] $supplierDeliveryInvoicePayments
 * @property SupplierDeliveryTrx[] $supplierDeliveryTrxes
 * @property TransaksiKeuangan[] $transaksiKeuangans
 * @property Employee $kdKaryawan
 * @property UserLevel $userLevel
 * @property UserAkses[] $userAkses
 * @property UserAppModule[] $userAppModules
 * @property UserLevel[] $userLevels
 * @property Voting[] $votings
 * @property Voucher[] $vouchers
 */
class User extends \backend\models\base\BaseModel implements IdentityInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }
    
    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'updated_at',
                ],
                'value' => function($event) {
                    return date('Y-m-d H:i:s');
                },
            ],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {        
        return [
            [['id', 'kd_karyawan'], 'unique'],
            [['id', 'user_level_id', 'kd_karyawan', 'password'], 'required'],
            [['user_level_id', 'not_active'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['id'], 'string', 'max' => 32],
            [['kd_karyawan'], 'string', 'max' => 7],
            [['password'], 'string', 'max' => 64],            
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'User ID',
            'kd_karyawan' => 'Kd Karyawan',
            'password' => 'Password',
            'user_level_id' => 'User Level',
            'not_active' => 'Not Active',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
  
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAbsensis()
    {
        return $this->hasMany(Absensi::className(), ['user_updated' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBookings()
    {
        return $this->hasMany(Booking::className(), ['user_updated' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBranches()
    {
        return $this->hasMany(Branch::className(), ['user_updated' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDirectPurchases()
    {
        return $this->hasMany(DirectPurchase::className(), ['user_updated' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDirectPurchaseTrxes()
    {
        return $this->hasMany(DirectPurchaseTrx::className(), ['user_updated' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployees()
    {
        return $this->hasMany(Employee::className(), ['user_updated' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHistoryCuds()
    {
        return $this->hasMany(HistoryCud::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItems()
    {
        return $this->hasMany(Item::className(), ['user_updated' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItemCategories()
    {
        return $this->hasMany(ItemCategory::className(), ['user_updated' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItemSkus()
    {
        return $this->hasMany(ItemSku::className(), ['user_updated' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKodeTransaksis()
    {
        return $this->hasMany(KodeTransaksi::className(), ['user_updated' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenus()
    {
        return $this->hasMany(Menu::className(), ['user_updated' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenuCategories()
    {
        return $this->hasMany(MenuCategory::className(), ['user_updated' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenuDiscounts()
    {
        return $this->hasMany(MenuDiscount::className(), ['user_updated' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenuQueues()
    {
        return $this->hasMany(MenuQueue::className(), ['user_updated' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenuReceipts()
    {
        return $this->hasMany(MenuReceipt::className(), ['user_updated' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenuSatuans()
    {
        return $this->hasMany(MenuSatuan::className(), ['user_updated' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMtables()
    {
        return $this->hasMany(Mtable::className(), ['user_updated' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMtableCategories()
    {
        return $this->hasMany(MtableCategory::className(), ['user_updated' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMtableOrders()
    {
        return $this->hasMany(MtableOrder::className(), ['user_void' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMtableSessions()
    {
        return $this->hasMany(MtableSession::className(), ['user_updated' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentMethods()
    {
        return $this->hasMany(PaymentMethod::className(), ['user_updated' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPurchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::className(), ['user_updated' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPurchaseOrderTrxes()
    {
        return $this->hasMany(PurchaseOrderTrx::className(), ['user_updated' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReturPurchases()
    {
        return $this->hasMany(ReturPurchase::className(), ['user_updated' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReturPurchaseTrxes()
    {
        return $this->hasMany(ReturPurchaseTrx::className(), ['user_updated' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReturSales()
    {
        return $this->hasMany(ReturSale::className(), ['user_updated' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSaldoKasirs()
    {
        return $this->hasMany(SaldoKasir::className(), ['user_updated' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSaleInvoices()
    {
        return $this->hasMany(SaleInvoice::className(), ['user_updated' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSaleInvoiceDetails()
    {
        return $this->hasMany(SaleInvoiceDetail::className(), ['user_updated' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSaleInvoicePayments()
    {
        return $this->hasMany(SaleInvoicePayment::className(), ['user_updated' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSettings()
    {
        return $this->hasMany(Settings::className(), ['user_updated' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShifts()
    {
        return $this->hasMany(Shift::className(), ['user_updated' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStocks()
    {
        return $this->hasMany(Stock::className(), ['user_updated' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStockMovements()
    {
        return $this->hasMany(StockMovement::className(), ['user_updated' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStockOpnames()
    {
        return $this->hasMany(StockOpname::className(), ['user_updated' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStorages()
    {
        return $this->hasMany(Storage::className(), ['user_updated' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStorageRacks()
    {
        return $this->hasMany(StorageRack::className(), ['user_updated' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSuppliers()
    {
        return $this->hasMany(Supplier::className(), ['user_updated' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSupplierDeliveries()
    {
        return $this->hasMany(SupplierDelivery::className(), ['user_updated' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSupplierDeliveryInvoices()
    {
        return $this->hasMany(SupplierDeliveryInvoice::className(), ['user_updated' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSupplierDeliveryInvoiceDetails()
    {
        return $this->hasMany(SupplierDeliveryInvoiceDetail::className(), ['user_updated' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSupplierDeliveryInvoicePayments()
    {
        return $this->hasMany(SupplierDeliveryInvoicePayment::className(), ['user_updated' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSupplierDeliveryTrxes()
    {
        return $this->hasMany(SupplierDeliveryTrx::className(), ['user_updated' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransaksiKeuangans()
    {
        return $this->hasMany(TransaksiKeuangan::className(), ['user_updated' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKdKaryawan()
    {
        return $this->hasOne(Employee::className(), ['kd_karyawan' => 'kd_karyawan']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserLevel()
    {
        return $this->hasOne(UserLevel::className(), ['id' => 'user_level_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserAkses()
    {
        return $this->hasMany(UserAkses::className(), ['user_updated' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserAppModules()
    {
        return $this->hasMany(UserAppModule::className(), ['user_updated' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserLevels()
    {
        return $this->hasMany(UserLevel::className(), ['user_updated' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVotings()
    {
        return $this->hasMany(Voting::className(), ['user_updated' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVouchers()
    {
        return $this->hasMany(Voucher::className(), ['user_updated' => 'id']);
    }
    
    ////IdentityInterface Section
    
    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['id' => $username]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        //return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        //return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
}
