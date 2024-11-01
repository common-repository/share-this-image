<?php

/*
Plugin Name: Share This Image
Description: Allows you to share in social networks any of your images
Version: 1.00
Author: ILLID
Text Domain: sti
*/


if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'STI_VER', '1.00' );


define( 'STI_DIR', dirname( __FILE__ ) );
define( 'STI_URL', plugins_url( '', __FILE__ ) );


if ( ! class_exists( 'STI_Main' ) ) :

/**
 * Main plugin class
 *
 * @class STI_Main
 */
final class STI_Main {

    /**
     * @var STI_Main The single instance of the class
     */
    protected static $_instance = null;

    /**
     * Main STI_Main Instance
     *
     * Ensures only one instance of STI_Main is loaded or can be loaded.
     *
     * @static
     * @return STI_Main - Main instance
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Constructor
     */
    public function __construct() {

        $this->includes();

        update_option( 'sti_plugin_ver', STI_VER );

        add_filter( 'plugin_action_links', array( $this, 'add_settings_link' ), 10, 2 );

        load_plugin_textdomain( 'sti', false, dirname( plugin_basename( __FILE__ ) ). '/languages/' );

    }

    /**
     * Include required core files used in admin and on the frontend
     */
    private function includes() {
        include_once( 'includes/class-sti-functions.php' );
        include_once( 'includes/class-sti-admin-fields.php' );
        include_once( 'includes/class-sti-admin.php' );
    }

    /*
     * Add settings link to plugins
     */
    public function add_settings_link( $links, $file ) {
        $plugin_base = plugin_basename( __FILE__ );

        if ( $file == $plugin_base ) {
            $setting_link = '<a href="' . admin_url('admin.php?page=sti-options') . '">'.__( 'Settings', 'sti' ).'</a>';
            array_unshift( $links, $setting_link );

            $premium_link = '<a href="http://codecanyon.net/item/share-this-image-image-sharing-plugin/9988272?ref=ILLID" target="_blank">'.__( 'Get Premium', 'sti' ).'</a>';
            array_unshift( $links, $premium_link );
        }

        return $links;
    }

}

endif;


/**
 * Returns the main instance of STI_Main
 *
 * @return STI_Main
 */
function STI() {
    return STI_Main::instance();
}

/*
 * Check if WooCommerce is active
 */
if ( ! sti_is_plugin_active( 'share-this-image-pro/share-this-image-pro.php' ) ) {
    STI();
}

/*
 * Check whether the plugin is active by checking the active_plugins list.
 */
function sti_is_plugin_active( $plugin ) {
    return in_array( $plugin, (array) get_option( 'active_plugins', array() ) ) || sti_is_plugin_active_for_network( $plugin );
}

/*
 * Check whether the plugin is active for the entire network
 */
function sti_is_plugin_active_for_network( $plugin ) {
    if ( !is_multisite() )
        return false;

    $plugins = get_site_option( 'active_sitewide_plugins' );
    if ( isset($plugins[$plugin]) )
        return true;

    return false;
}