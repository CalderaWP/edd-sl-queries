<?php

namespace CalderaWP\EDD\SL;


class user {

	/**
	 * @var int
	 */
	protected $user_id;

	/**
	 * user constructor.
	 *
	 * @param null|int $user_id Optional. User ID, current user ID if mull
	 */
	public function __construct( $user_id = null ) {
		if ( is_null( $this->user_id ) ){
			$this->user_id = get_current_user_id();
		}else{
			$this->user_id = $user_id;
		}

	}

	/**
	 * 	Get all licensed add-ons for a user
	 *
	 * @param bool $include_expired
	 * @param bool $with_license If true return is flat array of download_id => download title or false if none found. If false, license details included
	 *
	 * @return bool
	 */
	public function downloads_by_licensed_user(  $include_expired = false, $with_license = false ) {

		$user_id = $this->user_id;


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
						if ( ! $with_license ) {
							$licensed_downloads[ $id ] = get_the_title( $id );
						} else {
							$licensed_downloads[ $id ] = [
								'title' => get_the_title( $id ),
								'license' =>
									[
										'id' => $license[ 'post_id' ],
										'code' => \EDD_Software_Licensing::instance()->get_license_key( $license[ 'post_id' ] )
									],
							];
						}
					}

				}

			}

		}

		return $licensed_downloads;

	}



}