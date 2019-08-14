<?php
/**
 * Class Sellbrite_Api_Model_Catalog_Api2_Product_Rest_Admin_V1_Interceptor
 *
 * How To Extend a Logic of This Class?
 *
 * This class is designed to provide new extension points for overridden class Mage_Catalog_Model_Api2_Product_Rest_Admin_V1
 * Every extension can decorate/pluginize all methods of Mage_Catalog_Model_Api2_Product_Rest_Admin_V1 (public and protected)
 * using config.xml file. Please, declare your configuration in the following format:
 *  <config>
 *   <global>
 *    <catalog_product_list_item_plugins>
 *      <{intercepted_method_name}>
 *        <{strategy_type}>
 *          <any_node_name>namespace_module/class_name</any_node_name>
 *        </{strategy_type}>
 *      </{intercepted_method_name}>
 *    </catalog_product_list_item_plugins>
 *   </global>
 *  </config>
 *
 * where {intercepted_method_name} - one of the method of Mage_Catalog_Model_Api2_Product_Rest_Admin_V1,
 *       {strategy_type} - interception strategy type:
 *          - before (method invokeBefore of your class will be called before original call),
 *          - after (method invokeAfter of your class will be called after original call),
 *          - around (method invokeAround of your class will be called instead of original call)
 */
