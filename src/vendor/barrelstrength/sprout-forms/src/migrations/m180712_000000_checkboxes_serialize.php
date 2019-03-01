<?php

namespace barrelstrength\sproutforms\migrations;

use barrelstrength\sproutforms\fields\formfields\Checkboxes;
use craft\db\Migration;
use craft\db\Query;
use craft\helpers\Json;

/**
 * m180712_000000_checkboxes_serialize migration.
 */
class m180712_000000_checkboxes_serialize extends Migration
{
    /**
     * @inheritdoc
     * @throws \yii\base\NotSupportedException
     */
    public function safeUp()
    {
        $fields = (new Query())
            ->select(['id', 'handle'])
            ->from(['{{%fields}}'])
            ->where(['type' => Checkboxes::class])
            ->andWhere(['like', 'context', 'sproutForms:'])
            ->all();

        $forms = (new Query())
            ->select(['id', 'handle'])
            ->from(['{{%sproutforms_forms}}'])
            ->all();

        foreach ($forms as $form) {
            $contentTable = '{{%sproutformscontent_'.$form['handle'].'}}';

            foreach ($fields as $field) {
                $column = 'field_'.$field['handle'];

                if ($this->db->columnExists($contentTable, $column)) {

                    $entries = (new Query())
                        ->select(['id', $column])
                        ->from([$contentTable])
                        ->all();

                    foreach ($entries as $entry) {
                        $newValue = [];
                        $value = $entry[$column];
                        $values = Json::decode($value, true);

                        if ($values) {
                            foreach ($values as $value) {
                                if (isset($value['value'])) {
                                    $newValue[] = $value['value'];
                                }
                            }
                        }

                        if ($newValue) {
                            $newValueAsJson = Json::encode($newValue);
                            $this->update($contentTable, [$column => $newValueAsJson], ['id' => $entry['id']], [], false);
                        }
                    }
                }
            }
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180712_000000_checkboxes_serialize cannot be reverted.\n";
        return false;
    }
}
