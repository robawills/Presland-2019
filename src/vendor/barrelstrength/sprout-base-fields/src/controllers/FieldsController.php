<?php
/**
 * @link      https://sprout.barrelstrengthdesign.com/
 * @copyright Copyright (c) Barrel Strength Design LLC
 * @license   http://sprout.barrelstrengthdesign.com/license
 */

namespace barrelstrength\sproutbasefields\controllers;

use barrelstrength\sproutbasefields\SproutBaseFields;
use Craft;
use craft\base\Field;
use craft\web\Controller as BaseController;

use yii\web\Response;

class FieldsController extends BaseController
{
    protected $allowAnonymous = ['actionSproutAddress'];

    /**
     * @return \yii\web\Response
     * @throws \yii\web\BadRequestHttpException
     */
    public function actionEmailValidate(): Response
    {
        $this->requirePostRequest();
        $this->requireAcceptsJson();

        $value = Craft::$app->getRequest()->getParam('value');
        $oldFieldContext = Craft::$app->content->fieldContext;
        $elementId = Craft::$app->getRequest()->getParam('elementId');
        $fieldContext = Craft::$app->getRequest()->getParam('fieldContext');
        $fieldHandle = Craft::$app->getRequest()->getParam('fieldHandle');

        // Retrieve an Email Field, wherever it may be
        Craft::$app->content->fieldContext = $fieldContext;

        /** @var Field $field */
        $field = Craft::$app->fields->getFieldByHandle($fieldHandle);
        Craft::$app->content->fieldContext = $oldFieldContext;

        // If we don't find a URL Field, return a new URL Field model
        // @todo - why do we need to return a model? can we assume the user has Sprout Fields installed?
        if (!$field) {
            return $this->asJson(false);
        }

        if (!SproutBaseFields::$app->emailField->validate($value, $field, $elementId)) {
            return $this->asJson(false);
        }

        return $this->asJson(true);
    }

    /**
     * @return \yii\web\Response
     * @throws \yii\web\BadRequestHttpException
     */
    public function actionUrlValidate(): Response
    {
        $this->requirePostRequest();
        $this->requireAcceptsJson();

        $value = Craft::$app->getRequest()->getParam('value');
        $oldFieldContext = Craft::$app->content->fieldContext;
        $fieldContext = Craft::$app->getRequest()->getParam('fieldContext');
        $fieldHandle = Craft::$app->getRequest()->getParam('fieldHandle');

        // Retrieve a URL Field, wherever it may be
        Craft::$app->content->fieldContext = $fieldContext;

        /** @var Field $field */
        $field = Craft::$app->fields->getFieldByHandle($fieldHandle);
        Craft::$app->content->fieldContext = $oldFieldContext;

        // If we don't find a URL Field, return a new URL Field model
        if (!$field) {
            return $this->asJson(false);
        }

        if (!SproutBaseFields::$app->urlField->validate($value, $field)) {
            return $this->asJson(false);
        }

        return $this->asJson(true);
    }

    /**
     * @return \yii\web\Response
     * @throws \yii\web\BadRequestHttpException
     */
    public function actionPhoneValidate(): Response
    {
        $this->requirePostRequest();
        $this->requireAcceptsJson();

        $phone = Craft::$app->getRequest()->getParam('phone');
        $country = Craft::$app->getRequest()->getParam('country');

        if (!SproutBaseFields::$app->phoneField->validate($phone, $country)) {
            return $this->asJson(false);
        }

        return $this->asJson(true);
    }

    /**
     * @return \yii\web\Response
     * @throws \yii\web\BadRequestHttpException
     */
    public function actionRegularExpressionValidate(): Response
    {
        $this->requirePostRequest();
        $this->requireAcceptsJson();

        $value = Craft::$app->getRequest()->getParam('value');
        $oldFieldContext = Craft::$app->content->fieldContext;
        $fieldContext = Craft::$app->getRequest()->getParam('fieldContext');
        $fieldHandle = Craft::$app->getRequest()->getParam('fieldHandle');

        Craft::$app->content->fieldContext = $fieldContext;

        /** @var Field $field */
        $field = Craft::$app->fields->getFieldByHandle($fieldHandle);
        Craft::$app->content->fieldContext = $oldFieldContext;

        if (!SproutBaseFields::$app->regularExpressionField->validate($value, $field)) {
            return $this->asJson(false);
        }

        return $this->asJson(true);
    }
}
