<?php
/**
 * Created by PhpStorm.
 * User: josh
 * Date: 12/12/16
 * Time: 10:18 PM
 */

namespace CalderaWP\EDD\SL;


class user {

	/**
	 * Get all licensed add-ons for a user
	 *
	 * @param null|int $user_id Optional. User ID, current user ID if mull
	 * @param bool $include_expired Optional. If false the default, expired licenses will be skipped.
	 *
	 * @return bool|array Array of download_id => download title or false if none found.
	 */
	function downloads_by_licensed_user( $user_id = null, $include_expired = false ) {
		if ( is_null( $user_id ) ){
			$user_id = get_current_user_id();
		}

		$licensed_downloads = false;
		if ( 0 < absint( $user_id ) ) {
			global $wpdb;
			$query = $wpdb->prepare( 'SELECT `post_id` FROM `%2s` WHERE `meta_value` = %d AND `meta_key` = "_edd_sl_user_id"', $wpdb->postmeta, $user_id );
			$licenses = $wpdb->get_results( $query, ARRAY_A );

			if ( ! empty( $licenses ) ) {
				foreach( $licenses as $license ) {
					if ( ! $include_expired ) {
						$status = get_post_meta( $license[ 'post_id' ], '_edd_sl_status', true );
						if ( false ==  $status ) {
							continue;
						}

					}
					$id = get_post_meta( $license[ 'post_id'], '_edd_sl_download_id', true );
					if ( $id ) {
						$licensed_downloads[$id] = get_the_title( $id );
					}

				}

			}

		}

		return $licensed_downloads;

	}


}