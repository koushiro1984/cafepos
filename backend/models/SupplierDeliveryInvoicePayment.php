<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "supplier_delivery_invoice_payment".
 *
 * @property string $id
 * @property string $supplier_delivery_invoice_id
 * @property string $jumlah_bayar
 * @property string $created_at
 * @property string $user_created
 * @property string $updated_at
 * @property string $user_updated
 *
 * @property SupplierDeliveryInvoice $supplierDeliveryInvoice
 * @property User $userCreated
 * @property User $userUpdated
 */
class SupplierDeliveryInvoicePayment extends \backend\models\base\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'supplier_delivery_invoice_payment';
    }
        

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['supplier_delivery_invoice_id'], 'required'],
            [['jumlah_bayar'], 'number', 'min' => 1],
            [['created_at', 'updated_at'], 'safe'],
            [['supplier_delivery_invoice_id'], 'string', 'max' => 16],
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
            'supplier_delivery_invoice_id' => 'Supplier Delivery Invoice ID',
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
    public function getSupplierDeliveryInvoice()
    {
        return $this->hasOne(SupplierDeliveryInvoice::className(), ['id' => 'supplier_delivery_invoice_id']);
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
}
