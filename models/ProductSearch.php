<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Product;

/**
 * ProductSearch represents the model behind the search form of `app\models\Product`.
 */
class ProductSearch extends Product
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_id', 'display_order'], 'integer'],
            [['title', 'description', 'image', 'status', 'created_at', 'updated_at', 'product_category_id'], 'safe'],
            [['price'], 'number'],
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
        $query = Product::find()->joinWith('productCategory');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['attributes' => ['display_order'], 'defaultOrder' => ['display_order' => SORT_ASC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'product.product_id' => $this->product_id,
            //'product.product_category_id' => $this->product_category_id,
            'product.price' => $this->price,
            'product.display_order' => $this->display_order,
            'product.status' => $this->status,
            'product.created_at' => $this->created_at,
            'product.updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'product.title', $this->title])
            ->andFilterWhere(['like', 'product_category.title', $this->product_category_id])
            ->andFilterWhere(['like', 'product.description', $this->description])
            ->andFilterWhere(['like', 'product.image', $this->image]);

        return $dataProvider;
    }
}
