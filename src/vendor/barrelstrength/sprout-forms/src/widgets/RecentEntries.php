<?php
/**
 * @link      https://sprout.barrelstrengthdesign.com/
 * @copyright Copyright (c) Barrel Strength Design LLC
 * @license   http://sprout.barrelstrengthdesign.com/license
 */

namespace barrelstrength\sproutforms\widgets;

use barrelstrength\sproutforms\SproutForms;
use craft\base\Widget;
use Craft;
use barrelstrength\sproutforms\elements\Entry;

/**
 *
 * @property mixed  $bodyHtml
 * @property mixed  $settingsHtml
 * @property string $title
 */
class RecentEntries extends Widget
{
    /**
     * @var int
     */
    public $formId;

    /**
     * @var int
     */
    public $limit = 10;

    /**
     * @var string
     */
    public $showDate;

    /**
     * @inheritdoc
     */
    public static function displayName(): string
    {
        return Craft::t('sprout-forms', 'Recent Entries (Sprout Forms)');
    }

    /**
     * @inheritdoc
     */
    public function getTitle(): string
    {
        // Concat form name if the user select a specific form
        if ($this->formId !== 0 && $this->formId !== null) {
            $form = SproutForms::$app->forms->getFormById($this->formId);

            if ($form) {
                return Craft::t('sprout-forms', 'Recent {formName} Entries', [
                    'formName' => $form->name
                ]);
            }
        }

        return static::displayName();
    }

    /**
     * @inheritdoc
     */
    public static function iconPath()
    {
        return Craft::getAlias('@barrelstrength/sproutforms/icon-mask.svg');
    }

    /**
     * @inheritdoc
     *
     * @throws \Twig_Error_Loader
     * @throws \yii\base\Exception
     */
    public function getBodyHtml()
    {
        $query = Entry::find();

        if ($this->formId != 0) {
            $query->formId = $this->formId;
        }
        $query->limit = $this->limit;

        return Craft::$app->getView()->renderTemplate('sprout-forms/_components/widgets/RecentEntries/body', [
            'entries' => $query->all(),
            'widget' => $this
        ]);
    }

    /**
     * @inheritdoc
     *
     * @throws \Twig_Error_Loader
     * @throws \yii\base\Exception
     */
    public function getSettingsHtml()
    {
        $forms = [
            0 => Craft::t('sprout-forms', 'All forms')
        ];

        $sproutForms = SproutForms::$app->forms->getAllForms();

        if ($sproutForms) {
            foreach ($sproutForms as $form) {
                $forms[$form->id] = $form->name;
            }
        }

        return Craft::$app->getView()->renderTemplate('sprout-forms/_components/widgets/RecentEntries/settings', [
            'sproutForms' => $forms,
            'widget' => $this
        ]);
    }
}
