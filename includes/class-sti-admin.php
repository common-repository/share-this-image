<?php
/**
 * STI admin functions
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if ( ! class_exists( 'STI_Admin' ) ) :

    /**
     * Class for plugin search
     */
    class STI_Admin {

        /**
         * Return a singleton instance of the current class
         *
         * @return object
         */
        public static function factory() {
            static $instance = false;

            if ( ! $instance ) {
                $instance = new self();
                $instance->setup();
            }

            return $instance;
        }

        /**
         * Placeholder
         */
        public function __construct() {}

        /**
         * Setup actions and filters for all things settings
         */
        public function setup() {

            add_action( 'admin_init', array( &$this, 'register_settings' ) );
            add_action( 'admin_menu', array( &$this, 'add_admin_page' ) );
            add_action( 'admin_enqueue_scripts', array( &$this, 'admin_enqueue_scripts' ) );

            if ( ! get_option( 'sti_settings' ) ) {
                $this->initialize_settings();
            }

        }

        /*
        * Register plugin settings
        */
        public function register_settings() {
            register_setting( 'sti_settings', 'sti_settings' );
        }

        /*
         * Get plugin settings
         */
        public function get_settings() {
            $plugin_options = get_option( 'sti_settings' );
            return $plugin_options;
        }

        /**
         * Add options page
         */
        public function add_admin_page() {
            add_menu_page( __( 'Share Image Options', 'sti' ), __( 'Share Images', 'sti' ), 'manage_options', 'sti-options', array( &$this, 'display_admin_page' ), 'dashicons-format-image' );
        }

        /**
         * Generate and display options page
         */
        public function display_admin_page() {

            $options = $this->options_array();
            $settings = $this->get_settings();

            $tabs = array(
                'general' => __( 'General', 'sti' ),
                'content' => __( 'Content', 'sti' ),
            );

            $current_tab = empty( $_GET['tab'] ) ? 'general' : sanitize_title( $_GET['tab'] );

            $tabs_html = '';

            foreach ( $tabs as $name => $label ) {
                $tabs_html .= '<a href="' . admin_url( 'admin.php?page=sti-options&tab=' . $name ) . '" class="nav-tab ' . ( $current_tab == $name ? 'nav-tab-active' : '' ) . '">' . $label . '</a>';
            }

            $tabs_html .= '<a href="http://codecanyon.net/item/share-this-image-image-sharing-plugin/9988272?ref=ILLID" class="nav-tab premium-tab" target="_blank">' . __( 'Get Premium', 'sti' ) . '</a>';

            $tabs_html = '<h2 class="nav-tab-wrapper woo-nav-tab-wrapper">'.$tabs_html.'</h2>';
            

            if ( isset( $_POST["Submit"] ) ) {
                //$update_settings = array();

                foreach ( $options[$current_tab] as $values ) {

                    if ( $values['type'] === 'heading' ) {
                        continue;
                    }

                    if ( $values['type'] === 'checkbox' ) {
                        foreach ( $values['choices'] as $key => $val ) {
                            $settings[$values['id']][$key] = $_POST[ $values['id'] ][$key];
                        }
                        continue;
                    }

                    $settings[ $values['id'] ] = $_POST[ $values['id'] ];

                }

                update_option( 'sti_settings', $settings );
            }

            $sti_options = get_option( 'sti_settings' ); ?>


            <div class="wrap">

                <h1 class="sti-title"><?php _e( 'Share This Image', 'sti' ); ?></h1>

                <?php echo $tabs_html; ?>

                <form action="" name="sti_form" id="sti_form" method="post">

                    <?php

                    switch ($current_tab) {
                        case('content'):
                            new STI_Admin_Fields( $options['content'], $sti_options );
                            break;
                        default:
                            new STI_Admin_Fields( $options['general'], $sti_options );
                    }

                    ?>

                    <?php //new STI_Admin_Fields( $options, $sti_options ); ?>

                    <p class="submit"><input name="Submit" type="submit" class="button-primary" value="<?php _e( 'Save Changes', 'sti' ); ?>" /></p>

                </form>

            </div>

        <?php }

        /*
         * Options array that generate settings page
         */
        public function options_array() {

            require_once STI_DIR .'/includes/options.php';

            return $options;
        }

        /**
         * Initialize settings to their default values
         */
        public function initialize_settings() {
            $options = $this->options_array();
            $default_settings = array();

            foreach ( $options as $section_name => $section ) {
                foreach ($section as $values) {

                    if ($values['type'] === 'heading') {
                        continue;
                    }

                    if ($values['type'] === 'checkbox') {
                        foreach ($values['choices'] as $key => $val) {
                            $default_settings[$values['id']][$key] = $values['value'][$key];
                        }
                        continue;
                    }

                    $default_settings[$values['id']] = $values['value'];

                }
            }

            update_option( 'sti_settings', $default_settings );
        }

        /**
         * Enqueue admin scripts and styles
         */
        public function admin_enqueue_scripts() {
            if ( isset( $_GET['page'] ) && $_GET['page'] == 'sti-options' ) {
                wp_enqueue_style( 'sti-admin-style', STI_URL . '/assets/css/sti-admin.css' );
                wp_enqueue_script( 'jquery' );
                wp_enqueue_script( 'jquery-ui-sortable' );
                wp_enqueue_script( 'sti-admin-js', STI_URL . '/assets/js/admin.js', array('jquery') );
            }
        }

    }


endif;

STI_Admin::factory();