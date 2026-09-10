<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "installation_complexity".
 *
 * @property int $installation_complexity_id
 * @property string $type
 * @property string $price
 * @property string $created_at
 * @property string|null $updated_at
 */
class Installationcomplexity extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'installation_complexity';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'price'], 'required'],
            [
                ['price'],
                'match',
                'pattern' => '/^\d+(\.\d{1,2})?$/',
                'message' => 'Price must be a valid number with up to 2 decimal places.'
            ],
            [['created_at', 'updated_at'], 'safe'],
            [['type', 'price'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'installation_complexity_id' => Yii::t('app', 'Installation Complexity ID'),
            'type' => Yii::t('app', 'Type'),
            'price' => Yii::t('app', 'Price'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
}
