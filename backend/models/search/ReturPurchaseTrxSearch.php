<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ReturPurchaseTrx;

/**
 * ReturPurchaseTrxSearch represents the model behind the search form about `backend\models\ReturPurchaseTrx`.
 */
class ReturPurchaseTrxSearch extends ReturPurchaseTrx
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'storage_rack_id'], 'integer'],
            [['retur_purchase_id', 'item_id', 'item_sku_id', 'storage_id', 'keterangan', 'created_at', 'user_created', 'updated_at', 'user_updated'], 'safe'],
            [['jumlah_item', 'harga_satuan', 'jumlah_harga'], 'number'],
        ];
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
        $query = ReturPurchaseTrx::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => array(
                'pageSize' => 15,
            ),
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'storage_rack_id' => $this->storage_rack_id,
            'jumlah_item' => $this->jumlah_item,
            'harga_satuan' => $this->harga_satuan,
            'jumlah_harga' => $this->jumlah_harga,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'retur_purchase_id', $this->retur_purchase_id])
            ->andFilterWhere(['like', 'item_id', $this->item_id])
            ->andFilterWhere(['like', 'item_sku_id', $this->item_sku_id])
            ->andFilterWhere(['like', 'storage_id', $this->storage_id])
            ->andFilterWhere(['like', 'keterangan', $this->keterangan])
            ->andFilterWhere(['like', 'user_created', $this->user_created])
            ->andFilterWhere(['like', 'user_updated', $this->user_updated]);

        return $dataProvider;
    }
}
