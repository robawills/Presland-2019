<?php

namespace barrelstrength\sproutforms\fields\formfields;

use Craft;
use craft\helpers\Db;
use craft\helpers\Template as TemplateHelper;
use yii\db\Schema;
use craft\base\ElementInterface;
use LitEmoji\LitEmoji;
use craft\base\PreviewableFieldInterface;

use barrelstrength\sproutforms\base\FormField;

/**
 * Class PlainText
 *
 * @package Craft
 *
 * @property string      $contentColumnType
 * @property string      $svgIconPath
 * @property null|string $settingsHtml
 * @property mixed       $exampleInputHtml
 */
class Paragraph extends FormField implements PreviewableFieldInterface
{
    /**
     * @var string
     */
    public $cssClasses;

    /**
     * @var string|null The input’s placeholder text
     */
    public $placeholder = '';

    /**
     * @var int The minimum number of rows the input should have, if multi-line
     */
    public $initialRows = 4;

    /**
     * @var int|null The maximum number of characters allowed in the field
     */
    public $charLimit;

    /**
     * @var string The type of database column the field should have in the content table
     */
    public $columnType = Schema::TYPE_TEXT;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = parent::rules();
        $rules[] = [['charLimit'], 'validateCharLimit'];

        return $rules;
    }

    /**
     * @inheritdoc
     */
    public static function displayName(): string
    {
        return Craft::t('sprout-forms', 'Paragraph');
    }

    /**
     * @inheritdoc
     */
    public function getContentColumnType(): string
    {
        return $this->columnType;
    }

    /**
     * @inheritdoc
     */
    public function normalizeValue($value, ElementInterface $element = null)
    {
        if ($value !== null) {
            $value = LitEmoji::shortcodeToUnicode($value);
            $value = trim(preg_replace('/\R/u', "\n", $value));
        }

        return $value !== '' ? $value : null;
    }

    /**
     * Validates that the Character Limit isn't set to something higher than the Column Type will hold.
     *
     * @param string $attribute
     */
    public function validateCharLimit(string $attribute)
    {
        if ($this->charLimit) {
            $columnTypeMax = Db::getTextualColumnStorageCapacity($this->columnType);

            if ($columnTypeMax && $columnTypeMax < $this->charLimit) {
                $this->addError($attribute, Craft::t('app', 'Character Limit is too big for your chosen Column Type.'));
            }
        }
    }

    /**
     * @return string
     */
    public function getSvgIconPath()
    {
        return '@sproutbaseicons/paragraph.svg';
    }

    /**
     * @return null|string
     * @throws \Twig_Error_Loader
     * @throws \yii\base\Exception
     */
    public function getSettingsHtml()
    {
        $rendered = Craft::$app->getView()->renderTemplate(
            'sprout-forms/_components/fields/formfields/paragraph/settings',
            [
                'field' => $this,
            ]
        );

        return $rendered;
    }

    /**
     * Adds support for edit field in the Entries section of SproutForms (Control
     * panel html)
     *
     * @inheritdoc
     *
     * @throws \Twig_Error_Loader
     * @throws \yii\base\Exception
     */
    public function getInputHtml($value, ElementInterface $element = null): string
    {
        return Craft::$app->getView()->renderTemplate('sprout-base-fields/_components/fields/formfields/paragraph/input',
            [
                'name' => $this->handle,
                'value' => $value,
                'field' => $this,
            ]);
    }

    /**
     * @inheritdoc
     *
     * @throws \Twig_Error_Loader
     * @throws \yii\base\Exception
     */
    public function getExampleInputHtml()
    {
        return Craft::$app->getView()->renderTemplate('sprout-forms/_components/fields/formfields/paragraph/example',
            [
                'field' => $this
            ]
        );
    }

    /**
     * @param mixed      $value
     * @param array|null $renderingOptions
     *
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \yii\base\Exception
     */
    public function getFrontEndInputHtml($value, array $renderingOptions = null): string
    {
        $rendered = Craft::$app->getView()->renderTemplate(
            'paragraph/input',
            [
                'name' => $this->handle,
                'value' => $value,
                'field' => $this,
                'renderingOptions' => $renderingOptions
            ]
        );

        return TemplateHelper::raw($rendered);
    }

    /**
     * @inheritdoc
     */
    public function serializeValue($value, ElementInterface $element = null)
    {
        if ($value !== null) {
            $value = LitEmoji::unicodeToShortcode($value);
        }
        return $value;
    }

    /**
     * @inheritdoc
     */
    public function getSearchKeywords($value, ElementInterface $element): string
    {
        $value = (string)$value;
        $value = LitEmoji::unicodeToShortcode($value);
        return $value;
    }
}
