<?php

namespace barrelstrength\sproutbaseemail\emailtemplates;

use barrelstrength\sproutbaseemail\base\EmailTemplates;
use Craft;

/**
 * The Custom Templates is used to dynamically create an EmailTemplate
 * integration when a user selects the custom option and provides a path
 * to the custom templates they wish to use.
 *
 * The Custom Templates integration is not registered with Sprout Email
 * and will not display in the Email Templates dropdown list.
 */
class CustomTemplates extends EmailTemplates
{
    /**
     * @var string
     */
    private $_path;

    /**
     * @return string
     */
    public function getName(): string
    {
        return Craft::t('sprout-base-email', 'Custom Templates');
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->_path;
    }

    /**
     * @param string $path
     */
    public function setPath($path)
    {
        $this->_path = $path;
    }
}



