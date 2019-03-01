<?php

namespace barrelstrength\sproutforms\integrations\sproutimport\fields;

use barrelstrength\sproutbaseimport\base\FieldImporter;
use barrelstrength\sproutbaseimport\SproutBaseImport;
use barrelstrength\sproutforms\elements\Entry as EntryElement;

class Entries extends FieldImporter
{
    /**
     * @inheritdoc
     */
    public function getModelName(): string
    {
        return EntryElement::class;
    }

    /**
     * @inheritdoc
     * @throws \Exception
     */
    public function getMockData()
    {
        $settings = $this->model->settings;
        $limit = SproutBaseImport::$app->fieldImporter->getLimit($settings['limit'], 1);
        $sources = $settings['sources'];
        $attributes = [];

        $groupIds = SproutBaseImport::$app->fieldImporter->getElementGroupIds($sources);

        if (!empty($groupIds) && $groupIds != '*') {
            $attributes = [
                'formId' => $groupIds
            ];
        }

        $element = new EntryElement();

        $elementIds = SproutBaseImport::$app->fieldImporter->getMockRelations($element, $attributes, $limit);

        return $elementIds;
    }
}
