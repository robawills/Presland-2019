<?php
/**
 * @link      https://sprout.barrelstrengthdesign.com/
 * @copyright Copyright (c) Barrel Strength Design LLC
 * @license   http://sprout.barrelstrengthdesign.com/license
 */

namespace barrelstrength\sproutbaseimport\base;

abstract class FieldImporter extends Importer
{
    /**
     * @var int
     */
    protected $id;

    /**
     * Return the name of a Field from the FieldTypeModel
     *
     * @return mixed
     */
    public function getName(): string
    {
        return $this->getModel()->displayName();
    }

    /**
     * @return bool
     */
    public function isField(): bool
    {
        return true;
    }

    /**
     * Set our $this->model variable to the FieldModel Class.
     * Our setModel() Method for Fields will always use FieldModel.
     *
     * @param       $model
     * @param array $settings
     *
     * @return mixed|void
     */
    public function setModel($model, array $settings = [])
    {
        $this->model = $model;
    }

    /**
     * Return a new FieldType model for our field
     *
     * @return mixed
     */
    public function getModel()
    {
        $className = $this->getModelName();

        $this->model = $className;

        return new $this->model;
    }

    /**
     * Return dummy data that can be used to generate fake content for this field type
     *
     * @return mixed
     */
    public abstract function getMockData();

    /**
     * @return string
     */
    public function getSettingsHtml()
    {
        return '';
    }

    /**
     * Return any settings that can be customized when generating seed data for this field type
     *
     * @return string
     */
    public function getSeedSettingsHtml(): string
    {
        return '';
    }

    /**
     * @todo - clean up, empty method.
     */
    public function save()
    {

    }
}
