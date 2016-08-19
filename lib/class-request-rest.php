<?php
/**
 * The REST Request Object.
 *
 * @package Box_Spawner
 * @subpackage Framework
 *
 * @since 1.0.0
 */

namespace BoxSpawner;

/**
 * The REST Request class.
 *
 * An interface for creating cURL requests for RESTful APIs.
 *
 * @api
 *
 * @since 1.0.0
 */
class Request {
	/**
	 * The class constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param string $action The specific action/endpoint for the request.
	 * @param array  $data   Optional The data to send in the request.
	 * @param string $method Optional The HTTP method to use for the request (defaults to "GET").
	 */
	public function __construct( $endpoint, array $data = array(), $method = 'GET' ) {
		$this->endpoint = $endpoint;
		$this->data     = $data;
		$this->method   = strtoupper( $method ); // ensure method is uppercase
		$this->handle   = curl_init();

		$this->setup();

		$this->result = curl_exec( $this->handle );
	}

	/**
	 * Set the options on the cURL handle.
	 *
	 * @since 1.0.0
	 */
	protected function setup() {
		// Set the request method
		$this->options[ CURLOPT_CUSTOMREQUEST ] = $this->method;

		parent::setup();
	}
}
