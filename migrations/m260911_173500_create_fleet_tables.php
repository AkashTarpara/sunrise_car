<?php

use yii\db\Migration;

class m260911_173500_create_fleet_tables extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%fleet}}', [
            'id' => $this->primaryKey(),
            'label' => $this->string(255)->notNull(),
            'name' => $this->string(255)->notNull(),
            'passenger' => $this->integer()->null(),
            'laggage' => $this->integer()->null(),
            'description' => $this->text()->null(),
            'status' => "ENUM('Active','Inactive') NOT NULL DEFAULT 'Active'",
            'type' => "ENUM('SEDANS','SUVS','LIMOUSINES','VANS','BUSES','MOTORCYCLES','TRUCKS') NOT NULL",
            'created_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->dateTime()->null(),
            'deleted_at' => $this->dateTime()->null(),
        ]);

        $this->createTable('{{%fleet_image}}', [
            'id' => $this->primaryKey(),
            'fleet_id' => $this->integer()->notNull(),
            'image' => $this->string(255)->notNull(),
            'created_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->dateTime()->null(),
            'deleted_at' => $this->dateTime()->null(),
        ]);

        $this->createIndex('idx_fleet_status', '{{%fleet}}', 'status');
        $this->createIndex('idx_fleet_type', '{{%fleet}}', 'type');
        $this->createIndex('idx_fleet_deleted_at', '{{%fleet}}', 'deleted_at');
        $this->createIndex('idx_fleet_image_fleet_id', '{{%fleet_image}}', 'fleet_id');
        $this->createIndex('idx_fleet_image_deleted_at', '{{%fleet_image}}', 'deleted_at');

        $this->addForeignKey(
            'fk_fleet_image_fleet_id',
            '{{%fleet_image}}',
            'fleet_id',
            '{{%fleet}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        if ($this->db->schema->getTableSchema('{{%admin_sidemenu}}', true) !== null) {
            $this->execute("
                INSERT INTO {{%admin_sidemenu}}
                    (title, controller_name, sub_menu_action_name, action_name, icon, display_order, is_multiple, status, created_at)
                SELECT
                    'Fleet',
                    'fleet',
                    '',
                    'index',
                    'ti ti-car',
                    COALESCE(MAX(display_order), 0) + 1,
                    'No',
                    'Active',
                    NOW()
                FROM {{%admin_sidemenu}}
                WHERE NOT EXISTS (
                    SELECT 1 FROM (
                        SELECT admin_sidemenu_id FROM {{%admin_sidemenu}} WHERE controller_name = 'fleet'
                    ) AS existing_fleet_menu
                )
            ");
        }
    }

    public function safeDown()
    {
        if ($this->db->schema->getTableSchema('{{%admin_sidemenu}}', true) !== null) {
            $this->delete('{{%admin_sidemenu}}', ['controller_name' => 'fleet']);
        }

        $this->dropForeignKey('fk_fleet_image_fleet_id', '{{%fleet_image}}');
        $this->dropTable('{{%fleet_image}}');
        $this->dropTable('{{%fleet}}');
    }
}
