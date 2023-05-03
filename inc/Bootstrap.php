<?php

namespace Codemanas\Woomail;

//use Codemanas\ZoomWooCommerceAddon\Bootstrap;

class Bootstrap
{

    private static $instance = null;

    private function __construct() {

        $this->autoload();
        add_action( 'plugins_loaded', array( $this, 'initPlugin' ) );
    }


    public static function get_instance() {#1D0052
        if ( self::$instance == null ) {
            self::$instance = new Bootstrap();
        }

        return self::$instance;
    }
    public function autoload() {
        require_once CODEMANAS_WOOMAIL_ROOT_DIR_PATH . '/vendor/autoload.php';

    }

    public function initPlugin(){
        Maps::get_instance();
        Discount::get_instance();
        OrderNotes::get_instance();
        ShippingMethod::get_instance();

    }

}

Bootstrap::get_instance();