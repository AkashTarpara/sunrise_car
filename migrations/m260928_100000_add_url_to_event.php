<?php

use yii\db\Migration;

class m260928_100000_add_url_to_event extends Migration
{
    public function safeUp()
    {
        $table = '{{%event}}';
        $tableSchema = $this->db->getTableSchema($table);

        // External link so the frontend can open the event directly
        if ($tableSchema && !isset($tableSchema->columns['url'])) {
            $this->addColumn($table, 'url', $this->string(500)->null()->after('location'));
        }
    }

    public function safeDown()
    {
        $table = '{{%event}}';
        $tableSchema = $this->db->getTableSchema($table);
        if ($tableSchema && isset($tableSchema->columns['url'])) {
            $this->dropColumn($table, 'url');
        }
    }
}
