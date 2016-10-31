<?php
namespace CalderaWP\EDD\SL;

class sites {
  /**
	 * @var array
	 */
	protected $results = [];


	/**
	 * Get all sites with licensed downloads
	 *
	 * @since 0.1.0
	 *
	 * @return array
	 */
	public  function get_all(){
		global $wpdb;
		$r = $wpdb->get_results( "SELECT `meta_value` FROM $wpdb->postmeta WHERE `meta_key` = '_edd_sl_sites'", ARRAY_A );
		return $this->format_results( $r );

	}

	/**
	 * Get all sites with a specific downland activated
	 *
	 * @param int $download_id
	 *
	 * @return array
	 */
	public function get_by_download( int $download_id ) : array
	{
		global $wpdb;
		$licenses = $wpdb->get_results( sprintf( "SELECT `post_id` FROM $wpdb->postmeta WHERE `meta_key` = '_edd_sl_download_id' AND `meta_value` = %d ", $download_id ), ARRAY_A );
		return $this->get_sites_by_licenses( $licenses );

	}

	/**
	 * Get all sites that a user has activated licenses on
	 * @param int $user_id
	 *
	 * @return array
	 */
	public function get_user_sites( int $user_id ) : array
	{
		global $wpdb;
		$licenses = $wpdb->get_results( sprintf( "SELECT `post_id` FROM $wpdb->postmeta WHERE `meta_key` = 	'_edd_sl_user_id' AND `meta_value` = %d ", $user_id ), ARRAY_A );
		return $this->get_sites_by_licenses( $licenses );
	}

	protected function get_sites_by_licenses( array  $licenses)
	{
		global $wpdb;
		if( ! empty( $licenses )){
			$in = implode( ',', wp_list_pluck( $licenses, 'post_id' ) );
			$sites = $wpdb->get_results( sprintf( "SELECT `meta_value` FROM $wpdb->postmeta WHERE `meta_key` = '_edd_sl_sites' AND `post_id` IN(%s) ", $in), ARRAY_A );
			if( ! empty( $sites ) ){
				return $this->format_results( $sites );
			}
		}

		return null;
	}

	/**
	 * Turn serialized meta_values into array
	 *
	 * @param array $result
	 *
	 * @return array
	 */
	protected function format_results( array  $result ) : array
	{
		$values = wp_list_pluck( $result, 'meta_value' );
		array_walk( $values, function( $value, $key  ){
			$sites = maybe_unserialize( $value );
			if( ! empty( $sites ) ){
				$this->results = array_merge( $this->results, $sites );
			}
		});

		return $this->results;
	}

}
