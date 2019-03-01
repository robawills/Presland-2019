<?php
/**
 * @link      https://sprout.barrelstrengthdesign.com/
 * @copyright Copyright (c) Barrel Strength Design LLC
 * @license   http://sprout.barrelstrengthdesign.com/license
 */

namespace barrelstrength\sproutbasereports\elements\db;


use craft\elements\db\ElementQuery;
use craft\helpers\Db;
use Craft;

class ReportQuery extends ElementQuery
{
    public $id;

    public $name;

    public $hasNameFormat;

    public $nameFormat;

    public $handle;

    public $description;

    public $allowHtml;

    public $settings;

    public $dataSourceId;

    public $dataSourceSlug;

    public $enabled;

    public $groupId;

    public $dateCreated;

    public $dateUpdated;

    public $results;

    public $pluginHandle;

    /**
     * @inheritdoc
     */
    protected function beforePrepare(): bool
    {
        $this->joinElementTable('sproutreports_reports');
        $this->query->select([
            'sproutreports_reports.dataSourceId',
            'sproutreports_reports.name',
            'sproutreports_reports.hasNameFormat',
            'sproutreports_reports.nameFormat',
            'sproutreports_reports.handle',
            'sproutreports_reports.description',
            'sproutreports_reports.allowHtml',
            'sproutreports_reports.settings',
            'sproutreports_reports.groupId',
            'sproutreports_reports.enabled',
            'sproutreports_datasources.pluginHandle'
        ]);

        $this->query->innerJoin('{{%sproutreports_datasources}} sproutreports_datasources', '[[sproutreports_datasources.id]] = [[sproutreports_reports.dataSourceId]]');

        $pluginHandleRequest = Craft::$app->request->getBodyParam('criteria.pluginHandle');

        $pluginHandle = null;

        if ($pluginHandleRequest) {
            $pluginHandle = $pluginHandleRequest;
        }

        if ($this->pluginHandle) {
            $pluginHandle = $this->pluginHandle;
        }

        if ($pluginHandle != null) {
            $this->query->andWhere(Db::parseParam(
                'sproutreports_datasources.pluginHandle', $pluginHandle)
            );
        }

        if ($this->dataSourceId) {
            $this->query->andWhere(Db::parseParam(
                'sproutreports_reports.dataSourceId', $this->dataSourceId)
            );
        }

        if ($this->groupId) {
            $this->query->andWhere(Db::parseParam(
                'sproutreports_reports.groupId', $this->groupId)
            );
        }

        return parent::beforePrepare();
    }
}