<?php

use yii\db\Migration;

/**
 * Removes "Minibus" from the fleet type list.
 *
 * Existing Minibus vehicles are soft-deleted along with their images and parked
 * on a valid value so the ENUM can be applied.
 */
class m261013_100000_remove_minibus_fleet_type extends Migration
{
    private $oldTypes = [
        'Sedan',
        'SUV',
        'Bulletproof SUV',
        'Stretch Limo',
        'Antique',
        'Sprinter',
        'Jet Sprinter',
        'Minibus',
        'Mini Coach',
        'Motor Coach',
    ];

    private $newTypes = [
        'Sedan',
        'SUV',
        'Bulletproof SUV',
        'Stretch Limo',
        'Antique',
        'Sprinter',
        'Jet Sprinter',
        'Mini Coach',
        'Motor Coach',
    ];

    public function safeUp()
    {
        $fleet = '{{%fleet}}';
        $now = date('Y-m-d H:i:s');

        $removedIds = (new \yii\db\Query())
            ->select('id')
            ->from($fleet)
            ->where(['type' => 'Minibus'])
            ->column($this->db);

        if (!empty($removedIds)) {
            $this->update('{{%fleet_image}}', ['deleted_at' => $now], ['fleet_id' => $removedIds, 'deleted_at' => null]);
            $this->update($fleet, ['deleted_at' => $now], ['id' => $removedIds, 'deleted_at' => null]);
            // Park them on a valid value so the ENUM can be applied; they stay soft-deleted
            $this->update($fleet, ['type' => 'Sedan'], ['id' => $removedIds]);
        }

        $this->execute("ALTER TABLE {$fleet} MODIFY `type` " . $this->enumSql($this->newTypes) . " NOT NULL");
    }

    public function safeDown()
    {
        $this->execute("ALTER TABLE {{%fleet}} MODIFY `type` " . $this->enumSql($this->oldTypes) . " NOT NULL");
    }

    private function enumSql(array $values)
    {
        return 'ENUM(' . implode(',', array_map([$this->db, 'quoteValue'], $values)) . ')';
    }
}
