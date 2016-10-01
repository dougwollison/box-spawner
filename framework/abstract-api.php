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
	 * Internal instance-specific cache.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	protected $cache = array();

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

		$curl_options = array();

		// Get the URL for the request
		$curl_options[ CURLOPT_URL ] = $this->get_request_url( $data, $options );

		// Get the method for the request
		$curl_options[ CURLOPT_CUSTOMREQUEST ] = $this->get_request_method( $data, $options );

		// Get the headers for the request
		$curl_options[ CURLOPT_HTTPHEADER ] = $this->get_request_headers( $data, $options );

		// Get the body for the request
		$curl_options[ CURLOPT_POSTFIELDS ] = $this->get_request_body( $data, $options );

		// Get any additional options for the request
		$extra_options = $this->get_request_options( $data, $options );

		// Merge them into the main options, set them
		foreach ( $extra_options as $option => $value ) {
			// foreach loop because numeric keys
			$curl_options[ $option ] = $value;
		}

		curl_setopt_array( $curl, $curl_options );

		// Get the result
		$result = curl_exec( $curl );

		// Parse the result
		$result = $this->parse_result( $result, $curl, $data, $options );

		// We're done here
		curl_close( $curl );

		return $result;
	}

	/**
	 * Determine the URL of the request.
	 *
	 * @since 1.0.0
	 *
	 * @param string|array $data    The data to send in the request.
	 * @param array        $options The options of the request.
	 *
	 * @return string The URL for the request.
	 */
	protected function get_request_url( $data, $options ) {
		$url = static::ENDPOINT_BASE;

		if ( isset( $options['endpoint'] ) ) {
			$url .= $options['endpoint'];
		}

		return $url;
	}

	/**
	 * Determine the method of the request.
	 *
	 * @since 1.0.0
	 *
	 * @param string|array $data    The data to send in the request.
	 * @param array        $options The options of the request.
	 *
	 * @return string The method for the request.
	 */
	protected function get_request_method( $data, $options ) {
		$method = 'GET';

		if ( isset( $options['method'] ) ) {
			$method = $options['method'];
		}

		return $method;
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
		$headers = array();

		if ( isset( $options['headers'] ) ) {
			$headers = $options['headers'];
		}

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
		return $data;
	}

	/**
	 * Set the misc options of the request.
	 *
	 * @since 1.0.0
	 *
	 * @param string|array $data    The data to send in the request.
	 * @param array        $options The options of the request.
	 *
	 * @return array The cURL options for the request.
	 */
	protected function get_request_options( $data, $options ) {
		$curl_options = array(
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_VERBOSE        => true,
			CURLOPT_HEADER         => true,
		);

		if ( isset( $options['curl'] ) ) {
			$curl_options = array_merge( $curl_options, $options['curl'] );
		}

		return $curl_options;
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
	 * @throws Exception If there is an error with the cURL request.
	 *
	 * @return mixed The parsed result.
	 *     @option array  "headers" The list of response headers.
	 *     @option string "body"    The body of the response.
	 */
	protected function parse_result( $result, $curl, $data, $options ) {
		// Throw exception if there was an error
		if ( $result === false ) {
			throw new ResourceException( 'cURL Request Error: ' . curl_error( $curl ) );
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
