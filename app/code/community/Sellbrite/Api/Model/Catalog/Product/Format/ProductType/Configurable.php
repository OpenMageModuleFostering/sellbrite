<?php
/**
 * Class Sellbrite_Api_Model_Catalog_Product_Format_ProductType_Configurable
 *
 * REST API output format decorator for Configurable product type
 */
class Sellbrite_Api_Model_Catalog_Product_Format_ProductType_Configurable
{
    /**
     * Extend product item data with variations information
     *
     * @param array $productData
     * @return array
     */
    public function apply($productData)
    {
        /** @var Mage_Catalog_Model_Product $product */
        $product = Mage::getModel('catalog/product')->load($productData['entity_id']);
        /** @var Mage_Catalog_Model_Product_Type_Configurable $typeInstance */
        $typeInstance = $product->getTypeInstance();
        $attributes = $typeInstance->getConfigurableAttributesAsArray($product);
        $variationKeys = array_map(
            function ($item) {
                return $item['attribute_code'];
            },
            $attributes
        );
        $variationPrices = array();
        foreach ($attributes as $attribute) {
            foreach ($attribute['values'] as $value) {
                $variationPrices[$value['value_index']] = array(
                    'price' => $value['pricing_value'],
                    'is_percent' => $value['is_percent']
                );
            }
        }
        $productData['variation_keys'] = $variationKeys;
        $variations = array_map(
            function (Mage_Catalog_Model_Product $productItem) use ($variationKeys, $variationPrices, $product) {
                $result = array(
                    'entity_id' => $productItem->getId(),
                    'sku' => $productItem->getSku(),
                    'price' => 0,
                );
                foreach ($variationKeys as $key) {
                    $result['variation_fields'][$key] = $productItem->getAttributeText($key);
                    $priceKey = $productItem->getData($key);
                    if (isset($variationPrices[$priceKey])) {
                        $priceData = $variationPrices[$priceKey];
                        if ($priceData['is_percent']) {
                            $optionPrice = $product->getFinalPrice() * ($priceData['price'] / 100.00);
                        } else {
                            $optionPrice =  $priceData['price'];
                        }
                        $result['price'] += $optionPrice;
                    }
                }
                $result['price'] += $product->getFinalPrice();

                /** @var Mage_CatalogInventory_Model_Stock_Item $stock */
                $stock = Mage::getModel('cataloginventory/stock_item')->loadByProduct($productItem);
                $result['quantity'] = $stock->getQty();
                return $result;
            },
            $typeInstance->getUsedProducts(null, $product)
        );
        $productData['variations'] = $variationPrices;
        $productData['variations'] = $variations;
        return $productData;
    }
}