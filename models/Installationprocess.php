<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "installation_process".
 *
 * @property int $installation_process_id
 * @property string $title
 * @property int $order
 * @property string $created_at
 * @property string $updated_at
 */
class Installationprocess extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'installation_process';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'order', 'updated_at'], 'required'],
            [['order'], 'integer'],
            [['created_at', 'updated_at', 'image'], 'safe'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'installation_process_id' => Yii::t('app', 'Installation Process ID'),
            'title' => Yii::t('app', 'Title'),
            'order' => Yii::t('app', 'Order'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    public function upload()
    {
        if (is_object($this->image)) {

            if (file_exists(Yii::getAlias("@app") . "/" . $this->getOldAttribute('image')) && !empty($this->getOldAttribute('image'))) {
                unlink(Yii::getAlias("@app") . "/" . $this->getOldAttribute('image'));
            }

            $path = 'uploads/images/installationprocess/';
            if (!is_dir($path)) {
                mkdir($path, 0777, true);
                chmod($path, 0777);
            }

            $name = Yii::$app->MyFunctions->random_string(32);
            $this->image->saveAs($path . $name . '.' . $this->image->extension);
            $this->image = $path . $name . '.' . $this->image->extension;
        }
    }

    public function beforeValidate() {
        if (parent::beforeValidate()) {
            if ($this->isNewRecord) {
                $maxOrder = self::find()->max('`order`');
                $this->order = ($maxOrder ?? 0) + 1;
            }
            $this->updated_at = date('Y-m-d H:i:s');
            return true;
        }
        return false;
    }
}
