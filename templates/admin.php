<?php
class MiragetAdminTemplate
{
    private $DB ;
    private $miraget_nonce ;
    private $error_post =  false  ;
    private $is_post =  false  ;
    private $emails =  []  ;
    private $lastUpda ;
    private $updta_ok = false ;
    private $refresh_data  ;
    private $plugin_source  ;
    private $plugin_target  ;
    private $plugin_target_version  ;
    private $zoho_token  ;
    private $salesforce_user  ;
    private $salesforce_pass  ;
    private $salesforce_url  ;
    //add vars to insightly target
    // private $insightly_access_key;
    // private $insightly_secret_key;
    // private $insightly_session_key;
    // private $insightly_target_api;
    // private $insightly_token_api;

    function __construct(){

        global $wpdb;
        $this->DB = $wpdb;
        $this->getApitStatus() ;
        $this->miraget_nonce = wp_create_nonce( 'miraget_nonce' );
        // insert data if isset post
        $this->postInit() ;

        // get emails
        $this->getEmailes() ;

    }


    function bl_cron_hook() {
    }


    private function getEmailes(){

        $table = $this->DB->prefix . MIRG_TABLE_ACT ;
        $fivesdrafts = $this->DB->get_results(
            " SELECT id, email,time
              FROM $table
              ORDER BY `time` DESC
              LIMIT 8
            "
        );

        foreach ( $fivesdrafts as $fivesdraft )
        {
            $this->emails[] =  $fivesdraft ;
        }

    }


    private function postInit() {

      //debug responses ****************************** to be removed ###############################################

      $table2 = $this->DB->prefix.'debugg';
      $this->DB->insert(
          $table2  ,
          array(   'calls' =>   'postInit, count ' . count($_POST))
      );

      // **********************************************         #####################################################



        if( count($_POST) === 10 || count($_POST) === 14 || count($_POST) === 13   || 
        count($_POST) === 12 ){

            // get if zoho or salesfors
           // $we_are_in = ((int) sanitize_text_field($_POST['miraget_plugin_target']) === 2) ? 'salesforce' : 'zoho' ;
            //insightly 
            if( (int) sanitize_text_field($_POST['miraget_plugin_target'])===1 )$we_are_in='zoho';     
            if( (int) sanitize_text_field($_POST['miraget_plugin_target'])===2 )$we_are_in='salesforce';
            if( (int) sanitize_text_field($_POST['miraget_plugin_target'])===3 )$we_are_in='insightly';
            // zoho filed ;
            if( $we_are_in === 'zoho')
            $additional_post = array(
                  'miraget_plugin_zoho_vars', 'zoho_token','client_id', 'code', 'secret_id','zoho_datacenter', 'upd_crm_record'
                ) ;

            // salesforce filed ;
            if( $we_are_in === 'salesforce')
            $additional_post = array('salesforce_user', 'salesforce_pass', 'salesforce_url') ;
            //insightly fialds
            if( $we_are_in === 'insightly')
            $additional_post = array('insightly_access_key', 'insightly_secret_key','insightly_session_key', 'insightly_target_api', 'insightly_token_api');
            // standart fileds
            $array_post =  array( 'miraget_key' ,'m_none' ,'save',  'miraget_plugin_refresh_data','on_off', 'miraget_plugin_target','rows_to_sync' ) ;

            // all post in the request
            $result = array_merge($additional_post, $array_post);

            foreach ($_POST as $key => $value) {
                # chek condition...

                if(
                    ! in_array( sanitize_key( $key), $result ) ||
                    sanitize_text_field( trim( $value ) === '' ) ||
                   ! wp_verify_nonce( sanitize_text_field( $_POST['m_none']) , 'miraget_nonce' )
                ) {
                    //print(" error , key " . $key . " value " . $value  );
                     $this->error_post = true ;
                     $this->is_post = true ;
                     //break ;
                }
                // check zoho condition
                // check sefl condition

            }

            // update data
            if(  ! $this->error_post ){
               //print(" ok updating");
                $this->updateData($we_are_in) ;
            }
        }
    }


