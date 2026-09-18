<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "trade_pro_partner".
 */
class Tradepropartner extends \yii\db\ActiveRecord
{
    const STATUS_PENDING = 0;
    const STATUS_APPROVED = 1;
    const STATUS_REJECTED = 2;

    /**
     * @var string used only for API submission, not persisted
     */
    public $confirm_password;

    public static function tableName()
    {
        return 'trade_pro_partner';
    }

    public function rules()
    {
        return [
            [['full_name', 'email', 'password', 'terms_accepted'], 'required'],
            [['email'], 'email'],
            [['email'], 'unique'],
            [['password'], 'string', 'min' => 6],
            [['confirm_password'], 'compare', 'compareAttribute' => 'password', 'message' => Yii::t('app', 'Passwords do not match.')],
            [['terms_accepted'], 'compare', 'compareValue' => 1, 'operator' => '==', 'message' => Yii::t('app', 'Terms must be accepted.')],
            [[
                'interest_flooring',
                'interest_wall_panels',
                'interest_decking',
                'interest_installation_services',
                'interest_other',
                'terms_accepted',
                'status',
                'created_at',
                'updated_at',
            ], 'integer'],
            [['project_start_date', 'signed_date'], 'safe'],
            [['project_details'], 'string', 'max' => 500],
            [[
                'full_name',
                'email',
                'job_title',
                'years_experience',
                'project_type',
                'project_location',
                'project_size',
                'printed_name',
                'signature_title',
            ], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 50],
            [['password'], 'string', 'max' => 255],
            [['signature_image'], 'string', 'max' => 500],
            [['status'], 'default', 'value' => self::STATUS_PENDING],
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $time = time();
            if ($insert) {
                $this->created_at = $time;
                if (!empty($this->password)) {
                    $this->password = sha1($this->password);
                }
            }
            $this->updated_at = $time;
            return true;
        }

        return false;
    }

    public function fields()
    {
        $fields = parent::fields();
        unset($fields['password']);

        return $fields;
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'full_name' => Yii::t('app', 'Full Name'),
            'email' => Yii::t('app', 'Email Address'),
            'phone' => Yii::t('app', 'Phone Number'),
            'password' => Yii::t('app', 'Password'),
            'confirm_password' => Yii::t('app', 'Confirm Password'),
            'job_title' => Yii::t('app', 'Job Title / Role'),
            'years_experience' => Yii::t('app', 'Years of Experience'),
            'interest_flooring' => Yii::t('app', 'Flooring'),
            'interest_wall_panels' => Yii::t('app', 'Wall Panels'),
            'interest_decking' => Yii::t('app', 'Decking'),
            'interest_installation_services' => Yii::t('app', 'Installation Services'),
            'interest_other' => Yii::t('app', 'Other'),
            'project_type' => Yii::t('app', 'Project Type'),
            'project_location' => Yii::t('app', 'Project Location'),
            'project_start_date' => Yii::t('app', 'Estimated Project Start Date'),
            'project_size' => Yii::t('app', 'Estimated Project Size'),
            'project_details' => Yii::t('app', 'Additional Project Details'),
            'terms_accepted' => Yii::t('app', 'Terms Accepted'),
            'signature_image' => Yii::t('app', 'Electronic Signature'),
            'printed_name' => Yii::t('app', 'Printed Name'),
            'signature_title' => Yii::t('app', 'Title'),
            'signed_date' => Yii::t('app', 'Date'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    public function getStatusLabel()
    {
        $labels = [
            self::STATUS_PENDING => Yii::t('app', 'Pending'),
            self::STATUS_APPROVED => Yii::t('app', 'Approved'),
            self::STATUS_REJECTED => Yii::t('app', 'Rejected'),
        ];

        return $labels[$this->status] ?? Yii::t('app', 'Unknown');
    }

    public function interestSummary()
    {
        $map = [
            'interest_flooring' => 'Flooring',
            'interest_wall_panels' => 'Wall Panels',
            'interest_decking' => 'Decking',
            'interest_installation_services' => 'Installation Services',
            'interest_other' => 'Other',
        ];

        $selected = [];
        foreach ($map as $attribute => $label) {
            if (!empty($this->$attribute)) {
                $selected[] = $label;
            }
        }

        return implode(', ', $selected);
    }
}
