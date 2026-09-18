<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

class BookingSearch extends Booking
{
    public function rules()
    {
        return [
            [['id', 'fleet_id'], 'integer'],
            [['booking_number', 'pickup_date', 'payment_status', 'booking_status'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Booking::find()
            ->alias('booking')
            ->joinWith(['fleet fleet'])
            ->andWhere(['booking.deleted_at' => null]);

        $provider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['pickup_date' => SORT_DESC, 'pickup_time' => SORT_ASC]],
            'pagination' => ['pageSize' => 25],
        ]);

        $this->load($params);
        if (!$this->validate()) {
            return $provider;
        }

        $query->andFilterWhere([
            'booking.id' => $this->id,
            'booking.fleet_id' => $this->fleet_id,
            'booking.pickup_date' => $this->pickup_date,
            'booking.payment_status' => $this->payment_status,
            'booking.booking_status' => $this->booking_status,
        ]);
        $query->andFilterWhere(['like', 'booking.booking_number', $this->booking_number]);

        return $provider;
    }
}
