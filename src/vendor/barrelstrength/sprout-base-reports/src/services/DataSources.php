<?php
/**
 * @link      https://sprout.barrelstrengthdesign.com/
 * @copyright Copyright (c) Barrel Strength Design LLC
 * @license   http://sprout.barrelstrengthdesign.com/license
 */

namespace barrelstrength\sproutbasereports\services;

use barrelstrength\sproutbasereports\base\DataSource;
use barrelstrength\sproutbasereports\models\DataSource as DataSourceModel;
use barrelstrength\sproutbasereports\records\DataSource as DataSourceRecord;
use barrelstrength\sproutbase\SproutBase;

use yii\base\Component;
use craft\events\RegisterComponentTypesEvent;
use craft\db\Query;
use Craft;
use yii\base\Exception;

/**
 * Class DataSources
 *
 * @package Craft
 *
 * @property array    $allDataSources
 * @property mixed    $dataSourcePlugins
 * @property string[] $allDataSourceTypes
 */
class DataSources extends Component
{
    /**
     * @event
     */
    const EVENT_REGISTER_DATA_SOURCES = 'registerSproutReportsDataSources';

    private $dataSources;

    /**
     * @param int $dataSourceId
     *
     * @return DataSource|null
     * @throws Exception
     */
    public function getDataSourceById($dataSourceId)
    {
        /**
         * @var DataSourceRecord $dataSourceRecord
         */
        $dataSourceRecord = DataSourceRecord::find()->where([
            'id' => $dataSourceId
        ])->one();

        if ($dataSourceRecord === null) {
            return null;
        }

        if (class_exists($dataSourceRecord->type)) {
            $dataSource = new $dataSourceRecord->type;

            $dataSource->dataSourceId = $dataSourceRecord->id;

            return $dataSource;
        }

        throw new Exception(Craft::t('sprout-base-reports', 'Unable to find the class: {type}. Confirm the appropriate Data Source integrations are installed.', [
            'type' => $dataSourceRecord->type
        ]));
    }

    /**
     * @param $dataSourceClass
     *
     * @return DataSource|null
     */
    public function getDataSourceByType($dataSourceClass)
    {
        /**
         * @var $dataSourceRecord DataSourceRecord
         */
        $dataSourceRecord = DataSourceRecord::find()->where([
            'type' => $dataSourceClass
        ])->one();

        if ($dataSourceRecord === null) {
            return null;
        }

        $dataSource = new $dataSourceRecord->type;
        $dataSource->dataSourceId = $dataSourceRecord->id;

        return $dataSource;
    }

    /**
     * @param array $dataSourceClasses
     *
     * @return DataSourceModel|null
     * @throws \yii\db\Exception
     */
    public function installDataSources(array $dataSourceClasses = [])
    {
        $dataSources = null;

        foreach ($dataSourceClasses as $dataSourceClass) {

            /** @var DataSource $dataSource */
            $dataSource = new $dataSourceClass();

            $dataSourceModel = new DataSourceModel();
            $dataSourceModel->type = $dataSourceClass;
            $dataSourceModel->allowNew = 1;

            // Set all pre-built class to sprout-reports pluginHandle
            $dataSourceModel->pluginHandle = $dataSource->getPlugin()->handle ?? 'sprout-reports';

            $this->saveDataSource($dataSourceModel);

            $dataSources = $dataSourceModel;
        }

        return $dataSources;
    }

    /**
     * Returns all available Data Source classes
     *
     * @return string[]
     */
    public function getAllDataSourceTypes(): array
    {
        $event = new RegisterComponentTypesEvent([
            'types' => []
        ]);

        $this->trigger(self::EVENT_REGISTER_DATA_SOURCES, $event);

        return $event->types;
    }

