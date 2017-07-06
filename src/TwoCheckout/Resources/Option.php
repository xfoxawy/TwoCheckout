<?php
namespace TwoCheckout\Resources;

use TwoCheckout\Contracts\Abstracts\FullResource as BaseResource;

/**
 * Option Access Interface
 */
class Option extends BaseResource
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
        'get_option' => [
            'uri' => '/api/products/detail_option',
            'method' => 'GET'
        ],
        'list_options' => [
            'uri' => '/api/products/list_options',
            'method' => 'GET'
        ],
        'create_option' => [
            'uri' => '/api/products/create_option',
            'method' => 'POST'
        ],
        'update_option' => [
            'uri' => '/api/products/update_option',
            'method' => 'POST'
        ],
        'delete_option' => [
            'uri' => '/api/products/delete_option',
            'method' => 'POST'
        ],
        'list_options_next' => [
            'uri' => '',
            'method' => 'GET'
        ],
        'list_options_previous' => [
            'uri' => '',
            'method' => 'GET'
        ],
        'list_options_first' => [
            'uri' => '',
            'method' => 'GET'
        ],
        'list_options_last' => [
            'uri' => '',
            'method' => 'GET'
        ]
    ];
    /**
     * The list_options call is used to retrieve list of all options in the account.
     * @link https://www.2checkout.com/documentation/api/products/list-options
     * @param  Integer $cur_page 
     * @param  Integer $pagesize 
     * @param  String  $filter_by
     * @param  String  $sort_col 
     * @param  String  $sort_dir 
     * @return Array            
     */
    public function list($cur_page=1, $pagesize=20, $filter_by='option_name', $sort_col='option_id', $sort_dir='ASC'){
        $params['cur_page']= $cur_page;
        $params['pagesize'] = $pagesize;
        $params['filter_by'] = $filter_by;
        $params['sort_col'] = $sort_col;
        $params['sort_dir'] = $sort_dir;

        $retrieved = $this->retrieve(['list_options', 'params'=>$params]);
        $this->setPageCounters($retrieved);
        return $retrieved['options'];

    }

    public function listNextPage(){
        if(!self::$page_set){
            $this->list();
        }
        if(!self::$page_next){
            return null;
        }
        return $this->retrieve(['list_options_next',['uri'=>self::$page_next]]);
    }

    public function listPreviousPage(){
        if(!self::$page_set){
            $this->list();
        }
        if(!self::$page_previous){
            return null;
        }
        return $this->retrieve(['list_options_previous',['uri'=>self::$page_previous]]);
    }

    public function listFirstPage(){
        if(!self::$page_set){
            $this->list();
        }
        if(!self::$page_first){
            return null;
        }
        return $this->retrieve(['list_options_first',['uri'=>self::$page_first]]);
    }

    public function listLastPage(){
        if(!self::$page_set){
            $this->list();
        }
        if(!self::$page_last){
            return null;
        }
        return $this->retrieve(['list_options_last',['uri'=>self::$page_last]]);
    }

    public function listTotal(){
        if(!self::$page_set){
            $this->list();
        }
        return self::$total;
    }

    /**
     * The create_option call is used to create a new product option.
     * @link https://www.2checkout.com/documentation/api/products/create-option
     * @param  String $option_name            
     * @param  String $option_value_name      
     * @param  String $option_value_surcharge 
     * @return Array                        
     */
    public function new($option_name, $option_value_name, $option_value_surcharge){
        $params['option_name'] = $option_name;
        $params['option_value_name'] = $option_value_name;
        $params['option_value_surcharge'] = $option_value_surcharge;
        return $this->create($params);
    }

    /**
     * The detail_option call is used to retrieve the details for a single option.
     * @link https://www.2checkout.com/documentation/api/products/detail-option
     * @param  String $option_id 
     * @return Array            
     */
    public function get($option_id){
        $retrieved = $this->retrieve(['get_option', 'params'=>['option_id'=>$option_id]]);
        return $retrieved['option'][0];
    }

    /**
     * The create_option call is used to create a new product option.
     * @link https://www.2checkout.com/documentation/api/products/create-option
     * @param  Array $params            
     * @return Array                        
     */
    public function create(array $params){
        $retrieved = $this->retrieve(['create_option', 'params'=>$params]);
        return $retrieved['option_id'];
    }

    /**
     * The update_option call is used to update an option.
     * @link https://www.2checkout.com/documentation/api/products/update-option
     * @param  String $option_id 
     * @param  array  $params    
     * @return Boolean            
     */
    public function update($option_id, array $params){
        $params['option_id'] = $option_id;
        $this->retrieve(['update_option', 'params'=>$params]);
        return true;
    }

    /**
     * The update_option call is used to update an option.
     * @link https://www.2checkout.com/documentation/api/products/update-option
     * @param  String $option_id 
     * @param  String $option_value_name      
     * @param  String $option_value_surcharge 
     * @return Boolean            
     */
    public function createOptionValue($option_id, $option_value_name, $option_value_surcharge){
        return $this->update($option_id,['option_value_name'=>$option_value_name, 'option_value_surcharge'=>$option_value_surcharge]);
    }

     /**
     * The update_option call is used to update an option.
     * @link https://www.2checkout.com/documentation/api/products/update-option
     * @param  String $option_id 
     * @param  String $option_value_id      
     * @return Boolean            
     */
    public function deleteOptionValue($option_id, $option_value_id){
        return $this->update($option_id, ['option_value_id'=> $option_value_id]);
    }

    /**
     * The update_option call is used to update an option.
     * @link https://www.2checkout.com/documentation/api/products/update-option
     * @param  String $option_id 
     * @param  String $option_value_id      
     * @param  Array $params
     * @return Boolean            
     */
    public function updateOptionValue($option_id, $option_value_id, array $params){
        $params['option_value_id'] = $option_value_id;
        return $this->update($option_id, $params);
    }

    /**
     * The delete_option call is used to delete a product option.
     * @link https://www.2checkout.com/documentation/api/products/delete-option
     * @param  String $option_id 
     * @return Boolean           
     */
    public function delete($option_id){
        $this->retrieve(['delete_option','params'=>['option_id'=> $option_id]]);
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