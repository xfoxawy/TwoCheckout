<?php
namespace TwoCheckout\Resources;
use TwoCheckout\Contracts\Abstracts\FullResource as BaseResource;
use TwoCheckout\Exceptions\TwoCheckoutException;
use TwoCheckout\Exceptions\TwoCheckoutApiException;

class Sale extends BaseResource
{
    public static $page_set;
    public static $page_next;
    public static $page_previous;
    public static $page_first;
    public static $page_last;
    public static $total;
    protected $new_sale_params;
    
    /**
     * List of API callables 
     * @var Array
     */
    protected $callables = [
        'create_sale'=>[
            'uri' => '/checkout/api/1/%s/rs/authService',
            'method' => 'POST'
        ],
        'refund_sale' => [
            'uri' => '/api/sales/refund_invoice',
            'method' => 'POST'
        ],
        'refund_lineitem' => [
            'uri' => '/api/sales/refund_lineitem',
            'method' => 'POST'
        ],
        'stop_lineitem_recurring' => [
            'uri' => '/api/sales/stop_lineitem_recurring',
            'method' => 'POST'
        ],
        'mark_shipped' => [
            'uri' => '/api/sales/mark_shipped',
            'method' => 'POST'
        ],
        'create_comment' => [
            'uri' => '/api/sales/create_comment',
            'method' => 'POST'
        ],
        'list_sales' => [
            'uri' => '/api/sales/list_sales',
            'method' => 'GET'
        ],
        'list_sales_last' => [
            'uri' => '',
            'method' => 'GET'
        ],
        'list_sales_first' => [
            'uri' => '',
            'method' => 'GET'
        ],
        'get_sale' => [
            'uri' => '/api/sales/detail_sale',
            'method' => 'GET'
        ]
    ];
    
    /**
     * Create New simple Sale Object ready to charge
     * @link https://www.2checkout.com/documentation/payment-api/create-sale 
     * @param  String $merchantOrderId Your custom order identifier. Required.
     * @param  String $token           The credit card token. Required.
     * @param  Float  $total           The Sale Total. Format: 0.00-99999999.99, defaults to 0. (Only Use if you are not passing in lineItems.)
     * @param  String $currency        Use to specify the currency for the sale(Config Default).
     * @return Object                  Sale Object ready to charge
     */
    public function new($merchantOrderId, $token, $total=0, $currency=null){
        $this->new_sale_params['sellerId'] = $this->config()->seller_id;
        $this->new_sale_params['privateKey'] = $this->config()->private_key;
        $this->new_sale_params['currency'] = (isset($currency))? : $this->config()->default_currency;
        $this->new_sale_params['merchantOrderId'] = $merchantOrderId;
        $this->new_sale_params['token'] = $token;
        $this->new_sale_params['total'] = $total;
        return $this;
    }

    /**
     * Object that defines the billing address using the attributes specified below. Required
     * (Passed as a sub object to the Authorization Object.) 
     * @param String $name        Card holder’s name. (128 characters max) Required
     * @param String $email       Card holder’s email. (64 characters max) Required
     * @param String $phoneNumber Card holder’s phone. (16 characters max) Required
     * @param String $addrLine1   Card holder’s street address. (64 characters max) Required
     * @param String $city        Card holder’s city. (64 characters max) Required
     * @param String $country     Card holder’s country. (64 characters max) Required
     * @param String $state       Card holder’s state. (64 characters max) Required for some countries.
     * @param String $zipCode   Card holder’s zip. (16 characters max) Required for some countries.
     * @param String $addrLine2   Card holder’s street address line 2. (64 characters max). Optional.
     * @param String $phoneExt    Card holder’s phone extension. (9 characters max) Optional
     */
    public function addBillingAddress(
        $name, 
        $email, 
        $phoneNumber, 
        $addrLine1, 
        $city, 
        $country,
        $state=null, 
        $zipCode=null, 
        $addrLine2=null, 
        $phoneExt=null
    ){
        $billing['name'] = $name;
        $billing['email'] = $email;
        $billing['phoneNumber'] = $phoneNumber;
        $billing['city'] = $city;
        $billing['country'] = $country;
        $billing['addrLine1'] = $addrLine1;
        $billing['addrLine2'] = $addrLine2;
        $billing['state'] = $state;
        $billing['zipCode'] = $zipCode;
        $billing['phoneExt'] = $phoneExt;

        $this->new_sale_params['billingAddr'] = $billing;
        return $this;
    }

