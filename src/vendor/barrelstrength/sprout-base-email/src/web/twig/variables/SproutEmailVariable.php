<?php

namespace barrelstrength\sproutbaseemail\web\twig\variables;

use barrelstrength\sproutbaseemail\base\Mailer;
use barrelstrength\sproutbaseemail\base\EmailTemplates;
use barrelstrength\sproutbaseemail\emailtemplates\BasicTemplates;
use barrelstrength\sproutbaseemail\SproutBaseEmail;
use barrelstrength\sproutemail\SproutEmail;
use Craft;
use craft\helpers\UrlHelper;

class SproutEmailVariable
{
    /**
     * @return Mailer[]
     */
    public function getCampaignMailers(): array
    {
        return SproutBaseEmail::$app->mailers->getMailers();
    }

    /**
     * @return array
     */
    public function getCampaignTypes(): array
    {
        return SproutEmail::$app->campaignTypes->getCampaignTypes();
    }

    /**
     * @param $mailer
     *
     * @return Mailer
     * @throws \yii\base\Exception
     */
    public function getMailer($mailer): Mailer
    {
        return SproutBaseEmail::$app->mailers->getMailerByName($mailer);
    }

    /**
     * Returns the value of the displayDateScheduled general config setting
     *
     * @return mixed|null
     * @throws \yii\base\InvalidConfigException
     */
    public function getDisplayDateScheduled()
    {
        $config = Craft::$app->getConfig()->getConfigSettings('general');

        if (!is_array($config)) {
            return false;
        }

        return $config->displayDateScheduled ?? false;
    }

    public function getCampaignEmailById($id)
    {
        return SproutEmail::$app->campaignEmails->getCampaignEmailById($id);
    }

    public function getSentEmailById($sentEmailId)
    {
        return Craft::$app->getElements()->getElementById($sentEmailId);
    }

    /**
     * Returns a Campaign Email Share URL and Token
     *
     * @param $emailId
     * @param $campaignTypeId
     *
     * @return array|string
     */
    public function getCampaignEmailShareUrl($emailId, $campaignTypeId)
    {
        return UrlHelper::actionUrl('sprout-email/campaign-email/share-campaign-email', [
            'emailId' => $emailId,
            'campaignTypeId' => $campaignTypeId
        ]);
    }

    public function getNotificationEmailById($id)
    {
        return SproutBaseEmail::$app->notifications->getNotificationEmailById($id);
    }

    /**
     * Get the available Email Template Options
     *
     * @param null $notificationEmail
     *
     * @return array
     */
    public function getEmailTemplateOptions($notificationEmail = null): array
    {
        $defaultEmailTemplates = new BasicTemplates();

        $templates = SproutBaseEmail::$app->emailTemplates->getAllEmailTemplates();

        $templateIds = [];
        $options = [
            [
                'label' => Craft::t('sprout-base-email', 'Select...'),
                'value' => ''
            ]
        ];

        /**
         * Build our options
         *
         * @var EmailTemplates $template
         */
        foreach ($templates as $template) {
            $type = get_class($template);

            $options[] = [
                'label' => $template->getName(),
                'value' => $type
            ];
            $templateIds[] = $type;
        }

        $templateFolder = null;
        $plugin = Craft::$app->getPlugins()->getPlugin('sprout-email');

        if ($plugin) {
            $settings = $plugin->getSettings();
        }

        $templateFolder = $notificationEmail->emailTemplateId ?? $settings->emailTemplateId ?? $defaultEmailTemplates->getPath();

        $options[] = [
            'optgroup' => Craft::t('sprout-base-email', 'Custom Template Folder')
        ];

        if (!in_array($templateFolder, $templateIds, false) && $templateFolder != '') {
            $options[] = [
                'label' => $templateFolder,
                'value' => $templateFolder
            ];
        }

        $options[] = [
            'label' => Craft::t('sprout-base-email', 'Add Custom'),
            'value' => 'custom'
        ];

        return $options;
    }

    /**
     * Trigger a cleanUpSentEmails Job
     *
     * @throws \craft\errors\SiteNotFoundException
     */
    public function cleanUpSentEmails()
    {
        SproutEmail::$app->sentEmails->cleanUpSentEmails();
    }
}