<?php /** @noinspection ClassConstantCanBeUsedInspection */

namespace barrelstrength\sproutbaseemail\migrations;

use craft\db\Migration;

/**
 * m180515_000003_update_notification_eventId_types migration.
 */
class m180515_000003_update_notification_eventId_types extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp(): bool
    {
        // This migration isn't relevant to most users, this was a minor change during beta development
        $types = [
            0 => [
                'oldType' => 'sproutforms-basicsproutformsnotification',
                'newType' => 'barrelstrength\sproutforms\integrations\sproutemail\emailtemplates\basic\BasicSproutFormsNotification'
            ],
            1 => [
                'oldType' => 'sproutemail-basictemplates',
                'newType' => 'barrelstrength\sproutbaseemail\emailtemplates\BasicTemplates'
            ]
        ];

        foreach ($types as $type) {
            $this->update('{{%sproutemail_notificationemails}}', [
                'emailTemplateId' => $type['newType']
            ], ['emailTemplateId' => $type['oldType']], [], false);
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown(): bool
    {
        echo "m180515_000003_update_notification_eventId_types cannot be reverted.\n";
        return false;
    }
}
