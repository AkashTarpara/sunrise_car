<?php

use yii\db\Migration;

class m260925_100000_add_hourly_fields_to_fleet extends Migration
{
    public function safeUp()
    {
        $table = '{{%fleet}}';

        // Hourly charge / price per hour for hourly/charter booking
        $this->addColumnIfMissing($table, 'hourly_price', $this->decimal(10, 2)->notNull()->defaultValue(0.00)->after('km_per_hour_price'));

        // Minimum hours required to book this vehicle
        $this->addColumnIfMissing($table, 'minimum_hours', $this->integer()->notNull()->defaultValue(1)->after('hourly_price'));
    }

    public function safeDown()
    {
        $table = '{{%fleet}}';
        if ($this->columnExists($table, 'minimum_hours')) {
            $this->dropColumn($table, 'minimum_hours');
        }
        if ($this->columnExists($table, 'hourly_price')) {
            $this->dropColumn($table, 'hourly_price');
        }
    }

    private function addColumnIfMissing($table, $column, $type)
    {
        $tableSchema = $this->db->getTableSchema($table);
        if ($tableSchema && !isset($tableSchema->columns[$column])) {
            $this->addColumn($table, $column, $type);
        }
    }

    private function columnExists($table, $column)
    {
        $tableSchema = $this->db->getTableSchema($table);
        return $tableSchema && isset($tableSchema->columns[$column]);
    }
}
