<?php

class MC4WP_Forms_Listener {

	/**
	 * @param $data
	 *
	 * @return bool
	 */
	public function listen( array $data ) {

		// is form submitted?
		if( ! isset( $_POST['_mc4wp_form_submit'] ) ) {
			return false;
		}

		$request = new MC4WP_Form_Request( $data );
		$this->process( $request );

		return true;
	}

	/**
	 * @param MC4WP_Form_Request $request
	 *
	 * @return bool
	 */
	public function process( MC4WP_Form_Request $request ) {

		$valid = $request->validate();

		$success = false;

		if( $valid ) {

			// prepare request data
			$ready = $request->prepare();

			// if request is ready, send an API call to MailChimp
			if( $ready ) {
				$success = $request->process();
			}
		}

		$request->respond();

		return $success;
	}

}