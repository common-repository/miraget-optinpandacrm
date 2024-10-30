<?php namespace Miraget\Pages ;
/**
 * @package  Miraget Generator
 * insert emails if exist
 */
/*
use \Miraget\Api\SettingsApi ;
use \Miraget\Api\Callbacks\AdminCallbacks ; */
class CronSApi
{
    private $table_option ;
    private $time2Update = 2  ; // minutes
    private $pandaTible  ;
    private $lastUpdateTime ; // unix time
    private $curlApi = 'https://cloud.miraget.com/simple' ;
    private $token   ;
    private $lasEmailedId   ;

    function __construct(){

        global $wpdb;
        $this->DB = $wpdb;
        // table option
        $this->table_option = $this->DB->prefix . MIRG_TABLE_OP  ;
        // panda table
        $this->pandaTaible =  $this->DB->prefix .  'opanda_leads'  ;
        // init last emailed id
        $this->lasEmailedIdfun() ;
        $this->token = $this->myToken() ;


    }
    public function register(){

        // check if new emailes
        add_action( 'wp_footer',  array( $this, 'newEmailes' ) );

    }
   
    private function pandaUpdate(){

        if (  $this->getLastSend() ) {
            print($this->getLastSend());
            $table = $this->pandaTaible ;//lastEmailedId
            $leid = ( int ) $this->lastEmailedId ;
            $table_option = $this->DB->prefix . MIRG_TABLE_OP  ;

            $limit = $this->DB->get_var( "select value from $table_option where id='20'" );

            $where = $leid > 0 ? "WHERE `ID` >  $leid " : null ;
            $pandaEmails = $this->DB->get_results(
                "SELECT * FROM $table $where LIMIT " . (int) $limit
            );

            foreach ( $pandaEmails as $email )
            {
                //print(  $email);
                $this->send2Integrator( $email ) ;
            }
            // update last emai id

            //update totalemailssent in table option
            $table_act =$this->DB->prefix.MIRG_TABLE_ACT;
            $totalEmailsSent =$this->DB->get_var("SELECT count(*) FROM $table_act WHERE 1");
             $this->DB->update( $table_option,
                array(
                    'value' => $totalEmailsSent ,	// string
                ),
                array( 'id' => 3 ),
                array(
                    '%s',
                ),
                array( '%d' )
            );

            //debug responses ****************************** to be removed ###############################################
            $table2 = $wpdb->prefix.'debugg';
            $this->DB->insert(
                $table2  ,
                array(   'calls' =>   'pandaUpdate_start' )
            );

            // **********************************************         #####################################################


            $this->updateLastEmailId( end( $pandaEmails) ) ;
            $this->updateLastTime() ;

            //debug responses ****************************** to be removed ###############################################
            $table2 = $wpdb->prefix.'debugg';
            $this->DB->insert(
                $table2  ,
                array(   'calls' =>   'pandaUpdate_start' )
            );

            // **********************************************         #####################################################





        }
    }



    private function updateLastEmailId( $obj ){


        $table = $this->DB->prefix . MIRG_TABLE_OP  ;
        $is_update = $this->DB->update( $table,
            array(
                'value' => $obj->ID ,	// string
            ),
            array( 'id' => 6 ),
            array(
                '%s',
            ),
            array( '%d' )
        );
    }


    private function send2Integrator($e){


        $curlBody = array(

            "domain" => $_SERVER['HTTP_HOST'] ,
            "data_source"     => 'OptInPanda' ,
            "lead_name"       => empty($e->lead_name) ? 'unknown' : $e->lead_name  ,
            "lead_email"      => $e->lead_email ,
            "lead_date"       => $e->lead_date ,
            "lead_ip"         => $e->lead_ip ,
            "lead_item_title" => $e->lead_item_title ,
            "lead_post_title" => $e->lead_post_title ,
            "lead_referer"    => $e->lead_referer ,
            "Enrich_lead"    => 'yes'
        ) ;


        $this->curl2Apit( $curlBody ) ;
    }


