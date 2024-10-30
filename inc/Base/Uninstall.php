<?php namespace Miraget\Base ;
/**
 * @package  Miraget Generator
 */ 
    class Uninstall
    {
        /**
         * Uninstall method is called once uninstall this plugin
         * delete tables, options that used in plugin
         */
        public static function uninstall(){
        
            global $wpdb;
            $tblList = array( MIRG_TABLE_OP,MIRG_TABLE_ACT,MIRG_TABLE_DEBG   );

            foreach( $tblList as $table_name ) {
                $wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}$table_name" );
            }
        }

    }
