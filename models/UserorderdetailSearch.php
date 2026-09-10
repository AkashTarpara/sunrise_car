<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Userorderdetail;

/**
 * UserorderdetailSearch represents the model behind the search form of `app\models\Userorderdetail`.
 */
class UserorderdetailSearch extends Userorderdetail
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_order_detail_id', 'user_order_id', 'quantity'], 'integer'],
            [['price'], 'number'],
            [['created_at', 'product_id'], 'safe'],
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
        $query = Userorderdetail::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['attributes' => ['user_order_detail_id'], 'defaultOrder' => ['user_order_detail_id' => SORT_DESC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'user_order_detail_id' => $this->user_order_detail_id,
            'user_order_id' => $this->user_order_id,
            'product_id' => $this->product_id,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'created_at' => $this->created_at,
        ]);

        return $dataProvider;
    }

    public function userorderdetailsearch($params, $id)
    {
        $query = Userorderdetail::find()->joinWith(['product'])->where(['user_order_id' => $id]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['attributes' => ['user_order_detail_id'], 'defaultOrder' => ['user_order_detail_id' => SORT_DESC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'user_order_detail.user_order_detail_id' => $this->user_order_detail_id,
            'user_order_detail.user_order_id' => $this->user_order_id,
            //'user_order_detail.product_id' => $this->product_id,
            'product.price_per_box' => $this->price,
            'user_order_detail.quantity' => $this->quantity,
            'user_order_detail.created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'product.title', $this->product_id]);

        return $dataProvider;
    }
}
