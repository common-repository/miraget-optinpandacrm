<?php
/*
Plugin Name: MiragetOptinPandaCRM
Plugin URI: https://miraget.com/wordpress-plugin-miraget-optin-panda-sync-to-crm/
Description: Sync your Optin Panda leads with your CRM
Version: 1.0.0
Author: Miraget
Author URI:  https://miraget.com
*/
defined( 'ABSPATH' ) or die( 'U Can\'t include this file with way ' ) ;
/**
 * include autoload file
 */
if( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ){

    require_once dirname( __FILE__ ) . '/vendor/autoload.php' ;

}else die('Error Loading') ;
/**
 * define ower plugin path
 */
define( 'MIRG_PLUGIN_PATH', plugin_dir_path( __FILE__ ) ) ;
/**
 * define ower plugin path
 */
define( 'MIRG_PLUGIN_URL', plugin_dir_url( __FILE__ ) ) ;
/**
 * define ower plugin base_name
 */
define( 'MIRG_PLUGIN_BN', plugin_basename( __FILE__ ) ) ;
/**
 * define ower plugin base_name
 */
define( 'MIRG_PLUGIN_NAME', 'Miragetoptinpanda_crm_plugin' ) ;
/**
 * define table option
 */
define( 'MIRG_TABLE_OP', 'miragetgen_op' ) ;
/**
 * define table activity
 */
define( 'MIRG_TABLE_ACT', 'miragetgen_act' ) ;
/**
 * define table activity
 */
define( 'MIRG_TABLE_DEBG', 'debugg' ) ;
/**
 * define Api url token
 */
define( 'MIRG_API_URL', 'https://token.api.miraget.com/' ) ;
/**
 * The code that runs during plugin activation
 */
function activate_miraget_plugin() {

	 Miraget\Base\Activate::activate();
     $activate = new Miraget\Base\Activate() ;
     $activate->install() ;

}
register_activation_hook( __FILE__, 'activate_miraget_plugin' );
/**
 * The code that runs during plugin deactivation
 */
function deactivate_miraget_plugin() {
	\Miraget\Base\Deactivate::deactivate();
}
register_deactivation_hook( __FILE__ , 'uninstall_miraget_plugin' );
/**
 * Un install pugin
 */
function uninstall_miraget_plugin() {
	\Miraget\Base\Uninstall::uninstall();
}
register_uninstall_hook( __FILE__ , 'uninstall_miraget_plugin' );
/**
 * Initialize && loading All files ( pages action script style .... )
 */
if( class_exists( '\\Miraget\\Init' ) ){
    \Miraget\Init::register_services() ;
}

