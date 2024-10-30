<?php namespace Miraget ;
/**
 * @package  Miraget Generator
 */ 

    final class Init
    {
        /**
         * Store all the classes inside an array
         * @return array Full list of classes
         */
        public static function get_services() {
            return array(
                new Pages\Admin() ,
                new Pages\CronSApi() ,
                new Base\Enqueue(),
                new Base\SettingsLinks()
            ) ;
        }
        /***
         * Loop through the classes, instialize them,
         *  and call the register methde if exist
         * @return void
         */ 
        public static function register_services() {
            
            foreach ( self::get_services() as $class) {
                
                $service = self::instantiate( $class ) ;

                if( method_exists( $service, 'register' ) ) {
                    $service->register() ;
                }
            }
           
        }
        /**
         * initialize the class 
         * @param $class class from services array
         * @return new instans of class
         */
        private static function instantiate( $class ){

            return  $class ;
        }
    }
