<?php

namespace barrelstrength\sproutbaseimport\importers\fields;

use barrelstrength\sproutbaseimport\base\FieldImporter;
use barrelstrength\sproutbaseimport\SproutBaseImport;
use Craft;
use craft\commerce\elements\Product;
use craft\commerce\fields\Products as ProductsField;

class Products extends FieldImporter
{
    /**
     * @return string
     */
    public function getModelName(): string
    {
        return ProductsField::class;
    }

    /**
     * @return array|bool|mixed|null
     * @throws \Exception
     */
    public function getMockData()
    {
        $settings = $this->model->settings;

        $relatedMin = 1;
        $relatedMax = 3;

        if (!empty($settings['limit'])) {
            $relatedMax = $settings['limit'];
        }

        $mockDataSettings = [
            'fieldName' => $this->model->name,
            'required' => $this->model->required,
            'relatedMin' => $relatedMin,
            'relatedMax' => $relatedMax
        ];

        $sources = $settings['sources'];

        if (!isset($settings['sources'])) {
            SproutBaseImport::info(Craft::t('sprout-base-import', 'Unable to generate Mock Data for relations field: {fieldName}. No Sources found.', [
                'fieldName' => $this->model->name
            ]));
            return null;
        }

        $productTypeIds = SproutBaseImport::$app->fieldImporter->getElementGroupIds($sources);

        $attributes = [
            'typeId' => $productTypeIds
        ];

        $productElement = new Product();

        $elementIds = SproutBaseImport::$app->fieldImporter->getMockRelations($productElement, $attributes, $mockDataSettings);

        return $elementIds;
    }
}
