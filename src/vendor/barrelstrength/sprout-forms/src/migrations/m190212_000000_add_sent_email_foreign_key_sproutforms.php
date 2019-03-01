<?php /** @noinspection ClassConstantCanBeUsedInspection */

namespace barrelstrength\sproutforms\migrations;

use craft\db\Migration;
use barrelstrength\sproutbaseemail\migrations\m190212_000004_add_sent_email_foreign_key;

class m190212_000000_add_sent_email_foreign_key_sproutforms extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp(): bool
    {
        $migration = new m190212_000004_add_sent_email_foreign_key();

        ob_start();
        $migration->safeUp();
        ob_end_clean();

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown(): bool
    {
        echo "m190212_000000_add_sent_email_foreign_key_sproutforms cannot be reverted.\n";
        return false;
    }
}
