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

    /**
     * Return the raw returnUrl parameter.
     *
     * The upstream omnipay PxPayAuthorizeRequest::getReturnUrl() runs the URL through
     * htmlentities(), but getData() then assigns it to a SimpleXMLElement, which escapes
     * it a second time. Windcave therefore stores our UrlSuccess with a double-encoded
     * ampersand ("...&amp;amp;commerceTransactionHash=..."), and its server-side FPRN
     * callbacks arrive with a mangled query string that Craft Commerce cannot parse,
     * causing the payment to go unrecorded. SimpleXML alone provides the single level of
     * XML escaping Windcave requires, so the returnUrl must be passed through raw.
     *
     * @return mixed
     */
    public function getReturnUrl()
    {
        return $this->getParameter('returnUrl');
    }
}
