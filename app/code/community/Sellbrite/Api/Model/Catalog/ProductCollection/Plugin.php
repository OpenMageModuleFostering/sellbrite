<?php
/**
 * Class Sellbrite_Api_Model_Catalog_ProductCollection_Plugin
 *
 * How To Extend a Logic of This Class?
 *
 * This class is designed as a Plugin for Mage_Catalog_Model_Api2_Product_Rest_Admin_V1 model
 * Decorates REST API output format for all product types.
 *
 * If you need to add/override output decorator for any product type, please, add configuration to the config.xml file
 * in the following format:
 *  <config>
 *   <global>
 *    <compositions>
 *      <product_type_plugin>
 *        <{product_type_id}>namespace_module/class_name</{product_type_id}>
 *      </product_type_plugin>
 *    </compositions>
 *   </global>
 *  </config>
 *
 * where {product_type_id} - Product Type Code: simple, configurable, grouped, virtual, etc...
 */
class Sellbrite_Api_Model_Catalog_ProductCollection_Plugin
{
    /**
     * Customize output product format
     *
     * @param array $result
     * @return array
     */
    public function invokeAfter(array $result)
    {
        $composition = Mage::getConfig()->getNode('global/compositions/product_type_plugin')->asArray();

        foreach ($result as &$item) {
            if (isset($composition[$item['type_id']])) {
                $item = Mage::getSingleton($composition[$item['type_id']])->apply($item);
            }
        }
        return $result;
    }
}