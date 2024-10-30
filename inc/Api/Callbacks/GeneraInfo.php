<?php namespace Miraget\Api\Callbacks ;
/**
 * @package  Miraget Generator
 */ 

class GeneraInfo 
{
    public $api_status ;
    private $DB ;
    private $table_option ;

    function __construct(){

       global $wpdb;
       $this->DB = $wpdb;
       $this->table_option = $this->DB->prefix . MIRG_TABLE_OP ;
       $this->api_status = $this->DB->get_var( "select value from $this->table_option where id='2'" );

    }
    public function renderGenralInfo(){
         
        return  ""  ;

    }
}