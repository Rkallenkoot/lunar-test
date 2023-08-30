<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Lunar\Models\Attribute;
use Lunar\Models\AttributeGroup;
use Lunar\Models\Product;

class AttributeSeeder extends AbstractSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $attributes = $this->getSeedData('attributes');
        $testAttributes = $this->getSeedData('test_attributes');

        $attributeGroup = AttributeGroup::first();

        $testGroup =  AttributeGroup::firstOrCreate([
            'handle' => 'test',
        ], [
            'attributable_type' => Product::class,
            'name' => collect([
                'en' => 'Test Group',
            ]),
            'handle' => 'test',
            'position' => 2,
        ]);


        DB::transaction(function () use ($attributes, $attributeGroup) {
            foreach ($attributes as $attribute) {
                Attribute::firstOrCreate([
                    'attribute_type' => $attribute->attribute_type,
                    'handle' => $attribute->handle,
                ], [
                    'attribute_group_id' => $attributeGroup->id,
                    'attribute_type' => $attribute->attribute_type,
                    'handle' => $attribute->handle,
                    'section' => 'main',
                    'type' => $attribute->type,
                    'required' => false,
                    'searchable' => true,
                    'filterable' => false,
                    'system' => false,
                    'position' => $attributeGroup->attributes()->count() + 1,
                    'name' => [
                        'en' => $attribute->name,
                    ],
                    'configuration' => (array) $attribute->configuration,
                ]);
            }
        });

        DB::transaction(function () use ($testAttributes, $testGroup) {
            foreach ($testAttributes as $attribute) {
                Attribute::firstOrCreate([
                    'attribute_type' => $attribute->attribute_type,
                    'handle' => $attribute->handle,
                ], [
                    'attribute_group_id' => $testGroup->id,
                    'attribute_type' => $attribute->attribute_type,
                    'handle' => $attribute->handle,
                    'section' => 'main',
                    'type' => $attribute->type,
                    'required' => false,
                    'searchable' => true,
                    'filterable' => false,
                    'system' => false,
                    'position' => $testGroup->attributes()->count() + 1,
                    'name' => [
                        'en' => $attribute->name,
                    ],
                    'configuration' => (array) $attribute->configuration,
                ]);
            }
        });
    }
}