class Sellbrite_Api_Model_Catalog_Api2_Product_Rest_Admin_V1_Interceptor extends
    Mage_Catalog_Model_Api2_Product_Rest_Admin_V1
{
    const LISTENER_BEFORE = 'before';
    const LISTENER_AROUND = 'around';
    const LISTENER_AFTER = 'after';

    /**
     * Get declared plugins
     *
     * @param string $methodName
     * @return array|null
     */
    protected function _getPluginsInfo($methodName)
    {
        $pluginInfo = Mage::getConfig()->getNode(
            'global/catalog_product_list_item_plugins/' . $methodName
        );

        if (!$pluginInfo) {
            return null;
        }
        return $pluginInfo->asArray();
    }

    /**
     * Call plugins
     *
     * @param string $method
     * @param array $arguments
     * @param array $pluginInfo
     * @return mixed
     */
    protected function ___callPlugins($method, array $arguments, array $pluginInfo)
    {
        $result = null;
        if (isset($pluginInfo[self::LISTENER_BEFORE])) {
            // Call 'before' listeners
            foreach ($pluginInfo[self::LISTENER_BEFORE] as $code) {
                $beforeResult = call_user_func_array(array(Mage::getSingleton($code), 'invokeBefore'), $arguments);
                if ($beforeResult) {
                    $arguments = $beforeResult;
                }
            }
        }

        if (isset($pluginInfo[self::LISTENER_AROUND])) {
            // Call 'around' listener
            foreach ($pluginInfo[self::LISTENER_AFTER] as $code) {
                $result = call_user_func_array(
                    array(Mage::getSingleton($code), 'invokeAround'),
                    array_merge($arguments, $result)
                );
            }
        } else {
            // Call original method
            $result = call_user_func_array(array('parent', $method), $arguments);
        }

        if (isset($pluginInfo[self::LISTENER_AFTER])) {
            // Call 'after' listeners
            foreach ($pluginInfo[self::LISTENER_AFTER] as $code) {
                $result = call_user_func_array(array(Mage::getSingleton($code), 'invokeAfter'), array($result));
            }
        }
        return $result;
    }


    //======================================= Start Auto-generated Code ======================================//

    /**
     * {@inheritdoc}
     */
    public function getAvailableAttributes($userType, $operation)
    {
        $pluginInfo = $this->_getPluginsInfo('getAvailableAttributes');
        if (!$pluginInfo) {
            return parent::getAvailableAttributes($userType, $operation);
        } else {
            return $this->___callPlugins('getAvailableAttributes', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function _isAttributeVisible(Mage_Catalog_Model_Resource_Eav_Attribute $attribute, $userType)
    {
        $pluginInfo = $this->_getPluginsInfo('_isAttributeVisible');
        if (!$pluginInfo) {
            return parent::_isAttributeVisible($attribute, $userType);
        } else {
            return $this->___callPlugins('_isAttributeVisible', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function dispatch()
    {
        $pluginInfo = $this->_getPluginsInfo('dispatch');
        if (!$pluginInfo) {
            parent::dispatch();
        } else {
            return $this->___callPlugins('dispatch', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function _errorIfMethodNotExist($methodName)
    {
        $pluginInfo = $this->_getPluginsInfo('_errorIfMethodNotExist');
        if (!$pluginInfo) {
            parent::_errorIfMethodNotExist($methodName);
        } else {
            return $this->___callPlugins('_errorIfMethodNotExist', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function _checkMethodExist($methodName)
    {
        $pluginInfo = $this->_getPluginsInfo('_checkMethodExist');
        if (!$pluginInfo) {
            return parent::_checkMethodExist($methodName);
        } else {
            return $this->___callPlugins('_checkMethodExist', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getRequest()
    {
        $pluginInfo = $this->_getPluginsInfo('getRequest');
        if (!$pluginInfo) {
            return parent::getRequest();
        } else {
            return $this->___callPlugins('getRequest', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setRequest(Mage_Api2_Model_Request $request)
    {
        $pluginInfo = $this->_getPluginsInfo('setRequest');
        if (!$pluginInfo) {
            return parent::setRequest($request);
        } else {
            return $this->___callPlugins('setRequest', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getResourceType()
    {
        $pluginInfo = $this->_getPluginsInfo('getResourceType');
        if (!$pluginInfo) {
            return parent::getResourceType();
        } else {
            return $this->___callPlugins('getResourceType', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setResourceType($resourceType)
    {
        $pluginInfo = $this->_getPluginsInfo('setResourceType');
        if (!$pluginInfo) {
            return parent::setResourceType($resourceType);
        } else {
            return $this->___callPlugins('setResourceType', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getApiType()
    {
        $pluginInfo = $this->_getPluginsInfo('getApiType');
        if (!$pluginInfo) {
            return parent::getApiType();
        } else {
            return $this->___callPlugins('getApiType', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setApiType($apiType)
    {
        $pluginInfo = $this->_getPluginsInfo('setApiType');
        if (!$pluginInfo) {
            return parent::setApiType($apiType);
        } else {
            return $this->___callPlugins('setApiType', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getVersion()
    {
        $pluginInfo = $this->_getPluginsInfo('getVersion');
        if (!$pluginInfo) {
            return parent::getVersion();
        } else {
            return $this->___callPlugins('getVersion', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setVersion($version)
    {
        $pluginInfo = $this->_getPluginsInfo('setVersion');
        if (!$pluginInfo) {
            parent::setVersion($version);
        } else {
            return $this->___callPlugins('setVersion', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getResponse()
    {
        $pluginInfo = $this->_getPluginsInfo('getResponse');
        if (!$pluginInfo) {
            return parent::getResponse();
        } else {
            return $this->___callPlugins('getResponse', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setResponse(Mage_Api2_Model_Response $response)
    {
        $pluginInfo = $this->_getPluginsInfo('setResponse');
        if (!$pluginInfo) {
            parent::setResponse($response);
        } else {
            return $this->___callPlugins('setResponse', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getFilter()
    {
        $pluginInfo = $this->_getPluginsInfo('getFilter');
        if (!$pluginInfo) {
            return parent::getFilter();
        } else {
            return $this->___callPlugins('getFilter', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setFilter(Mage_Api2_Model_Acl_Filter $filter)
    {
        $pluginInfo = $this->_getPluginsInfo('setFilter');
        if (!$pluginInfo) {
            parent::setFilter($filter);
        } else {
            return $this->___callPlugins('setFilter', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getRenderer()
    {
        $pluginInfo = $this->_getPluginsInfo('getRenderer');
        if (!$pluginInfo) {
            return parent::getRenderer();
        } else {
            return $this->___callPlugins('getRenderer', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setRenderer(Mage_Api2_Model_Renderer_Interface $renderer)
    {
        $pluginInfo = $this->_getPluginsInfo('setRenderer');
        if (!$pluginInfo) {
            parent::setRenderer($renderer);
        } else {
            return $this->___callPlugins('setRenderer', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getUserType()
    {
        $pluginInfo = $this->_getPluginsInfo('getUserType');
        if (!$pluginInfo) {
            return parent::getUserType();
        } else {
            return $this->___callPlugins('getUserType', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setUserType($userType)
    {
        $pluginInfo = $this->_getPluginsInfo('setUserType');
        if (!$pluginInfo) {
            return parent::setUserType($userType);
        } else {
            return $this->___callPlugins('setUserType', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getApiUser()
    {
        $pluginInfo = $this->_getPluginsInfo('getApiUser');
        if (!$pluginInfo) {
            return parent::getApiUser();
        } else {
            return $this->___callPlugins('getApiUser', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setApiUser(Mage_Api2_Model_Auth_User_Abstract $apiUser)
    {
        $pluginInfo = $this->_getPluginsInfo('setApiUser');
        if (!$pluginInfo) {
            return parent::setApiUser($apiUser);
        } else {
            return $this->___callPlugins('setApiUser', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getActionType()
    {
        $pluginInfo = $this->_getPluginsInfo('getActionType');
        if (!$pluginInfo) {
            return parent::getActionType();
        } else {
            return $this->___callPlugins('getActionType', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setActionType($actionType)
    {
        $pluginInfo = $this->_getPluginsInfo('setActionType');
        if (!$pluginInfo) {
            return parent::setActionType($actionType);
        } else {
            return $this->___callPlugins('setActionType', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getOperation()
    {
        $pluginInfo = $this->_getPluginsInfo('getOperation');
        if (!$pluginInfo) {
            return parent::getOperation();
        } else {
            return $this->___callPlugins('getOperation', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setOperation($operation)
    {
        $pluginInfo = $this->_getPluginsInfo('setOperation');
        if (!$pluginInfo) {
            return parent::setOperation($operation);
        } else {
            return $this->___callPlugins('setOperation', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getConfig()
    {
        $pluginInfo = $this->_getPluginsInfo('getConfig');
        if (!$pluginInfo) {
            return parent::getConfig();
        } else {
            return $this->___callPlugins('getConfig', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getWorkingModel()
    {
        $pluginInfo = $this->_getPluginsInfo('getWorkingModel');
        if (!$pluginInfo) {
            return parent::getWorkingModel();
        } else {
            return $this->___callPlugins('getWorkingModel', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function _render($data)
    {
        $pluginInfo = $this->_getPluginsInfo('_render');
        if (!$pluginInfo) {
            parent::_render($data);
        } else {
            return $this->___callPlugins('_render', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function _critical($message, $code = null)
    {
        $pluginInfo = $this->_getPluginsInfo('_critical');
        if (!$pluginInfo) {
            parent::_critical($message, $code);
        } else {
            return $this->___callPlugins('_critical', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function _getCriticalErrors()
    {
        $pluginInfo = $this->_getPluginsInfo('_getCriticalErrors');
        if (!$pluginInfo) {
            return parent::_getCriticalErrors();
        } else {
            return $this->___callPlugins('_getCriticalErrors', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function _error($message, $code)
    {
        $pluginInfo = $this->_getPluginsInfo('_error');
        if (!$pluginInfo) {
            return parent::_error($message, $code);
        } else {
            return $this->___callPlugins('_error', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function _successMessage($message, $code, $params = array())
    {
        $pluginInfo = $this->_getPluginsInfo('_successMessage');
        if (!$pluginInfo) {
            return parent::_successMessage($message, $code, $params);
        } else {
            return $this->___callPlugins('_successMessage', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function _errorMessage($message, $code, $params = array())
    {
        $pluginInfo = $this->_getPluginsInfo('_errorMessage');
        if (!$pluginInfo) {
            return parent::_errorMessage($message, $code, $params);
        } else {
            return $this->___callPlugins('_errorMessage', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function _applyFilter(Varien_Data_Collection_Db $collection)
    {
        $pluginInfo = $this->_getPluginsInfo('_applyFilter');
        if (!$pluginInfo) {
            return parent::_applyFilter($collection);
        } else {
            return $this->___callPlugins('_applyFilter', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function _multicall($resourceInstanceId)
    {
        $pluginInfo = $this->_getPluginsInfo('_multicall');
        if (!$pluginInfo) {
            return parent::_multicall($resourceInstanceId);
        } else {
            return $this->___callPlugins('_multicall', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function _getSubModel($resourceId, array $requestParams)
    {
        $pluginInfo = $this->_getPluginsInfo('_getSubModel');
        if (!$pluginInfo) {
            return parent::_getSubModel($resourceId, $requestParams);
        } else {
            return $this->___callPlugins('_getSubModel', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function _isSubCallAllowed($resourceId)
    {
        $pluginInfo = $this->_getPluginsInfo('_isSubCallAllowed');
        if (!$pluginInfo) {
            return parent::_isSubCallAllowed($resourceId);
        } else {
            return $this->___callPlugins('_isSubCallAllowed', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setReturnData($flag)
    {
        $pluginInfo = $this->_getPluginsInfo('setReturnData');
        if (!$pluginInfo) {
            return parent::setReturnData($flag);
        } else {
            return $this->___callPlugins('setReturnData', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function _getLocation($resource)
    {
        $pluginInfo = $this->_getPluginsInfo('_getLocation');
        if (!$pluginInfo) {
            return parent::_getLocation($resource);
        } else {
            return $this->___callPlugins('_getLocation', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function _getResourceAttributes()
    {
        $pluginInfo = $this->_getPluginsInfo('_getResourceAttributes');
        if (!$pluginInfo) {
            return parent::_getResourceAttributes();
        } else {
            return $this->___callPlugins('_getResourceAttributes', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getExcludedAttributes($userType, $operation)
    {
        $pluginInfo = $this->_getPluginsInfo('getExcludedAttributes');
        if (!$pluginInfo) {
            return parent::getExcludedAttributes($userType, $operation);
        } else {
            return $this->___callPlugins('getExcludedAttributes', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getForcedAttributes()
    {
        $pluginInfo = $this->_getPluginsInfo('getForcedAttributes');
        if (!$pluginInfo) {
            return parent::getForcedAttributes();
        } else {
            return $this->___callPlugins('getForcedAttributes', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getIncludedAttributes($userType, $operationType)
    {
        $pluginInfo = $this->_getPluginsInfo('getIncludedAttributes');
        if (!$pluginInfo) {
            return parent::getIncludedAttributes($userType, $operationType);
        } else {
            return $this->___callPlugins('getIncludedAttributes', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getEntityOnlyAttributes($userType, $operationType)
    {
        $pluginInfo = $this->_getPluginsInfo('getEntityOnlyAttributes');
        if (!$pluginInfo) {
            return parent::getEntityOnlyAttributes($userType, $operationType);
        } else {
            return $this->___callPlugins('getEntityOnlyAttributes', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getAvailableAttributesFromConfig()
    {
        $pluginInfo = $this->_getPluginsInfo('getAvailableAttributesFromConfig');
        if (!$pluginInfo) {
            return parent::getAvailableAttributesFromConfig();
        } else {
            return $this->___callPlugins('getAvailableAttributesFromConfig', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getDbAttributes()
    {
        $pluginInfo = $this->_getPluginsInfo('getDbAttributes');
        if (!$pluginInfo) {
            return parent::getDbAttributes();
        } else {
            return $this->___callPlugins('getDbAttributes', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getEavAttributes($onlyVisible = false, $excludeSystem = false)
    {
        $pluginInfo = $this->_getPluginsInfo('getEavAttributes');
        if (!$pluginInfo) {
            return parent::getEavAttributes($onlyVisible, $excludeSystem);
        } else {
            return $this->___callPlugins('getEavAttributes', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function _getStore()
    {
        $pluginInfo = $this->_getPluginsInfo('_getStore');
        if (!$pluginInfo) {
            return parent::_getStore();
        } else {
            return $this->___callPlugins('_getStore', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function _retrieve()
    {
        $pluginInfo = $this->_getPluginsInfo('_retrieve');
        if (!$pluginInfo) {
            return parent::_retrieve();
        } else {
            return $this->___callPlugins('_retrieve', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function _applyCategoryFilter(Mage_Catalog_Model_Resource_Product_Collection $collection)
    {
        $pluginInfo = $this->_getPluginsInfo('_applyCategoryFilter');
        if (!$pluginInfo) {
            parent::_applyCategoryFilter($collection);
        } else {
            return $this->___callPlugins('_applyCategoryFilter', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function _getProduct()
    {
        $pluginInfo = $this->_getPluginsInfo('_getProduct');
        if (!$pluginInfo) {
            return parent::_getProduct();
        } else {
            return $this->___callPlugins('_getProduct', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function _setProduct(Mage_Catalog_Model_Product $product)
    {
        $pluginInfo = $this->_getPluginsInfo('_setProduct');
        if (!$pluginInfo) {
            parent::_setProduct($product);
        } else {
            return $this->___callPlugins('_setProduct', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function _getCategoryById($categoryId)
    {
        $pluginInfo = $this->_getPluginsInfo('_getCategoryById');
        if (!$pluginInfo) {
            return parent::_getCategoryById($categoryId);
        } else {
            return $this->___callPlugins('_getCategoryById', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function _getPrice($price, $includingTax = null, $shippingAddress = null,
                                 $billingAddress = null, $ctc = null, $priceIncludesTax = null
    )
    {
        $pluginInfo = $this->_getPluginsInfo('_getPrice');
        if (!$pluginInfo) {
            return parent::_getPrice($price, $includingTax, $shippingAddress, $billingAddress, $ctc, $priceIncludesTax);
        } else {
            return $this->___callPlugins('_getPrice', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function _calculatePrice($price, $percent, $includeTax)
    {
        $pluginInfo = $this->_getPluginsInfo('_calculatePrice');
        if (!$pluginInfo) {
            return parent::_calculatePrice($price, $percent, $includeTax);
        } else {
            return $this->___callPlugins('_calculatePrice', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function _getTierPrices()
    {
        $pluginInfo = $this->_getPluginsInfo('_getTierPrices');
        if (!$pluginInfo) {
            return parent::_getTierPrices();
        } else {
            return $this->___callPlugins('_getTierPrices', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function _getCustomerGroupId()
    {
        $pluginInfo = $this->_getPluginsInfo('_getCustomerGroupId');
        if (!$pluginInfo) {
            return parent::_getCustomerGroupId();
        } else {
            return $this->___callPlugins('_getCustomerGroupId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function _applyTaxToPrice($price, $withTax = true)
    {
        $pluginInfo = $this->_getPluginsInfo('_applyTaxToPrice');
        if (!$pluginInfo) {
            return parent::_applyTaxToPrice($price, $withTax);
        } else {
            return $this->___callPlugins('_applyTaxToPrice', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function _prepareProductForResponse(Mage_Catalog_Model_Product $product)
    {
        $pluginInfo = $this->_getPluginsInfo('_prepareProductForResponse');
        if (!$pluginInfo) {
            parent::_prepareProductForResponse($product);
        } else {
            return $this->___callPlugins('_prepareProductForResponse', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function _filterOutArrayKeys(array $array, array $keys, $dropOrigKeys = false)
    {
        $pluginInfo = $this->_getPluginsInfo('_filterOutArrayKeys');
        if (!$pluginInfo) {
            return parent::_filterOutArrayKeys($array, $keys, $dropOrigKeys);
        } else {
            return $this->___callPlugins('_filterOutArrayKeys', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function _retrieveCollection()
    {
        $pluginInfo = $this->_getPluginsInfo('_retrieveCollection');
        if (!$pluginInfo) {
            return parent::_retrieveCollection();
        } else {
            return $this->___callPlugins('_retrieveCollection', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function _delete()
    {
        $pluginInfo = $this->_getPluginsInfo('_delete');
        if (!$pluginInfo) {
            parent::_delete();
        } else {
            return $this->___callPlugins('_delete', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function _create(array $data)
    {
        $pluginInfo = $this->_getPluginsInfo('_create');
        if (!$pluginInfo) {
            return parent::_create($data);
        } else {
            return $this->___callPlugins('_create', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function _update(array $data)
    {
        $pluginInfo = $this->_getPluginsInfo('_update');
        if (!$pluginInfo) {
            parent::_update($data);
        } else {
            return $this->___callPlugins('_update', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function _isManageStockEnabled($stockData)
    {
        $pluginInfo = $this->_getPluginsInfo('_isManageStockEnabled');
        if (!$pluginInfo) {
            return parent::_isManageStockEnabled($stockData);
        } else {
            return $this->___callPlugins('_isManageStockEnabled', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function _isConfigValueUsed($data, $field)
    {
        $pluginInfo = $this->_getPluginsInfo('_isConfigValueUsed');
        if (!$pluginInfo) {
            return parent::_isConfigValueUsed($data, $field);
        } else {
            return $this->___callPlugins('_isConfigValueUsed', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function _prepareDataForSave($product, $productData)
    {
        $pluginInfo = $this->_getPluginsInfo('_prepareDataForSave');
        if (!$pluginInfo) {
            parent::_prepareDataForSave($product, $productData);
        } else {
            return $this->___callPlugins('_prepareDataForSave', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function _filterStockData(&$stockData)
    {
        $pluginInfo = $this->_getPluginsInfo('_filterStockData');
        if (!$pluginInfo) {
            parent::_filterStockData($stockData);
        } else {
            return $this->___callPlugins('_filterStockData', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function _filterConfigValueUsed(&$data, $fields)
    {
        $pluginInfo = $this->_getPluginsInfo('_filterConfigValueUsed');
        if (!$pluginInfo) {
            parent::_filterConfigValueUsed($data, $fields);
        } else {
            return $this->___callPlugins('_filterConfigValueUsed', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function _isAllowedAttribute($attribute, $attributes = null)
    {
        $pluginInfo = $this->_getPluginsInfo('_isAllowedAttribute');
        if (!$pluginInfo) {
            return parent::_isAllowedAttribute($attribute, $attributes);
        } else {
            return $this->___callPlugins('_isAllowedAttribute', func_get_args(), $pluginInfo);
        }
    }
    //======================================= Start Auto-generated Code ======================================//
}
