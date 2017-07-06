<?php
namespace TwoCheckout\Resources;

use TwoCheckout\Contracts\Abstracts\FullResource as BaseResource;

class Product extends BaseResource
{
    public static $page_set;
    public static $page_next;
    public static $page_previous;
    public static $page_first;
    public static $page_last;
    public static $total;

    /**
     * List of API callables 
     * @var Array
     */
    protected $callables = [
        'list_products' => [
            'uri' => '/api/products/list_products',
            'method' => 'GET'
        ],
        'list_products_next' => [
            'uri' => '',
            'method'=>'GET'
        ],
        'list_products_previous' => [
            'uri' => '',
            'method' => 'GET'
        ],
        'list_products_first' => [
            'uri' => '',
            'method' => 'GET'
        ],
        'list_products_last' => [
            'uri' => '',
            'method' => 'GET'
        ],
        'get_product' => [
            'uri'=>'/api/products/detail_product',
            'method'=>'GET'
        ],
        'create_product' => [
            'uri' => '/api/products/create_product',
            'method'=> 'POST'
        ],
        'update_product' => [
            'uri' => '/api/products/update_product',
            'method' => 'POST' 
        ],
        'delete_product' => [
            'uri' =>'/api/products/delete_product',
            'method' => 'POST'
        ]
    ];

    public function list($cur_page=1, $pagesize=20, $filter_by='product_id', $sort_col='product_id', $sort_dir='ASC'){
        $retrieved = $this->retrieve(['list_products', 
            'params'=>[
                'cur_page'=>$cur_page, 
                'pagesize'=>$pagesize, 
                'filter_by'=>$filter_by, 
                'sort_col'=>$sort_col, 
                'sort_dir'=>$sort_dir
            ]
        ]);
        $this->setPageCounters($retrieved);
        return $retrieved['products'];
    }

    public function get($product_id){
        $retrieved = $this->retrieve(['get_product','params'=>['product_id'=>$product_id]]);
        return $retrieved['product'];
    }

    public function listNextPage(){
        if(!self::$page_set){
            $this->list();
        }
        if(!self::$page_next){
            return null;
        }
        return $this->retrieve(['list_products_next',['uri'=>self::$page_next]]);
    }

    public function listPreviousPage(){
        if(!self::$page_set){
            $this->list();
        }
        if(!self::$page_previous){
            return null;
        }
        return $this->retrieve(['list_products_previous',['uri'=>self::$page_previous]]);
    }

    public function listFirstPage(){
        if(!self::$page_set){
            $this->list();
        }
        if(!self::$page_first){
            return null;
        }
        return $this->retrieve(['list_products_first',['uri'=>self::$page_first]]);
    }

    public function listLastPage(){
        if(!self::$page_set){
            $this->list();
        }
        if(!self::$page_last){
            return null;
        }
        return $this->retrieve(['list_products_last',['uri'=>self::$page_last]]);
    }

    public function listTotal(){
        if(!self::$page_set){
            $this->list();
        }
        return self::$total;
    }

    public function new(
        $name, 
        $price, 
        $vendor_product_id='', 
        $description='', 
        $long_description='', 
        $pending_url='',
        $approved_url='',
        $tangible='',
        $weight='',
        $handling='',
        $recurring=0,
        $startup_fee='',
        $recurrence='',
        $duration='',
        $commission='',
        $commission_type='',
        $commission_amount='',
        $option_id='',
        $category_id=''
        ){
        return $this->create([
        'name' => $name,
        'price'=> $price,
        'vendor_product_id' => $vendor_product_id,
        'description' => $description,
        'long_description' => $long_description,
        'pending_url' => $pending_url,
        'approved_url' => $approved_url,
        'tangible' => $tangible,
        'weight' => $weight,
        'handling' => $handling,
        'recurring' => $recurring,
        'startup_fee' => $startup_fee,
        'recurrence' => $recurrence,
        'duration' => $duration,
        'commission' => $commission,
        'commission_type' => $commission_type,
        'commission_amount' => $commission_amount,
        'option_id' => $option_id,
        'category_id' => $category_id,
        ]);
    }

    public function create(array $params){
        $retrieved = $this->retrieve(['create_product', 'params'=>$params]);
        return ['product_id' => $retrieved['product_id'], 'assigned_product_id' => $retrieved['assigned_product_id']];
    }

    public function update($product_id, array $params){
        $params['product_id'] = $product_id;
        $this->retrieve(['update_product', 'params'=>$params]);
        return true;
    }

    public function delete($product_id){
        $params['product_id'] = $product_id;
        $this->retrieve(['delete_product', 'params'=>$params]);
        return true;
    }


     private function setPageCounters(array $res){
        $this::$page_set = true;
        $this::$page_next = $this->stripBaseUri($res['page_info']['next_page']);
        $this::$page_previous = $this->stripBaseUri($res['page_info']['previous_page']);
        $this::$page_first = $this->stripBaseUri($res['page_info']['first_page_url']);
        $this::$page_last = $this->stripBaseUri($res['page_info']['last_page_url']);
        $this::$total = $res['page_info']['total_entries'];
    }

    private function stripBaseUri($uri){
        return substr($uri, strlen($this->client->config->base_uri));
    }

}

