<?php

namespace platocreative\paymentexpress\omnipay;

/**
 * PxPay Purchase Request
 *
 * Uses the PxPayAuthorizeRequest as base to inherit EmailAddress support
 */
class PxPayPurchaseRequest extends PxPayAuthorizeRequest
{
    protected $action = 'Purchase';
}
