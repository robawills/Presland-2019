<?php /** @noinspection ClassConstantCanBeUsedInspection */

namespace barrelstrength\sproutforms\migrations;

use craft\db\Migration;

/**
 * m180719_000000_forms_and_entry_elements_types migration.
 */
class m180719_000000_forms_and_entry_elements_types extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $types = [
            0 => [
                'oldType' => 'SproutForms_Form',
                'newType' => 'barrelstrength\sproutforms\elements\Form'
            ],
            1 => [
                'oldType' => 'SproutForms_Entry',
                'newType' => 'barrelstrength\sproutforms\elements\Entry'
            ]
        ];

        foreach ($types as $type) {
            $this->update('{{%elements}}', [
                'type' => $type['newType']
            ], ['type' => $type['oldType']], [], false);
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180719_000000_forms_and_entry_elements_types cannot be reverted.\n";
        return false;
    }
}
