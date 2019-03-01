<?php
/**
 * @link      https://sprout.barrelstrengthdesign.com/
 * @copyright Copyright (c) Barrel Strength Design LLC
 * @license   http://sprout.barrelstrengthdesign.com/license
 */

namespace barrelstrength\sproutbasefields\services;

use craft\base\Field;
use yii\base\Component;
use Craft;

/**
 * Class Url
 */
class Url extends Component
{
    /**
     * Validates a phone number against a given mask/pattern
     *
     * @param       $value
     * @param Field $field
     *
     * @return bool
     */
    public function validate($value, Field $field): bool
    {
        $customPattern = $field->customPattern;
        $checkPattern = $field->customPatternToggle;

        if ($customPattern && $checkPattern) {
            // Use backtick as delimiters as they are invalid characters for emails
            $customPattern = '`'.$customPattern.'`';

            if (preg_match($customPattern, $value)) {
                return true;
            }
        } else {
            $path = parse_url($value, PHP_URL_PATH);
            $encodedPath = array_map('urlencode', explode('/', $path));
            $url = str_replace($path, implode('/', $encodedPath), $value);

            if (!filter_var($url, FILTER_VALIDATE_URL) === false) {
                return true;
            }
        }

        return false;
    }

    /**
     * Return error message
     *
     * @param $fieldName
     * @param $field
     *
     * @return string
     */
    public function getErrorMessage($fieldName, $field): string
    {
        if ($field->customPatternToggle && $field->customPatternErrorMessage) {
            return Craft::t('sprout-base-fields', $field->customPatternErrorMessage);
        }

        return Craft::t('sprout-base-fields', $fieldName.' must be a valid URL.');
    }

}
