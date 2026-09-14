<?php

use yii\db\Migration;

class m260911_181000_add_subject_to_contact_us extends Migration
{
    public function safeUp()
    {
        $table = '{{%contact_us}}';
        $schema = $this->db->schema->getTableSchema($table, true);

        if ($schema === null || isset($schema->columns['subject'])) {
            return;
        }

        $column = $this->string(255)->null();
        if (isset($schema->columns['number'])) {
            $column = $column->after('number');
        } elseif (isset($schema->columns['phone_number'])) {
            $column = $column->after('phone_number');
        }

        $this->addColumn($table, 'subject', $column);
    }

    public function safeDown()
    {
        $table = '{{%contact_us}}';
        $schema = $this->db->schema->getTableSchema($table, true);

        if ($schema !== null && isset($schema->columns['subject'])) {
            $this->dropColumn($table, 'subject');
        }
    }
}
