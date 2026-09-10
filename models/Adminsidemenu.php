<?php
namespace app\models;
use Yii;
use yii\helpers\Url;
use yii\helpers\Html;   
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "admin_sidemenu".
 *
 * @property int $admin_sidemenu_id
 * @property string $title
 * @property string $controller_name
 * @property string $action_name
 * @property string $icon
 * @property string $status
 * @property string $created_at
 *
 * @property AdminSidemenuDetail[] $adminSidemenuDetails
 */
class Adminsidemenu extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'admin_sidemenu';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'controller_name'], 'required'],
            [['display_order'], 'integer'],
            [['status','is_multiple', 'controller_name','sub_menu_action_name'], 'string'],
            [['created_at', 'action_name', 'icon'], 'safe'],
            ['created_at','default','value'=>date('Y-m-d H:i:s')],
            [['sub_menu_action_name','action_name', 'icon'],'default','value'=>''],
            [['title', 'action_name', 'icon'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'admin_sidemenu_id' => Yii::t('app', 'Admin Sidemenu ID'),
            'title' => Yii::t('app', 'Title'),
            'controller_name' => Yii::t('app', 'Controller Name'),
            'sub_menu_action_name' => Yii::t('app', 'Sub Menu Action Name'),
            'action_name' => Yii::t('app', 'Action Name'),
            'icon' => Yii::t('app', 'Icon'),
            'display_order' => Yii::t('app', 'Display Order'),
            'is_multiple' => Yii::t('app', 'Is Multiple'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdminSidemenuDetails()
    {
        return $this->hasMany(Adminsidemenudetail::className(), ['admin_sidemenu_id' => 'admin_sidemenu_id'])->where(['status'=>'Active'])->orderBy(['display_order'=>SORT_ASC]);
    }

    /*public function IsActive(){
        if($this->status == 'Active'){
            $status="Activated";
            $class="btn btn-sm btn-light-success";
            $title = "Click here to deactivated";
        }else{
            $status="Deactivated";
            $class="btn btn-sm btn-light-danger";
            $title = "Click here to activated";
        }

        return Html::a($status,Yii::$app->urlManager->createUrl(['adminsidemenu/changestatus','id'=>Yii::$app->MyFunctions->encode( $this->admin_sidemenu_id )]),['class'=>$class,"data-toggle"=>"tooltip","title"=>$title]);
    }*/

    public function IsActive(){
        $checked='';
        if($this->status == 'Active'){
            $checked='checked';
        }
        return " <form id='form_".$this->admin_sidemenu_id."' action='".Yii::$app->urlManager->createUrl(['adminsidemenu/changestatus'])."' method='POST'>
            <input type='hidden' value='".Yii::$app->MyFunctions->encode($this->admin_sidemenu_id)."' name='id'>
            <div class='card-body'>
                <input type='checkbox' data-toggle='switchbutton'".$checked." data-onlabel='Activated' data-offlabel='Deactivated' data-onstyle='success' data-offstyle='danger'>
            </div>
        </form>";

        //return Html::a($status,Yii::$app->urlManager->createUrl(['adminsidemenu/changestatus','id'=>Yii::$app->MyFunctions->encode( $this->admin_sidemenu_id )]),['class'=>$class,"data-toggle"=>"tooltip","title"=>$title]);
    }


    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            
            if($this->isNewRecord)
            {
                $Homescreen = Adminsidemenu::find()->orderBy('display_order DESC')->all();
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