    /**
     * Object that defines the shipping address using the attributes specified below. Optional
     * Only required if a shipping lineitem is passed.
     * (Passed as a sub object to the Authorization Object.) 
     * @param String $name        Customer’s name. (128 characters max) Required
     * @param String $addrLine1   Customer’s street address. (64 characters max). Required.
     * @param String $city        Customer’s city. (64 characters max) Required
     * @param String $country     Customer’s country. (64 characters max) Optional
     * @param String $state       Customer’s state. (64 characters max) Required for some countries.
     * @param String $zipCode     Customer’s zip. (16 characters max) Required for some countries.
     * @param String $phoneNumber Customer’s phone. (16 characters max) Optional
     * @param String $email       Customer’s email. (64 characters max) Optional
     * @param String $addrLine2   Customer’s street address line 2. (64 characters max). Optional.
     * @param String $phoneExt    Customer’s phone extension. (9 characters max) Optional
     * 
     */
    public function addShippingAddress(
        $name,
        $addrLine1,
        $city,
        $country,
        $state=null,
        $zipCode=null,
        $phoneNumber = null,
        $email=null,
        $addrLine2=null,
        $phoneExtension= null
    ){
        $shippingAddr['name'] = $name;
        $shippingAddr['addrLine2'] = $addrLine2;
        $shippingAddr['city'] = $city;
        $shippingAddr['country'] = $country;
        $shippingAddr['state'] = $state;
        $shippingAddr['zipCode'] = $zipCode;
        $shippingAddr['addrLine1'] = $addrLine1;
        $shippingAddr['phoneNumber'] = $phoneNumber;
        $shippingAddr['email'] = $email;
        $shippingAddr['phoneExtension'] = $phoneExtension;

        $this->new_sale_params['shippingAddr'] = $shippingAddr; 
        return $this;
    }

    /**
     * Array of lineitem objects using the attributes specifies Sale Detial. 
     * (Passed as a sub object of a lineItem object.)
     * @link https://www.2checkout.com/documentation/payment-api/create-sale#
     * @param String  $type       The type of line item. (Lower Case, ‘product’, ‘shipping’, ‘tax’ or ‘coupon’, defaults to ‘product’) Required
     * @param String  $name       Name of the item passed in. (128 characters max, defaults to capitalized version of ‘type’.) Required
     * @param Float   $price      Price of the line item. Format: 0.00-99999999.99, defaults to 0 if a value isn’t passed in or if value is incorrectly formatted, no negatives (use positive values for coupons). Required
     * @param Float   $startupFee Any start up fees for the product or service. Can be negative to provide discounted first installment pricing, but cannot equal or surpass the product price. Optional
     * @param Integer $quantity   Quantity of the item passed in. (0-999, defaults to 1 if not passed in or incorrectly formatted.) Optional
     * @param String  $recurrence Sets billing frequency.(Can use # Week, # Month or # Year) Required for recurring lineitems.
     * @param String  $duration   Sets how long to continue billing.(Forever or # Week, # Month, # Year) Required for recurring lineitems.
     * @param String  $tangible   Y or N. Will default to Y if the type is shipping. Optional
     * @param String  $productId  Your custom product identifier. Optional
     * @return Object             Sale Object ready to charge
     */
    public function addLineItem(
        $type, 
        $name, 
        $price, 
        $startupFee = null, 
        $quantity = null, 
        $recurrence = null, 
        $duration = null, 
        $tangible = null,
        $productId = null
    ){
        $item['type'] = $type; 
        $item['name'] = $name; 
        $item['price'] = $price; 
        $item['startupFee'] = $startupFee; 
        $item['quantity'] = $quantity; 
        $item['recurrence'] = $recurrence; 
        $item['duration'] = $duration; 
        $item['tangible'] = $tangible; 
        $item['productId'] = $productId; 

        $this->new_sale_params['lineItems'][] = $item;
        
        return $this;
    }

