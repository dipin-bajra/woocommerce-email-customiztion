<?php

namespace Codemanas\Woomail;

class Maps
{
    public static ?Maps $instance = null;

    public static function get_instance(): ?Maps {
        return is_null( self::$instance ) ? self::$instance = new self() : self::$instance;
    }

    public function __construct() {
        add_action( 'init', array( $this, 'hooks' ) );
    }

    public function hooks() {
        //Add custom  tab to the single product page
        add_filter('woocommerce_product_tabs', array($this, 'add_custom_product_tab'));
    }

    function add_custom_product_tab($tabs)
    {
        $tabs['custom_tab'] = array(
            'title' => __('Location', 'text-domain'),
            'priority' => 50,
            'callback' => array($this, 'custom_tab_content'),
        );
        return $tabs;
    }
    function custom_tab_content()
    {
        ?>
        <iframe src="https://www.google.com/maps/embed?pb=!1m21!1m12!1m3!1d28267.914284830225!2d85.30294707638551!3d27.671268593766083!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!4m6!3e6!4m0!4m3!3m2!1d27.676627399999997!2d85.2996855!5e0!3m2!1sen!2snp!4v1681300892149!5m2!1sen!2snp"
                width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"></iframe>
        <?php
    }
}