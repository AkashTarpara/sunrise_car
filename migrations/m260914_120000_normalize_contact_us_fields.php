<?php

use yii\db\Migration;

class m260914_120000_normalize_contact_us_fields extends Migration
{
    public function safeUp()
    {
        $table = '{{%contact_us}}';
        $schema = $this->db->schema->getTableSchema($table, true);

        if ($schema === null) {
            $this->createTable($table, [
                'contact_us_id' => $this->primaryKey(),
                'full_name' => $this->string(255)->notNull(),
                'email' => $this->string(255)->notNull(),
                'phone_number' => $this->string(255)->notNull(),
                'subject' => $this->string(255)->notNull(),
                'message' => $this->text()->notNull(),
                'created_at' => $this->dateTime()->notNull(),
            ]);
            return;
        }

        $this->addColumnIfMissing($table, 'full_name', $this->string(255)->null()->after('contact_us_id'));
        $this->addColumnIfMissing($table, 'email', $this->string(255)->null()->after('full_name'));
        $this->addColumnIfMissing($table, 'phone_number', $this->string(255)->null()->after('email'));
        $this->addColumnIfMissing($table, 'subject', $this->string(255)->null()->after('phone_number'));
        $this->addColumnIfMissing($table, 'message', $this->text()->null()->after('subject'));
        $this->addColumnIfMissing($table, 'created_at', $this->dateTime()->null()->after('message'));

        $schema = $this->db->schema->getTableSchema($table, true);

        if (isset($schema->columns['first_name'])) {
            $nameSql = isset($schema->columns['last_name'])
                ? 'TRIM(CONCAT(COALESCE(`first_name`, ""), " ", COALESCE(`last_name`, "")))'
                : 'TRIM(COALESCE(`first_name`, ""))';
            $this->execute("UPDATE {$this->db->quoteTableName($table)} SET `full_name` = {$nameSql} WHERE (`full_name` IS NULL OR `full_name` = '')");
        }

        if (isset($schema->columns['number'])) {
            $this->execute("UPDATE {$this->db->quoteTableName($table)} SET `phone_number` = `number` WHERE (`phone_number` IS NULL OR `phone_number` = '')");
        }

        $this->execute("UPDATE {$this->db->quoteTableName($table)} SET `full_name` = 'Unknown' WHERE `full_name` IS NULL");
        $this->execute("UPDATE {$this->db->quoteTableName($table)} SET `email` = CONCAT('unknown-', `contact_us_id`, '@example.invalid') WHERE `email` IS NULL OR `email` = ''");
        $this->execute("UPDATE {$this->db->quoteTableName($table)} SET `phone_number` = 'N/A' WHERE `phone_number` IS NULL");
        $this->execute("UPDATE {$this->db->quoteTableName($table)} SET `subject` = 'Contact Request' WHERE `subject` IS NULL OR `subject` = ''");
        $this->execute("UPDATE {$this->db->quoteTableName($table)} SET `message` = '' WHERE `message` IS NULL");
        $this->execute("UPDATE {$this->db->quoteTableName($table)} SET `created_at` = NOW() WHERE `created_at` IS NULL");

        $this->alterColumn($table, 'full_name', $this->string(255)->notNull());
        $this->alterColumn($table, 'email', $this->string(255)->notNull());
        $this->alterColumn($table, 'phone_number', $this->string(255)->notNull());
        $this->alterColumn($table, 'subject', $this->string(255)->notNull());
        $this->alterColumn($table, 'message', $this->text()->notNull());
        $this->alterColumn($table, 'created_at', $this->dateTime()->notNull());

        foreach (['first_name', 'last_name', 'number', 'address'] as $column) {
            $this->dropColumnIfExists($table, $column);
        }
    }

    public function safeDown()
    {
        $table = '{{%contact_us}}';
        $schema = $this->db->schema->getTableSchema($table, true);
        if ($schema === null) {
            return;
        }

        $this->addColumnIfMissing($table, 'first_name', $this->string(255)->null()->after('contact_us_id'));
        $this->addColumnIfMissing($table, 'last_name', $this->string(255)->null()->after('first_name'));
        $this->addColumnIfMissing($table, 'number', $this->string(255)->null()->after('email'));
        $this->addColumnIfMissing($table, 'address', $this->text()->null()->after('subject'));

        $schema = $this->db->schema->getTableSchema($table, true);
        if (isset($schema->columns['full_name'])) {
            $this->execute("UPDATE {$this->db->quoteTableName($table)} SET `first_name` = `full_name` WHERE (`first_name` IS NULL OR `first_name` = '')");
        }

        if (isset($schema->columns['phone_number'])) {
            $this->execute("UPDATE {$this->db->quoteTableName($table)} SET `number` = `phone_number` WHERE (`number` IS NULL OR `number` = '')");
        }

        foreach (['full_name', 'phone_number'] as $column) {
            $this->dropColumnIfExists($table, $column);
        }
    }

    private function addColumnIfMissing($table, $column, $type)
    {
        $schema = $this->db->schema->getTableSchema($table, true);
        if ($schema !== null && !isset($schema->columns[$column])) {
            $this->addColumn($table, $column, $type);
        }
    }

    private function dropColumnIfExists($table, $column)
    {
        $schema = $this->db->schema->getTableSchema($table, true);
        if ($schema !== null && isset($schema->columns[$column])) {
            $this->dropColumn($table, $column);
        }
    }
}
