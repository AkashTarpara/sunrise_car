<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Userorder;

/**
 * UserorderSearch represents the model behind the search form of `app\models\Userorder`.
 */
class UserorderSearch extends Userorder
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_order_id', 'appuser_address_id'], 'integer'],
            [['payment_type', 'payment_status', 'payment_id', 'payment_date', 'order_status', 'created_at', 'appuser_id', 'delivery_status', 'order_number', 'delivery_type'], 'safe'],
            [['sub_total', 'total'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = Userorder::find()->joinWith(['appuser']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['attributes' => ['user_order_id'], 'defaultOrder' => ['user_order_id' => SORT_DESC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'user_order.user_order_id' => $this->user_order_id,
            //'user_order.appuser_id' => $this->appuser_id,
            'user_order.appuser_address_id' => $this->appuser_address_id,
            'user_order.sub_total' => $this->sub_total,
            'user_order.total' => $this->total,
            'user_order.payment_date' => $this->payment_date,
            'user_order.delivery_status' => $this->delivery_status,
            'user_order.delivery_type' => $this->delivery_type,
            'user_order.created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'user_order.payment_type', $this->payment_type])
            ->andFilterWhere(['like', 'appuser.full_name', $this->appuser_id])
            ->andFilterWhere(['like', 'user_order.order_number', $this->order_number])
            ->andFilterWhere(['like', 'user_order.payment_status', $this->payment_status])
            ->andFilterWhere(['like', 'user_order.payment_id', $this->payment_id])
            ->andFilterWhere(['like', 'user_order.order_status', $this->order_status]);

        return $dataProvider;
    }
}
