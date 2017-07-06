<?php
namespace TwoCheckout\Resources;

use TwoCheckout\Contracts\Abstracts\FullResource as BaseResource;

/**
 * Coupon Access Interface 
 */
class Coupon extends BaseResource
{
    /**
     * List of API callables 
     * @var Array
     */
    protected $callables = [
        'get_coupon' => [
            'uri'=> '/api/products/detail_coupon',
            'method'=> 'GET'
        ],
        'create_coupon' => [
            'uri'=> '/api/products/create_coupon',
            'method'=> 'POST'
        ],
        'update_coupon' => [
            'uri'=> '/api/products/update_coupon',
            'method'=> 'POST'
        ],
        'delete_coupon' => [
            'uri'=> '/api/products/delete_coupon',
            'method'=> 'POST'
        ],
        'list_coupon' => [
            'uri'=> '/api/products/list_coupons',
            'method'=> 'GET'
        ]
    ];
    /**
     * Create new Product
     * @link https://www.2checkout.com/documentation/api/products/create-coupon
     * @param  String  $date_expire      Expiration date of new coupon. (YYYY-MM-DD) Required.
     * @param  String  $type             Denotes if coupon applies to shipping, sale or product. Required.
     * @param  String  $coupon_code      The string value of coupon code. Optional.
     * @param  Integer $percentage_off   Percentage, if supplied, to discount from purchase price. Can only be used with sale or product type 
     * coupons. (value_off must be NULL, if percentage_off used)
     * @param  Integer $value_off        Decimal value of amount to discount. Can only be used with sale or product type coupons. (percentage_off 
     * must be NULL, if value_off used)
     * @param  Integer $minimum_purchase Decimal value indicating minimum required purchase amount before discount applies. Optional.
     * @param  String  $product_id       2CO system product ID. Can accept multiple product_ids in querystring. (i.e. 
     * product_id=112345678&product_id=987564321) Required for product coupons if select_all is not specified.
     * @param  Integer $select_all       If set to true (1), will select all products and override any product_ids supplied. Set to 0 to remove all 
     * product assignments. Required for product coupons if product_id is not specified.
     * @return String                    coupon_code
     */
    public function new(
        $date_expire, 
        $type, 
        $coupon_code=null,
        $percentage_off=null, 
        $value_off=null, 
        $minimum_purchase=null, 
        $product_id = null, 
        $select_all=0
    ){
        $params['date_expire'] = $date_expire;
        $params['type'] = $type;
        $params['coupon_code'] = $coupon_code;
        $params['percentage_off'] = $percentage_off;
        $params['value_off'] = $value_off;
        $params['minimum_purchase'] = $minimum_purchase;
        $params['product_id'] = $product_id;
        $params['select_all'] = (is_null($product_id)) ? $select_all : null;

        return $this->create($params);
    }

    /**
     * Create New Coupon
     * @link https://www.2checkout.com/documentation/api/products/create-coupon
     * @param  Array $params Coupon's Parameters
     * @return String        Coupon_Code
     */
    public function create(array $params){
        $retrieved = $this->retrieve(['create_coupon', 'params'=>$params]);
        return $retrieved['coupon_code'];
    }

    /**
     * The list_coupons call is used to retrieve list of all coupons in the account.
     * @link https://www.2checkout.com/documentation/api/products/list-coupons
     * @return Array Collection of all coupons
     */
    public function list(){
        $retrieved = $this->retrieve(['list_coupon']);
        return $retrieved['coupon'];
    }

    public function get($coupon_code){
        $params['coupon_code'] = $coupon_code;
        $retrieved = $this->retrieve(['get_coupon', 'params'=>$params]);
        return $retrieved['coupon'];
    }

    /**
     * The update_coupon call is used to update a coupon.
     * @link https://www.2checkout.com/documentation/api/products/update-coupon
     * @param  String $coupon_code 
     * @param  Array  $params      
     * @return Boolean
     */
    public function update($coupon_code, array $params){
        $params['coupon_code'] = $coupon_code;
        $this->retrieve(['update_coupon', 'params'=>$params]);
        return true;
    }

    /**
     * The delete_coupon call is used to delete a coupon.
     * @link https://www.2checkout.com/documentation/api/products/delete-coupon
     * @param  String  $coupon_code 
     * @return Boolean 
     */
    public function delete($coupon_code){
        $params['coupon_code'] = $coupon_code;
        $this->retrieve(['delete_coupon','params'=>$params]);
        return true;
    }

}