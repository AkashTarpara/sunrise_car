<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "trade_pro_application".
 */
class Tradeproapplication extends \yii\db\ActiveRecord
{
    const STATUS_PENDING = 0;
    const STATUS_APPROVED = 1;
    const STATUS_REJECTED = 2;

    public static function tableName()
    {
        return 'trade_pro_application';
    }

    public function rules()
    {
        return [
            [['business_name', 'primary_contact_name', 'terms_accepted'], 'required'],
            [['business_address'], 'string'],
            [['coverage_amount'], 'number'],
            [['signed_date'], 'safe'],
            [[
                'taxable_account',
                'resale_account',
                'tax_exempt_organization',
                'flooring_contractor',
                'general_contractor',
                'builder_developer',
                'property_management',
                'retail_showroom',
                'distributor_supplier',
                'commercial_end_user',
                'spc_flooring',
                'commercial_glue_down_lvt',
                'project_supply_logistics',
                'private_label_programs',
                'national_bulk_supply',
                'government_institutional_projects',
                'terms_accepted',
                'status',
                'created_at',
                'updated_at',
            ], 'integer'],
            [['terms_accepted'], 'compare', 'compareValue' => 1, 'operator' => '==', 'message' => Yii::t('app', 'Terms must be accepted.')],
            [['email'], 'email'],
            [[
                'business_name',
                'primary_contact_name',
                'title_position',
                'email',
                'website',
                'printed_name',
                'sign_title',
                'business_type_other',
                'insurance_carrier',
                'policy_number',
                'product_interest_other',
            ], 'string', 'max' => 255],
            [['city', 'state', 'federal_ein_tax_id'], 'string', 'max' => 100],
            [['zip'], 'string', 'max' => 20],
            [['phone', 'mobile'], 'string', 'max' => 50],
            [['st3_resale_certificate', 'tax_exemption_certificate', 'authorized_signature'], 'string', 'max' => 500],
            [['status'], 'default', 'value' => self::STATUS_PENDING],
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $time = time();
            if ($insert) {
                $this->created_at = $time;
            }
            $this->updated_at = $time;
            return true;
        }

        return false;
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'business_name' => Yii::t('app', 'Business Name'),
            'primary_contact_name' => Yii::t('app', 'Primary Contact Name'),
            'title_position' => Yii::t('app', 'Title / Position'),
            'business_address' => Yii::t('app', 'Business Address'),
            'city' => Yii::t('app', 'City'),
            'state' => Yii::t('app', 'State'),
            'zip' => Yii::t('app', 'ZIP'),
            'phone' => Yii::t('app', 'Phone'),
            'mobile' => Yii::t('app', 'Mobile'),
            'email' => Yii::t('app', 'Email'),
            'website' => Yii::t('app', 'Website'),
            'federal_ein_tax_id' => Yii::t('app', 'Federal EIN / Tax ID'),
            'terms_accepted' => Yii::t('app', 'Terms Accepted'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
}
