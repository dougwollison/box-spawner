<?php
/**
 * The CloudFlare API.
 *
 * @package Box_Spawner
 * @subpackage CloudFlare
 *
 * @since 1.0.0
 */
namespace BoxSpawner\CloudFlare;

/**
 * The API class.
 *
 * The base interface for all CloudFlare API handling.
 *
 * @internal Extended by other CloudFlare classes.
 *
 * @since 1.0.0
 */
abstract class API extends \BoxSpawner\API {
	/**
	 * The API email for all requests.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected static $api_email;

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
	 * @param string $email The API email to set with.
	 * @param string $key   The API key to set with.
	 */
	public static function set_auth( $email, $key ) {
		static::$api_email = $email;
		static::$api_key   = $key;
	}

	/**
	 * The main cURL request method.
	 *
	 * Adds the X-Auth-* headers.
	 *
	 * @since 1.0.0
	 *
	 * @see BoxSpawner\API::request() for parameter details.
	 */
	public static function request( $endpoint, array $data = array(), array $headers = array(), $method = 'GET' ) {
		$headers = array(
			'X-Auth-Email: ' . static::$api_email,
			'X-Auth-Key: '   . static::$api_key,
		);

		return parent::request( $endpoint, $data, $headers, $method );
	}
}
