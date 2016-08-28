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
	 * Set the headers of the request.
	 *
	 * @since 1.0.0
	 *
	 * @param resource     $curl    The cURL handle of the request.
	 * @param array        $options The options of the request.
	 * @param string|array $data    The data to send in the request. (unused)
	 */
	protected function set_request_headers( $curl, $options, $data ) {
		$headers = array(
			'X-Auth-Email: ' . $this->api_email,
			'X-Auth-Key: '   . $this->api_key,
		);

		if ( isset( $options['headers'] ) ) {
			foreach ( $options['headers'] as $header => $value ) {
				if ( is_int( $header ) ) {
					$headers[] = $value;
				} else {
					$headers[] = "$header: $value";
				}
			}
		}

		curl_setopt( $curl, CURLOPT_HTTPHEADER, $headers );
	}
}