    private function updateData($type){

        $dataKey     = sanitize_text_field( $_POST['miraget_key'] ) ;
        $on_off_val  =  (int) $_POST['on_off'] ;
        $on_off   =  ( $on_off_val === 0 OR $on_off_val === 1) ? $on_off_val : 0  ;

        $refresh_data = (int) $_POST['miraget_plugin_refresh_data'] ;
        $data2Refresh = ($refresh_data >= 5 && $refresh_data <= 15 && ($refresh_data % 5 === 0 ) ) ? $refresh_data : 5 ;
        /**
         * plugin surce :  miraget_plugin_with
         * Opt-In Panda : value = 1
         * OptinMonster : value = 2
         */
        $post_var_plugin_source = 1 ;
        $post_var_plugin_target = (int) $_POST['miraget_plugin_target'] ;
        $plugin_source =  1 ;

        $plugin_target =  ( $post_var_plugin_target === 1 
        || $post_var_plugin_target === 2 || $post_var_plugin_target === 3 )  ? $post_var_plugin_target : 1 ;
        
        if( $type === 'zoho'){

            $api_v = (int) $_POST['miraget_plugin_zoho_vars'] ;
            
           /* $array2Update = array(

                '9' => ( $api_v === 1 || $api_v === 2 ) ? $api_v : 1 ,
                '10' => sanitize_text_field( $_POST['zoho_token'] ) ,
                '15' => sanitize_text_field( $_POST['client_id'] ) ,
                '16' => sanitize_text_field( $_POST['code'] ) ,
                '17' => sanitize_text_field( $_POST['zoho_datacenter']),
                '18' => sanitize_text_field( $_POST['secret_id'] ),
                '19' => sanitize_text_field( $_POST['upd_crm_record'] )

            ) ;*/
            if($api_v == 1){
                $array2Update = array(

                    '9' => ( $api_v === 1 || $api_v === 2 ) ? $api_v : 1 ,
                    '10' => sanitize_text_field( $_POST['zoho_token'] ) ,
                    /*'15' => sanitize_text_field( $_POST['client_id'] ) ,
                    '16' => sanitize_text_field( $_POST['code'] ) ,
                    '17' => sanitize_text_field( $_POST['zoho_datacenter']),
                    '18' => sanitize_text_field( $_POST['secret_id'] ),*/
                    '19' => sanitize_text_field( $_POST['upd_crm_record'] )
    
                ) ;
            }
            if($api_v==2){
                $array2Update = array(

                    '9' => ( $api_v === 1 || $api_v === 2 ) ? $api_v : 1 ,
                    //'10' => sanitize_text_field( $_POST['zoho_token'] ) ,
                    '15' => sanitize_text_field( $_POST['client_id'] ) ,
                    '16' => sanitize_text_field( $_POST['code'] ) ,
                    '17' => sanitize_text_field( $_POST['zoho_datacenter']),
                    '18' => sanitize_text_field( $_POST['secret_id'] ),
                    '19' => sanitize_text_field( $_POST['upd_crm_record'] )
                );
            }
        }
        if( $type === 'salesforce'){
            
            $array2Update = array(

                '11' => sanitize_text_field( $_POST['salesforce_user'] ),
                '12' => sanitize_text_field( $_POST['salesforce_pass'] ),
                '13' => sanitize_text_field( $_POST['salesforce_url'] ) ,

            ) ;
           
        }
        //if insightly
        if( $type ==='insightly'){
            $array2Update = array(

                '21' => sanitize_text_field( $_POST['insightly_access_key'] ),
                '22' => sanitize_text_field( $_POST['insightly_secret_key'] ),
                '23' => sanitize_text_field( $_POST['insightly_session_key'] ) ,
                '24' => sanitize_text_field( $_POST['insightly_target_api'] ), 
                '25' => sanitize_text_field( $_POST['insightly_token_api'] )
            ) ;

        }
         //************************************************** 
          $table = $this->DB->prefix . MIRG_TABLE_OP  ;
          $row_2_sync = (int) $_POST['rows_to_sync'] ;
        
        if( $row_2_sync === 10 || $row_2_sync === 20 || $row_2_sync === 30  ){
            $update_id_value = array(
                '1' => $dataKey ,
                '5' => $plugin_source ,
                '7' => $data2Refresh ,
                '8' => $plugin_target ,
                '14' => $on_off  ,
                '20' => $row_2_sync  ,
            );

            $rsult_arr_2_update =  ( $update_id_value + $array2Update ) ;

            foreach ( $rsult_arr_2_update as $id => $value ) {

                $this->DB->update( $table,
                    array( 'value' => $value  ),
                    array( 'id' => $id ),
                    array( '%s',  ),
                    array( '%s' )
                );
            }
            $this->updta_ok = true ;


        }

    }
    public function render(){

        return   '
          currentPage = "main" ;
          var apiStatus = "' .  $this->api_status . '" ;
          var miragetKey = "' .  $this->key . '" ;
          var emailes =  ' . json_encode($this->emails) . '  ;
          var errors_post =  ' .( int )  $this->error_post . '  ;
          var update =  ' .( int )  $this->updta_ok . '  ;
          var pluginSource = ' .( int )  $this->plugin_source . '  ;
          var pluginTarget = ' .( int )  $this->plugin_target . '  ;
          var pluginTargetVersion = ' .( int )  $this->plugin_target_version . '  ;
          var zohoToken = "' . $this->zoho_token . '"  ;
          var salesforceUser = "' . $this->salesforce_user . '"  ;
          var salesforcePass = "' . $this->salesforce_pass . '"  ;
          var salesforceUrl = "' . $this->salesforce_url . '"  ;
          var refresh_data = ' .( int )  $this->refresh_data . '  ;
          var is_post =  ' .( int )  $this->is_post . '  ;
          var lastUpdate =  ' .( int )  $this->lastUpda . '  ;
          var onOff =  ' .( int )  $this->on_of . '  ;
          var isWorkable =  '.  $this->isWorkable() .' ;
          var nonce = "' .  $this->miraget_nonce . '" ;
          var clientId = "' .  $this->client_id . '" ;
          var codeZoho = "' .  $this->code . '" ;
          var secretId = "' .  $this->secret_id . '" ;
          var zohoDataCenter =  ' .  ( int ) $this->zoho_dataCenter . '  ;
          var updCrmRecord =  ' .  ( int ) $this->upd_crm_record . '  ;
          var rowsToSync =  ' .  ( int ) $this->rows_to_sync . '  ;
          var insightlyAccessKey = ' .(int) $this->insightly_access_key.';
          var insightlySecretKey = ' .(int) $this->insightly_secret_key.';
          var insightlySessionKey = ' .(int) $this->insightly_session_key.';
          var insightlyTargetApi = ' .(int) $this->insightly_target_api.';
          var insightlyTokenApi = ' .(int) $this->insightly_token_api.';
          var pageTitle = "MiragetGeneartor info" ;
        ' ;
    }


