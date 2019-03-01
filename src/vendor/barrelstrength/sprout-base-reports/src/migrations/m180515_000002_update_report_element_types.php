<?php /** @noinspection ClassConstantCanBeUsedInspection */

namespace barrelstrength\sproutbasereports\migrations;

use craft\db\Migration;

/**
 * m180515_000002_update_report_element_types migration.
 */
class m180515_000002_update_report_element_types extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp(): bool
    {
        $seedClasses = [
            0 => [
                'oldType' => 'barrelstrength\sproutbase\elements\sproutreports\Report',
                'newType' => 'barrelstrength\sproutbasereports\elements\Report'
            ]
        ];

        foreach ($seedClasses as $seedClass) {
            $this->update('{{%elements}}', [
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
        echo "m180515_000002_update_report_element_types cannot be reverted.\n";
        return false;
    }
}
