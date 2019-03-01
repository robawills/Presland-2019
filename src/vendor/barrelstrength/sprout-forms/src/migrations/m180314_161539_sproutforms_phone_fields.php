<?php

namespace barrelstrength\sproutforms\migrations;

use craft\db\Migration;
use craft\db\Query;
use barrelstrength\sproutforms\fields\formfields\SingleLine;
use craft\helpers\Json;

/**
 * m180314_161539_sproutforms_phone_fields migration.
 */
class m180314_161539_sproutforms_phone_fields extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $newSettings = [
            'placeholder' => '',
            'charLimit' => '255',
            'columnType' => 'string'
        ];

        $fields = (new Query())
            ->select(['id', 'handle', 'settings'])
            ->from(['{{%fields}}'])
            ->where(['type' => 'SproutFields_Phone'])
            ->andWhere(['like', 'context', 'sproutForms:'])
            ->all();

        foreach ($fields as $field) {
            $settingsAsJson = Json::encode($newSettings);
            $this->update('{{%fields}}', ['type' => SingleLine::class, 'settings' => $settingsAsJson], ['id' => $field['id']], [], false);
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180314_161539_sproutforms_phone_fields cannot be reverted.\n";
        return false;
    }
}