     /**
     * Adds Option Object to the last Element of LineItems Array
     * Array of option objects using the attributes specified below. Optional
     * (Passed as a sub object of a lineItem object.)
     * @param String  $name   Name of product option. Ex. Size (64 characters max) Required.
     * @param String  $value  Option selected. Ex. Small (64 characters max) Required
     * @param Float   $charge Option price in seller currency. (0.00 for no cost options) Optional.
     * @param Boolean $all    If true will add Option all lineItems if there is more than one, Otherwise adds to last item in array.
     * @return Object         Sale Object ready to charge
     */
    public function addLineItemOption($name, $value, $charge=0.00, $all=false){
        if(!isset($this->new_sale_params) || !isset($this->new_sale_params['lineItems'])){
            throw new TwoCheckoutException("Add at least one line item before adding an Option", 1);
        }
        $option['optName'] = $name;
        $option['optValue'] = $value;
        $option['optSurcharge'] = $charge;
        
        $len = count($this->new_sale_params['lineItems']);
        
        if($all && $len > 1){
            for($i=0; $i < $len; $i++){
                $this->new_sale_params['lineItems'][$i]['options'][] = $option;
            }
        }else{
            $this->new_sale_params['lineItems'][$len - 1]['options'][] = $option;
        }

        return $this;
    }

    /**
     * Executes Charge on a Sale Object
     * @return Array Sale Details
     */
    public function charge(){
        return $this->create($this->new_sale_params);
    }

    /**
     * Create New Sale with a charge
     * @link https://www.2checkout.com/documentation/payment-api/create-sale#
     * @param  Array  $params Sale Required Params
     * @return Array          Sale Detailed Response
     */
    public function create(array $params){
        $this->validateSaleParams($this->new_sale_params);
        $uri = sprintf($this->callables['create_sale']['uri'], $this->config()->seller_id);
        $retrieved = $this->retrieve(['create_sale', 'uri'=>$uri, 'params'=>$params]);
        $this->checkSaleExceptions($retrieved);
        return $retrieved['response'];
    }

    /**
     * Internally Used for Refund Sale/Refund LineItem/Stop Recurring/Mark Shipped/Create Comment
     * @param  String $opt     Operation Callable Name  
     * @param  Array  $params  Operation Parameters
     * @return Boolean
     */
    public function update($opt, array $params){
        $this->retrieve([$opt, 'params'=>$params]);
        return true;
    }

    /**
     * Not Available for a Sale 
     * @throws TwoCheckout\Exceptions\TwoCheckoutException
     */
    public function delete($id=null){
        throw new TwoCheckoutException("Deleting A Sale is not allowed", 1);
    }

    /**
     * The list_sales call is used to retrieve a summary of all sales or only those matching a variety of sale attributes.
     * @link https://www.2checkout.com/documentation/api/sales/list-sales
     * @param  String  $filter_by Search for sale with Key. Default sale_id. Optional
     * @param  String  $filter_val Search Value.
     * @param  Integer $cur_page  The page number to retrieve. First page = 1. Optional.
     * @param  Integer $pagesize  Total rows per page. Possible values are 1-100. If pagesize not specified, default of 20 items per page. Optional.
     * @param  String  $sort_col  The name of the column to sort on. Possibile values are sale_id, date_placed, customer_name, recurring, recurring_declined and usd_total. (case insensitive) Optional.
     * @param  String  $sort_dir The direction of the sort process. (‘ASC’ or ‘DESC’) (case insensitive) Optional.
     * @return Array             Collection of sales summaries 
     */
    public function list($filter_by=null, $filter_val=null, $cur_page=1, $pagesize=20, $sort_col='sale_id', $sort_dir='ASC'){
        if(!is_null($filter_by) && !is_null($filter_val)){
            $params[$filter_by] = $filter_val; 
        }

        $params['cur_page'] = $cur_page;
        $params['pagesize'] = $pagesize;
        $params['sort_col'] = $sort_col;
        $params['sort_dir'] = $sort_dir;

        $retrieved = $this->retrieve(['list_sales', 'params'=>$params]);
        $this->setPageCounters($retrieved);
        return $retrieved['sale_summary'];
    }

    /**
    * The detail_sale call is used to retrieve information about a specific sale or invoice.
    * @link  https://www.2checkout.com/api/sales/detail_sale
    * @param  String $sale_id    The order number of the requested sale. Optional if invoice_id is specified.
    * @param  String $invoice_id The invoice number of the requested invoice (specify to include only the requested invoice). Optional if sale_id is specified.
    * @return Array             Sale Details
    * @throws TwoCheckout\Exception\TwoCheckoutApiException
    */
    public function get($sale_id=null, $invoice_id=null){
        $params['sale_id'] = $sale_id;
        $params['invoice_id'] = $invoice_id;
        $retrieved = $this->retrieve(['get_sale','params'=>$params]);
        return $retrieved['sale'];
    }

