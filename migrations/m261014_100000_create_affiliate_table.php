<?php

use yii\db\Migration;

class m261014_100000_create_affiliate_table extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        // Affiliate (chauffeur / fleet operator) onboarding applications submitted from /affiliates; file columns hold relative upload paths
        $this->createTable('{{%affiliate}}', [
            'affiliate_id' => $this->primaryKey(),

            // Primary contact
            'full_name' => $this->string(255)->notNull(),
            'email' => $this->string(255)->notNull(),
            'phone' => $this->string(50)->notNull(),

            // 1. Vehicle details & passenger capacity
            'model_year' => $this->string(4)->notNull(),
            'make_model' => $this->string(255)->notNull(),
            'exterior_finish' => $this->string(50)->null(),
            'interior_upholstery' => $this->string(50)->null(),
            'vehicle_class' => $this->string(50)->null(),
            'tlc_base_affiliation' => $this->string(255)->null(),

            // 2. Vehicle & chauffeur photographs
            'photo_front' => $this->string(500)->notNull(),
            'photo_cabin' => $this->string(500)->notNull(),
            'photo_rear_plate' => $this->string(500)->notNull(),
            'photo_driver_headshot' => $this->string(500)->notNull(),

            // 3. Driver & livery regulatory credentials
            'doc_dmv_license' => $this->string(500)->notNull(),
            'doc_dmv_license_back' => $this->string(500)->null(),
            'doc_tlc_license' => $this->string(500)->notNull(),
            'doc_tlc_inspection' => $this->string(500)->notNull(),
            'doc_dmv_inspection' => $this->string(500)->notNull(),
            'doc_livery_registration' => $this->string(500)->notNull(),
            'doc_commercial_insurance' => $this->string(500)->notNull(),
            'doc_liability_coi' => $this->string(500)->notNull(),

            // 4. Tax & payout verification
            'legal_business_name' => $this->string(255)->notNull(),
            'tax_id' => $this->string(50)->notNull(),
            'payout_method' => $this->string(20)->null(),
            'routing_number' => $this->string(20)->notNull(),
            'account_number' => $this->string(34)->notNull(),
            'doc_w9' => $this->string(500)->notNull(),
            'doc_voided_check' => $this->string(500)->notNull(),

            'status' => $this->tinyInteger()->notNull()->defaultValue(0)->comment('0 Pending, 1 Approved, 2 Rejected'),
            'admin_notes' => $this->text()->null(),
            'created_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->dateTime()->null(),
        ], $tableOptions);

        $this->createIndex('idx_affiliate_email', '{{%affiliate}}', 'email');
        $this->createIndex('idx_affiliate_status_created', '{{%affiliate}}', ['status', 'created_at']);

        if ($this->db->schema->getTableSchema('{{%admin_sidemenu}}', true) !== null) {
            $this->execute("
                INSERT INTO {{%admin_sidemenu}}
                    (title, controller_name, sub_menu_action_name, action_name, icon, display_order, is_multiple, status, created_at)
                SELECT
                    'Affiliates',
                    'affiliate',
                    '',
                    'index',
                    'ti ti-steering-wheel',
                    COALESCE(MAX(display_order), 0) + 1,
                    'No',
                    'Active',
                    NOW()
                FROM {{%admin_sidemenu}}
                WHERE NOT EXISTS (
                    SELECT 1 FROM (
                        SELECT admin_sidemenu_id FROM {{%admin_sidemenu}} WHERE controller_name = 'affiliate'
                    ) AS existing_affiliate_menu
                )
            ");
        }
    }

    public function safeDown()
    {
        if ($this->db->schema->getTableSchema('{{%admin_sidemenu}}', true) !== null) {
            $this->delete('{{%admin_sidemenu}}', ['controller_name' => 'affiliate']);
        }

        $this->dropTable('{{%affiliate}}');
    }
}
