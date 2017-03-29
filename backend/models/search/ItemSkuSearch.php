<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ItemSku;

/**
 * ItemSkuSearch represents the model behind the search form about `backend\models\ItemSku`.
 */
class ItemSkuSearch extends ItemSku
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'item_id', 'barcode', 'nama_sku', 'created_at', 'user_created', 'updated_at', 'user_updated', 
                'item.nama_item', 'item.parentItemCategory.nama_category'], 'safe'],
            [['stok_minimal', 'per_stok', 'harga_satuan', 'harga_beli'], 'number'],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributes() {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), ['item.nama_item', 'item.parentItemCategory.nama_category']);
    }
    
    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = ItemSku::find();
        $query->joinWith(['item', 'item.parentItemCategory']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => array(
                'pageSize' => 15,
            ),
        ]);                
        
        $dataProvider->sort->attributes['item.nama_item'] = [
            'asc' => ['item.nama_item' => SORT_ASC],
            'desc' => ['item.nama_item' => SORT_DESC],
        ];
        
        $dataProvider->sort->attributes['item.parentItemCategory.nama_category'] = [
            'asc' => ['item_category.nama_category' => SORT_ASC],
            'desc' => ['item_category.nama_category' => SORT_DESC],
        ];

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'stok_minimal' => $this->stok_minimal,
            'per_stok' => $this->per_stok,
            'harga_satuan' => $this->harga_satuan,
            'harga_beli' => $this->harga_beli,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'item_sku.id', $this->id])
            ->andFilterWhere(['like', 'item_sku.item_id', $this->item_id])
            ->andFilterWhere(['like', 'item_sku.barcode', $this->barcode])
            ->andFilterWhere(['like', 'item_sku.nama_sku', $this->nama_sku])
            ->andFilterWhere(['like', 'item_sku.user_created', $this->user_created])
            ->andFilterWhere(['like', 'item_sku.user_updated', $this->user_updated])
            ->andFilterWhere(['like', 'item.nama_item', $this->getAttribute('item.nama_item')])
            ->andFilterWhere(['like', 'item_category.nama_category', $this->getAttribute('item.parentItemCategory.nama_category')]);

        return $dataProvider;
    }
    
    /**
     * Find Sku based on item_id     
     * 
     * @return ActiveQuery
     */
    
    public static function getData($item_id) {
        $query = ItemSku::find();        
        $query->where(['item_id' => $item_id])->orderBy('no_urut');
        
        return $query;
    }
}
