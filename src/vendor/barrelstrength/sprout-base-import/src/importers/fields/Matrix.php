<?php

namespace barrelstrength\sproutbaseimport\importers\fields;

use barrelstrength\sproutbaseimport\base\FieldImporter;
use barrelstrength\sproutbaseimport\SproutBaseImport;
use Craft;
use craft\fields\Matrix as MatrixField;

class Matrix extends FieldImporter
{
    /**
     * @return string
     */
    public function getModelName(): string
    {
        return MatrixField::class;
    }

    /**
     * @return mixed
     */
    public function getMockData()
    {
        $fieldId = $this->model->id;
        $blocks = Craft::$app->getMatrix()->getBlockTypesByFieldId($fieldId);

        $values = [];

        if (!empty($blocks)) {
            $count = 1;

            foreach ($blocks as $block) {
                $key = 'new'.$count;

                $values[$key] = [
                    'type' => $block->handle,
                    'enabled' => 1
                ];

                $fieldLayoutId = $block->fieldLayoutId;

                $fieldLayouts = Craft::$app->getFields()->getFieldsByLayoutId($fieldLayoutId);

                $values[$key]['fields'] = SproutBaseImport::$app->fieldImporter->getFieldsWithMockData($fieldLayouts);

                $count++;
            }
        }

        return $values;
    }
}