    /**
     * Returns all Data Sources
     *
     * @todo - refactor
     *       Using too many foreach loops as arrays aren't indexed by class name. We could probably
     *       simplify this if we could get certain arrays indexed by class name / type.
     *
     * @return array
     * @throws \yii\db\Exception
     */
    public function getAllDataSources(): array
    {
        $dataSourceTypes = $this->getAllDataSourceTypes();
        $dataSourceRecords = DataSourceRecord::find()->all();

        $dataSources = [];
        $savedDataSources = [];

        foreach ($dataSourceTypes as $dataSourceType) {
            $dataSources[$dataSourceType] = new $dataSourceType;
        }

        $this->dataSources = $dataSources;

        /**
         * Add the additional data we store in the database to the Data Source classes
         *
         * @var $dataSourceRecord DataSourceRecord
         */
        foreach ($dataSourceRecords as $dataSourceRecord) {
            try {
                if ($this->isDataSourceExists($dataSourceRecord)) {
                    $dataSources[$dataSourceRecord->type]->dataSourceId = $dataSourceRecord->id;
                    $dataSources[$dataSourceRecord->type]->allowNew = $dataSourceRecord->allowNew;
                }
            } catch (\Exception $exception) {
                SproutBase::error($exception->getMessage());
            }
        }

        // Make sure all registered datasources have a record in the database
        foreach ($dataSources as $dataSourceClass => $dataSource) {

            if ($dataSource->dataSourceId === null) {
                $savedDataSources[] = $this->installDataSources([$dataSourceClass]);
            }
        }

        // Make sure we assign any new dataSource IDs so we can build our URLs
        foreach ($savedDataSources as $savedDataSource) {
            if ($savedDataSource->type === get_class($dataSources[$savedDataSource->type])) {
                $dataSources[$savedDataSource->type]->dataSourceId = $savedDataSource->id;
            }
        }

        uasort($dataSources, function($a, $b) {
            /**
             * @var $a DataSource
             * @var $b DataSource
             */
            return $a->getName() <=> $b->getName();
        });

        return $dataSources;
    }

    private function isDataSourceExists($dataSourceRecord): bool
    {
        return class_exists($dataSourceRecord->type)
            AND isset($this->dataSources[$dataSourceRecord->type])
            AND $dataSourceRecord->type === get_class($this->dataSources[$dataSourceRecord->type]);
    }

    /**
     * @return array
     */
    public function getDataSourcePlugins(): array
    {
        $query = new Query();

        $dataSourcePlugins = $query->select('pluginHandle')
            ->from(['{{%sproutreports_datasources}}'])
            ->distinct()
            ->all();

        return $dataSourcePlugins;
    }

    /**
     * Save attributes to datasources record table
     *
     * @param DataSourceModel $dataSourceModel
     *
     * @return bool
     * @throws \yii\db\Exception
     */
    public function saveDataSource(DataSourceModel $dataSourceModel): bool
    {
        /**
         * @var $dataSourceRecord DataSourceRecord
         */
        $dataSourceRecord = DataSourceRecord::find()
            ->where(['id' => $dataSourceModel->id])
            ->one();

        if ($dataSourceRecord !== null) {
            $dataSourceRecord->id = $dataSourceModel->id;
        } else {
            $dataSourceRecord = new DataSourceRecord();
            $dataSourceRecord->type = $dataSourceModel->type;
        }

        $dataSourceRecord->pluginHandle = $dataSourceModel->pluginHandle;
        $dataSourceRecord->allowNew = $dataSourceModel->allowNew;

        $transaction = Craft::$app->getDb()->beginTransaction();

        if ($dataSourceRecord->validate()) {
            if ($dataSourceRecord->save(false)) {
                $dataSourceModel->id = $dataSourceRecord->id;

                if ($transaction) {
                    $transaction->commit();
                }

                return true;
            }
        } else {
            $dataSourceModel->addErrors($dataSourceRecord->getErrors());
        }

        return false;
    }

    /**
     * Delete reports by type
     *
     * @param $type
     *
     * @return bool
     * @throws \yii\db\Exception
     */
    public function deleteReportsByType($type): bool
    {
        $query = new Query();

        $source = $query
            ->select(['id', 'type'])
            ->from(['{{%sproutreports_datasources}}'])
            ->where(['type' => $type])
            ->one();

        if ($source) {
            $query->createCommand()
                ->delete('{{%sproutreports_reports}}', ['dataSourceId' => $source['id']])
                ->execute();
        }

        return true;
    }
}
