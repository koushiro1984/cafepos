<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\SaleInvoicePayment;

/**
 * SaleInvoicePaymentSearch represents the model behind the search form about `backend\models\SaleInvoicePayment`.
 */
class SaleInvoicePaymentSearch extends SaleInvoicePayment
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['sale_invoice_id', 'payment_method_id', 'created_at', 'user_created', 'updated_at', 'user_updated', 'paymentMethod.nama_payment'], 'safe'],
            [['jumlah_bayar'], 'number'],
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
        $query = SaleInvoicePayment::find();
        $query->select(['sale_invoice_payment.*', 'SUM(child.jumlah_bayar) AS jumlah_bayar_child'])
            ->joinWith([
                'paymentMethod',
                'saleInvoicePayments' => function($q) {
                    $q->from('sale_invoice_payment child');                        
                }
            ])
            ->andWhere(['IS', 'sale_invoice_payment.parent_id', null])
            ->andWhere(['payment_method.method' => 'hutang'])
            ->groupBy(['child.parent_id', 'sale_invoice_payment.sale_invoice_id']);
            

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => array(
                'pageSize' => 15,
            ),
        ]);
        
        $dataProvider->sort->attributes['paymentMethod.nama_payment'] = [
            'asc' => ['payment_method.nama_payment' => SORT_ASC],
            'desc' => ['payment_method.nama_payment' => SORT_DESC],
        ];

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'sale_invoice_payment.id' => $this->id,
            'sale_invoice_payment.jumlah_bayar' => $this->jumlah_bayar,
            'sale_invoice_payment.created_at' => $this->created_at,
            'sale_invoice_payment.updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'sale_invoice_payment.sale_invoice_id', $this->sale_invoice_id])
            ->andFilterWhere(['like', 'sale_invoice_payment.payment_method_id', $this->payment_method_id])
            ->andFilterWhere(['like', 'sale_invoice_payment.user_created', $this->user_created])
            ->andFilterWhere(['like', 'sale_invoice_payment.user_updated', $this->user_updated])
            ->andFilterWhere(['like', 'payment_method.nama_payment', $this->getAttribute('paymentMethod.nama_payment')]);                         

        return $dataProvider;
    }
}
