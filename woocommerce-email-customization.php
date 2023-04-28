<?php
/*
Plugin Name: Woocommerce email customization
Description: Customize WooCommerce Email Templates
Version: 1.0
Author: Dipin Bajracharya
Author URI: https://github.com/dipin-bajra/
*/
defined( 'ABSPATH' ) or die( 'lol' );
function custom_woocommerce_email_template_override( $template, $template_name, $template_path ) {

    // Look in plugin/woocommerce/emails/custom-header.php
    if ( $template_name == 'emails/email-header.php' ) {
        $template = plugin_dir_path( __FILE__ ) . 'templates/email-header.php';
    }

    // Look in plugin/woocommerce/emails/custom-footer.php
    if ( $template_name == 'emails/email-footer.php' ) {
        $template = plugin_dir_path( __FILE__ ) . 'templates/email-footer.php';
    }

    if ( $template_name == 'emails/email-styles.php'){
        $template =plugin_dir_path( __FILE__) . 'templates/email-styles.php';
    }

    return $template;
}

add_filter( 'woocommerce_locate_template', 'custom_woocommerce_email_template_override', 10, 3 );

if ( ! defined( 'CODEMANAS_WOOMAIL_VERSION' ) ) {
    define( 'CODEMANAS_WOOMAIL_VERSION', '1.0.0' );
}

if ( ! defined( 'CODEMANAS_WOOMAIL_FILE_PATH' ) ) {
    define( 'CODEMANAS_WOOMAIL_FILE_PATH', __FILE__ );
}

if ( ! defined( 'CODEMANAS_WOOMAIL_ROOT_DIR_PATH' ) ) {
    define( 'CODEMANAS_WOOMAIL_ROOT_DIR_PATH', DIRNAME( __FILE__ ) );
}

if ( ! defined( 'CODEMANAS_WOOMAIL_ROOT_URI_PATH' ) ) {
    define( 'CODEMANAS_WOOMAIL_ROOT_URI_PATH', plugin_dir_url( __FILE__ ) );
}

if ( ! defined( 'CODEMANAS_WOOMAIL_BASE_FILE' ) ) {
    define( 'CODEMANAS_WOOMAIL_BASE_FILE', plugin_basename( __FILE__ ) );
}

require_once CODEMANAS_WOOMAIL_ROOT_DIR_PATH . '/inc/Bootstrap.php';







