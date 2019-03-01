<?php

namespace barrelstrength\sproutforms\fields\formfields;

use Craft;
use craft\base\ElementInterface;
use craft\helpers\Template as TemplateHelper;
use yii\db\Schema;

use barrelstrength\sproutforms\base\FormField;
use barrelstrength\sproutbasefields\web\assets\quill\QuillAsset;

/**
 *
 * @property string $svgIconPath
 * @property mixed  $settingsHtml
 * @property mixed  $exampleInputHtml
 */
class SectionHeading extends FormField
{
    /**
     * @var string
     */
    public $cssClasses;

    /**
     * @var bool
     */
    public $allowRequired = false;

    /**
     * @var string
     */
    public $notes;

    /**
     * @var bool
     */
    public $hideLabel;

    /**
     * @var string
     */
    public $output;

    public static function displayName(): string
    {
        return Craft::t('sprout-forms', 'Section Heading');
    }

    /**
     * @inheritdoc
     */
    public function isPlainInput()
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function defineContentAttribute()
    {
        return Schema::TYPE_STRING;
    }

    /**
     * @inheritdoc
     */
    public function displayInstructionsField()
    {
        return false;
    }

    /**
     * @inheritdoc
     */
    public static function hasContentColumn(): bool
    {
        return false;
    }

    /**
     * @return string
     */
    public function getSvgIconPath()
    {
        return '@sproutbaseicons/header.svg';
    }

    /**
     * @inheritdoc
     * @return string
     * @throws \ReflectionException
     * @throws \Twig_Error_Loader
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     */
    public function getSettingsHtml()
    {
        $reflect = new \ReflectionClass($this);
        $name = $reflect->getShortName();

        $inputId = Craft::$app->getView()->formatInputId($name);
        $view = Craft::$app->getView();
        $namespaceInputId = $view->namespaceInputId($inputId);

        $view->registerAssetBundle(QuillAsset::class);

        $options = [
            'richText' => 'Rich Text',
            'markdown' => 'Markdown',
            'html' => 'HTML'
        ];

        return $view->renderTemplate(
            'sprout-forms/_components/fields/formfields/sectionheading/settings',
            [
                'id' => $namespaceInputId,
                'name' => $name,
                'field' => $this,
                'outputOptions' => $options
            ]
        );
    }

    /**
     * @inheritdoc
     *
     * @throws \Twig_Error_Loader
     * @throws \yii\base\Exception
     */
    public function getInputHtml($value, ElementInterface $element = null): string
    {
        $name = $this->handle;
        $inputId = Craft::$app->getView()->formatInputId($name);
        $namespaceInputId = Craft::$app->getView()->namespaceInputId($inputId);

        if ($this->notes === null) {
            $this->notes = '';
        }

        return Craft::$app->getView()->renderTemplate(
            'sprout-base-fields/_components/fields/formfields/sectionheading/input',
            [
                'id' => $namespaceInputId,
                'name' => $name,
                'field' => $this
            ]
        );
    }

    /**
     * @inheritdoc
     *
     * @throws \Twig_Error_Loader
     * @throws \yii\base\Exception
     */
    public function getExampleInputHtml()
    {
        return Craft::$app->getView()->renderTemplate('sprout-forms/_components/fields/formfields/sectionheading/example',
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
        $name = $this->handle;
        $namespaceInputId = $this->getNamespace().'-'.$name;

        if ($this->notes === null) {
            $this->notes = '';
        }

        $rendered = Craft::$app->getView()->renderTemplate('sectionheading/input',
            [
                'id' => $namespaceInputId,
                'field' => $this
            ]
        );

        return TemplateHelper::raw($rendered);
    }
}
