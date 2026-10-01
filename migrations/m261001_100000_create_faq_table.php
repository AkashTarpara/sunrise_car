<?php

use yii\db\Migration;

class m261001_100000_create_faq_table extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        // FAQ / Terms & Conditions entries managed from the CMS; description holds editor HTML
        $this->createTable('{{%faq}}', [
            'faq_id' => $this->primaryKey(),
            'type' => $this->string(50)->notNull()->defaultValue('faq'),
            'title' => $this->string(255)->notNull(),
            'description' => 'LONGTEXT NOT NULL',
            'display_order' => $this->integer()->notNull()->defaultValue(0),
            'status' => "ENUM('Active','Inactive') NOT NULL DEFAULT 'Active'",
            'created_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->dateTime()->null(),
        ], $tableOptions);

        $this->createIndex('idx_faq_type_status_order', '{{%faq}}', ['type', 'status', 'display_order']);

        if ($this->db->schema->getTableSchema('{{%admin_sidemenu}}', true) !== null) {
            $this->execute("
                INSERT INTO {{%admin_sidemenu}}
                    (title, controller_name, sub_menu_action_name, action_name, icon, display_order, is_multiple, status, created_at)
                SELECT
                    'FAQ & Terms',
                    'faq',
                    '',
                    'index',
                    'ti ti-help',
                    COALESCE(MAX(display_order), 0) + 1,
                    'No',
                    'Active',
                    NOW()
                FROM {{%admin_sidemenu}}
                WHERE NOT EXISTS (
                    SELECT 1 FROM (
                        SELECT admin_sidemenu_id FROM {{%admin_sidemenu}} WHERE controller_name = 'faq'
                    ) AS existing_faq_menu
                )
            ");
        }
    }

    public function safeDown()
    {
        if ($this->db->schema->getTableSchema('{{%admin_sidemenu}}', true) !== null) {
            $this->delete('{{%admin_sidemenu}}', ['controller_name' => 'faq']);
        }

        $this->dropTable('{{%faq}}');
    }
}
