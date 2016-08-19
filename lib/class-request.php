<?php
/**
 * The Request Object.
 *
 * @package Box_Spawner
 * @subpackage Framework
 *
 * @since 1.0.0
 */

namespace BoxSpawner;

/**
 * The Request class.
 *
 * An interface for creating cURL requests.
 *
 * @api
 *
 * @since 1.0.0
 */
class Request {
	/**
	 * The base endpoint URL.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	const ENDPOINT_BASE = '';

	/**
	 * The action of the request.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $endpoint;

	/**
	 * The data of the request.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	protected $data;

	/**
	 * The cURL handle.
	 *
	 * @since 1.0.0
	 *
	 * @var resource
	 */
	protected $handle;

	/**
	 * The options for curl_setopt_array().
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	protected $options = array(
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_VERBOSE        => true,
		CURLOPT_HEADER         => true,
	);

	/**
	 * The result of curl_exec().
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $result;

	/**
	 * Get the cURL handle.
	 *
	 * @since 1.0.0
	 *
	 * @return resource The cURL handle.
	 */
	public function get_curl_handle() {
		return $this->handle;
	}

	/**
	 * Get the cURL result.
	 *
	 * @since 1.0.0
	 *
	 * @return string The cURL result.
	 */
	public function get_curl_result() {
		return $this->result;
	}

	/**
	 * The class constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param string $action The specific action/endpoint for the request.
	 * @param array  $data   Optional The data to send in the request.
	 */
	public function __construct( $endpoint, array $data = array() ) {
		$this->endpoint = $endpoint;
		$this->data     = $data;
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
		if ( ! isset( $this->options[ CURLOPT_CUSTOMREQUEST ] ) ) {
			$this->options[ CURLOPT_CUSTOMREQUEST ] = 'POST';
		}
		if ( ! isset( $this->options[ CURLOPT_URL ] ) ) {
			$this->options[ CURLOPT_URL ] = static::ENDPOINT_BASE . $this->endpoint;
		}
		if ( ! isset( $this->options[ CURLOPT_POSTFIELDS ] ) ) {
			$this->options[ CURLOPT_POSTFIELDS ] = $this->data;
		}

		curl_setopt_array( $this->handle, $this->options );
	}
}
