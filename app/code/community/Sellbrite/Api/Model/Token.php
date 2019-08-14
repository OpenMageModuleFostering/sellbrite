<?php
/**
 * Class Sellbrite_Api_Model_Token
 *
 * @method Sellbrite_Api_Model_Resource_Token getResource
 */
class Sellbrite_Api_Model_Token extends Mage_Oauth_Model_Token
{
    /**
     * Authorize token
     *
     * @param int $userId Authorization user identifier
     * @param string $userType Authorization user type
     * @return Mage_Oauth_Model_Token
     */
    public function authorize($userId, $userType)
    {
        if (!$this->getId() || !$this->getConsumerId()) {
            Mage::throwException('Token is not ready to be authorized');
        }
        if ($this->getAuthorized()) {
            Mage::throwException('Token is already authorized');
        }
        if (self::USER_TYPE_ADMIN == $userType) {
            $this->setAdminId($userId);
        } elseif (self::USER_TYPE_CUSTOMER == $userType) {
            $this->setCustomerId($userId);
        } else {
            Mage::throwException('User type is unknown');
        }
        /** @var $helper Mage_Oauth_Helper_Data */
        $helper = Mage::helper('oauth');
        if  ($this->getCustomerId()) {
            /**
             * override parent logic not to return different token for the same customer account
             */
            $this->getResource()->getTokenForCustomer($this);
        }
        if (!$this->getCustomerId() || ($this->getCustomerId() && $this->getType() != self::TYPE_ACCESS)) {
            $this->setVerifier($helper->generateVerifier());
        }

        $this->setAuthorized(1);
        $this->save();
        $this->getResource()->cleanOldAuthorizedTokensExcept($this);

        return $this;
    }

    /**
     * Convert token to access type
     *
     * @return Mage_Oauth_Model_Token
     */
    public function convertToAccess()
    {
        /**
         * customization to allow the same token for difference devices
         */
        if ($this->getType() != self::TYPE_ACCESS && self::TYPE_REQUEST != $this->getType()) {
            Mage::throwException('Can not convert due to token is not request type');
        }

        /** @var $helper Mage_Oauth_Helper_Data */
        $helper = Mage::helper('oauth');
        /**
         * customization to allow the same token
         * we will only save when it is a new token
         */
        if ($this->getType() != self::TYPE_ACCESS) {
            $this->setType(self::TYPE_ACCESS);
            $this->setToken($helper->generateToken());
            $this->setSecret($helper->generateTokenSecret());
            $this->save();
        }
        return $this;
    }
}