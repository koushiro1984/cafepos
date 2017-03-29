<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "supplier_delivery_invoice".
 *
 * @property string $id
 * @property string $date
 * @property string $supplier_delivery_id
 * @property string $payment_method
 * @property string $jumlah_harga
 * @property string $jumlah_bayar
 * @property string $created_at
 * @property string $user_created
 * @property string $updated_at
 * @property string $user_updated
 *
 * @property SupplierDelivery $supplierDelivery
 * @property User $userCreated
 * @property User $userUpdated
 * @property PaymentMethod $paymentMethod
 * @property SupplierDeliveryInvoiceDetail[] $supplierDeliveryInvoiceDetails
 */
class SupplierDeliveryInvoice extends \backend\models\base\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'supplier_delivery_invoice';
    }
        

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'match', 'pattern' => '/^[a-zA-Z0-9_-]+$/', 'message' => 'Can only contain alphanumeric characters, underscores and dashes.'],
            [['date', 'supplier_delivery_id', 'payment_method'], 'required'],
            [['date', 'created_at', 'updated_at'], 'safe'],
            [['jumlah_harga', 'jumlah_bayar'], 'number', 'min' => 1],
            [['id', 'payment_method'], 'string', 'max' => 16],
            [['supplier_delivery_id'], 'string', 'max' => 13],
            [['user_created', 'user_updated'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'date' => 'Date',
            'supplier_delivery_id' => 'Supplier Delivery ID',
            'payment_method' => 'Payment Method',
            'jumlah_harga' => 'Jumlah Harga',
            'jumlah_bayar' => 'Jumlah Bayar',
            'created_at' => 'Created At',
            'user_created' => 'User Created',
            'updated_at' => 'Updated At',
            'user_updated' => 'User Updated',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSupplierDelivery()
    {
        return $this->hasOne(SupplierDelivery::className(), ['id' => 'supplier_delivery_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserCreated()
    {
        return $this->hasOne(User::className(), ['id' => 'user_created']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserUpdated()
    {
        return $this->hasOne(User::className(), ['id' => 'user_updated']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentMethod()
    {
        return $this->hasOne(PaymentMethod::className(), ['id' => 'payment_method']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSupplierDeliveryInvoiceDetails()
    {
        return $this->hasMany(SupplierDeliveryInvoiceDetail::className(), ['supplier_delivery_invoice_id' => 'id']);
    }
}
