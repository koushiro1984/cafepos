<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "purchase_order".
 *
 * @property string $id
 * @property string $date
 * @property string $kd_supplier
 * @property double $jumlah_item
 * @property string $jumlah_harga
 * @property string $created_at
 * @property string $user_created
 * @property string $updated_at
 * @property string $user_updated
 *
 * @property Supplier $kdSupplier
 * @property User $userCreated
 * @property User $userUpdated
 * @property PurchaseOrderTrx[] $purchaseOrderTrxes
 * @property SupplierDeliveryTrx[] $supplierDeliveryTrxes
 */
class PurchaseOrder extends \backend\models\base\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'purchase_order';
    }
        

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'match', 'pattern' => '/^[a-zA-Z0-9_-]+$/', 'message' => 'Can only contain alphanumeric characters, underscores and dashes.'],
            [['kd_supplier', 'date'], 'required'],
            [['date', 'created_at', 'updated_at'], 'safe'],
            [['jumlah_harga'], 'number', 'min' => 1],
            [['jumlah_item'], 'number', 'min' => 0.001],
            [['id'], 'string', 'max' => 13],
            [['kd_supplier'], 'string', 'max' => 7],
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
            'kd_supplier' => 'Kd Supplier',
            'jumlah_item' => 'Jumlah Item',
            'jumlah_harga' => 'Jumlah Harga',
            'created_at' => 'Created At',
            'user_created' => 'User Created',
            'updated_at' => 'Updated At',
            'user_updated' => 'User Updated',
            
            'kdSupplier.nama' => 'Nama Supplier'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKdSupplier()
    {
        return $this->hasOne(Supplier::className(), ['kd_supplier' => 'kd_supplier']);
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
    public function getPurchaseOrderTrxes()
    {
        return $this->hasMany(PurchaseOrderTrx::className(), ['purchase_order_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSupplierDeliveryTrxes()
    {
        return $this->hasMany(SupplierDeliveryTrx::className(), ['purchase_order_id' => 'id']);
    }
}
