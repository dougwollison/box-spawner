<?php
/**
 * The Linode API.
 *
 * @package Box_Spawner
 * @subpackage Linode
 *
 * @since 1.0.0
 */
namespace BoxSpawner\Linode;

/**
 * The API class.
 *
 * The base interface for all Linode API handling.
 *
 * @internal Extended by other Linode classes.
 *
 * @since 1.0.0
 */
abstract class API extends \BoxSpawner\API {
	/**
	 * The API key for all requests.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected static $api_key;

	/**
	 * Set the API key.
	 *
	 * @api
	 *
	 * @since 1.0.0
	 *
	 * @param string $key The API key to set with.
	 */
	public static function set_api_key( $key ) {
		static::$api_key = $key;
	}

	/**
	 * The main cURL request method.
	 *
	 * Inserts the api_key into the $data array.
	 *
	 * @since 1.0.0
	 *
	 * @see BoxSpawner\API::request() for parameter details.
	 */
	protected static function request( $endpoint, array $data = array() ) {
		$data['api_key'] = static::$api_key;

		return parent::request( $endpoint, $data );
	}
}
