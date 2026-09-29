<?php

use yii\db\Migration;

class m260929_100000_add_appuser_id_to_booking extends Migration
{
    public function safeUp()
    {
        $table = '{{%booking}}';
        $tableSchema = $this->db->getTableSchema($table);

        // Link bookings to the logged-in app user so they can see their booking history
        if ($tableSchema && !isset($tableSchema->columns['appuser_id'])) {
            $this->addColumn($table, 'appuser_id', $this->integer()->null()->after('booking_number'));
            $this->createIndex('idx_booking_appuser_id', $table, ['appuser_id', 'pickup_date']);
        }

        // Backfill existing bookings by matching passenger email with the app user's email
        $this->execute("
            UPDATE {{%booking}} b
            INNER JOIN {{%appuser}} u
                ON LOWER(u.email) = LOWER(JSON_UNQUOTE(JSON_EXTRACT(b.passenger_data, '$.email')))
                AND u.is_deleted <> 'Yes'
            SET b.appuser_id = u.appuser_id
            WHERE b.appuser_id IS NULL
        ");
    }

    public function safeDown()
    {
        $table = '{{%booking}}';
        $tableSchema = $this->db->getTableSchema($table);
        if ($tableSchema && isset($tableSchema->columns['appuser_id'])) {
            $this->dropIndex('idx_booking_appuser_id', $table);
            $this->dropColumn($table, 'appuser_id');
        }
    }
}
