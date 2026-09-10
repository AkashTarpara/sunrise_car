<?php

namespace app\models;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;   
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "admin_sidemenu_detail".
 *
 * @property int $admin_sidemenu_detail_id
 * @property int $admin_sidemenu_id
 * @property string $title
 * @property string $controller_name
 * @property string $action_name
 * @property string $icon
 * @property string $status
 * @property string $created_at
 *
 * @property AdminSidemenu $adminSidemenu
 */
class Adminsidemenudetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'admin_sidemenu_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['admin_sidemenu_id','display_order'], 'integer'],
            [['title', 'controller_name'], 'required'],
            [['status', 'controller_name'], 'string'],
            [['created_at', 'icon'], 'safe'],
            ['created_at','default','value'=>date('Y-m-d H:i:s')],
            [['title', 'action_name', 'icon', 'action_name'], 'string', 'max' => 255],
            [['admin_sidemenu_id'], 'exist', 'skipOnError' => true, 'targetClass' => Adminsidemenu::className(), 'targetAttribute' => ['admin_sidemenu_id' => 'admin_sidemenu_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'admin_sidemenu_detail_id' => Yii::t('app', 'Admin Sidemenu Detail ID'),
            'admin_sidemenu_id' => Yii::t('app', 'Admin Sidemenu ID'),
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
     * @return \yii\db\ActiveQuery
     */
    public function getAdminSidemenu()
    {
        return $this->hasOne(Adminsidemenu::className(), ['admin_sidemenu_id' => 'admin_sidemenu_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdminsidemenusubdetail()
    {
        return $this->hasMany(Adminsidemenusubdetail::className(), ['admin_sidemenu_detail_id' => 'admin_sidemenu_detail_id'])->where(['status'=>'Active'])->orderBy(['display_order'=>SORT_ASC]);
    }

    public function IsActive(){
        if($this->status == 'Active'){
            $status="Activated";
            $class="btn btn-light-success";
            $title = "Click here to deactivated";
        }else{
            $status="Deactivated";
            $class="btn btn-light-danger";
            $title = "Click here to activated";
        }
        return Html::a($status,Yii::$app->urlManager->createUrl(['adminsidemenudetail/changestatus','id'=>Yii::$app->MyFunctions->encode( $this->admin_sidemenu_detail_id )]),['class'=>$class,"data-toggle"=>"tooltip","title"=>$title]);
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            
            if($this->isNewRecord)
            {
                $Homescreen = Adminsidemenudetail::find()->where(['admin_sidemenu_id'=>$this->admin_sidemenu_id])->orderBy('display_order DESC')->all();
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
