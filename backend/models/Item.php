<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "item".
 *
 * @property string $id
 * @property string $parent_item_category_id
 * @property string $item_category_id
 * @property string $nama_item
 * @property string $keterangan
 * @property integer $not_active
 * @property string $created_at
 * @property string $user_created
 * @property string $updated_at
 * @property string $user_updated
 *
 * @property ItemCategory $itemCategory
 * @property User $userCreated
 * @property User $userUpdated
 * @property ItemCategory $parentItemCategory
 * @property ItemSku[] $itemSkus
 * @property MenuReceipt[] $menuReceipts
 */
class Item extends \backend\models\base\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'item';
    }
        

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'parent_item_category_id', 'nama_item'], 'required'],
            [['id'], 'match', 'pattern' => '/^[a-zA-Z0-9_-]+$/', 'message' => 'Can only contain alphanumeric characters, underscores and dashes.'],
            [['keterangan'], 'string'],
            [['not_active'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['id', 'parent_item_category_id', 'item_category_id'], 'string', 'max' => 16],
            [['id'], 'unique'],
            [['nama_item', 'user_created', 'user_updated'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parent_item_category_id' => 'Item Category ID',
            'item_category_id' => 'Sub Item Category ID',
            'nama_item' => 'Nama Item',
            'keterangan' => 'Keterangan',
            'not_active' => 'Not Active',
            'created_at' => 'Created At',
            'user_created' => 'User Created',
            'updated_at' => 'Updated At',
            'user_updated' => 'User Updated',
            
            'itemCategory.nama_category' => 'Nama Sub Category',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItemCategory()
    {
        return $this->hasOne(ItemCategory::className(), ['id' => 'item_category_id']);
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
    public function getParentItemCategory()
    {
        return $this->hasOne(ItemCategory::className(), ['id' => 'parent_item_category_id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItemSkus()
    {
        return $this->hasMany(ItemSku::className(), ['item_id' => 'id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenuReceipts()
    {
        return $this->hasMany(MenuReceipt::className(), ['item_id' => 'id']);
    }
}
