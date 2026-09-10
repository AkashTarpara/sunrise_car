<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "subfloor_condition".
 *
 * @property int $subfloor_condition_id
 * @property string $type
 * @property string $price
 * @property string $created_at
 * @property string|null $updated_at
 */
class Subfloorcondition extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'subfloor_condition';
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
            'subfloor_condition_id' => Yii::t('app', 'Subfloor Condition ID'),
            'type' => Yii::t('app', 'Type'),
            'price' => Yii::t('app', 'Price'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
}
