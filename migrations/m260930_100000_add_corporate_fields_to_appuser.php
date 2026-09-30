<?php

use yii\db\Migration;

class m260930_100000_add_corporate_fields_to_appuser extends Migration
{
    private $columns = [
        'account_type',
        'company_name',
        'company_country',
        'company_region',
        'company_city',
        'travel_volume',
        'job_title',
    ];

    public function safeUp()
    {
        $table = '{{%appuser}}';
        $tableSchema = $this->db->getTableSchema($table, true);
        if (!$tableSchema) {
            return;
        }

        // Corporate account registration details (Individual users keep the defaults)
        $definitions = [
            'account_type' => $this->string(20)->notNull()->defaultValue('Individual'),
            'company_name' => $this->string(255)->null(),
            'company_country' => $this->string(50)->null(),
            'company_region' => $this->string(100)->null(),
            'company_city' => $this->string(100)->null(),
            'travel_volume' => $this->string(50)->null(),
            'job_title' => $this->string(150)->null(),
        ];

        foreach ($definitions as $column => $definition) {
            if (!isset($tableSchema->columns[$column])) {
                $this->addColumn($table, $column, $definition);
            }
        }

        $this->createIndex('idx_appuser_account_type', $table, 'account_type');
    }

    public function safeDown()
    {
        $table = '{{%appuser}}';
        $tableSchema = $this->db->getTableSchema($table, true);
        if (!$tableSchema) {
            return;
        }

        if (isset($tableSchema->columns['account_type'])) {
            $this->dropIndex('idx_appuser_account_type', $table);
        }

        foreach (array_reverse($this->columns) as $column) {
            if (isset($tableSchema->columns[$column])) {
                $this->dropColumn($table, $column);
            }
        }
    }
}
