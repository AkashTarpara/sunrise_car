<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Installation;

/**
 * InstallationSearch represents the model behind the search form of `app\models\Installation`.
 */
class InstallationSearch extends Installation
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['installation_id'], 'integer'],
            [['label', 'title', 'sub_title', 'video', 'before_image', 'after_image', 'created_at', 'updated_at'], 'safe'],
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
        $query = Installation::find();

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
            'installation_id' => $this->installation_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'label', $this->label])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'sub_title', $this->sub_title])
            ->andFilterWhere(['like', 'video', $this->video])
            ->andFilterWhere(['like', 'before_image', $this->before_image])
            ->andFilterWhere(['like', 'after_image', $this->after_image]);

        return $dataProvider;
    }
}
