<?php

use yii\db\Migration;

class m260918_130000_create_booking_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%booking}}', [
            'id' => $this->primaryKey(),
            'booking_number' => $this->string(32)->notNull(),
            'fleet_id' => $this->integer()->notNull(),
            'pickup_date' => $this->date()->notNull(),
            'pickup_time' => $this->time()->notNull(),
            'service' => $this->string(100)->null(),
            'pickup_location_type' => $this->string(50)->null(),
            'dropoff_location_type' => $this->string(50)->null(),
            'pickup' => $this->text()->null(),
            'dropoff' => $this->text()->null(),
            'ride_data' => $this->json()->notNull(),
            'quote_data' => $this->json()->notNull(),
            'extras' => $this->json()->null(),
            'notes' => $this->text()->null(),
            'passenger_data' => $this->json()->notNull(),
            'promo_code' => $this->string(100)->null(),
            'payment_preference' => $this->string(50)->null(),
            'terms_accepted' => $this->boolean()->notNull()->defaultValue(false),
            'base_price' => $this->decimal(10, 2)->notNull()->defaultValue(0),
            'km_per_hour_price' => $this->decimal(10, 2)->notNull()->defaultValue(0),
            'distance_price' => $this->decimal(10, 2)->notNull()->defaultValue(0),
            'total' => $this->decimal(10, 2)->notNull()->defaultValue(0),
            'currency' => $this->string(3)->notNull()->defaultValue('usd'),
            'payment_intent_id' => $this->string(255)->null(),
            'payment_status' => $this->string(30)->notNull()->defaultValue('pending'),
            'booking_status' => $this->string(30)->notNull()->defaultValue('pending_payment'),
            'created_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->dateTime()->null(),
            'deleted_at' => $this->dateTime()->null(),
        ]);

        $this->createIndex('ux_booking_number', '{{%booking}}', 'booking_number', true);
        $this->createIndex('idx_booking_fleet_date', '{{%booking}}', ['fleet_id', 'pickup_date']);
        $this->createIndex('idx_booking_date', '{{%booking}}', 'pickup_date');
        $this->createIndex('idx_booking_payment_intent', '{{%booking}}', 'payment_intent_id');
        $this->createIndex('idx_booking_status', '{{%booking}}', ['booking_status', 'payment_status']);
        $this->addForeignKey(
            'fk_booking_fleet_id',
            '{{%booking}}',
            'fleet_id',
            '{{%fleet}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );

        if ($this->db->schema->getTableSchema('{{%admin_sidemenu}}', true) !== null) {
            $this->execute("\n                INSERT INTO {{%admin_sidemenu}}\n                    (title, controller_name, sub_menu_action_name, action_name, icon, display_order, is_multiple, status, created_at)\n                SELECT 'Bookings', 'booking', '', 'index', 'ti ti-calendar-event',\n                    COALESCE(MAX(display_order), 0) + 1, 'No', 'Active', NOW()\n                FROM {{%admin_sidemenu}}\n                WHERE NOT EXISTS (\n                    SELECT 1 FROM (SELECT admin_sidemenu_id FROM {{%admin_sidemenu}} WHERE controller_name = 'booking') existing_booking_menu\n                )\n            ");
        }
    }

    public function safeDown()
    {
        if ($this->db->schema->getTableSchema('{{%admin_sidemenu}}', true) !== null) {
            $this->delete('{{%admin_sidemenu}}', ['controller_name' => 'booking']);
        }

        $this->dropForeignKey('fk_booking_fleet_id', '{{%booking}}');
        $this->dropTable('{{%booking}}');
    }
}
