<?php

namespace platocreative\paymentexpress\omnipay;

use SimpleXMLElement;
use Omnipay\PaymentExpress\Message\PxPayAuthorizeRequest as BasePxPayAuthorizeRequest;

/**
 * PxPay Authorize Request with EmailAddress support
 *
 * Extends the standard PxPayAuthorizeRequest to include EmailAddress field
 * required for 3D Secure authentication (Visa requirement as of Feb 2026)
 */
class PxPayAuthorizeRequest extends BasePxPayAuthorizeRequest
{
    /**
     * Get the EmailAddress
     *
     * @return mixed
     */
    public function getEmailAddress()
    {
        return $this->getParameter('emailAddress');
    }

    /**
     * Set the EmailAddress
     *
     * @param string $value
     * @return $this
     */
    public function setEmailAddress($value)
    {
        return $this->setParameter('emailAddress', $value);
    }

    /**
     * Get the transaction data
     *
     * Overrides parent to include EmailAddress in the XML request
     *
     * @return SimpleXMLElement
     */
    public function getData()
    {
        // Call parent to get the standard XML structure
        $data = parent::getData();

        // Add EmailAddress if it's set
        if ($this->getEmailAddress()) {
            $data->EmailAddress = $this->getEmailAddress();
        }

        return $data;
    }
}
