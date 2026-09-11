<?php

use yii\db\Migration;

class m260911_192500_add_sprinters_to_fleet_type extends Migration
{
    public function safeUp()
    {
        $this->execute("
            ALTER TABLE {{%fleet}}
            MODIFY `type` ENUM('SEDANS','SUVS','LIMOUSINES','SPRINTERS','VANS','BUSES','MOTORCYCLES','TRUCKS') NOT NULL
        ");
    }

    public function safeDown()
    {
        $this->execute("
            ALTER TABLE {{%fleet}}
            MODIFY `type` ENUM('SEDANS','SUVS','LIMOUSINES','VANS','BUSES','MOTORCYCLES','TRUCKS') NOT NULL
        ");
    }
}
