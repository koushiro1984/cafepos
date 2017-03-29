<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\SupplierDeliveryTrx;

/**
 * SupplierDeliveryTrxSearch represents the model behind the search form about `backend\models\SupplierDeliveryTrx`.
 */
class SupplierDeliveryTrxSearch extends SupplierDeliveryTrx
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['supplier_delivery_id', 'purchase_order_id', 'item_id', 'item_sku_id', 'keterangan', 'created_at', 'user_created', 'updated_at', 'user_updated'], 'safe'],
            [['jumlah_order', 'jumlah_terima', 'harga_satuan', 'jumlah_harga'], 'number'],
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
        $query = SupplierDeliveryTrx::find();

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
            'jumlah_order' => $this->jumlah_order,
            'jumlah_terima' => $this->jumlah_terima,
            'harga_satuan' => $this->harga_satuan,
            'jumlah_harga' => $this->jumlah_harga,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'supplier_delivery_id', $this->supplier_delivery_id])
            ->andFilterWhere(['like', 'purchase_order_id', $this->purchase_order_id])
            ->andFilterWhere(['like', 'item_id', $this->item_id])
            ->andFilterWhere(['like', 'item_sku_id', $this->item_sku_id])
            ->andFilterWhere(['like', 'keterangan', $this->keterangan])
            ->andFilterWhere(['like', 'user_created', $this->user_created])
            ->andFilterWhere(['like', 'user_updated', $this->user_updated]);

        return $dataProvider;
    }
}
