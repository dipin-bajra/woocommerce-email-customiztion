<?php

namespace Codemanas\Woomail;

class Discount
{
    public static ?Discount $instance = null;

    public static function get_instance(): ?Discount {
        return is_null( self::$instance ) ? self::$instance = new self() : self::$instance;
    }

    public function __construct() {
        add_action( 'init', array( $this, 'hooks' ) );
    }

    public function hooks() {
        // Display the new discounted price
        add_filter('woocommerce_product_get_price',array($this, 'black_friday_discount') , 10, 2);
        add_filter('woocommerce_product_get_regular_price', array($this, 'black_friday_discount'), 10, 2);

        //Display discount message in the cart
        add_action( 'woocommerce_before_calculate_totals', array($this, 'apply_black_friday_discount') );

        // Add a custom checkbox field to variable product variations
        add_action( 'woocommerce_variation_options_pricing',array($this, 'add_variation_black_friday_discount_field'), 20, 3 );

        // Save the custom checkbox field value
        add_action( 'woocommerce_save_product_variation', array($this, 'save_variation_black_friday_discount_field'), 10, 2 );

        //Display the Black friday discount in the frontend
        add_filter( 'woocommerce_get_price_html',array($this, 'modify_product_price_html'), 10, 2 );

        // Display the Black Friday discount notice on the cart page for variable product variations
        add_action( 'woocommerce_before_calculate_totals', array($this, 'display_variation_black_friday_discount_notice'), 10, 1 );

        //Add a text to the woocommerce email footer
        add_action( 'woocommerce_email_footer', array($this, 'custom_woocommerce_email_footer_text'), 10, 1 );

    }

    function black_friday_discount($price, $product) {
        if (has_term('black-friday', 'product_cat', $product->get_id())) {
            $discount = 0.5; // 50% discount
            $price = $price * $discount;
        }
        return $price;
    }

    function apply_black_friday_discount( $cart ) {

        // Loop through the cart items
        foreach ( $cart->get_cart() as $cart_item ) {
            $product = $cart_item['data'];
            if ( has_term( 'black-friday', 'product_cat', $product->get_id() ) ) {
                // Display the message
                if(is_cart())
                    wc_add_notice( 'Black Friday discount on ' . $product->get_name(), 'success' );
            }
        }
    }

    function add_variation_black_friday_discount_field( $loop, $variation_data, $variation ) {
        woocommerce_wp_checkbox( array(
            'id'            => '_black_friday_discount[' . $variation->ID . ']',
            'name'          => '_black_friday_discount[' . $variation->ID . ']',
            'description'   => __( ' Black Friday discount ', 'woocommerce' ),
            'wrapper_class' => 'form-row form-row-full',
            'value'         => get_post_meta( $variation->ID, '_black_friday_discount', true ),
        ) );
    }

    function save_variation_black_friday_discount_field( $variation_id, $i ) {
        if ( isset( $_POST['_black_friday_discount'][$variation_id] ) ) {
            update_post_meta( $variation_id, '_black_friday_discount', 'yes' );
        } else {
            delete_post_meta( $variation_id, '_black_friday_discount' );
        }
    }

    function modify_product_price_html( $price_html, $product ) {
        if ( $product->is_type( 'variation' ) && get_post_meta( $product->get_id(), '_black_friday_discount', true ) === 'yes' ) {
            $discount = 0.5;
            $price = $product->get_price() * $discount;
            $price_html = wc_price( $price ) . $product->get_price_suffix();
        }
        return $price_html;
    }

    function display_variation_black_friday_discount_notice( $cart ) {
        foreach ( $cart->get_cart() as $cart_item ) {
            if ( $cart_item['data']->is_type( 'variation' ) && get_post_meta( $cart_item['variation_id'], '_black_friday_discount', true ) === 'yes' ) {
                if(is_cart()) {
                    wc_add_notice( 'Black Friday discount on ' . $cart_item['data']->get_name(), 'success' );
                    $discount = 0.5;
                    $price = $cart_item['data']->get_price() * $discount;
                    $cart_item['data']->set_price($price);
                }
            }
        }
    }

    function custom_woocommerce_email_footer_text( ) {
        echo '<p>Thank you for purchasing using the black friday discount offer. Please recommend your friend and chance to win exciting gift hamper and more prizes.</p>';
    }

}