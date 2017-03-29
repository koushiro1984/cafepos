<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "sale_invoice_correction_payment".
 *
 * @property string $id
 * @property string $sale_invoice_correction_id
 * @property string $payment_method_id
 * @property string $jumlah_bayar
 * @property string $parent_id
 * @property string $keterangan
 * @property string $created_at
 * @property string $user_created
 * @property string $updated_at
 * @property string $user_updated
 *
 * @property PaymentMethod $paymentMethod
 * @property SaleInvoiceCorrectionPayment $parent
 * @property SaleInvoiceCorrectionPayment[] $saleInvoiceCorrectionPayments
 * @property User $userCreated
 * @property User $userUpdated
 * @property SaleInvoiceCorrection $saleInvoiceCorrection
 */
class SaleInvoiceCorrectionPayment extends \backend\models\base\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sale_invoice_correction_payment';
    }
        

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sale_invoice_correction_id', 'payment_method_id'], 'required'],
            [['sale_invoice_correction_id', 'parent_id'], 'integer'],
            [['jumlah_bayar'], 'number'],
            [['keterangan'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['payment_method_id'], 'string', 'max' => 16],
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
            'sale_invoice_correction_id' => 'Sale Invoice Correction ID',
            'payment_method_id' => 'Payment Method ID',
            'jumlah_bayar' => 'Jumlah Bayar',
            'parent_id' => 'Parent ID',
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
    public function getPaymentMethod()
    {
        return $this->hasOne(PaymentMethod::className(), ['id' => 'payment_method_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(SaleInvoiceCorrectionPayment::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSaleInvoiceCorrectionPayments()
    {
        return $this->hasMany(SaleInvoiceCorrectionPayment::className(), ['parent_id' => 'id']);
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
    public function getSaleInvoiceCorrection()
    {
        return $this->hasOne(SaleInvoiceCorrection::className(), ['id' => 'sale_invoice_correction_id']);
    }
}
