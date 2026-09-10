<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "space_type".
 *
 * @property int $space_type_id
 * @property string $type
 * @property string $price
 * @property string $created_at
 * @property string|null $updated_at
 */
class Spacetype extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'space_type';
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
            'space_type_id' => Yii::t('app', 'Space Type ID'),
            'type' => Yii::t('app', 'Type'),
            'price' => Yii::t('app', 'Price'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
}
