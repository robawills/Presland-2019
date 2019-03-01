<?php

namespace barrelstrength\sproutbaseimport\services;

use barrelstrength\sproutbaseimport\SproutBaseImport;
use Craft;
use craft\base\Component;

class ImportUtilities extends Component
{
    /**
     * @var array
     */
    public $errors;

    /**
     * Call this method to get singleton
     *
     * @param bool $refresh
     *
     * @return ImportUtilities|null|static
     */
    public static function Instance($refresh = false)
    {
        static $inst = null;
        if ($inst === null) {
            $inst = new ImportUtilities();
        }

        return $inst;
    }

    /**
     * @param string $key
     * @param mixed  $data
     * @param mixed  $default
     *
     * @return mixed
     */
    public function getValueByKey($key, $data, $default = null)
    {
        if (!is_array($data)) {
            SproutBaseImport::error(Craft::t('sprout-base-import', 'getValueByKey() was passed in a non-array as data.'));

            return $default;
        }

        if (!is_string($key) || empty($key) || !count($data)) {
            return $default;
        }

        // @assert $key contains a dot notated string
        if (strpos($key, '.') !== false) {
            $keys = explode('.', $key);

            foreach ($keys as $innerKey) {
                if (!array_key_exists($innerKey, $data)) {
                    return $default;
                }

                $data = $data[$innerKey];
            }

            return $data;
        }

        return array_key_exists($key, $data) ? $data[$key] : $default;
    }
}