<?php /** @noinspection ClassConstantCanBeUsedInspection */

/** @noinspection ClassConstantCanBeUsedInspection */

namespace barrelstrength\sproutbaseemail\migrations;

use craft\db\Migration;
use craft\db\Query;
use craft\helpers\Json;

/**
 * m181026_000000_update_notification_data migration.
 */
class m181026_000000_update_notification_data extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp(): bool
    {
        // Craft 2 envent ids
        $types = [
            0 => [
                'oldType' => 'SproutEmail-users-saveUser',
                'newType' => 'barrelstrength\sproutemail\events\notificationevents\UsersSave',
                'pluginHandle' => 'sprout-email'
            ],

            1 => [
                'oldType' => 'SproutForms-sproutForms-saveEntry',
                'newType' => 'barrelstrength\sproutforms\integrations\sproutemail\events\notificationevents\SaveEntryEvent',
                'pluginHandle' => 'sprout-forms'
            ],

            2 => [
                'oldType' => 'SproutEmail-users-deleteUser',
                'newType' => 'barrelstrength\sproutemail\events\notificationevents\UsersDelete',
                'pluginHandle' => 'sprout-email'
            ],

            3 => [
                'oldType' => 'SproutEmail-entries-saveEntry',
                'newType' => 'barrelstrength\sproutemail\events\notificationevents\EntriesSave',
                'pluginHandle' => 'sprout-email'
            ],

            4 => [
                'oldType' => 'SproutEmail-entries-deleteEntry',
                'newType' => 'barrelstrength\sproutemail\events\notificationevents\EntriesDelete',
                'pluginHandle' => 'sprout-email'
            ]
        ];

        foreach ($types as $type) {
            $notifications = (new Query())
                ->select(['id', 'settings'])
                ->from(['{{%sproutemail_notificationemails}}'])
                ->where(['eventId' => $type['oldType']])
                ->all();

            if ($notifications) {
                foreach ($notifications as $notification) {
                    $options = Json::decode($notification['settings'], true);
                    $newOptions = [];
                    if (isset($options['craft'])) {
                        if (isset($options['craft']['saveUser'])) {
                            $newOptions = $options['craft']['saveUser'];
                        } else if (isset($options['craft']['deleteUser'])) {
                            $newOptions = $options['craft']['deleteUser'];
                        } else if (isset($options['craft']['saveEntry'])) {
                            $newOptions = $options['craft']['saveEntry'];
                        } else if (isset($options['craft']['deleteEntry'])) {
                            $newOptions = $options['craft']['deleteEntry'];
                        }
                    } else if (isset($options['sproutForms']['saveEntry'])) {
                        $newOptions = $options['sproutForms']['saveEntry'];
                    }

                    $this->update('{{%sproutemail_notificationemails}}', [
                        'eventId' => $type['newType'],
                        'pluginHandle' => $type['pluginHandle'],
                        'settings' => Json::encode($newOptions)
                    ], ['id' => $notification['id']], [], false);
                }
            }
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown(): bool
    {
        echo "m181026_000000_update_notification_data cannot be reverted.\n";
        return false;
    }
}