    private function curl2Apit( $body ){

        // merg array
        $data_body = $this->InitOptInPanda() + $body ;
         
        /* echo "<pre>" ;
         print_r($data_body) ;
         die;*/
         
        
          
        $response = wp_remote_post( $this->curlApi, array(
            'method' => 'POST',
            'headers' => array( 'Content-Type' => 'application/json' , 'X-Api-Key' => $this->token ) ,
            'body' => json_encode( $data_body )

            )
        );

        //debug responses ****************************** to be removed ###############################################
        $table2 = $wpdb->prefix.'debugg';
        $this->DB->insert(
            $table2  ,
            array(   'calls' =>  'json : ' . $response['response']['message'] )
        );
        $this->DB->insert(
            $table2  ,
            array( 'calls' =>  'Response : ' . implode( " ",   array(
                'method' => 'POST',
                'headers' => array( 'Content-Type' => 'application/json' , 'X-Api-Key' => $this->token ) ,
                'body' => json_encode( $data_body )

                ) )   )
        );
        

        // **********************************************         #####################################################

        $code = 404 ;
        $msg  = 'Error' ;

        if( isset( $response['response'] )  ){

            $code = $response['response']['code'] ;
            $msg  = $response['response']['message'] ;

        }
        $msgStatus = $code.$msg ;

        if ( $code > 250 ) {

            $this->insertStatus( $msgStatus ) ;
            return  false ;

        } else {
            // activity table
            $table = $this->DB->prefix . MIRG_TABLE_ACT   ;
            $this->DB->insert(
                $table,
                 array( 'email' => $body['lead_email'] , 'time' => current_time( 'timestamp' )  )
                );
            $this->insertStatus( $msgStatus ) ;

        }


    }


    private function insertStatus( $num ){

        $table = $this->DB->prefix . MIRG_TABLE_OP  ;
        $is_update = $this->DB->update( $table,
            array(
                'value' =>  $num ,	// string
            ),
            array( 'id' => 2 ),
            array(
                '%s',
            ),
            array( '%d' )
        );
    }


    public function newEmailes(){

        if(  $this->InitOptInPanda() === false  ) return false ;

        //if plug in off
        if( $this->onOff() === 1 ) $this->pandaUpdate() ;


    }
    //simple easy encryption funtion to encrypt data sent to server
     /*token
     * user name
     * password
     * client id
     * secret id
     * 
     */
    private function easyEncryption($args){
        return $args[0].$args.substr($args, -1);
    }



