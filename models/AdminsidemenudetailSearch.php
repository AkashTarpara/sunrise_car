<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Adminsidemenudetail;

/**
 * AdminsidemenudetailSearch represents the model behind the search form of `app\models\Adminsidemenudetail`.
 */
class AdminsidemenudetailSearch extends Adminsidemenudetail
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['admin_sidemenu_detail_id', 'admin_sidemenu_id'], 'integer'],
            [['title', 'controller_name', 'action_name', 'icon', 'status', 'created_at'], 'safe'],
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
        $query = Adminsidemenudetail::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['attributes' => ['admin_sidemenu_detail_id'],'defaultOrder'=>[ 'admin_sidemenu_detail_id' => SORT_ASC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'admin_sidemenu_detail_id' => $this->admin_sidemenu_detail_id,
            'admin_sidemenu_id' => $this->admin_sidemenu_id,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'controller_name', $this->controller_name])
            ->andFilterWhere(['like', 'action_name', $this->action_name])
            ->andFilterWhere(['like', 'icon', $this->icon])
            ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }

    public function submenuSearch($params,$id=0)
    {
        $query = Adminsidemenudetail::find()->where(['admin_sidemenu_id'=>$id]);
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
            'admin_sidemenu_detail_id' => $this->admin_sidemenu_detail_id,
            'admin_sidemenu_id' => $this->admin_sidemenu_id,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'controller_name', $this->controller_name])
            ->andFilterWhere(['like', 'action_name', $this->action_name])
            ->andFilterWhere(['like', 'icon', $this->icon])
            ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
}
