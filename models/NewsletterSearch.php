<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Newsletter;

/**
 * NewsletterSearch represents the model behind the search form of `app\models\Newsletter`.
 */
class NewsletterSearch extends Newsletter
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['newsletter_id', 'image_height', 'image_width', 'thumbnail_height', 'thumbnail_width'], 'integer'],
            [['title', 'description', 'image', 'thumbnail_image', 'date', 'slug', 'status', 'created_at', 'publish_date', 'type'], 'safe'],
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
        $query = Newsletter::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['attributes' => ['newsletter_id'], 'defaultOrder' => ['newsletter_id' => SORT_DESC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'newsletter_id' => $this->newsletter_id,
            //'news_catagory_id' => $this->news_catagory_id,
            'image_height' => $this->image_height,
            'image_width' => $this->image_width,
            'thumbnail_height' => $this->thumbnail_height,
            'thumbnail_width' => $this->thumbnail_width,
            'date' => $this->date,
            'publish_date' => $this->publish_date,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'image', $this->image])
            ->andFilterWhere(['like', 'thumbnail_image', $this->thumbnail_image])
            ->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'slug', $this->slug])
            ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
}
