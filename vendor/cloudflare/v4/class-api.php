<?php
/**
 * The CloudFlare API (version 4).
 *
 * @package Box_Spawner
 * @subpackage CloudFlare
 *
 * @since 1.0.0
 */
namespace BoxSpawner\CloudFlare\V4;

/**
 * The API class.
 *
 * The interface for all API requests.
 *
 * @api
 *
 * @since 1.0.0
 */
class API extends \BoxSpawner\API {
	/**
	 * The base endpoint URL.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	const ENDPOINT_BASE = 'https://api.cloudflare.com/client/v4/';

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
	 * The constructor.
	 *
	 * Sets the authentication credentials.
	 *
	 * @since 1.0.0
	 *
	 * @param string|array $options An API key or other options.
	 * @param int|string   $version Optional
	 */
	public function __construct( array $options ) {
		$this->api_email = $options['api_email'];
		$this->api_key   = $options['api_key'];
	}

	/**
	 * Determine the headers of the request.
	 *
	 * @since 1.0.0
	 *
	 * @param string|array $data    The data to send in the request.
	 * @param array        $options The options of the request.
	 *
	 * @return array The headers for the request.
	 */
	protected function get_request_headers( $curl, $options, $data ) {
		$headers = parent::get_request_headers( $data, $options );

		$headers[] = 'X-Auth-Email: ' . $this->api_email;
		$headers[] = 'X-Auth-Key: '   . $this->api_key;

		return $headers;
	}
}
