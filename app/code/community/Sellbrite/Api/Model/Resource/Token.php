<?php
/**
 * Class Sellbrite_Api_Model_Resource_Token
 */
class Sellbrite_Api_Model_Resource_Token extends Mage_Oauth_Model_Resource_Token
{
    /**
     * Retrieve oauth token using customer id
     *
     * @param Mage_Oauth_Model_Token $token
     * @return Mage_Oauth_Model_Token
     */
    public function getTokenForCustomer(Mage_Oauth_Model_Token $token)
    {
        if (!$token->getId() || !$token->getCustomerId()) {
            //return original data
            return $token;
        }
        $_oldCallbackUrl = $token->getCallbackUrl();
        $select = $this->_getWriteAdapter()->select()
            ->from($this->getMainTable(), array('entity_id'))
            ->where('authorized = 1')
            ->where('type = ?', 'access')
            ->where('entity_id <> ?', $token->getId())
            ->where('customer_id = ?',  $token->getCustomerId());
        $tokenId = $this->_getWriteAdapter()->fetchOne($select);
        if ($tokenId) {
            $this->load($token,$tokenId);
            $token->setCallbackUrl($_oldCallbackUrl);
        }
        return $this;
    }
}