    private function InitOptInPanda(){

        $table_option = $this->DB->prefix . MIRG_TABLE_OP ;
        /*
        "data_arget_api_user": "user",
        "data_arget_api_pass": "pass",
        "data_arget_api_url": "https://ekekeke/e/ee"*/
        $plugin_target = $this->DB->get_var( "select value from $table_option where id='8'" );
        $plugin_target_version = $this->DB->get_var( "select value from $table_option where id='9'" );

        $zoho_token = $this->DB->get_var( "select value from $table_option where id='10'" );
        $data_target_client_id = $this->DB->get_var( "select value from $table_option where id='15'" );
        $data_target_secret_id = $this->DB->get_var( "select value from $table_option where id='18'" );
        $data_target_code = $this->DB->get_var( "select value from $table_option where id='16'" );
        $data_target_DC = $this->DB->get_var( "select value from $table_option where id='17'" );
        $data_target_update = $this->DB->get_var( "select value from $table_option where id='18'" );

        $salesforce_user = $this->DB->get_var( "select value from $table_option where id='11'" );
        $salesforce_pass = $this->DB->get_var( "select value from $table_option where id='12'" );
        $salesforce_url = $this->DB->get_var( "select value from $table_option where id='13'" );
        //insightly target dat fields
        $insightly_access_key = $this->DB->get_var( "select value from $table_option where id='21'" );
        $insightly_secret_key = $this->DB->get_var( "select value from $table_option where id='22'" );
        $insightly_session_key = $this->DB->get_var( "select value from $table_option where id='23'" );
        $insightly_target_key = $this->DB->get_var( "select value from $table_option where id='24'" );
        $insightly_token_key = $this->DB->get_var( "select value from $table_option where id='25'" );
       
        $emmit = false ;

        // check if user input token ..
        if(
            $zoho_token &&
            (int) $plugin_target === 1 &&
            (int) $plugin_target_version > 0
        )  $emmit = true ;

        if(
             $salesforce_user &&
             (int) $plugin_target === 2 &&
             $salesforce_url   &&
             $salesforce_pass
        ) $emmit = true ;
        //check if user input insightly fields
        if(
            $insightly_access_key &&
            (int) $plugin_target === 3 &&
            $insightly_secret_key   &&
            $insightly_session_key &&
            $insightly_target_key &&
            $insightly_token_key
       ) $emmit = true ;
        print($emmit);
            //test for for plugin target ? : zoho, salesforce , insightly
            if( $plugin_target == '1' )$data_target_n = 'zoho';
            if( $plugin_target == '2' )$data_target_n = 'salesforce';
            if( $plugin_target == '3' )$data_target_n = 'insightly';

        if( $emmit ) { 
           if($data_target_n == 'zoho' || $data_target_n == 'salesforce') { 
             return array(
            // 'data_target' => $plugin_target == '1' ? 'Zoho' : 'Salesforce' ,
            'data_target'=> $data_target_n,
            'data_target_api_version' =>  $plugin_target_version ,
            'data_target_api_token'   =>  $this->easyEncryption($zoho_token) ,
            'data_target_api_user'    =>  $this->easyEncryption($salesforce_user) ,
            'data_target_api_pass'    =>  $this->easyEncryption($salesforce_pass) ,
            'data_target_api_url'    =>   $salesforce_url ,
            "data_target_client_id" => $this->easyEncryption($data_target_client_id),
            "data_target_secret_id" => $this->easyEncryption($data_target_secret_id),
            "data_target_code" => $data_target_code,
            "data_target_dc" => $data_target_DC ,
            "data_target_update" =>  $data_target_update
             ) ;
            }

             else if($data_target_n =='insightly'){
             return array(
                // 'data_target' => $plugin_target == '1' ? 'Zoho' : 'Salesforce' ,
                'data_target'=>$data_target_n,
                'data_target_api_version' =>  $plugin_target_version ,
                'data_target_api_token'   =>  $this->easyEncryption($insightly_token_key) ,
                'data_target_api_user'    =>  $this->easyEncryption($insightly_access_key) ,
                'data_target_api_pass'    =>  $this->easyEncryption($insightly_secret_key) ,
                'data_target_api_url'    =>   $salesforce_url ,
                "data_target_client_id" => $this->easyEncryption($insightly_session_key),
                "data_target_secret_id" => $this->easyEncryption($insightly_target_key),
                "data_target_code" => $data_target_code,
                "data_target_dc" => $data_target_DC ,
                "data_target_update" =>  $data_target_update
                 ) ;
                }

        }
        else return false ;

    }


    private function onOff(){

        $table_option = $this->DB->prefix . MIRG_TABLE_OP ;
        $on_off = $this->DB->get_var( "select value from $table_option where id='14'" );

        return ( int ) $on_off ;
    }

    // update settings table with the latest time
    public function updateLastTime(){

      #  $current_time = new DateTime('now');
        $table = $this->DB->prefix . MIRG_TABLE_OP  ;
        $is_update = $this->DB->update( $table,
            array(
                'value' => current_time( 'timestamp' ),	// string
            ),
            array( 'id' => 4 ),
            array(
                '%s',
            ),
            array( '%d' )
        );
        #$wpdb->query($wpdb->prepare("UPDATE $table SET value='$current_time' WHERE id=4"));
    }

    /**
     * return boolean
     * true if last update > 5 min else false
     */
    private function getLastSend(){

        $lastSend = $this->DB->get_var( "select value from $this->table_option where id='4'" );
        $this->time2Update = $this->DB->get_var( "select value from $this->table_option where id='7'" );
        $this->lastUpdateTime =  $lastSend ;
        return current_time( 'timestamp' ) > ( ( int ) $lastSend + $this->time2Update * 60 )   ;

    }


    private function myToken(){

        $token = $this->DB->get_var( "select value from $this->table_option where id='1'" );
        return $token ;

    }


    private function lasEmailedIdfun(){

        $this->lastEmailedId = $this->DB->get_var( "select value from $this->table_option where id='6'" );

    }


    private function isWorkable(){


        if(
            $this->zoho_token &&
            (int) $this->plugin_target === 1 &&
            (int) $this->plugin_target_version > 0
        ) return 'true' ;

        if(
             $this->salesforce_user &&
             (int) $this->plugin_target === 2 &&
             $this->salesforce_url   &&
             $this->salesforce_pass
        ) return 'true' ;

        return 'false' ;
    }
}
