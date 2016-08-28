<?php
/**
 * The Linode API (version 3).
 *
 * @package Box_Spawner
 * @subpackage Linode
 *
 * @since 1.0.0
 */
namespace BoxSpawner\Linode\V3;

/**
 * The API class.
 *
 * The interface for all API requests.
 *
 * @api
 *
 * @since 1.0.0
 */
class API extends \BoxSpawner\JSON_API {
	/**
	 * The API key for all requests.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $api_key;

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
		$this->api_key   = $options['api_key'];
	}

	/**
	 * Make the request, return it's result.
	 *
	 * @since 1.0.0
	 *
	 * @param string $action The api_action
	 * @param array  $data   The data of the request.
	 *
	 * @return mixed The result of the request.
	 */
	public function request( $endpoint, array $data = array() ) {
		$data['api_action'] = $endpoint;

		return parent::request( $data );
	}

	/**
	 * Set the URL of the request.
	 *
	 * @since 1.0.0
	 *
	 * @param resource     $curl    The cURL handle of the request.
	 */
	protected function get_request_url( $data, $options ) {
		return 'https://api.linode.com/?' . http_build_query( $data );
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
	protected function get_request_headers( $data, $options ) {
		$headers = parent::get_request_headers( $data, $options );

		$headers[] = 'Authorization: Basic ' . base64_encode( "api_key:{$this->api_key}" );

		return $headers;
	}

	/**
	 * Determine the body of the request.
	 *
	 * @since 1.0.0
	 *
	 * @param string|array $data    The data to send in the request.
	 * @param array        $options The options of the request.
	 *
	 * @return string|array The body for the request.
	 */
	protected function get_request_body( $data, $options ) {
		return null;
	}

	/**
	 * Parse the results.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed        $result  The result of the request.
	 * @param resource     $curl    The cURL handle of the request.
	 * @param string|array $data    The data to send in the request. (unused)
	 * @param array        $options The options of the request. (unused)
	 *
	 * @throws InvalidResponseException If the result is missing the DATA section.
	 * @throws ErrorResponseException   If the API returned an error.
	 *
	 * @return mixed The parsed result.
	 *     @option array  "headers" The list of response headers.
	 *     @option string "body"    The body of the response.
	 */
	protected function parse_result( $result, $curl, $data, $options ) {
		// Get the result
		$result = parent::parse_result( $result, $curl, $data, $options );

		$json = $result['body'];

		if ( ! isset( $json['DATA'] ) ) {
			throw new \BoxSpawner\InvalidResponseException( 'Unrecognized response format. "DATA" entry should be present.' );
		}

		if ( isset( $json['ERRORARRAY'] ) && count( $json['ERRORARRAY'] ) > 0 ) {
			$error = $json['ERRORARRAY'][0];
			if ( $error['ERRORCODE'] !== 0 ) {
				throw new \BoxSpawner\ErrorResponseException( 'Linode API Error: ' . $error['ERRORMESSAGE'] );
			}
		}

		// Get the response data
		$response = $json['DATA'];

		// Standardize the keys of the DATA entries if needed
		if ( $json['ACTION'] == 'linode.config.list' ) {
			$formatted = array();
			foreach ( $response as $entry ) {
				// Convert all keys to uppercase
				$keys = array_map( 'strtoupper', array_keys( $entry ) );
				$values = array_values( $entry );

				$formatted[] = array_combine( $keys, $values );
			}
			$response = $formatted;
		}

		return $response;
	}
}