    /**
    * The detail_sale call is used to retrieve information about a specific sale.
    * @link  https://www.2checkout.com/api/sales/detail_sale
    * @param  String $sale_id    The order number of the requested sale
    * @return Array             Sale Details
    * @throws TwoCheckout\Exception\TwoCheckoutApiException
    * 
    */
    public function getBySaleId($sale_id){
        return $this->get($sale_id);
    }

    /**
    * The detail_sale call is used to retrieve information about an invoice.
    * @link  https://www.2checkout.com/api/sales/detail_sale
    * @param  String $invoice_id The invoice number of the requested invoice
    * @return Array             Sale Details
    * @throws TwoCheckout\Exception\TwoCheckoutApiException
    */
    public function getByInvoiceId($invoice_id){
        return $this->get(null, $invoice_id);
    }

   /**
    * Search For Sale by Key
    * @link https://www.2checkout.com/documentation/api/sales/list-sales
    * @param  String  $key      Search for sale with specific Paramters. Required.
    * @param  String  $value    Search Parameter Value. Optional.
    * @param  Integer $cur_page Current Search Page. Default 1.
    * @param  Integer $pagesize Items No Per Page. Default 20.
    * @param  String  $sort_col Sort Items by Parameter. Default sale_id.
    * @param  String  $sort_dir Sort Items Direction. Default ASC.
    * @return Array             Collection of filterd sales summeries.          
    * @throws TwoCheckout\Exception\TwoCheckoutApiException
    */
    public function searchBy($key, $value, $cur_page=1, $pagesize=20, $sort_col='sale_id', $sort_dir='ASC'){
        return $this->list($key, $value, $cur_page, $pagesize, $sort_col, $sort_dir);
    }

