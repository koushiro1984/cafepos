<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "mtable".
 *
 * @property string $id
 * @property string $mtable_category_id
 * @property string $nama_meja
 * @property string $kapasitas
 * @property string $status
 * @property string $keterangan
 * @property integer $not_ppn
 * @property integer $not_service_charge
 * @property string $image
 * @property string $layout_x
 * @property string $layout_y
 * @property string $shape
 * @property string $created_at
 * @property string $user_created
 * @property string $updated_at
 * @property string $user_updated
 *
 * @property Booking[] $bookings
 * @property MtableCategory $mtableCategory
 * @property User $userCreated
 * @property User $userUpdated
 * @property MtableSession[] $mtableSessions
 * @property MtableSession[] $mtableSessionsJoin
 * @property SaleInvoice[] $saleInvoices
 * @property SaleOrder[] $saleOrders
 */
class Mtable extends \backend\models\base\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mtable';
    }
        

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'mtable_category_id'], 'required'],
            [['id'], 'match', 'pattern' => '/^[a-zA-Z0-9_-]+$/', 'message' => 'Can only contain alphanumeric characters, underscores and dashes.'],
            [['mtable_category_id', 'kapasitas', 'not_ppn', 'not_service_charge', 'layout_x', 'layout_y'], 'integer'],
            [['status', 'keterangan', 'image', 'shape'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['id'], 'string', 'max' => 24],
            [['id'], 'unique'],
            [['nama_meja', 'user_created', 'user_updated'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Meja',
            'mtable_category_id' => 'Mtable Category ID',
            'nama_meja' => 'Nama Meja',
            'kapasitas' => 'Kapasitas',
            'status' => 'Status',
            'keterangan' => 'Keterangan',
            'not_ppn' => 'Not Ppn',
            'not_service_charge' => 'Not Service Charge',
            'image' => 'Image',
            'layout_x' => 'Layout X',
            'layout_y' => 'Layout Y',
            'shape' => 'Shape',
            'created_at' => 'Created At',
            'user_created' => 'User Created',
            'updated_at' => 'Updated At',
            'user_updated' => 'User Updated',
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBookings()
    {
        return $this->hasMany(Booking::className(), ['mtable_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMtableCategory()
    {
        return $this->hasOne(MtableCategory::className(), ['id' => 'mtable_category_id']);
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
    public function getMtableSessions()
    {
        return $this->hasMany(MtableSession::className(), ['mtable_id' => 'id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMtableSessionsJoin()
    {
        return $this->hasMany(MtableSession::className(), ['join_mtable_id' => 'id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSaleInvoices()
    {
        return $this->hasMany(SaleInvoice::className(), ['mtable_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSaleOrders()
    {
        return $this->hasMany(SaleOrder::className(), ['mtable_id' => 'id']);
    }
}
