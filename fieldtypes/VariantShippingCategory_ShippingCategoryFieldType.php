<?php
/**
 * Variant Shipping Category plugin for Craft CMS
 *
 * VariantShippingCategory_ShippingCategory FieldType
 *
 * --snip--
 * Whenever someone creates a new field in Craft, they must specify what type of field it is. The system comes with
 * a handful of field types baked in, and we’ve made it extremely easy for plugins to add new ones.
 *
 * https://craftcms.com/docs/plugins/field-types
 * --snip--
 *
 * @author    Roundhouse Agency
 * @copyright Copyright (c) 2017 Roundhouse Agency
 * @link      http://roundhouseagency.com
 * @package   VariantShippingCategory
 * @since     0.1.0
 */

namespace Craft;

class VariantShippingCategory_ShippingCategoryFieldType extends BaseFieldType
{
    /**
     * Returns the name of the fieldtype.
     *
     * @return mixed
     */
    public function getName()
    {
        return Craft::t('VariantShippingCategory_ShippingCategory');
    }

    /**
     * Returns the content attribute config.
     *
     * @return mixed
     */
    public function defineContentAttribute()
    {
        return AttributeType::Mixed;
    }

    /**
     * Returns the field's input HTML.
     *
     * @param string $name
     * @param mixed  $value
     * @return string
     */
    public function getInputHtml($name, $value)
    {
        if (!$value)
            $value = null;
        
        $id = craft()->templates->formatInputId($name);
        $namespacedId = craft()->templates->namespaceInputId($id);
        $shippingCategories = craft()->commerce_shippingCategories->getAllShippingCategories(true);
        
        $shippingCategoryOptions = array();
        foreach ($shippingCategories as $shippingCategory) {
          $shippingCategoryOptions[] = array(
            'label' => $shippingCategory->name,
            'value' => $shippingCategory->id
          );
        }
        
/* -- Include our Javascript & CSS */

        craft()->templates->includeCssResource('variantshippingcategory/css/fields/VariantShippingCategory_ShippingCategoryFieldType.css');
        craft()->templates->includeJsResource('variantshippingcategory/js/fields/VariantShippingCategory_ShippingCategoryFieldType.js');

/* -- Variables to pass down to our field.js */

        $jsonVars = array(
          'id' => $id,
          'name' => $name,
          'value' => $value,
          'element' => $this->element,
          'field' => $this->model,
          'options' => $shippingCategoryOptions
        );

        $jsonVars = json_encode($jsonVars);
        craft()->templates->includeJs("$('#{$namespacedId}-field').VariantShippingCategory_ShippingCategoryFieldType(" . $jsonVars . ");");

/* -- Variables to pass down to our rendered template */
        $variables = array(
          'id' => $id,
          'name' => $name,
          'value' => $value,
          'element' => $this->element,
          'field' => $this->model,
          'options' => $shippingCategoryOptions
        );
        return craft()->templates->render('variantshippingcategory/fields/VariantShippingCategory_ShippingCategoryFieldType.twig', $variables);
    }

    /**
     * Returns the input value as it should be saved to the database.
     *
     * @param mixed $value
     * @return mixed
     */
    public function prepValueFromPost($value)
    {
        // $model = $this->element;
        return $value;
    }

    /**
     * Prepares the field's value for use.
     *
     * @param mixed $value
     * @return mixed
     */
    public function prepValue($value)
    {
        return $value;
    }
}