    /**
     * ShortHand Calling SearchBy.
     * @example searchByCustomerName($name)
     * @example searchByCustomerEmail($email)
     * @example searchByCustomerPhone($phone)
     * @example searchBySaleId($sale_id)
     * @example searchByInvoiceId($invoice_id)
     * @example searchByVendorProductId($vendor_product_id)
     * @example searchByCcardFirst6($ccard_first6)
     * @example searchByCcardLast2($ccard_last2)
     * @example searchBySaleDateBegin($sale_date_begin)
     * @example searchBySaleDateEnd($sale_date_end)
     * @example searchByDeclinedRecurrings()
     * @example searchByActiveRecurrings()
     * @example searchByRefunded()
     * @return Array             Collection of filterd sales summeries.          
     */
    public function __call($name, $params){
        $keys = ['sale_id', 'invoice_id', 'customer_name', 'customer_email', 'customer_phone', 'vendor_product_id', 'ccard_first6', 'ccard_last2', 'sale_date_begin', 'sale_date_end', 'declined_recurrings', 'active_recurrings', 'refunded'];

        if(preg_match('/(searchBy)/', $name)){
            $key = substr(strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $name)), strlen('search_by_'));
            if(in_array($key, $keys)){
                $input = (isset($params[0])) ? $params[0] : true;
                return $this->searchBy($key, $input);
            }

        }
        throw new TwoCheckoutException("Undefined method {$name}", 1);
        
    }

    /**
     * The refund_invoice call is used to attempt to issue a full or partial refund on an invoice by sale id. This call will send the REFUND_ISSUED INS message.
     * @link https://www.2checkout.com/documentation/api/sales/refund-invoice
     * @param  String  $sale_id    Order number/sale ID to issue a refund on. Optional when invoice_id is specified, otherwise required.
     * @param  String  $comment    Message explaining why the refund was issued. Required. May not contain ‘<’ or ‘>’. (5000 character max)
     * @param  Integer $category   ID representing the reason the refund was issued. Required.
     * @param  Integer $amount     The amount to refund. Only needed when issuing a partial refund. If an amount is not specified, the remaining amount for the invoice is assumed.
     * @param  String  $currency   Currency type of refund amount. Can be ‘usd’, ‘vendor’ or ‘customer’. Only required if amount is used.
     * @return Boolean              Tells the user whether or not the operation was successful
     * @throws TwoCheckout\Exception\TwoCheckoutApiException
     */
    public function refundSale($sale_id, $comment, $category=10, $amount=null, $currency=null){
        $params['sale_id'] = $sale_id;
        $params['comment'] = $comment;
        $params['category'] = $category;
        $params['amount'] = $amount;
        $params['currency'] = $currency;

        return $this->update('refund_sale', $params);
    }

    /**
     * The refund_invoice call is used to attempt to issue a full or partial refund on an invoice by invoice id. This call will send the REFUND_ISSUED INS message.
     * @link https://www.2checkout.com/documentation/api/sales/refund-invoice
     * @param  String  $invoice_id Invoice ID to issue a refund on.
     * @param  String  $comment    Message explaining why the refund was issued. Required. May not contain ‘<’ or ‘>’. (5000 character max)
     * @param  Integer $category   ID representing the reason the refund was issued. Required.
     * @param  Integer $amount     The amount to refund. Only needed when issuing a partial refund. If an amount is not specified, the remaining amount for the invoice is assumed.
     * @param  String  $currency   Currency type of refund amount. Can be ‘usd’, ‘vendor’ or ‘customer’. Only required if amount is used.
     * @return Boolean              Tells the user whether or not the operation was successful.
     * @throws TwoCheckout\Exception\TwoCheckoutApiException
     */
    public function refundInvoice($invoice_id, $comment, $category=10, $amount=null, $currency=null){
        $params['invoice_id'] = $invoice_id;
        $params['comment'] = $comment;
        $params['category'] = $category;
        $params['amount'] = $amount;
        $params['currency'] = $currency;

        return $this->update('refund_sale', $params);
    }


    /**
     * The refund_lineitem call is used to attempt to issue a full refund on a lineitem. This call will send the REFUND_ISSUED INS message.
     * @link https://www.2checkout.com/documentation/api/sales/refund-lineitem
     * @param  String  $lineitem_id Line item to issue refund on. Required.
     * @param  String  $comment    Message explaining why the refund was issued. Required. May not contain ‘<’ or ‘>’. (5000 character max)
     * @param  Integer $category    ID representing the reason the refund was issued. Required.
     * @return Boolean              Tells the user whether or not the operation was successful.
     * @throws TwoCheckout\Exception\TwoCheckoutApiException
     */
    public function refundLineItem($lineitem_id, $comment, $category=10){
        $params['lineitem_id'] = $lineitem_id;
        $params['comment'] = $comment;
        $params['category'] = $category;
        return $this->update('refund_lineitem', $params);
    }

    /**
     * The reauth call is used to attempt to reauthorize sale having expired pre-authorized payment. Please note you can only attempt to reauthorize a sale once per day.
     * @link https://www.2checkout.com/documentation/api/sales/reauth
     * @param  String $sale_id  The order number/sale ID to reauthorize. Required.
     * @return Boolean          Tells the user whether or not the operation was successful
     */
    public function reauthorize($sale_id){

    }

    /**
     * The stop_lineitem_recurring call is used to attempt to stop a recurring line item for a specified sale. This call will send the RECURRING_STOPPED INS message.
     * @link https://www.2checkout.com/documentation/api/sales/stop-lineitem-recurring
     * @param  String  $lineitem_id  Line Item ID to stop recurring on. Required.
     * @return Boolean               Tells the user whether or not the operation was successful
     * @throws TwoCheckout\Exception\TwoCheckoutApiException
     */
    public function stopLineItemRecurring($lineitem_id){
        return $this->update('stop_lineitem_recurring', ['lineitem_id'=>$lineitem_id]);
    }

    /**
     * The mark_shipped call is used to attempt to mark an order as shipped and will attempt to reauthorize sale if specified in call. This call will send the SHIP_STATUS_CHANGED INS message.
     * @link https://www.2checkout.com/documentation/api/sales/mark-shipped
     * @param  String  $sale_id         The order number/sale ID to mark shipped. Optional when invoice_id is present.
     * @param  String  $tracking_number The tracking number issued by the shipper. Required.
     * @param  String  $invoice_id      ID of the invoice to add tracking information to. Required on sales with more than one invoice.
     * @param  String  $comment         Specify whether the customer should be automatically notified. Use “1” for true. Defaults to false. Optional.
     * @param  Integer $cc_customer     Specify whether the customer should be automatically notified. Use “1” for true. Defaults to false. Optional.
     * @param  Boolean $reauthorize     Reauthorize payment if payment authorization has expired. Defaults to false. Optional.
     * @return Boolean                  Tells the user whether or not the operation was successful.
     * @throws TwoCheckout\Exception\TwoCheckoutApiException
     */
    public function markShipped($sale_id, $tracking_number, $invoice_id=null, $comment=null, $cc_customer=0, $reauthorize=false){
        $params['sale_id'] = $sale_id;
        $params['tracking_number'] = $tracking_number;
        $params['invoice_id'] = $invoice_id;
        $params['comment'] = $comment;
        $params['cc_customer'] = $cc_customer;
        $params['reauthorize'] = $reauthorize;

        return $this->update('mark_shipped', $params);
    }

    /**
     * The create_comment call is used to add a comment to a specified sale.
     * @link https://www.2checkout.com/documentation/api/sales/create-comment
     * @param  String  $sale_id      The order number/sale ID of a sale to look for. Required.
     * @param  String  $sale_comment String value of comment to be submitted. Required.
     * @param  Integer $cc_vendor    Set to 1 to have a copy sent to the merchant. Optional.
     * @param  Integer $cc_customer  Set to 1 to have the buyer sent an email copy. Optional.
     * @return Boolean               Tells the user whether or not the operation was successful
     * @throws TwoCheckout\Exception\TwoCheckoutApiException
     */
    public function createComment($sale_id, $sale_comment, $cc_vendor=0, $cc_customer=0){
        $params['sale_id'] = $sale_id;
        $params['sale_comment'] = $sale_comment;
        $params['cc_vendor'] = $cc_vendor;
        $params['cc_customer'] = $cc_customer;

        return $this->update('create_comment', $params);
    }

    public function listNextPage(){
        if(!self::$page_set)
        {
            $this->list();
        }

        if(!self::$page_next){
            return null;
        }
        return $this->list(null, null, self::$page_next);
    }

    public function listPreviousPage(){
        if(!self::$page_set)
        {
            $this->list();
        }

        if(!self::$page_previous)
        {
            return null;
        }
        return $this->list(null, null, self::$page_previous);
    }

    public function listFirstPage(){
        if(!self::$page_set)
        {
            $this->list();
        }

        if(!self::$page_first)
        {
            return null;
        }
        return $this->retrieve(['list_sales_first', 'uri'=>self::$page_first]);
    }

    public function listLastPage(){
        if(!self::$page_set)
        {
            $this->list();
        }

        if(!self::$page_last)
        {
            return null;
        }
        return $this->retrieve(['list_sales_last', 'uri'=>self::$page_last]);
    }

    public function listTotal()
    {
        if(!self::$page_set)
        {
            $this->list();
        }
        return self::$total;
    }

    private function setPageCounters(array $res)
    {
        $this::$page_set = true;
        $this::$page_next = $res['page_info']['next_page'];
        $this::$page_previous = $res['page_info']['previous_page'];
        $this::$page_first = $this->stripBaseUri($res['page_info']['first_page_url']);
        $this::$page_last = $this->stripBaseUri($res['page_info']['last_page_url']);
        $this::$total = $res['page_info']['total_entries'];
    }

    private function stripBaseUri($uri)
    {
        return substr($uri, strlen($this->config()->base_uri));
    }

    private function checkSaleExceptions(array $response)
    {
        if(isset($response['exception']) && !empty($response['exception']))
        {
            $exp = $response['exception'];
            throw new TwoCheckoutApiException("ErrorCode ". $exp['errorCode'] .' : ' . $exp['errorMsg']);
        }
    }

    /**
     * Validate Sale Parameters against 2Checkout Sale Rules
     * @param  array  $sale 
     * @throws TwoCheckout\Exceptions\TwoCheckoutException
     */
    private function validateSaleParams(array $sale)
    {
        if(isset($sale['lineItems']) && $sale['total'] == 0)
        {
            unset($sale['total']);
        }

        if(isset($sale['total']) && isset($sale['lineItems']))
        {
            throw new TwoCheckoutException("Sale Total can't be set if LineItems are set", 1);     
        }

        if(isset($sale['lineItems']))
        {
            foreach ($sale['lineItems'] as $item) 
            {
                if(!in_array($item['type'], ['product', 'shipping', 'tax', 'coupon']))
                {
                    throw new TwoCheckoutException("Sale Invalid LineItem Type", 1);     
                }

                if($item['type'] == 'shipping' && !isset($sale['shippingAddr']))
                {
                    throw new TwoCheckoutException("Must set Shipping Address for shipping type", 1);     
                }
            }
        }

        if(!isset($sale['billingAddr']))
        {
            throw new TwoCheckoutException("CardHolder's Billing Address Must be set", 1);     
        }
    }

}