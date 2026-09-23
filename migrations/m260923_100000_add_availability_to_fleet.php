<?php

use yii\db\Migration;

class m260923_100000_add_availability_to_fleet extends Migration
{
    public function safeUp()
    {
        $table = '{{%fleet}}';

        // Manual admin override: true = available, false = manually blocked (maintenance etc.)
        $this->addColumnIfMissing($table, 'is_available', $this->tinyInteger(1)->notNull()->defaultValue(1)->after('status'));

        // Auto-updated: set to pickup_date of the latest confirmed booking for this fleet.
        // Fleet is available again when today >= available_after (or available_after IS NULL)
        $this->addColumnIfMissing($table, 'available_after', $this->date()->null()->after('is_available'));
    }

    public function safeDown()
    {
        $table = '{{%fleet}}';
        if ($this->columnExists($table, 'available_after')) {
            $this->dropColumn($table, 'available_after');
        }
        if ($this->columnExists($table, 'is_available')) {
            $this->dropColumn($table, 'is_available');
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
