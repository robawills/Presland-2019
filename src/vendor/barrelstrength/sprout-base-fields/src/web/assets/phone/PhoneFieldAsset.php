<?php
/**
 * @link      https://sprout.barrelstrengthdesign.com/
 * @copyright Copyright (c) Barrel Strength Design LLC
 * @license   http://sprout.barrelstrengthdesign.com/license
 */

namespace barrelstrength\sproutbasefields\web\assets\phone;

use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

class PhoneFieldAsset extends AssetBundle
{
    public function init()
    {
        // define the path that your publishable resources live
        $this->sourcePath = '@sproutbasefields/web/assets';

        // define the dependencies
        $this->depends = [
            CpAsset::class
        ];

        $this->js = [
            'phone/dist/js/sproutphonefield.js'
        ];

        $this->css = [
            'base/css/sproutfields.css',
        ];

        parent::init();
    }
}