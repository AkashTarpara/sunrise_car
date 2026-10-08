<?php

use yii\db\Migration;

/**
 * Replaces the fleet type list with the new categories (in display order):
 * Sedan, SUV, Bulletproof SUV, Stretch Limo, Antique, Sprinter, Jet Sprinter,
 * Minibus, Mini Coach, Motor Coach.
 *
 * Existing vehicles are mapped to the closest new type. Vehicles whose type has
 * no equivalent (VANS, BUSES, MOTORCYCLES, TRUCKS) are soft-deleted along with
 * their images.
 */
class m261008_100000_update_fleet_types extends Migration
{
    private $newTypes = [
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

    private $oldTypes = ['SEDANS', 'SUVS', 'LIMOUSINES', 'SPRINTERS', 'VANS', 'BUSES', 'MOTORCYCLES', 'TRUCKS'];

    private $upMap = [
        'SEDANS'     => 'Sedan',
        'SUVS'       => 'SUV',
        'LIMOUSINES' => 'Stretch Limo',
        'SPRINTERS'  => 'Sprinter',
    ];

    private $downMap = [
        'Sedan'           => 'SEDANS',
        'SUV'             => 'SUVS',
        'Bulletproof SUV' => 'SUVS',
        'Stretch Limo'    => 'LIMOUSINES',
        'Antique'         => 'LIMOUSINES',
        'Sprinter'        => 'SPRINTERS',
        'Jet Sprinter'    => 'SPRINTERS',
        'Minibus'         => 'BUSES',
        'Mini Coach'      => 'BUSES',
        'Motor Coach'     => 'BUSES',
    ];

    public function safeUp()
    {
        $fleet = '{{%fleet}}';
        $now = date('Y-m-d H:i:s');

        // Loosen the column so old and new values can coexist during the update
        $this->alterColumn($fleet, 'type', $this->string(50)->notNull());

        foreach ($this->upMap as $old => $new) {
            $this->update($fleet, ['type' => $new], ['type' => $old]);
        }

        // Remove vehicles whose type is no longer offered
        $removedIds = (new \yii\db\Query())
            ->select('id')
            ->from($fleet)
            ->where(['not in', 'type', $this->newTypes])
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
        $fleet = '{{%fleet}}';

        $this->alterColumn($fleet, 'type', $this->string(50)->notNull());

        foreach ($this->downMap as $new => $old) {
            $this->update($fleet, ['type' => $old], ['type' => $new]);
        }

        $this->execute("ALTER TABLE {$fleet} MODIFY `type` " . $this->enumSql($this->oldTypes) . " NOT NULL");
    }

    private function enumSql(array $values)
    {
        return 'ENUM(' . implode(',', array_map([$this->db, 'quoteValue'], $values)) . ')';
    }
}
