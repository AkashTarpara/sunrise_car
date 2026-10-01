<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "faq".
 *
 * @property int $faq_id
 * @property string $type faq / terms_condition
 * @property string $title
 * @property string $description HTML content from the editor
 * @property int $display_order
 * @property string $status
 * @property string $created_at
 * @property string|null $updated_at
 */
class Faq extends \yii\db\ActiveRecord
{
    const TYPE_FAQ = 'faq';
    const TYPE_TERMS_CONDITION = 'terms_condition';

    public static function tableName()
    {
        return 'faq';
    }

    public static function typeList()
    {
        return [
            self::TYPE_FAQ => Yii::t('app', 'FAQ'),
            self::TYPE_TERMS_CONDITION => Yii::t('app', 'Terms & Conditions'),
        ];
    }

    public function getTypeLabel()
    {
        $types = self::typeList();
        return isset($types[$this->type]) ? $types[$this->type] : $this->type;
    }

    public function rules()
    {
        return [
            [['type', 'title', 'description'], 'required'],
            [['type'], 'in', 'range' => array_keys(self::typeList())],
            [['title'], 'string', 'max' => 255],
            [['description'], 'string'],
            [['display_order'], 'integer', 'min' => 0, 'on' => 'updateorder'],
            [['display_order'], 'required', 'on' => 'updateorder'],
            [['status'], 'in', 'range' => ['Active', 'Inactive']],
            [['status'], 'default', 'value' => 'Active'],
            [['created_at', 'updated_at'], 'safe'],
            ['created_at', 'default', 'value' => date('Y-m-d H:i:s')],
        ];
    }

    public function attributeLabels()
    {
        return [
            'faq_id' => Yii::t('app', 'ID'),
            'type' => Yii::t('app', 'Type'),
            'title' => Yii::t('app', 'Title'),
            'description' => Yii::t('app', 'Description'),
            'display_order' => Yii::t('app', 'Display Order'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        if ($insert) {
            // Place new entries at the end of their type; order is managed from the list page
            $this->display_order = (int)static::find()->where(['type' => $this->type])->max('display_order') + 1;
        } else {
            $this->updated_at = date('Y-m-d H:i:s');
        }

        return true;
    }
}
