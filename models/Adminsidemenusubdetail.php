<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "admin_sidemenu_sub_detail".
 *
 * @property int $admin_sidemenu_sub_detail_id
 * @property int|null $admin_sidemenu_detail_id
 * @property string $title
 * @property string $controller_name
 * @property string $action_name
 * @property string $icon
 * @property int $display_order
 * @property string $status
 * @property string $created_at
 *
 * @property AdminSidemenuDetail $adminSidemenuDetail
 */
class Adminsidemenusubdetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'admin_sidemenu_sub_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['admin_sidemenu_detail_id', 'display_order'], 'integer'],
            [['title', 'controller_name', 'action_name'], 'required'],
            [['status'], 'string'],
            [['created_at', 'icon'], 'safe'],
            ['created_at','default','value'=>date('Y-m-d H:i:s')],
            [['icon'],'default','value'=>''],
            [['title', 'controller_name', 'action_name', 'icon'], 'string', 'max' => 255],
            [['admin_sidemenu_detail_id'], 'exist', 'skipOnError' => true, 'targetClass' => Adminsidemenudetail::class, 'targetAttribute' => ['admin_sidemenu_detail_id' => 'admin_sidemenu_detail_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'admin_sidemenu_sub_detail_id' => Yii::t('app', 'Admin Sidemenu Sub Detail ID'),
            'admin_sidemenu_detail_id' => Yii::t('app', 'Admin Sidemenu Detail ID'),
            'title' => Yii::t('app', 'Title'),
            'controller_name' => Yii::t('app', 'Controller Name'),
            'action_name' => Yii::t('app', 'Action Name'),
            'icon' => Yii::t('app', 'Icon'),
            'display_order' => Yii::t('app', 'Display Order'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
        ];
    }

    /**
     * Gets query for [[AdminSidemenuDetail]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAdminSidemenuDetail()
    {
        return $this->hasOne(Adminsidemenudetail::class, ['admin_sidemenu_detail_id' => 'admin_sidemenu_detail_id']);
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            
            if($this->isNewRecord)
            {
                $Homescreen = Adminsidemenusubdetail::find()->where(['admin_sidemenu_detail_id'=>$this->admin_sidemenu_detail_id])->orderBy('display_order DESC')->all();
                if(!$Homescreen){
                    $this->display_order = 1;
                }else{
                    $this->display_order = $Homescreen[0]->display_order+1;
                }
            } 
            return true;
        } else {
            return false;
        }
    }
}
