<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\MenuReceipt;

/**
 * MenuReceiptSearch represents the model behind the search form about `backend\models\MenuReceipt`.
 */
class MenuReceiptSearch extends MenuReceipt
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['menu_id', 'item_id', 'item_sku_id', 'created_at', 'user_created', 'updated_at', 'user_updated'], 'safe'],
            [['jumlah'], 'number'],
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
        $query = MenuReceipt::find();

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
            'jumlah' => $this->jumlah,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'menu_id', $this->menu_id])
            ->andFilterWhere(['like', 'item_id', $this->item_id])
            ->andFilterWhere(['like', 'item_sku_id', $this->item_sku_id])
            ->andFilterWhere(['like', 'user_created', $this->user_created])
            ->andFilterWhere(['like', 'user_updated', $this->user_updated]);

        return $dataProvider;
    }
    
//    /**
//     * Creates data provider instance with search query applied
//     *
//     * @param array $params
//     *
//     * @return ActiveDataProvider
//     */
//    public static function search2()
//    {
//        $query = MenuReceipt::findBySql('SELECT * FROM menu_receipt WHERE true=false');
//        
//        //$query->joinWith(['item', 'itemSku', 'menu']);        
//
//        $dataProvider = new ActiveDataProvider([
//            'query' => $query,
//            'sort' => false,
//        ]);
//
//        return $dataProvider;
//    }
}
