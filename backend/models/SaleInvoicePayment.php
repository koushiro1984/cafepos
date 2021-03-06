<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "sale_invoice_payment".
 *
 * @property string $id
 * @property string $sale_invoice_id
 * @property string $payment_method_id
 * @property string $jumlah_bayar
 * @property string $parent_id
 * @property string $keterangan
 * @property string $created_at
 * @property string $user_created
 * @property string $updated_at
 * @property string $user_updated
 *
 * @property SaleInvoice $saleInvoice
 * @property PaymentMethod $paymentMethod
 * @property SaleInvoicePayment $parent
 * @property SaleInvoicePayment[] $saleInvoicePayments
 * @property User $userCreated
 * @property User $userUpdated
 */
class SaleInvoicePayment extends \backend\models\base\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sale_invoice_payment';
    }
        

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [            
            [['sale_invoice_id', 'payment_method_id'], 'required'],
            [['jumlah_bayar'], 'number', 'min' => 0],
            [['parent_id'], 'integer'],
            [['keterangan'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['sale_invoice_id'], 'string', 'max' => 15],
            [['payment_method_id'], 'string', 'max' => 16],
            [['user_created', 'user_updated'], 'string', 'max' => 32]
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributes() {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), ['paymentMethod.nama_payment', 'jumlah_bayar_child']);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sale_invoice_id' => 'Sale Invoice ID',
            'payment_method_id' => 'Payment Method ID',
            'jumlah_bayar' => 'Jumlah Bayar',
            'parent_id' => 'Parent ID',
            'keterangan' => 'Keterangan',
            'created_at' => 'Created At',
            'user_created' => 'User Created',
            'updated_at' => 'Updated At',
            'user_updated' => 'User Updated',
            
            'jumlah_bayar_child' => 'Jumlah Diterima'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSaleInvoice()
    {
        return $this->hasOne(SaleInvoice::className(), ['id' => 'sale_invoice_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentMethod()
    {
        return $this->hasOne(PaymentMethod::className(), ['id' => 'payment_method_id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(SaleInvoicePayment::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSaleInvoicePayments()
    {
        return $this->hasMany(SaleInvoicePayment::className(), ['parent_id' => 'id']);
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
