<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "general_setting".
 *
 * @property string $setting_id
 * @property string $terms_conditions
 * @property string $privacy_policy
 */
class Generalsetting extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'general_setting';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            //[['setting_id', 'terms_conditions', 'privacy_policy','matches','century','half_century','wickets','trophies','about_us','android_app_version','ios_app_version','version_message'], 'required'],

            [['setting_id', 'matches', 'century', 'half_century', 'wickets', 'trophies', 'android_app_version', 'ios_app_version'], 'integer'],
            [['tax'], 'number'],
            [['terms_conditions', 'privacy_policy', 'about_us', 'facebook', 'instagram', 'twitter', 'youtube', 'tiktok', 'snapchat', 'ticket_policy', 'cookie', 'code_of_conduct', 'user_terms_conditions', 'user_privacy_policy', 'user_terms_service', 'club_terms_service', 'api_key', 'sps_flooring_title', 'sps_flooring_sub_title', 'weekly_bestsellers_title', 'weekly_bestsellers_sub_title', 'clients_say_title', 'clients_say_sub_title', 'pick_up_delivery', 'agree_delivery', 'meta_description', 'latitude', 'longitude', 'warehouse_address', 'contact_us_image'], 'string'],

            [['setting_id'], 'unique'],

            [['facebook', 'instagram', 'twitter', 'youtube', 'tiktok', 'snapchat', 'access_token', 'token_type', 'force_update', 'threed_url', 'ios_unity_file', 'contact_no', 'telephone_no', 'email', 'address', 'map_url', 'ticket_policy', 'linkedin', 'cookie', 'code_of_conduct', 'user_terms_conditions', 'user_privacy_policy', 'user_terms_service', 'club_terms_service', 'api_key', 'sps_flooring_title', 'sps_flooring_sub_title', 'weekly_bestsellers_title', 'weekly_bestsellers_sub_title', 'clients_say_title', 'clients_say_sub_title', 'pick_up_delivery', 'agree_delivery', 'meta_title', 'meta_tag', 'latitude', 'longitude', 'warehouse_address', 'contact_us_image', 'delivery_charge', 'need_to_display_roomvo', 'terms_conditions_trade_pro'], 'safe'],

            [
                ['delivery_charge'],
                'match',
                'pattern' => '/^\d+(\.\d{1,2})?$/',
                'message' => 'Price must be a valid number with up to 2 decimal places.'
            ],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'setting_id' => Yii::t('app', 'Setting ID'),
            'terms_conditions' => Yii::t('app', 'Terms Conditions'),
            'privacy_policy' => Yii::t('app', 'Privacy Policy'),
            'user_terms_conditions' => Yii::t('app', 'User Terms Conditions'),
            'user_privacy_policy' => Yii::t('app', 'User Privacy Policy'),
            'user_terms_service' => Yii::t('app', 'User Terms Service'),
            'club_terms_service' => Yii::t('app', 'Club Terms Service'),
            'ticket_policy' => Yii::t('app', 'Ticket Policy'),
            'matches' => Yii::t('app', 'Matches'),
            'century' => Yii::t('app', '100\'S'),
            'half_century' => Yii::t('app', '50\'S'),
            'wickets' => Yii::t('app', 'Wickets'),
            'trophies' => Yii::t('app', 'Trophies'),
            'about_us' => Yii::t('app', 'About Us'),
            'code_of_conduct' => Yii::t('app', 'Code Of Conduct'),
            'android_app_version' => Yii::t('app', 'Android App version'),
            'ios_app_version' => Yii::t('app', 'Ios App Version'),
            'version_message' => Yii::t('app', 'Version Message'),
            'force_update' => Yii::t('app', 'Force Update'),
            'threed_url' => Yii::t('app', 'Android Unity 3D File'),
            'ios_unity_file' => Yii::t('app', 'Ios Unity AR File'),
            'facebook' => Yii::t('app', 'Facebook'),
            'instagram' => Yii::t('app', 'Instagram'),
            'twitter' => Yii::t('app', 'Yelp'),
            'youtube' => Yii::t('app', 'YouTube'),
            'tiktok' => Yii::t('app', 'TikTok'),
            'contact_no' => Yii::t('app', 'Contact No'),
            'telephone_no' => Yii::t('app', 'Telephone No'),
            'email' => Yii::t('app', 'Email'),
            'address' => Yii::t('app', 'Address'),
            'map_url' => Yii::t('app', 'Map Url'),
            'snapchat' => Yii::t('app', 'Snapchat'),
            'linkedin' => Yii::t('app', 'Linkedin'),
            'cookie' => Yii::t('app', 'Cookie'),
            'api_key' => Yii::t('app', 'Api Key'),
            'sps_flooring_title' => Yii::t('app', 'Sps Flooring Title'),
            'sps_flooring_sub_title' => Yii::t('app', 'Sps Flooring Sub Title'),
            'weekly_bestsellers_title' => Yii::t('app', 'Weekl Bestsellers Title'),
            'weekly_bestsellers_sub_title' => Yii::t('app', 'Weekl Bestsellers Sub Title'),
            'clients_say_title' => Yii::t('app', 'Clients Say Title'),
            'clients_say_sub_title' => Yii::t('app', 'Clients Say Sub Title'),
            'pick_up_delivery' => Yii::t('app', 'Pick Up Delivery Message'),
            'agree_delivery' => Yii::t('app', 'Agree Delivery Message'),
            'meta_title' => Yii::t('app', 'Meta Title'),
            'meta_tag' => Yii::t('app', 'Meta Tag'),
            'meta_description' => Yii::t('app', 'Meta Description'),
            'tax' => Yii::t('app', 'Tax %'),
            'warehouse_address' => Yii::t('app', 'Warehouse Address'),
            'latitude' => Yii::t('app', 'Warehouse Latitude'),
            'longitude' => Yii::t('app', 'Warehouse Longitude'),
            'contact_us_image' => Yii::t('app', 'Contact Us Image'),
        ];
    }


    public function upload()
    {
        if (is_object($this->contact_us_image)) {

            if (file_exists(Yii::getAlias("@app") . "/" . $this->getOldAttribute('contact_us_image')) && !empty($this->getOldAttribute('contact_us_image'))) {
                unlink(Yii::getAlias("@app") . "/" . $this->getOldAttribute('contact_us_image'));
            }

            $path = 'uploads/images/generalsetting/';
            if (!is_dir($path)) {
                mkdir($path, 0777, true);
                chmod($path, 0777);
            }

            $name = Yii::$app->MyFunctions->random_string(32);
            $this->contact_us_image->saveAs($path . $name . '.' . $this->contact_us_image->extension);
            $this->contact_us_image = $path . $name . '.' . $this->contact_us_image->extension;
            // $this->image = Yii::$app->MyFunctions->compress_image($this->image->tempName,$path . $name . '.' . $this->image->extension,30);
            // Yii::$app->MyFunctions->CopyToMediaBank($this->image,'Image');
        }
    }

    /* public function upload()
    {
        if (is_object($this->download_calendar)) {

            if ($this->getOldAttribute('download_calendar')) {
                $url = $this->getOldAttribute('download_calendar');
                $check_image = Yii::$app->MyFunctions->CheckAndDeleteS3Image($url);
            }

            $path = 'uploads/images/generalsetting/downloadcalendar/';


            $this->download_calendar = Yii::$app->MyFunctions->StoreS3Image($this->download_calendar, $path);
        }
    } */
}
