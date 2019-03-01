<?php

namespace barrelstrength\sproutforms\base;

use Craft;

/**
 * Class FormTemplates
 */
abstract class FormTemplates
{
    /**
     * The Template ID of the Form Templates in the format {pluginhandle}-{formtemplateclassname}
     *
     * @example
     * sproutforms-accessibletemplates
     * sproutforms-basictemplates
     *
     * @var string
     */
    public $templateId;

    /**
     * Generates the Template ID
     *
     * @return string
     * @throws \ReflectionException
     */
    public function getTemplateId()
    {
        $pluginHandle = Craft::$app->getPlugins()->getPluginHandleByClass(get_class($this));

        // Build $templateId: pluginhandle-formtemplateclassname
        $pluginHandleWithoutSpaces = str_replace('-', '', $pluginHandle);

        $captchaClass = (new \ReflectionClass($this))->getShortName();

        $templateId = $pluginHandleWithoutSpaces.'-'.$captchaClass;

        $this->templateId = strtolower($templateId);

        return $this->templateId;
    }

    /**
     * The name of your Form Templates
     *
     * @return string
     */
    abstract public function getName();

    /**
     * The folder path where your form templates exist
     *
     * @return string
     */
    abstract public function getPath();

    /**
     * Adds pre-defined options for css classes.
     *
     * These classes will display in the CSS Classes dropdown list on the Field Edit modal
     * for Field Types that support the $cssClasses property.
     *
     * @return array
     */
    public function getCssClassDefaults()
    {
        return [];
    }
}
