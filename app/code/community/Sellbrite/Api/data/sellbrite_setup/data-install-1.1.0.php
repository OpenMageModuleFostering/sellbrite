<?php
/**
 * Install Sellbrite API data fixtures
 * Required entities:
 *  - Consumer APP
 *  - Grand Admin user type with ALL privileges
 *  - Rest Role with all privileges granted
 *  - Rest User
 *  - Access Token
 */

//I. Create REST API Credentials
$chars = Mage_Core_Helper_Data::CHARS_PASSWORD_LOWERS
    . Mage_Core_Helper_Data::CHARS_PASSWORD_UPPERS
    . Mage_Core_Helper_Data::CHARS_PASSWORD_DIGITS
    . Mage_Core_Helper_Data::CHARS_PASSWORD_SPECIALS;
/** @var $helper Mage_Oauth_Helper_Data */
$helper = Mage::helper('oauth');
/** @var Mage_Core_Helper_Data $generator */
$generator = Mage::helper('core');

//======================= Predefined values ======================//
$authName = Sellbrite_Api_Model_Credentials::AUTH_NAME;
$adminEmail = 'magento@sellbrite.com';
$callBackURL = Mage::getBaseUrl();
$adminPassword = $generator->getRandomString(8, $chars);
//================================================================//

/** 1. Create consumer app in Magento */
/** @var $consumer Mage_Oauth_Model_Consumer */
$consumer = Mage::getModel('oauth/consumer');
$consumer->load($authName, 'name');
if (!$consumer->getId()) {
    $consumer->setKey($helper->generateConsumerKey());
    $consumer->setSecret($helper->generateConsumerSecret());
    $consumer->setName($authName);
    $consumer->save();
}

/** 2. Update REST admin attributes to have access to All */
/** @var $attribute Mage_Api2_Model_Acl_Filter_Attribute */
$attribute = Mage::getModel('api2/acl_filter_attribute');
/** @var $collection Mage_Api2_Model_Resource_Acl_Filter_Attribute_Collection */
$collection = $attribute->getCollection();
//Clean up existing resources if any
$collection->addFilterByUserType('admin');
/** @var $model Mage_Api2_Model_Acl_Filter_Attribute */
foreach ($collection as $model) {
    $model->delete();
}
$attribute->setUserType('admin');
$attribute->setResourceId(Mage_Api2_Model_Acl_Global_Rule::RESOURCE_ALL);
$attribute->save();

/** 3. Create REST role with all permissions */

/** @var $role Mage_Api2_Model_Acl_Global_Role */
$role = Mage::getModel('api2/acl_global_role');
$role->load($authName, 'role_name');
if (!$role->getId()) {
    $role->setRoleName($authName);
    $role->save();
} else {
    //Remove existing rule if any
    /** @var Mage_Api2_Model_Resource_Acl_Global_Rule_Collection $ruleCollection */
    $ruleCollection = Mage::getResourceModel('api2/acl_global_rule_collection');
    $ruleCollection->addFilterByRoleId($role->getId());
    /** @var $ruleModel Mage_Api2_Model_Acl_Global_Rule */
    foreach ($collection as $ruleModel) {
        $ruleModel->delete();
    }
}
//Create new rule
/** @var $rule Mage_Api2_Model_Acl_Global_Rule */
$rule = Mage::getModel('api2/acl_global_rule');
$rule->setRoleId($role->getId());
$rule->setResourceId(Mage_Api2_Model_Acl_Global_Rule::RESOURCE_ALL);
$rule->save();

/** 4. Create admin user and associate with REST role */
/** @var Mage_Admin_Model_User $adminUser */
$adminUser = Mage::getModel('admin/user');
$adminUser->loadByUsername($authName);
if (!$adminUser->getId()) {
    $adminUser->setUsername($authName);
    $adminUser->setFirstname($authName);
    $adminUser->setLastname($authName);
    $adminUser->setEmail($adminEmail);
    $adminUser->setPassword($adminPassword);
    $adminUser->setIsActive(1);
    $adminUser->save();
}

/** @var $resourceModel Mage_Api2_Model_Resource_Acl_Global_Role */
$resourceModel = Mage::getResourceModel('api2/acl_global_role');
//Create/update relation row of admin user to API2 role
$resourceModel->saveAdminToRoleRelation($adminUser->getId(), $role->getId());


/** 5. Associate this admin user to work with the created app */
/** @var Mage_Oauth_Model_Token $token */
$token = Mage::getModel('oauth/token');
$token->setConsumerId($consumer->getId());
$token->setAdminId($adminUser->getId());
$token->setType(Mage_Oauth_Model_Token::TYPE_ACCESS);
$token->setToken($helper->generateToken());
$token->setSecret($helper->generateTokenSecret());
$token->setCallbackUrl($callBackURL);
$token->setVerifier($helper->generateVerifier());
$token->setAuthorized(1);
$token->save();

//Clean up old authorized tokens for specified consumer-user pairs
$token->getResource()->cleanOldAuthorizedTokensExcept($token);

//II. Create SOAP API Credentials
//1. Generate API key
$apiKey = Mage::getStoreConfig('sellbrite_api/config/api_key');
if( !$apiKey || strlen($apiKey) < 32 ){
    $apiKey = md5(uniqid(rand(), true));
    $config = new Mage_Core_Model_Config();
    $config ->saveConfig('sellbrite_api/config/api_key', $apiKey, 'default', 0);
}

//2. Create API Role
/** @var Mage_Api_Model_Resource_Roles_Collection $roleCollection */
$roleCollection = Mage::getModel('api/roles')->getCollection();
$roleCollection->addFieldToFilter('role_name', $authName);
$roleCollection->setPageSize(1);
$apiRole = $roleCollection->fetchItem();
if (!$apiRole) {
    //create new role
    /** @var Mage_Api_Model_Roles $role */
    $apiRole = Mage::getModel("api/roles");
    $apiRole->setName($authName);
    $apiRole->setRoleType('G');
    $apiRole->save();

    //give "all" privileges to role
    /** @var Mage_Api_Model_Rules $rule */
    $rule = Mage::getModel("api/rules");
    $rule->setRoleId($apiRole->getId());
    $rule->setResources(array(Mage_Api2_Model_Acl_Global_Rule::RESOURCE_ALL));
    $rule->saveRel();
}

//3. Create SOAP Api User
//create user if it does not exist
$apiUser = Mage::getModel('api/user');
$apiUser->loadByUsername($authName);
if ($apiUser->getId()) {
    //update api key
    $apiUser->setApiKey($apiKey);
    $apiUser->save();
}else{
    /** @var Mage_Api_Model_User $apiUser */
    $apiUser = Mage::getModel('api/user')
        ->setData(array(
            'username'             => $authName,
            'firstname'            => $authName,
            'lastname'             => $authName,
            'email'                => $adminEmail,
            'api_key'              => $apiKey,
            'api_key_confirmation' => $apiKey,
            'is_active'            => 1,
            'user_roles'           => '',
            'assigned_user_role'   => '',
            'role_name'            => '',
            'roles'                => array($apiRole->getId()) // your created custom role
        ));
    $apiUser->save();

    //assign role to user
    $apiUser
        ->setRoleIds(array($apiRole->getId()))  // your created custom role
        ->setRoleUserId($apiUser->getUserId())
        ->saveRelations();
}