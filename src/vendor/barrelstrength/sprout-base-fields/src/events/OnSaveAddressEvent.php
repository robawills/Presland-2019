<?php
/**
 * @link      https://sprout.barrelstrengthdesign.com/
 * @copyright Copyright (c) Barrel Strength Design LLC
 * @license   http://sprout.barrelstrengthdesign.com/license
 */

namespace barrelstrength\sproutbasefields\events;

use yii\base\Event;

class OnSaveAddressEvent extends Event
{
    public $model;
    public $source;
}
