<?php

namespace barrelstrength\sproutbaseimport\records;

use craft\db\ActiveRecord;

class Seed extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName(): string
    {
        return '{{%sproutimport_seeds}}';
    }
}