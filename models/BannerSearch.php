<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Banner;

/**
 * BannerSearch represents the model behind the search form of `app\models\Banner`.
 */
class BannerSearch extends Banner
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['banner_id'], 'integer'],
            [['title', 'url', 'type', 'image', 'video', 'status', 'created_at', 'banner_type_id'], 'safe'],
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
        $query = Banner::find();

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
            'banner_id' => $this->banner_id,
            //'banner_type_id' => $this->banner_type_id,
            'banner.status' => $this->status,
            'banner.created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'banner.title', $this->title])
            ->andFilterWhere(['like', 'url', $this->url])
            ->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'image', $this->image])
            ->andFilterWhere(['like', 'video', $this->video]);

        return $dataProvider;
    }
}
