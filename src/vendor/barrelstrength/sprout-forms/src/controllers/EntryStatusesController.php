<?php

namespace barrelstrength\sproutforms\controllers;

use Craft;
use barrelstrength\sproutforms\SproutForms;
use barrelstrength\sproutforms\models\EntryStatus;
use craft\helpers\Json;
use craft\web\Controller as BaseController;
use yii\web\NotFoundHttpException;

class EntryStatusesController extends BaseController
{
    /**
     * @param int|null         $entryStatusId
     * @param EntryStatus|null $entryStatus
     *
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionEdit(int $entryStatusId = null, EntryStatus $entryStatus = null)
    {
        if (!$entryStatus) {
            if ($entryStatusId) {
                $entryStatus = SproutForms::$app->entries->getEntryStatusById($entryStatusId);

                if (!$entryStatus->id) {
                    throw new NotFoundHttpException(Craft::t('sprout-forms', 'Entry Status not found'));
                }
            } else {
                $entryStatus = new EntryStatus();
            }
        }

        return $this->renderTemplate('sprout-forms/settings/entrystatuses/_edit', [
            'entryStatus' => $entryStatus,
            'entryStatusId' => $entryStatusId
        ]);
    }

    /**
     * @return null|\yii\web\Response
     * @throws \yii\base\Exception
     * @throws \yii\db\Exception
     * @throws \yii\web\BadRequestHttpException
     */
    public function actionSave()
    {
        $this->requirePostRequest();

        $id = Craft::$app->request->getBodyParam('entryStatusId');
        $entryStatus = SproutForms::$app->entries->getEntryStatusById($id);

        $entryStatus->name = Craft::$app->request->getBodyParam('name');
        $entryStatus->handle = Craft::$app->request->getBodyParam('handle');
        $entryStatus->color = Craft::$app->request->getBodyParam('color');
        $entryStatus->isDefault = Craft::$app->request->getBodyParam('isDefault');

        if (!SproutForms::$app->entries->saveEntryStatus($entryStatus)) {
            Craft::$app->session->setError(Craft::t('sprout-forms', 'Could not save Entry Status.'));

            Craft::$app->getUrlManager()->setRouteParams([
                'entryStatus' => $entryStatus
            ]);

            return null;
        }

        Craft::$app->session->setNotice(Craft::t('sprout-forms', 'Entry Status saved.'));

        return $this->redirectToPostedUrl();
    }

    /**
     * @return \yii\web\Response
     * @throws \Exception
     * @throws \yii\web\BadRequestHttpException
     */
    public function actionReorder()
    {
        $this->requirePostRequest();

        $ids = Json::decode(Craft::$app->request->getRequiredBodyParam('ids'), true);

        if ($success = SproutForms::$app->entries->reorderEntryStatuses($ids)) {
            return $this->asJson(['success' => $success]);
        }

        return $this->asJson(['error' => Craft::t('sprout-forms', "Couldn't reorder Order Statuses.")]);
    }

    /**
     * @return \yii\web\Response
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     * @throws \yii\web\BadRequestHttpException
     */
    public function actionDelete()
    {
        $this->requirePostRequest();

        $entryStatusId = Craft::$app->request->getRequiredBodyParam('id');

        if (!SproutForms::$app->entries->deleteEntryStatusById($entryStatusId)) {
            $this->asJson(['success' => false]);
        }

        return $this->asJson(['success' => true]);
    }

}
