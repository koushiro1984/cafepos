<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "sale_invoice".
 *
 * @property string $id
 * @property string $date
 * @property string $mtable_session_id
 * @property string $user_operator
 * @property string $jumlah_harga
 * @property double $pajak
 * @property double $service_charge
 * @property string $jumlah_bayar
 * @property string $jumlah_kembali
 * @property string $created_at
 * @property string $user_created
 * @property string $updated_at
 * @property string $user_updated
 *
 * @property MtableSession $mtableSession
 * @property User $userOperator
 * @property User $userCreated
 * @property User $userUpdated
 * @property SaleInvoiceCorrection[] $saleInvoiceCorrections
 * @property SaleInvoiceDetail[] $saleInvoiceDetails
 * @property SaleInvoicePayment[] $saleInvoicePayments
 */
class SaleInvoice extends \backend\models\base\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sale_invoice';
    }
        

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'date', 'mtable_session_id'], 'required'],
            [['date', 'created_at', 'updated_at'], 'safe'],
            [['mtable_session_id'], 'integer'],
            [['jumlah_harga', 'jumlah_bayar'], 'number', 'min' => 0],
            [['pajak', 'service_charge', 'jumlah_kembali'], 'number', 'min' => 0],
            [['id'], 'string', 'max' => 15],
            [['user_operator', 'user_created', 'user_updated'], 'string', 'max' => 32]
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
            'mtable_session_id' => 'Mtable Session ID',
            'user_operator' => 'User Operator',
            'jumlah_harga' => 'Jumlah Harga',
            'pajak' => 'Pajak',
            'service_charge' => 'Service Charge',
            'jumlah_bayar' => 'Jumlah Bayar',
            'jumlah_kembali' => 'Jumlah Kembali',
            'created_at' => 'Created At',
            'user_created' => 'User Created',
            'updated_at' => 'Updated At',
            'user_updated' => 'User Updated',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMtableSession()
    {
        return $this->hasOne(MtableSession::className(), ['id' => 'mtable_session_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserOperator()
    {
        return $this->hasOne(User::className(), ['id' => 'user_operator']);
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
    public function getSaleInvoiceCorrections()
    {
        return $this->hasMany(SaleInvoiceCorrection::className(), ['sale_invoice_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSaleInvoiceDetails()
    {
        return $this->hasMany(SaleInvoiceDetail::className(), ['sale_invoice_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSaleInvoicePayments()
    {
        return $this->hasMany(SaleInvoicePayment::className(), ['sale_invoice_id' => 'id']);
    }
}
