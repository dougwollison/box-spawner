<?php
/**
 * The root API Framework.
 *
 * @package Box_Spawner
 * @subpackage Framework
 *
 * @since 1.0.0
 */
namespace BoxSpawner;

/**
 * The API class.
 *
 * The base for all API classes.
 *
 * @internal Extended by other API classes.
 *
 * @since 1.0.0
 */
abstract class API {
	/**
	 * The base URL of all endpoints.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	const ENDPOINT_BASE = '';

	/**
	 * The configuration options (e.g. API key)
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	protected $options = array();

	/**
	 * The constructor.
	 *
	 * Initializes the API, taking a version number and options.
	 *
	 * @since 1.0.0
	 *
	 * @param string|array $options An API key or other options.
	 * @param int|string   $version Optional
	 */
	public function __construct( array $options ) {
		$this->options = $options;
	}

	/**
	 * Make the request, return it's result.
	 *
	 * @since 1.0.0
	 *
	 * @param string|array $data    The data to send in the request.
	 * @param array        $options The options of the request.
	 *
	 * @return mixed The result of the request.
	 */
	public function request( $data, array $options = array() ) {
		// Initialize a cURL handle
		$curl = curl_init();

		// Set the URL for the request
		$this->set_request_url( $curl, $options, $data );

		// Set the method for the request
		$this->set_request_method( $curl, $options, $data );

		// Set the headers for the request
		$this->set_request_headers( $curl, $options, $data );

		// Set the body for the request
		$this->set_request_body( $curl, $options, $data );

		// Set any additional options for the request
		$this->set_request_options( $curl, $options, $data );

		// Get the result
		$result = curl_exec( $curl );

		// Parse the result
		$result = $this->parse_result( $result, $curl, $options, $data );

		// We're done here
		curl_close( $curl );

		return $result;
	}

	/**
	 * Set the URL of the request.
	 *
	 * @since 1.0.0
	 *
	 * @param resource     $curl    The cURL handle of the request.
	 * @param array        $options The options of the request.
	 * @param string|array $data    The data to send in the request. (unused)
	 */
	protected function set_request_url( $curl, $options, $data ) {
		$url = static::ENDPOINT_BASE;

		if ( isset( $options['endpoint'] ) ) {
			$url .= $options['endpoint'];
		}

		curl_setopt( $curl, CURLOPT_URL, $url );
	}

	/**
	 * Set the method of the request.
	 *
	 * @since 1.0.0
	 *
	 * @param resource     $curl    The cURL handle of the request.
	 * @param array        $options The options of the request.
	 * @param string|array $data    The data to send in the request. (unused)
	 */
	protected function set_request_method( $curl, $options, $data ) {
		$method = 'GET';

		if ( isset( $options['method'] ) ) {
			$method = $options['method'];
		}

		curl_setopt( $curl, CURLOPT_CUSTOMREQUEST, $method );
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
		$headers = array();

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

	/**
	 * Set the body of the request.
	 *
	 * @since 1.0.0
	 *
	 * @param resource     $curl    The cURL handle of the request.
	 * @param array        $options The options of the request. (unused)
	 * @param string|array $data    The data to send in the request.
	 */
	protected function set_request_body( $curl, $options, $data ) {
		$body = $this->prepare_request_body( $data, $options, $curl );

		curl_setopt( $curl, CURLOPT_POSTFIELDS, $body );
	}

	/**
	 * Prepare the body of the request.
	 *
	 * @since 1.0.0
	 *
	 * @param string|array $data    The data to prepare as the body.
	 * @param resource     $curl    The cURL handle of the request.
	 * @param array        $options The options of the request.
	 *
	 * @return string|array The prepared body.
	 */
	protected function prepare_request_body( $data, $options, $curl ) {
		return $data;
	}

	/**
	 * Set the misc options of the request.
	 *
	 * @since 1.0.0
	 *
	 * @param resource     $curl    The cURL handle of the request.
	 * @param array        $options The options of the request.
	 * @param string|array $data    The data to prepare as the body. (unused)
	 */
	protected function set_request_options( $curl, $options, $data ) {
		$curl_options = array(
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_VERBOSE        => true,
			CURLOPT_HEADER         => true,
		);

		if ( isset( $options['curl'] ) ) {
			$curl_options = array_merge( $curl_options, $options['curl'] );
		}

		curl_setopt_array( $curl, $curl_options );
	}

	/**
	 * Parse the results.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed        $result  The result of the request.
	 * @param resource     $curl    The cURL handle of the request.
	 * @param array        $options The options of the request. (unused)
	 * @param string|array $data    The data to send in the request. (unused)
	 *
	 * @return mixed The parsed result.
	 *     @option array  "headers" The list of response headers.
	 *     @option string "body"    The body of the response.
	 */
	protected function parse_result( $result, $curl, $options, $data ) {
		// Throw exception if there was an error
		if ( $result === false ) {
			throw new Exception( 'cURL Error: ' . curl_error( $curl ) );
		}

		// Separate the headers and body
		$header_size = curl_getinfo( $curl, CURLINFO_HEADER_SIZE );
		$headers = substr( $result, 0, $header_size );
		$body = substr( $result, $header_size );

		// Convert headers to an array
		$headers = explode( "\r\n", $headers );

		return array(
			'headers' => $headers,
			'body'    => $body,
		);
	}
}
