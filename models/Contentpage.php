<?php

namespace app\models;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\behaviors\SluggableBehavior;

/**
 * This is the model class for table "content_page".
 *
 * @property int $content_page_id
 * @property string $title
 * @property string $meta_title
 * @property string $meta_tag
 * @property string $meta_description
 * @property string $slug
 * @property string $status
 * @property string $created_at
 * @property string|null $updated_at
 *
 * @property ContentPageDetail[] $contentPageDetails
 */
class Contentpage extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'content_page';
    }

    public function behaviors()
    {
        return [
            [
                'class' => SluggableBehavior::className(),
                'attribute' => 'title',
                'ensureUnique' => true,
                'immutable' => true,
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['meta_description', 'status'], 'string'],
            [['created_at', 'updated_at', 'meta_title', 'meta_tag', 'meta_description'], 'safe'],
            ['created_at', 'default', 'value' => date('Y-m-d H:i:s')],
            [['meta_title', 'meta_tag', 'meta_description'], 'default', 'value' => ''],
            [['title', 'meta_title', 'meta_tag', 'slug'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'content_page_id' => Yii::t('app', 'Content Page ID'),
            'title' => Yii::t('app', 'Title'),
            'meta_title' => Yii::t('app', 'Meta Title'),
            'meta_tag' => Yii::t('app', 'Meta Tag'),
            'meta_description' => Yii::t('app', 'Meta Description'),
            'slug' => Yii::t('app', 'Slug'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[ContentPageDetails]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getContentPageDetails()
    {
        return $this->hasMany(Contentpagedetail::class, ['content_page_id' => 'content_page_id'])->orderBy(['display_order' => SORT_ASC]);
    }

    public function beforeSave($insert)
    {
        //echo "<pre>"; print_r($this->fleet_id); exit;
        if (parent::beforeSave($insert)) {

            if (!$this->isNewRecord) {
                $this->updated_at = date('Y-m-d H:i:s');
                //$this->is_publish=($this->is_schedule==true)?'No':'Yes';
            }
            return true;
        } else {
            return false;
        }
    }
}
