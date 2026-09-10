<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Sample;

/**
 * SampleSearch represents the model behind the search form of `app\models\Sample`.
 */
class SampleSearch extends Sample
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sample_id'], 'integer'],
            [['first_name', 'last_name', 'company_name', 'email', 'phone_no', 'address', 'address_line_2', 'city', 'state', 'zip_code', 'order_number', 'payment_type', 'payment_status', 'payment_id', 'payment_date', 'order_status', 'delivery_status', 'created_at', 'product_name'], 'safe'],
            [['sub_total', 'tax', 'shipping', 'total'], 'number'],
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
        $query = Sample::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['attributes' => ['sample_id'], 'defaultOrder' => ['sample_id' => SORT_DESC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'sample_id' => $this->sample_id,
            'sub_total' => $this->sub_total,
            'tax' => $this->tax,
            'shipping' => $this->shipping,
            'total' => $this->total,
            'payment_date' => $this->payment_date,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'first_name', $this->first_name])
            ->andFilterWhere(['like', 'last_name', $this->last_name])
            ->andFilterWhere(['like', 'company_name', $this->company_name])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'phone_no', $this->phone_no])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'address_line_2', $this->address_line_2])
            ->andFilterWhere(['like', 'city', $this->city])
            ->andFilterWhere(['like', 'state', $this->state])
            ->andFilterWhere(['like', 'zip_code', $this->zip_code])
            ->andFilterWhere(['like', 'product_name', $this->product_name])
            ->andFilterWhere(['like', 'order_number', $this->order_number])
            ->andFilterWhere(['like', 'payment_type', $this->payment_type])
            ->andFilterWhere(['like', 'payment_status', $this->payment_status])
            ->andFilterWhere(['like', 'payment_id', $this->payment_id])
            ->andFilterWhere(['like', 'order_status', $this->order_status])
            ->andFilterWhere(['like', 'delivery_status', $this->delivery_status]);

        return $dataProvider;
    }
}
