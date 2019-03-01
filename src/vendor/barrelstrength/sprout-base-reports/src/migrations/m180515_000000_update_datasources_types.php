<?php /** @noinspection ClassConstantCanBeUsedInspection */

/** @noinspection ClassConstantCanBeUsedInspection */

namespace barrelstrength\sproutbasereports\migrations;

use craft\db\Migration;

/**
 * m180515_000000_update_datasources_types migration.
 */
class m180515_000000_update_datasources_types extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp(): bool
    {
        $seedClasses = [
            0 => [
                'oldType' => 'barrelstrength\sproutreports\integrations\sproutreports\datasources\CustomQuery',
                'newType' => 'barrelstrength\sproutreports\datasources\CustomQuery'
            ],
            1 => [
                'oldType' => 'barrelstrength\sproutreports\integrations\sproutreports\datasources\CustomTwigTemplate',
                'newType' => 'barrelstrength\sproutreports\datasources\CustomTwigTemplate'
            ]
        ];

        foreach ($seedClasses as $seedClass) {
            $this->update('{{%sproutreports_datasources}}', [
                'type' => $seedClass['newType']
            ], ['type' => $seedClass['oldType']], [], false);
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown(): bool
    {
        echo "m180515_000000_update_datasources_types cannot be reverted.\n";
        return false;
    }
}
