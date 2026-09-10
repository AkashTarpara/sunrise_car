<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Shippingcharge;

/**
 * ShippingchargeSearch represents the model behind the search form of `app\models\Shippingcharge`.
 */
class ShippingchargeSearch extends Shippingcharge
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['shipping_charge_id', 'min_mile', 'max_mile'], 'integer'],
            [['price'], 'number'],
            [['status', 'created_at', 'updated_at'], 'safe'],
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
        $query = Shippingcharge::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['attributes' => ['shipping_charge_id'], 'defaultOrder' => ['shipping_charge_id' => SORT_DESC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'shipping_charge_id' => $this->shipping_charge_id,
            'min_mile' => $this->min_mile,
            'max_mile' => $this->max_mile,
            'price' => $this->price,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
}
