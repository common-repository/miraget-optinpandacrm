<?php namespace Miraget\Base ;
/**
 * @package  Miraget Generator
 */
    class Activate
    {
        private $DB ;

        public static function activate() {

             flush_rewrite_rules();
        }

        public function install(){

            /* Global  */
            global $wpdb;
            // init database
            $this->DB = $wpdb ;

            // header to send Array
            $args = array(
                'headers' => array(
                    'plugin_name' => MIRG_PLUGIN_NAME
                )
            );
            // skip CURLOPT_SSL_VERIFYHOST
            add_filter( 'https_ssl_verify', '__return_false' );
            //send request to  get token
            $response = wp_remote_get(  $this->initUrl(), $args ) ;
            // responce code
            $http_code = wp_remote_retrieve_response_code( $response );
            // get body
            $body = wp_remote_retrieve_body( $response );
            // get message body  <Array > ['message]
            $msg_body = json_decode($body) ;

            if( $http_code > 300  ){
                return;
            }
            if( isset( $msg_body->Token ) ){

                /* create table  */
                $this->createTabes() ;
                /** insert data  */
                $this->insertData( $msg_body->Token ) ;

            }




        }
        private function insertData( $key ){

            $this->DB->insert(
                $this->DB->prefix . MIRG_TABLE_OP ,
                array( 'id' => '1' , 'meta' => 'key',     'value' =>  esc_sql( $key ) )
            );
            $this->DB->insert(
                $this->DB->prefix . MIRG_TABLE_OP ,
                array( 'id' => '2' , 'meta' => 'status',  'value' =>  '1' )
            );
            $this->DB->insert(
                $this->DB->prefix . MIRG_TABLE_OP ,
                array( 'id' => '3' , 'meta' => 'totalEmailSent',  'value' =>  '0' )
            );
            $this->DB->insert(
                $this->DB->prefix . MIRG_TABLE_OP ,
                array( 'id' => '4' , 'meta' => 'lastSend',  'value' =>  '0')
            );
            $this->DB->insert(
                $this->DB->prefix . MIRG_TABLE_OP ,
                array( 'id' => '5' , 'meta' => 'plugInName',  'value' =>  '1')
            );
            $this->DB->insert(
                $this->DB->prefix . MIRG_TABLE_OP ,
                array( 'id' => '6' , 'meta' => 'last_emailed_id',  'value' =>  '0')
            );
            $this->DB->insert(
                $this->DB->prefix . MIRG_TABLE_OP ,
                array( 'id' => '7' , 'meta' => 'refresh_data_time',  'value' =>  '5')
            );
            $this->DB->insert(
                $this->DB->prefix . MIRG_TABLE_OP ,
                array( 'id' => '8' , 'meta' => 'plugin_target',  'value' =>  '1')
            );
            $this->DB->insert(
                $this->DB->prefix . MIRG_TABLE_OP ,
                array( 'id' => '9' , 'meta' => 'zoho_version',  'value' =>  '1')
            );
            $this->DB->insert(
                $this->DB->prefix . MIRG_TABLE_OP ,
                array( 'id' => '10' , 'meta' => 'zoho_token',  'value' =>  '0')
            );
            $this->DB->insert(
                $this->DB->prefix . MIRG_TABLE_OP ,
                array( 'id' => '11' , 'meta' => 'salesforce_user',  'value' =>  '0')
            );
            $this->DB->insert(
                $this->DB->prefix . MIRG_TABLE_OP ,
                array( 'id' => '12' , 'meta' => 'salesforce_pass',  'value' =>  '0')
            );
            $this->DB->insert(
                $this->DB->prefix . MIRG_TABLE_OP ,
                array( 'id' => '13' , 'meta' => 'salesforce_url',  'value' =>  '0')
            );
            $this->DB->insert(
                $this->DB->prefix . MIRG_TABLE_OP ,
                array( 'id' => '14' , 'meta' => 'on_off',  'value' =>  '1')
            );
            $this->DB->insert(
                $this->DB->prefix . MIRG_TABLE_OP ,
                array( 'id' => '15' , 'meta' => 'client_id',  'value' =>  '0')
            );
            $this->DB->insert(
                $this->DB->prefix . MIRG_TABLE_OP ,
                array( 'id' => '16' , 'meta' => 'code',  'value' =>  '0')
            );
            $this->DB->insert(
                $this->DB->prefix . MIRG_TABLE_OP ,
                array( 'id' => '17' , 'meta' => 'zoho_dataCenter',  'value' =>  '0')
            );
            $this->DB->insert(
                $this->DB->prefix . MIRG_TABLE_OP ,
                array( 'id' => '18' , 'meta' => 'secret_id',  'value' =>  '0')
            );
            $this->DB->insert(
                $this->DB->prefix . MIRG_TABLE_OP ,
                array( 'id' => '19' , 'meta' => 'upd_crm_record',  'value' =>  '1')
            );
            $this->DB->insert(
                $this->DB->prefix . MIRG_TABLE_OP ,
                array( 'id' => '20' , 'meta' => 'rows_to_sync',  'value' =>  '10')
            );
            // add insightly fields
            $this->DB->insert(
                $this->DB->prefix . MIRG_TABLE_OP ,
                array( 'id' => '21' , 'meta' => 'insightly_access_key',  'value' =>  '0')
            );
            $this->DB->insert(
                $this->DB->prefix . MIRG_TABLE_OP ,
                array( 'id' => '22' , 'meta' => 'insightly_secret_key',  'value' =>  '0')
            );
            $this->DB->insert(
                $this->DB->prefix . MIRG_TABLE_OP ,
                array( 'id' => '23' , 'meta' => 'insightly_session_key',  'value' =>  '0')
            );
            $this->DB->insert(
                $this->DB->prefix . MIRG_TABLE_OP ,
                array( 'id' => '24' , 'meta' => 'insightly_target_api',  'value' =>  '0')
            );
            $this->DB->insert(
                $this->DB->prefix . MIRG_TABLE_OP ,
                array( 'id' => '25' , 'meta' => 'insightly_token_api',  'value' =>  '10')
            );



        }
        private function createTabes(){


            $table_option = $this->DB->prefix . MIRG_TABLE_OP ;
            $table_activity = $this->DB->prefix . MIRG_TABLE_ACT ;
            $table_debugg = $this->DB->prefix . MIRG_TABLE_DEBG ;

            // Create tables option
            $this->DB->query("CREATE TABLE IF NOT EXISTS `" . $table_option . "` (
                `id` int(11) NOT NULL,
                `meta` varchar(100) NOT NULL,
                `value` varchar(100) NOT NULL
              ) ENGINE=MyISAM" );
            // Create tables activity
            $this->DB->query("CREATE TABLE IF NOT EXISTS `" . $table_activity . "`(
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `email` varchar(60) CHARACTER SET utf8 NOT NULL,
                `time` int(11) NOT NULL,
                PRIMARY KEY (`id`)
              ) ENGINE=MyISAM" );
            // debug table 
            $this->DB->query("CREATE TABLE IF NOT EXISTS `" . $table_debugg . "`(
                `calls` varchar(20) NOT NULL
              ) ENGINE=MyISAM" );


        }
        /**
         * return full url token
         */
        private function initUrl(){

            $user = wp_get_current_user();
            $userEmail = $user->user_email;
            $userDomain = get_option( 'siteurl' );

            $userDomain = isset( $_SERVER['HTTP_HOST'] ) ? trim( $_SERVER['HTTP_HOST'] ) : false;

            if( ! $userDomain  ) die( 'Please your Domain URL not valid ...' ) ;

            // init url to get token
            // https://token.api.miraget.com/token?domain=
            return  MIRG_API_URL . "token?domain=" . $userDomain . "&email=" . $userEmail . '&source=MiragetOptinPanda';
        }


    }
