<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "affiliate".
 *
 * @property int $affiliate_id
 * @property string $full_name
 * @property string $email
 * @property string $phone
 * @property string $model_year
 * @property string $make_model
 * @property string|null $exterior_finish
 * @property string|null $interior_upholstery
 * @property string|null $vehicle_class
 * @property string|null $tlc_base_affiliation
 * @property string $photo_front
 * @property string $photo_cabin
 * @property string $photo_rear_plate
 * @property string $photo_driver_headshot
 * @property string $doc_dmv_license
 * @property string|null $doc_dmv_license_back
 * @property string $doc_tlc_license
 * @property string $doc_tlc_inspection
 * @property string $doc_dmv_inspection
 * @property string $doc_livery_registration
 * @property string $doc_commercial_insurance
 * @property string $doc_liability_coi
 * @property string $legal_business_name
 * @property string $tax_id
 * @property string|null $payout_method
 * @property string $routing_number
 * @property string $account_number
 * @property string $doc_w9
 * @property string $doc_voided_check
 * @property int $status
 * @property string|null $admin_notes
 * @property string $created_at
 * @property string|null $updated_at
 */
class Affiliate extends \yii\db\ActiveRecord
{
    const STATUS_PENDING = 0;
    const STATUS_APPROVED = 1;
    const STATUS_REJECTED = 2;

    public static function tableName()
    {
        return 'affiliate';
    }

    public static function exteriorFinishOptions()
    {
        return [
            'jet_black_gloss' => 'Jet Black Gloss (Standard)',
            'obsidian_black_metallic' => 'Obsidian Black Metallic',
            'black_raven' => 'Black Raven',
            'other_luxury_deep_black' => 'Other Luxury Deep Black',
        ];
    }

    public static function interiorUpholsteryOptions()
    {
        return [
            'black_nappa_leather' => 'Black Nappa / Leather',
            'dark_truffle_espresso' => 'Dark Truffle / Espresso',
            'charcoal_executive' => 'Charcoal Executive',
        ];
    }

    public static function vehicleClassOptions()
    {
        return [
            'first_sedan' => 'First Sedan (3 Pass, 3 Lug)',
            'luxury_suv' => 'Luxury SUV (6 Pass, 6 Lug)',
            'vip_sprinter' => 'VIP Sprinter (14 Pass, High-Top)',
        ];
    }

    public static function payoutMethodOptions()
    {
        return [
            'ach' => 'ACH Direct Deposit',
            'zelle' => 'Direct Zelle Business',
        ];
    }

    public static function statusOptions()
    {
        return [
            self::STATUS_PENDING => Yii::t('app', 'Pending'),
            self::STATUS_APPROVED => Yii::t('app', 'Approved'),
            self::STATUS_REJECTED => Yii::t('app', 'Rejected'),
        ];
    }

    /**
     * Upload fields => [required, allowed extensions].
     */
    public static function fileFields()
    {
        $images = ['jpg', 'jpeg', 'png', 'webp'];
        $docs = ['pdf', 'jpg', 'jpeg', 'png'];

        return [
            'photo_front' => [true, $images],
            'photo_cabin' => [true, $images],
            'photo_rear_plate' => [true, $images],
            'photo_driver_headshot' => [true, $images],
            'doc_dmv_license' => [true, $docs],
            'doc_dmv_license_back' => [false, $docs],
            'doc_tlc_license' => [true, $docs],
            'doc_tlc_inspection' => [true, $docs],
            'doc_dmv_inspection' => [true, $docs],
            'doc_livery_registration' => [true, $docs],
            'doc_commercial_insurance' => [true, $docs],
            'doc_liability_coi' => [true, $docs],
            'doc_w9' => [true, ['pdf']],
            'doc_voided_check' => [true, $docs],
        ];
    }

