<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Contentpagedetail;

/**
 * ContentpagedetailSearch represents the model behind the search form of `app\models\Contentpagedetail`.
 */
class ContentpagedetailSearch extends Contentpagedetail
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['content_page_detail_id', 'content_page_id', 'display_order'], 'integer'],
            [['type', 'section_title', 'title', 'sub_title', 'image', 'button_1_title', 'button_1_url', 'button_2_title', 'button_2_url', 'logo', 'video', 'status', 'created_at', 'updated_at'], 'safe'],
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
        $query = Contentpagedetail::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'content_page_detail_id' => $this->content_page_detail_id,
            'content_page_id' => $this->content_page_id,
            'display_order' => $this->display_order,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'section_title', $this->section_title])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'sub_title', $this->sub_title])
            ->andFilterWhere(['like', 'image', $this->image])
            ->andFilterWhere(['like', 'button_1_title', $this->button_1_title])
            ->andFilterWhere(['like', 'button_1_url', $this->button_1_url])
            ->andFilterWhere(['like', 'button_2_title', $this->button_2_title])
            ->andFilterWhere(['like', 'button_2_url', $this->button_2_url])
            ->andFilterWhere(['like', 'logo', $this->logo])
            ->andFilterWhere(['like', 'video', $this->video])
            ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
    public function contentpagedetailsearch($params,$id)
    {
        $query = Contentpagedetail::find()->where(['content_page_id'=>$id]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['attributes' => ['display_order'],'defaultOrder'=>[ 'display_order' => SORT_ASC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'content_page_detail_id' => $this->content_page_detail_id,
            'content_page_id' => $this->content_page_id,
            'display_order' => $this->display_order,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'section_title', $this->section_title])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'sub_title', $this->sub_title])
            ->andFilterWhere(['like', 'image', $this->image])
            ->andFilterWhere(['like', 'button_1_title', $this->button_1_title])
            ->andFilterWhere(['like', 'button_1_url', $this->button_1_url])
            ->andFilterWhere(['like', 'button_2_title', $this->button_2_title])
            ->andFilterWhere(['like', 'button_2_url', $this->button_2_url])
            ->andFilterWhere(['like', 'logo', $this->logo])
            ->andFilterWhere(['like', 'video', $this->video])
            ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
}