    public function getApitStatus(){

        $this->table_option = $this->DB->prefix . MIRG_TABLE_OP ;
        $this->api_status = $this->DB->get_var( "select value from $this->table_option where id='2'" );
        $this->lastUpda = $this->DB->get_var( "select value from $this->table_option where id='4'" );
        $this->key = $this->DB->get_var( "select value from $this->table_option where id='1'" );
        $this->refresh_data = $this->DB->get_var( "select value from $this->table_option where id='7'" );
        $this->plugin_source = $this->DB->get_var( "select value from $this->table_option where id='5'" );
        $this->plugin_target = $this->DB->get_var( "select value from $this->table_option where id='8'" );
        $this->plugin_target_version = $this->DB->get_var( "select value from $this->table_option where id='9'" );
        $this->zoho_token = $this->DB->get_var( "select value from $this->table_option where id='10'" );
        $this->salesforce_user = $this->DB->get_var( "select value from $this->table_option where id='11'" );
        $this->salesforce_pass = $this->DB->get_var( "select value from $this->table_option where id='12'" );
        $this->salesforce_url = $this->DB->get_var( "select value from $this->table_option where id='13'" );
        $this->on_of = $this->DB->get_var( "select value from $this->table_option where id='14'" );
        $this->client_id = $this->DB->get_var( "select value from $this->table_option where id='15'" );
        $this->code = $this->DB->get_var( "select value from $this->table_option where id='16'" );
        $this->zoho_dataCenter = $this->DB->get_var( "select value from $this->table_option where id='17'" );
        $this->secret_id = $this->DB->get_var( "select value from $this->table_option where id='18'" );
        $this->upd_crm_record = $this->DB->get_var( "select value from $this->table_option where id='19'" );
        $this->rows_to_sync = $this->DB->get_var( "select value from $this->table_option where id='20'" );
        //insightly failds
        $this->insightly_access_key = $this->DB->get_var( "select value from $this->table_option where id='21'" );
        $this->insightly_secret_key = $this->DB->get_var( "select value from $this->table_option where id='22'" );
        $this->insightly_session_key= $this->DB->get_var( "select value from $this->table_option where id='23'" );
        $this->insightly_target_api = $this->DB->get_var( "select value from $this->table_option where id='24'" );
        $this->insightly_token_api = $this->DB->get_var( "select value from $this->table_option where id='25'" );
    }


    private function isWorkable(){

      //debug responses ****************************** to be removed ###############################################
      $table2 = $this->DB->prefix.'debugg';
      $this->DB->insert(
          $table2  ,
          array(   'calls' =>   ' isWorkable , client_id' . $this->client_id . ' plugin_target ' . $this->plugin_target . 'plugin_target_version' . $this->plugin_target_version  . ' code ' . $this->code)
      );

      // **********************************************         #####################################################


        if(
            $this->client_id &&
            $this->secret_id &&
            $this->code &&
            (int) $this->plugin_target === 1 &&
            (int) $this->plugin_target_version === 2
        ) return 'true' ;

        if(
            $this->zoho_token &&
            (int) $this->plugin_target === 1 &&
            (int) $this->plugin_target_version === 1
        ) return 'true' ;

        if(
             $this->salesforce_user &&
             (int) $this->plugin_target === 2 &&
             $this->salesforce_url   &&
             $this->salesforce_pass
        ) return 'true' ;
        if ( 
            $this->insightly_access_key &&
            (int) $this->plugin_target === 3 &&
            $this->insightly_secret_key &&
            $this->insightly_session_key &&
            $this->insightly_target_api &&
            $this->insightly_token_api
            ) return 'true';



        //debug responses ****************************** to be removed ###############################################
        $table2 = $this->DB->prefix.'debugg';
        $this->DB->insert(
            $table2  ,
            array(   'calls' =>   'false')
        );

        // **********************************************         #####################################################

        return 'false' ;

    }
}
