<?php

use yii\db\Migration;

class m261006_100000_add_reserve_your_ride_confirmation_msg_to_general_setting extends Migration
{
    public function safeUp()
    {
        $table = '{{%general_setting}}';
        $tableSchema = $this->db->getTableSchema($table, true);
        if (!$tableSchema) {
            return;
        }

        // Message shown to the user after submitting the "Reserve Your Ride" form
        if (!isset($tableSchema->columns['reserve_your_ride_confirmation_msg'])) {
            $this->addColumn($table, 'reserve_your_ride_confirmation_msg', $this->text()->null());
        }
    }

    public function safeDown()
    {
        $table = '{{%general_setting}}';
        $tableSchema = $this->db->getTableSchema($table, true);
        if (!$tableSchema) {
            return;
        }

        if (isset($tableSchema->columns['reserve_your_ride_confirmation_msg'])) {
            $this->dropColumn($table, 'reserve_your_ride_confirmation_msg');
        }
    }
}
