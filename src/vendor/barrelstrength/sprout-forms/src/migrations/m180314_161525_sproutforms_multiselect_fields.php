<?php

namespace barrelstrength\sproutforms\migrations;

use craft\db\Migration;
use craft\db\Query;
use barrelstrength\sproutforms\fields\formfields\Multiselect;
use craft\fields\Multiselect as CraftMultiselect;

/**
 * m180314_161525_sproutforms_multiselect_fields migration.
 */
class m180314_161525_sproutforms_multiselect_fields extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $multiselectFields = (new Query())
            ->select(['id', 'handle', 'settings'])
            ->from(['{{%fields}}'])
            ->where(['type' => CraftMultiselect::class])
            ->andWhere(['like', 'context', 'sproutForms:'])
            ->all();

        foreach ($multiselectFields as $multiselectField) {
            $this->update('{{%fields}}', ['type' => Multiselect::class], ['id' => $multiselectField['id']], [], false);
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180314_161525_sproutforms_multiselect_fields cannot be reverted.\n";
        return false;
    }
}
