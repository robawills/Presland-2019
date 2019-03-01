<?php
/**
 * @link      https://sprout.barrelstrengthdesign.com/
 * @copyright Copyright (c) Barrel Strength Design LLC
 * @license   http://sprout.barrelstrengthdesign.com/license
 */

namespace barrelstrength\sproutbasefields\web\assets\quill;

use craft\web\AssetBundle;

class QuillAsset extends AssetBundle
{
    public function init()
    {
        // define the path that your publishable resources live
        $this->sourcePath = '@sproutbasefieldslib/quill';

        // define the relative path to CSS/JS files that should be registered with the page
        // when this asset bundle is registered
        $this->js = [
            'quill.min.js',
        ];

        $this->css = [
            'quill.snow.css',
        ];

        parent::init();
    }
}