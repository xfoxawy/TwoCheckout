<?php
namespace TwoCheckout\Resources;

use TwoCheckout\Contracts\Abstracts\ImmutableResource as BaseResource;
use TwoCheckout\Exceptions\TwoCheckoutException;
/**
 * Account Access Interface
 */
class Account extends BaseResource
{
    /**
     * List of API callables 
     * @var Array
     */
    protected $callables = [
        'company_info' => [
            'uri' => '/api/acct/detail_company_info',
            'method' => 'GET'
        ],
        'contact_info' => [
            'uri' => '/api/acct/detail_contact_info',
            'method' => 'GET'
        ]
    ];

    /**
     * The detail_company_info call is used to retrieve your account’s company information details from the Site Management page.
     * @link https://www.2checkout.com/documentation/api/account/detail-company-info
     * @return Array Account's Company info
     */
    public function getCompanyInfo(){
        $retrieved = $this->retrieve(['company_info']);
        return $retrieved['vendor_company_info'];
    }

    /**
     * The detail_contact_info call is used to retrieve your account’s contact information details from the Contact Info page.
     * @link https://www.2checkout.com/documentation/api/account/detail-contact-info
     * @return Array Account's Contact Info
     */
    public function getContactInfo(){
        $retrieved = $this->retrieve(['contact_info']);
        return $retrieved['vendor_contact_info'];
    }    
}