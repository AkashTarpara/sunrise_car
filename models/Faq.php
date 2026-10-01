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
            [['display_order'], 'integer', 'min' => 1, 'on' => 'updateorder'],
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

        if ($insert || $this->isAttributeChanged('type')) {
            // Place new entries (or entries moved to another type) at the end of their type
            $this->display_order = (int)static::find()->where(['type' => $this->type])->max('display_order') + 1;
        }
        if (!$insert) {
            $this->updated_at = date('Y-m-d H:i:s');
        }

        return true;
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        // Close the gap left in the previous type's list
        if (!$insert && isset($changedAttributes['type']) && $changedAttributes['type'] !== $this->type) {
            static::reorder($changedAttributes['type']);
        }
    }

    public function afterDelete()
    {
        parent::afterDelete();
        static::reorder($this->type);
    }

    /**
     * Renumbers a type's entries as 1..n. When $faqId is given, that entry is moved
     * to $position and the others shift up or down to make room.
     */
    public static function reorder($type, $faqId = null, $position = null)
    {
        $ids = static::find()
            ->select('faq_id')
            ->where(['type' => $type])
            ->orderBy(['display_order' => SORT_ASC, 'faq_id' => SORT_ASC])
            ->column();
        $ids = array_map('intval', $ids);

        if ($faqId !== null) {
            $faqId = (int)$faqId;
            $ids = array_values(array_diff($ids, [$faqId]));
            $index = max(0, min((int)$position - 1, count($ids)));
            array_splice($ids, $index, 0, [$faqId]);
        }

        $transaction = static::getDb()->beginTransaction();
        try {
            foreach ($ids as $i => $id) {
                static::updateAll(['display_order' => $i + 1], ['faq_id' => $id]);
            }
            $transaction->commit();
        } catch (\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }
    }
}
