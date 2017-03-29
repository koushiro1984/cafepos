<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\MenuDiscount;

/**
 * MenuDiscountSearch represents the model behind the search form about `backend\models\MenuDiscount`.
 */
class MenuDiscountSearch extends MenuDiscount
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['type', 'menu_id', 'menu_category_id', 'discount_type', 'start_date', 'end_date', 'created_at', 
                'user_created', 'updated_at', 'user_updated', 'menu.nama_menu', 'menuCategory.nama_category'], 'safe'],
            [['jumlah_discount'], 'number'],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributes() {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), ['menu.nama_menu', 'menuCategory.nama_category']);
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
        $query = MenuDiscount::find();
        $query->joinWith(['menu', 'menuCategory']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => array(
                'pageSize' => 15,
            ),
        ]);
        
        $dataProvider->sort->attributes['menu.nama_menu'] = [
            'asc' => ['menu.nama_menu' => SORT_ASC],
            'desc' => ['menu.nama_menu' => SORT_DESC],
        ];
        
        $dataProvider->sort->attributes['menuCategory.nama_category'] = [
            'asc' => ['menu_category.nama_category' => SORT_ASC],
            'desc' => ['menu_category.nama_category' => SORT_DESC],
        ];

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'jumlah_discount' => $this->jumlah_discount,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'menu_discount.menu_id', $this->menu_id])
            ->andFilterWhere(['like', 'menu_discount.menu_category_id', $this->menu_category_id])
            ->andFilterWhere(['like', 'menu_discount.discount_type', $this->discount_type])
            ->andFilterWhere(['like', 'menu_discount.user_created', $this->user_created])
            ->andFilterWhere(['like', 'menu_discount.user_updated', $this->user_updated])
            ->andFilterWhere(['like', 'menu.nama_menu',  $this->getAttribute('menu.nama_menu')])
            ->andFilterWhere(['like', 'menu_category.nama_category',  $this->getAttribute('menuCategory.nama_category')]);

        return $dataProvider;
    }
}
