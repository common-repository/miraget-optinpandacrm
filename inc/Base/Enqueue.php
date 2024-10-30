<?php namespace Miraget\Base ;
/**
 * @package  Miraget Generator
 */ 
    class Enqueue
    {
        public function register() {

            add_action( 'admin_enqueue_scripts', array( $this, 'enqueue')) ;
            
        }
        function enqueue(){
            // add date() for dev testing ssk
            wp_enqueue_style( 'mypluinstyle',  MIRG_PLUGIN_URL . 'assets/css/app.css','',time());
            wp_enqueue_script( 'mypluinscript', MIRG_PLUGIN_URL .'assets/js/app.js','',time()) ;
           // wp_enqueue_script( 'mypluinscript2', MIRG_PLUGIN_URL .'assets/js/settings.js') ;
    
        }
    }