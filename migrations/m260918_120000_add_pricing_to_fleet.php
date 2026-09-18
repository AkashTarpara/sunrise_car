<?php

use yii\db\Migration;

class m260918_120000_add_pricing_to_fleet extends Migration
{
    public function safeUp()
    {
        $this->addColumn(
            '{{%fleet}}',
            'base_price',
            $this->decimal(10, 2)->notNull()->defaultValue(100)
        );
        $this->addColumn(
            '{{%fleet}}',
            'km_per_hour_price',
            $this->decimal(10, 2)->notNull()->defaultValue(24)
        );
    }

    public function safeDown()
    {
        $this->dropColumn('{{%fleet}}', 'km_per_hour_price');
        $this->dropColumn('{{%fleet}}', 'base_price');
    }
}