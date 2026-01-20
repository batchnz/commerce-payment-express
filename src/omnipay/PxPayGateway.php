<?php

namespace platocreative\paymentexpress\omnipay;

use Omnipay\PaymentExpress\PxPayGateway as BasePxPayGateway;

/**
 * PxPay Gateway
 *
 * Extends the standard PxPayGateway to use custom request classes
 * that support the EmailAddress field for 3D Secure authentication
 */
class PxPayGateway extends BasePxPayGateway
{
    /**
     * Create an authorize request
     *
     * @param array $parameters
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function authorize(array $parameters = array())
    {
        return $this->createRequest('\platocreative\paymentexpress\omnipay\PxPayAuthorizeRequest', $parameters);
    }

    /**
     * Create a purchase request
     *
     * @param array $parameters
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function purchase(array $parameters = array())
    {
        // Check if using PxPost for card reference (same logic as parent)
        if (!empty($parameters['cardReference']) && $this->getPxPostPassword() && $this->getPxPostUsername()) {
            $gateway = \Omnipay\Omnipay::create('PaymentExpress_PxPost');
            $gateway->setPassword($this->getPxPostPassword());
            $gateway->setUserName($this->getPxPostUsername());
            return $gateway->purchase($parameters);
        }

        return $this->createRequest('\platocreative\paymentexpress\omnipay\PxPayPurchaseRequest', $parameters);
    }
}
