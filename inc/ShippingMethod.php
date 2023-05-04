<?php

namespace Codemanas\Woomail;

class ShippingMethod
{
    public static ?ShippingMethod $instance = null;

    public static function get_instance(): ?ShippingMethod
    {
        return is_null(self::$instance) ? self::$instance = new self() : self::$instance;
    }

    public function __construct()
    {
        add_action('init', array($this, 'hooks'));
        add_action('init', array($this, 'add_custom_roles'));
    }

    public function hooks()
    {
	    add_filter('woocommerce_package_rates', array($this, 'custom_shipping_methods_based_on_user_role'), 999, 2);
    }

    function add_custom_roles()
    {
        add_role('premium_customer', 'Premium Customer', array(
            'read' => true,
            'edit_posts' => false,
            'delete_posts' => false,
            'edit_products' => true,
            'edit_published_products' => true,
            'edit_others_products' => false,
            'publish_products' => true,
            'read_private_products' => false,
            'edit_private_products' => true,
            'delete_private_products' => true,
            'delete_published_products' => false,
            'manage_woocommerce' => true,
            'view_woocommerce_reports' => true
        ));
    }

	function custom_shipping_methods_based_on_user_role( $rates, $package ) {
		if ( is_user_logged_in() ) {
			$user = wp_get_current_user();
			if ( in_array( 'premium_customer', (array) $user->roles ) ) {
				// Remove all shipping methods except for free shipping for premium customer
				foreach ( $rates as $rate_key => $rate ) {
					if ( 'free_shipping' !== $rate->method_id ) {
						unset( $rates[ $rate_key ] );
					}
				}
			} else {
				// Remove free shipping method for normal customer
				foreach ( $rates as $rate_key => $rate ) {
					if ( 'free_shipping' === $rate->method_id ) {
						unset( $rates[ $rate_key ] );
					}
				}
			}
		}else{
			foreach ( $rates as $rate_key => $rate ) {
				if ( 'free_shipping' === $rate->method_id ) {
					unset( $rates[ $rate_key ] );
				}
			}
		}
		return $rates;
	}
}