    public function rules()
    {
        $requiredFiles = [];
        foreach (self::fileFields() as $field => $config) {
            if ($config[0]) {
                $requiredFiles[] = $field;
            }
        }

        return [
            [array_merge([
                'full_name', 'email', 'phone', 'model_year', 'make_model',
                'legal_business_name', 'tax_id', 'routing_number', 'account_number',
            ], $requiredFiles), 'required'],
            [['full_name', 'email', 'phone', 'make_model', 'tlc_base_affiliation', 'legal_business_name', 'tax_id', 'routing_number', 'account_number'], 'trim'],
            ['email', 'email'],
            ['model_year', 'match', 'pattern' => '/^(19|20)\d{2}$/', 'message' => Yii::t('app', 'Model Year must be a valid 4-digit year.')],
            ['routing_number', 'match', 'pattern' => '/^\d{9}$/', 'message' => Yii::t('app', 'Routing Number must be 9 digits.')],
            ['account_number', 'match', 'pattern' => '/^[0-9A-Za-z]{4,34}$/', 'message' => Yii::t('app', 'Account Number is invalid.')],
            // Free text allowed; known options are normalized to their keys by the API
            [['exterior_finish', 'interior_upholstery'], 'trim'],
            [['exterior_finish', 'interior_upholstery'], 'string', 'max' => 50],
            ['vehicle_class', 'in', 'range' => array_keys(self::vehicleClassOptions())],
            ['payout_method', 'in', 'range' => array_keys(self::payoutMethodOptions())],
            ['status', 'in', 'range' => array_keys(self::statusOptions())],
            ['status', 'default', 'value' => self::STATUS_PENDING],
            [['admin_notes'], 'string'],
            [['full_name', 'email', 'make_model', 'tlc_base_affiliation', 'legal_business_name'], 'string', 'max' => 255],
            [['phone', 'tax_id'], 'string', 'max' => 50],
            [array_keys(self::fileFields()), 'string', 'max' => 500],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        $now = date('Y-m-d H:i:s');
        if ($insert) {
            $this->created_at = $now;
        }
        $this->updated_at = $now;

        return true;
    }

    public function getStatusLabel()
    {
        return self::statusOptions()[$this->status] ?? Yii::t('app', 'Unknown');
    }

    public function optionLabel($attribute)
    {
        $map = [
            'exterior_finish' => self::exteriorFinishOptions(),
            'interior_upholstery' => self::interiorUpholsteryOptions(),
            'vehicle_class' => self::vehicleClassOptions(),
            'payout_method' => self::payoutMethodOptions(),
        ];

        return $map[$attribute][$this->$attribute] ?? $this->$attribute;
    }

    public static function mask($value)
    {
        $value = (string) $value;
        if (strlen($value) <= 4) {
            return $value;
        }

        return str_repeat('*', strlen($value) - 4) . substr($value, -4);
    }

    public function attributeLabels()
    {
        return [
            'affiliate_id' => Yii::t('app', 'ID'),
            'full_name' => Yii::t('app', 'Full Name'),
            'email' => Yii::t('app', 'Email Address'),
            'phone' => Yii::t('app', 'Mobile Phone'),
            'model_year' => Yii::t('app', 'Model Year'),
            'make_model' => Yii::t('app', 'Make & Model'),
            'exterior_finish' => Yii::t('app', 'Exterior Finish'),
            'interior_upholstery' => Yii::t('app', 'Interior Upholstery'),
            'vehicle_class' => Yii::t('app', 'Class & Passenger Configuration'),
            'tlc_base_affiliation' => Yii::t('app', 'NYC TLC Base Affiliation'),
            'photo_front' => Yii::t('app', 'Front 3/4 Profile'),
            'photo_cabin' => Yii::t('app', 'Passenger Cabin'),
            'photo_rear_plate' => Yii::t('app', 'Rear Profile & Plate'),
            'photo_driver_headshot' => Yii::t('app', 'Driver Headshot'),
            'doc_dmv_license' => Yii::t('app', "DMV Driver's License"),
            'doc_dmv_license_back' => Yii::t('app', "DMV Driver's License (Back)"),
            'doc_tlc_license' => Yii::t('app', "TLC Driver's License"),
            'doc_tlc_inspection' => Yii::t('app', 'TLC Inspection & Diamond Decal'),
            'doc_dmv_inspection' => Yii::t('app', 'DMV Inspection Sticker'),
            'doc_livery_registration' => Yii::t('app', 'Vehicle Livery Registration'),
            'doc_commercial_insurance' => Yii::t('app', 'Commercial Insurance'),
            'doc_liability_coi' => Yii::t('app', 'Liability Certificate (ACORD COI)'),
            'legal_business_name' => Yii::t('app', 'Legal Entity / Business Name'),
            'tax_id' => Yii::t('app', 'EIN / Federal Tax ID / SSN'),
            'payout_method' => Yii::t('app', 'Disbursement Channel Preference'),
            'routing_number' => Yii::t('app', 'Routing Number (ABA)'),
            'account_number' => Yii::t('app', 'Account Number'),
            'doc_w9' => Yii::t('app', 'W-9 Signed PDF'),
            'doc_voided_check' => Yii::t('app', 'Voided Check / Bank Letter'),
            'status' => Yii::t('app', 'Status'),
            'admin_notes' => Yii::t('app', 'Admin Notes'),
            'created_at' => Yii::t('app', 'Submitted At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
}
