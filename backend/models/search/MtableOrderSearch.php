<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\MtableOrder;

/**
 * MtableOrderSearch represents the model behind the search form about `backend\models\MtableOrder`.
 */
class MtableOrderSearch extends MtableOrder
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'mtable_session_id', 'is_free_menu', 'is_void'], 'integer'],
            [['menu_id', 'catatan', 'discount_type', 'void_at', 'user_void', 'created_at', 'user_created', 'updated_at', 'user_updated'], 'safe'],
            [['discount', 'harga_satuan', 'jumlah'], 'number'],
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
        $query = MtableOrder::find();

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
            'mtable_session_id' => $this->mtable_session_id,
            'discount' => $this->discount,
            'harga_satuan' => $this->harga_satuan,
            'jumlah' => $this->jumlah,
            'is_free_menu' => $this->is_free_menu,
            'is_void' => $this->is_void,
            'void_at' => $this->void_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'menu_id', $this->menu_id])
            ->andFilterWhere(['like', 'catatan', $this->catatan])
            ->andFilterWhere(['like', 'discount_type', $this->discount_type])
            ->andFilterWhere(['like', 'user_void', $this->user_void])
            ->andFilterWhere(['like', 'user_created', $this->user_created])
            ->andFilterWhere(['like', 'user_updated', $this->user_updated]);

        return $dataProvider;
    }
}
