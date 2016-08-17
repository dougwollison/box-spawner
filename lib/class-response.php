<?php
/**
 * The Response Object.
 *
 * @package Box_Spawner
 * @subpackage Framework
 *
 * @since 1.0.0
 */

namespace BoxSpawner;

/**
 * The Response class.
 *
 * An interface for handling the result of cURL requests.
 *
 * @api
 *
 * @since 1.0.0
 */
class Response {
	/**
	 * The original request object.
	 *
	 * @since 1.0.0
	 *
	 * @var BoxSpawner\Request
	 */
	protected $request;

	/**
	 * The class constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param BoxSpawner\Request $request The (executed) cURL request object.
	 */
	public function __construct( Request $request ) {
		$this->request = $request;
	}

	/**
	 * Get the results of the request.
	 *
	 * Return an array contianing all headers (parsed) and the body.
	 *
	 * @since 1.0.0
	 */
	public function result() {
		$handler = $this->request->get_curl_handle();
		$response = $this->request->get_curl_result();

		$header_size = curl_getinfo( $handler, CURLINFO_HEADER_SIZE );
		$headers = explode( "\r\n", substr( $response, 0, $header_size ) );
		$body = substr( $response, $header_size );

		return array(
			'headers' => $headers,
			'body'    => $body,
		);
	}
}