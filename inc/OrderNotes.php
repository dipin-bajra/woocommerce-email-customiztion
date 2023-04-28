<?php

namespace Codemanas\Woomail;

use Codemanas\ZoomWooCommerceAddon\Orders;

class OrderNotes
{
    public static ?OrderNotes $instance = null;

    public static function get_instance(): ?OrderNotes {
        return is_null( self::$instance ) ? self::$instance = new self() : self::$instance;
    }

    public function __construct() {
        add_action( 'init', array( $this, 'hooks' ) );
    }

    public function hooks() {
        //Add order notes if the product is a zoom meeting product
        add_action( 'woocommerce_thankyou', array($this, 'add_order_note_zoom_meeting_created') );
    }

    function add_order_note_zoom_meeting_created( $order_id ) {
        $order = wc_get_order( $order_id );
        $items = $order->get_items();

        foreach( $items as $item ) {
            $product_id = $item->get_product_id();
            $product = wc_get_product( $product_id );

            if ( $product->is_type( 'simple' ) && $product->get_meta( '_vczapi_enable_zoom_link' ) == 'yes' ) {
                // Get the Zoom meeting ID from the order item meta data
                $zoom_meeting_id = $product-> get_meta( '_vczapi_wc_recording_host' );
                var_dump($zoom_meeting_id);
                // Add the Zoom meeting ID to the order note
                $order_note = 'Zoom meeting created with ID' . $zoom_meeting_id;
                $order->add_order_note( $order_note );
                break;
            }
        }
    }
}