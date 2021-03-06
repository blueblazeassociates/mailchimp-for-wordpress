<?php

class MC4WP_WooCommerce_Integration extends MC4WP_Integration_Base {

	public $type = 'woocommerce';
	public $name = 'WooCommerce';
	public $default_options = array(
		'position' => 'order'
	);

	/**
	 * Add hooks
	 */
	public function add_hooks() {
		add_filter( 'woocommerce_checkout_fields', array( $this, 'add_checkout_field' ), 20 );

		add_action( 'woocommerce_checkout_update_order_meta', array( $this, 'save_woocommerce_checkout_checkbox_value' ) );
		add_action( 'woocommerce_checkout_order_processed', array( $this, 'subscribe_from_woocommerce_checkout' ) );
	}

	/**
	 * @param $fields
	 *
	 * @return mixed
	 */
	public function add_checkout_field( $fields ) {

		$default = ( $this->is_prechecked() ) ? 1 : 0;
		$label = $this->get_label_text();
		$position = $this->options['woocommerce_position'];

		$fields[ $position ]['_mc4wp_subscribe_woocommerce_checkout'] = array(
			'type'    => 'checkbox',
			'label'   => $label,
			'default' => $default,
		);

		return $fields;
	}

	/**
	* @param int $order_id
	*/
	public function save_woocommerce_checkout_checkbox_value( $order_id ) {
		update_post_meta( $order_id, '_mc4wp_optin', $this->checkbox_was_checked() );
	}

	/**
	* @param int $order_id
	* @return boolean
	*/
	public function subscribe_from_woocommerce_checkout( $order_id ) {

		$do_optin = get_post_meta( $order_id, '_mc4wp_optin', true );

		if( $do_optin ) {

			$order = new WC_Order( $order_id );
			$email = $order->billing_email;
			$merge_vars = array(
				'NAME' => sprintf( '%s %s', $order->billing_first_name, $order->billing_last_name ),
				'FNAME' => $order->billing_first_name,
				'LNAME' => $order->billing_last_name,

			);

			return $this->subscribe( $email, $merge_vars, $this->type, $order_id );
		}

		return false;
	}

}