<?php
/**
 * Class Sellbrite_Api_Model_Credentials
 *
 * Data Model for API credentials
 */
class Sellbrite_Api_Model_Credentials
{
    const AUTH_NAME = 'Sellbrite';

    /**
     * Sellbrite end point URL
     *
     * @var string
     */
    private $endPointURL;

    /**
     * Merchant API key
     *
     * @var string
     */
    protected $apiKey;

    /**
     * Merchant site URL
     *
     * @var string
     */
    protected $siteURL;

    /**
     * Consumer key
     *
     * @var string
     */
    protected $consumerKey;

    /**
     * Consumer secret
     *
     * @var string
     */
    protected $consumerSecret;

    /**
     * Get access token
     *
     * @var string
     */
    protected $accessToken;

    /**
     * Get SOAP user
     *
     * @var string
     */
    protected $soapUser;

    /**
     * Get access secret
     *
     * @var string
     */
    protected $accessSecret;

    public function __construct()
    {
        $this->apiKey = Mage::getStoreConfig('sellbrite_api/config/api_key');
        $this->siteURL = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB);

        $apiUser = Mage::getModel('api/user');
        $apiUser->loadByUsername(self::AUTH_NAME);
        $this->soapUser = $apiUser->getUsername();

        /** @var $consumer Mage_Oauth_Model_Consumer */
        $consumer = Mage::getModel('oauth/consumer');
        $consumer->load(self::AUTH_NAME, 'name');
        $this->consumerKey = $consumer->getKey();
        $this->consumerSecret = $consumer->getSecret();

        /** @var Mage_Admin_Model_User $adminUser */
        $adminUser = Mage::getModel('admin/user');
        $adminUser->loadByUsername(self::AUTH_NAME);

        /** @var Mage_Oauth_Model_Resource_Token_Collection $collection */
        $collection = Mage::getResourceModel('oauth/token_collection');
        $collection->addFilterByAdminId((int) $adminUser->getId());
        $collection->addFilterByConsumerId((int) $consumer->getId());
        /** @var Mage_Oauth_Model_Token $token */
        $token = $collection->getFirstItem();
        $this->accessToken = $token->getToken();
        $this->accessSecret = $token->getSecret();
        $this->endPointURL = (string) Mage::getConfig()->getNode('default/sellbrite_endpoint_url');
    }

    /**
     * Get API Key
     *
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * Get Soap User Name
     *
     * @return string
     */
    public function getSoapUser()
    {
        return $this->soapUser;
    }

    /**
     * Get site URL
     *
     * @return string
     */
    public function getSiteURL()
    {
        return $this->siteURL;
    }

    /**
     * Get end point URL
     *
     * @return string
     */
    public function getEndPointURL()
    {
        return $this->endPointURL;
    }

    /**
     * Get consumer key
     *
     * @return string
     */
    public function getConsumerKey()
    {
        return $this->consumerKey;
    }

    /**
     * Get consumer secret
     *
     * @return string
     */
    public function getConsumerSecret()
    {
        return $this->consumerSecret;
    }

    /**
     * Get access secret
     *
     * @return string
     */
    public function getAccessSecret()
    {
        return $this->accessSecret;
    }

    /**
     * Get access key
     *
     * @return string
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }
}