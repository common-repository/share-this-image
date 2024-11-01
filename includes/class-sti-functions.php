<?php
/**
 * STI functions
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if ( ! class_exists( 'STI_Functions' ) ) :

    /**
     * Class for plugin search
     */
    class STI_Functions {

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

            add_action( 'wp_enqueue_scripts', array( &$this, 'enqueue_scripts' ), 999999 );
            add_action( 'wp_head', array( &$this, 'metatags' ), 999999 );

            add_shortcode( 'sti_image', array( $this, 'shortcode' ) );

        }

        /*
         * Register plugin settings
         */
        public function get_settings( $id = false ) {
            $sti_options = get_option( 'sti_settings' );
            if ( $id ) {
                return $sti_options[ $id ];
            } else {
                return $sti_options;
            }
        }

        /*
         * Return list of active share buttons
         */
        private function get_primary_menu() {
            $primary_menu_array = explode( ',', $this->get_settings('primary_menu') );
            return $primary_menu_array;
        }

        /**
         * Enqueue frontend scripts and styles
         *
         * @return void
         */
        public function enqueue_scripts() {

            $settings = $this->get_settings();

            if ( wp_is_mobile() && $settings['on_mobile'] === 'false' ) {
                return false;
            }

            wp_enqueue_style( 'sti-style', STI_URL . '/assets/css/sti.css', array(), STI_VER );
            wp_enqueue_script( 'jquery' );
            wp_enqueue_script( 'sti-script', STI_URL . '/assets/js/sti.js', array('jquery'), STI_VER, true );
            wp_localize_script( 'sti-script', 'sti_vars', array(
                'ajaxurl'      => admin_url( 'admin-ajax.php' ),
                'selector'     => $settings['selector'],
                'title'        => $settings['title'],
                'summary'      => $settings['summary'],
                'minWidth'     => $settings['minWidth'],
                'minHeight'    => $settings['minHeight'],
                'fb_app'       => $settings['fb_app'],
                'fb_type'      => $settings['fb_type'],
                'sharer'       => ( $settings['sharer'] == 'true' ) ? STI_URL . '/sharer.php' : '',
                'is_mobile'    => wp_is_mobile() ? true : false,
                'always_show'  => ( $settings['always_show'] == 'true' ) ? true : false,
                'primary_menu' => $this->get_primary_menu(),
                'twitterVia'   => $settings['twitter_via'],
            ) );

        }
        
        /**
         * Add special metatags to the head of the site
         */
        public function metatags() {

            if ( isset( $_GET['img'] ) ) {

                $page_link = 'http://'.$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
                $title = isset($_GET['title']) ? $_GET['title'] : '';
                $desc = isset($_GET['desc']) ? $_GET['desc'] : '';

                echo '<meta property="og:type" content="article" />';

                echo '<link rel="canonical" href="'.$page_link.'"/>';
                echo '<meta property="og:url" content="'.$page_link.'"/>';
                echo '<meta property="twitter:url" content="'.$page_link.'"/>';

                echo '<meta property="og:image" content="http://'.$_GET['img'].'"/>';
                echo '<meta property="twitter:image" content="http://'.$_GET['img'].'"/>';

                if ( $title ) {
                    echo '<title>'.$title.'</title>';
                    echo '<meta property="og:title" content="'.$title.'"/>';
                    echo '<meta property="twitter:title" content="'.$title.'"/>';
                }

                if ( $desc ) {
                    echo '<meta name="description" content="'.$desc.'">';
                    echo '<meta property="og:description" content="'.$desc.'"/>';
                    echo '<meta property="twitter:description" content="'.$desc.'"/>';
                }
            }

            if ( isset( $_GET['close'] ) ) { ?>
                <script type="text/javascript">
                    window.close();
                </script>
            <?php }

        }

        /*
         * Shortcode for image sharing
         */
        public function shortcode( $atts = array() ) {

            extract( shortcode_atts( array(
                'image'           => '',
                'shared_image'    => '',
                'shared_url'      => '',
                'shared_title'    => '',
                'shared_desc'     => '',
            ), $atts ) );


            $params_string = '';
            $output = '';

            $params = array(
                'data-media'   => $shared_image,
                'data-url'     => $shared_url,
                'data-title'   => $shared_title,
                'data-summary' => $shared_desc,
            );

            foreach( $params as $key => $value ) {
                if ( $value ) {
                    $params_string .= $key . '="' . $value . '" ';
                }
            }

            if ( $image ) {
                $output = '<img src="' . $image . '" ' . $params_string . '>';
            }

            return apply_filters( 'sti_shortcode_output', $output );

        }

    }


endif;

STI_Functions::factory();