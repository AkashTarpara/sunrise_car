<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "installation_scheduling_request".
 */
class InstallationSchedulingRequest extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'installation_scheduling_request';
    }

    public function rules()
    {
        return [
            [[
                'customer_name',
                'phone_number',
                'email_address',
                'installation_address_line1',
                'property_type',
                'preferred_contact_time',
                'preferred_installation_timeframe',
                'will_someone_be_present',
                'agreed',
                'signature',
                'signed_date',
            ], 'required'],
            [['email_address'], 'email'],
            [['additional_project_notes'], 'string'],
            [['signed_date'], 'date', 'format' => 'php:Y-m-d'],
            [[
                'will_someone_be_present',
                'gate_code_required',
                'building_access_required',
                'elevator_reservation_required',
                'parking_restrictions',
                'access_other',
                'agreed',
                'created_at',
                'updated_at',
            ], 'integer'],
            [['agreed'], 'compare', 'compareValue' => 1, 'operator' => '==', 'message' => Yii::t('app', 'Acknowledgement must be accepted.')],
            [['phone_number'], 'string', 'max' => 50],
            [[
                'property_type',
                'preferred_contact_time',
                'preferred_installation_timeframe',
            ], 'string', 'max' => 100],
            [[
                'customer_name',
                'email_address',
                'property_type_other',
                'access_other_note',
                'signature',
            ], 'string', 'max' => 255],
            [[
                'installation_address_line1',
                'installation_address_line2',
            ], 'string', 'max' => 500],
            [[
                'gate_code_required',
                'building_access_required',
                'elevator_reservation_required',
                'parking_restrictions',
                'access_other',
            ], 'default', 'value' => 0],
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
            'customer_name' => Yii::t('app', 'Customer Name'),
            'phone_number' => Yii::t('app', 'Phone Number'),
            'email_address' => Yii::t('app', 'Email Address'),
            'installation_address_line1' => Yii::t('app', 'Installation Address Line 1'),
            'installation_address_line2' => Yii::t('app', 'Installation Address Line 2'),
            'property_type' => Yii::t('app', 'Property Type'),
            'property_type_other' => Yii::t('app', 'Property Type Other'),
            'preferred_contact_time' => Yii::t('app', 'Preferred Contact Time'),
            'preferred_installation_timeframe' => Yii::t('app', 'Preferred Installation Timeframe'),
            'will_someone_be_present' => Yii::t('app', 'Will Someone Be Present'),
            'gate_code_required' => Yii::t('app', 'Gate Code Required'),
            'building_access_required' => Yii::t('app', 'Building Access Required'),
            'elevator_reservation_required' => Yii::t('app', 'Elevator Reservation Required'),
            'parking_restrictions' => Yii::t('app', 'Parking Restrictions'),
            'access_other' => Yii::t('app', 'Other Access Restriction'),
            'access_other_note' => Yii::t('app', 'Other Access Note'),
            'additional_project_notes' => Yii::t('app', 'Additional Project Notes'),
            'agreed' => Yii::t('app', 'Agreed'),
            'signature' => Yii::t('app', 'Signature'),
            'signed_date' => Yii::t('app', 'Date'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
}
