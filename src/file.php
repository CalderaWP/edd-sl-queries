<?php


namespace CalderaWP\EDD\SL;


trait file {

	/**
	 * Get a download file for license/ID
	 *
	 * @param int $download_id
	 * @param int $license_id
	 *
	 * @return string
	 */
	public function get_file_by_license_id( $download_id, $license_id ){
		$payment_id = \EDD_Software_Licensing::instance()->get_payment_id( $license_id );
		$payment_key = edd_get_payment_key( $payment_id );
		$file_key  = get_post_meta( $download_id, '_edd_sl_upgrade_file_key', true );
		$email       = edd_get_payment_user_email( $payment_id );

		$file = edd_get_download_file_url( $payment_key, $email, $file_key, $download_id );

		return $file;
	}
}