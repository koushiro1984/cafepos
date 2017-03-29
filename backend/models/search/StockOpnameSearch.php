<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\StockOpname;

/**
 * StockOpnameSearch represents the model behind the search form about `backend\models\StockOpname`.
 */
class StockOpnameSearch extends StockOpname
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'storage_rack_id'], 'integer'],
            [['item_id', 'item_sku_id', 'storage_id', 'action', 'date_action', 'user_action', 'created_at', 'user_created', 'updated_at', 'user_updated',
                'item.nama_item', 'itemSku.nama_sku'], 'safe'],
            [['jumlah', 'jumlah_awal', 'jumlah_adjustment'], 'number'],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributes() {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), ['item.nama_item', 'itemSku.nama_sku']);
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
    public function search($params, $isWaiting = false)
    {
        $query = StockOpname::find();
        $query->joinWith(['item', 'itemSku', 'storage', 'storageRack']);

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
        
        $dataProvider->sort->attributes['itemSku.nama_sku'] = [
            'asc' => ['item_sku.nama_sku' => SORT_ASC],
            'desc' => ['item_sku.nama_sku' => SORT_DESC],
        ];

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'stock_opname.id' => $this->id,
            'stock_opname.storage_rack_id' => $this->storage_rack_id,
            'stock_opname.jumlah' => $this->jumlah,
            'stock_opname.jumlah_awal' => $this->jumlah_awal,
            'stock_opname.jumlah_adjustment' => $this->jumlah_adjustment,
            'stock_opname.date_action' => $this->date_action,
            'stock_opname.created_at' => $this->created_at,
            'stock_opname.updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'stock_opname.item_id', $this->item_id])
            ->andFilterWhere(['like', 'stock_opname.item_sku_id', $this->item_sku_id])
            ->andFilterWhere(['like', 'stock_opname.storage_id', $this->storage_id])                            
            ->andFilterWhere(['like', 'stock_opname.user_action', $this->user_action])
            ->andFilterWhere(['like', 'stock_opname.user_created', $this->user_created])
            ->andFilterWhere(['like', 'stock_opname.user_updated', $this->user_updated])
            ->andFilterWhere(['like', 'item.nama_item',  $this->getAttribute('item.nama_item')])
            ->andFilterWhere(['like', 'itemSku.nama_sku',  $this->getAttribute('item_sku.nama_sku')]);
        
        if ($isWaiting)
            $query->andFilterWhere(['stock_opname.action' => 'waiting']);            
        else
            $query->andFilterWhere(['like', 'stock_opname.action', $this->action]);

        return $dataProvider;
    }
}
