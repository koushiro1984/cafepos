<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "payment_method".
 *
 * @property string $id
 * @property string $nama_payment
 * @property string $type
 * @property string $method
 * @property string $keterangan
 * @property string $created_at
 * @property string $user_created
 * @property string $updated_at
 * @property string $user_updated
 *
 * @property User $userCreated
 * @property User $userUpdated
 * @property SaleInvoicePayment[] $saleInvoicePayments
 * @property SupplierDeliveryInvoice[] $supplierDeliveryInvoices
 */
class PaymentMethod extends \backend\models\base\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'payment_method';
    }
        

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'nama_payment'], 'required'],
            [['id'], 'match', 'pattern' => '/^[a-zA-Z0-9_-]+$/', 'message' => 'Can only contain alphanumeric characters, underscores and dashes.'],
            [['type', 'method', 'keterangan'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['id'], 'string', 'max' => 16],
            [['id'], 'unique'],
            [['nama_payment', 'user_created', 'user_updated'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nama_payment' => 'Nama Payment',
            'type' => 'Type',
            'method' => 'Method',
            'keterangan' => 'Keterangan',
            'created_at' => 'Created At',
            'user_created' => 'User Created',
            'updated_at' => 'Updated At',
            'user_updated' => 'User Updated',
        ];
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
    public function getSaleInvoicePayments()
    {
        return $this->hasMany(SaleInvoicePayment::className(), ['payment_method_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSupplierDeliveryInvoices()
    {
        return $this->hasMany(SupplierDeliveryInvoice::className(), ['payment_method' => 'id']);
    }
}
