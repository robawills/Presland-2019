<?php

$vendorDir = dirname(__DIR__);

return array (
  'ether/simplemap' => 
  array (
    'class' => 'ether\\simplemap\\SimpleMap',
    'basePath' => $vendorDir . '/ether/simplemap/src',
    'handle' => 'simplemap',
    'aliases' => 
    array (
      '@ether/simplemap' => $vendorDir . '/ether/simplemap/src',
    ),
    'name' => 'SimpleMap',
    'version' => '3.3.4',
    'schemaVersion' => '3.0.0',
    'description' => 'A beautifully simple Google Map field type.',
    'developer' => 'Ether Creative',
    'developerUrl' => 'https://ethercreative.co.uk',
  ),
  'craftcms/redactor' => 
  array (
    'class' => 'craft\\redactor\\Plugin',
    'basePath' => $vendorDir . '/craftcms/redactor/src',
    'handle' => 'redactor',
    'aliases' => 
    array (
      '@craft/redactor' => $vendorDir . '/craftcms/redactor/src',
    ),
    'name' => 'Redactor',
    'version' => '2.3.2',
    'description' => 'Edit rich text content in Craft CMS using Redactor by Imperavi.',
    'developer' => 'Pixel & Tonic',
    'developerUrl' => 'https://pixelandtonic.com/',
  ),
  'barrelstrength/sprout-forms' => 
  array (
    'class' => 'barrelstrength\\sproutforms\\SproutForms',
    'basePath' => $vendorDir . '/barrelstrength/sprout-forms/src',
    'handle' => 'sprout-forms',
    'aliases' => 
    array (
      '@barrelstrength/sproutforms' => $vendorDir . '/barrelstrength/sprout-forms/src',
    ),
    'name' => 'Sprout Forms',
    'version' => '3.0.0-beta.45',
    'description' => 'Simple, beautiful forms. 100% control.',
    'developer' => 'Barrel Strength',
    'developerUrl' => 'https://barrelstrengthdesign.com',
    'documentationUrl' => 'https://sprout.barrelstrengthdesign.com/craft-plugins/forms',
    'changelogUrl' => 'https://raw.githubusercontent.com/BarrelStrength/sprout-forms/master/CHANGELOG.md',
    'downloadUrl' => 'https://github.com/BarrelStrength/sprout-forms/archive/master.zip',
  ),
);
