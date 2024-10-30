<?php namespace Miraget\Base ;
/**
 * @package  Miraget Generator
 */
    class SettingsLinks
    {

        protected $plugin = MIRG_PLUGIN_BN ;

        public function register() {

            //add_filter( "plugin_action_links_$this->plugin", array( $this, 'settings_link' ) ) ;

        }
        function settings_link( $links ){
            // costom settings links
            $setting_link = '<a href="admin.php?page=miraget_plugin">Settings</a>';
            array_push( $links, $setting_link   ) ;

            return $links  ;
        }
    }
