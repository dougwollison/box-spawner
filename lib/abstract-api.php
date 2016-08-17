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
 * The basis for all API classes.
 *
 * @internal Extended by other vendor-specific API classes.
 *
 * @since 1.0.0
 */
abstract class API {
	/**
	 * The name of the class to use for requests.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected static $request_class = 'Request';

	/**
	 * The name of the class to use for responses.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected static $response_class = 'Response';

	/**
	 * The main cURL request method.
	 *
	 * Used to send the request and extract the response.
	 *
	 * @since 1.0.0
	 *
	 * @param string $endpoint The specific endpoint/action for the request.
	 * @param array  $data     Optional The data to send in the request.
	 *
	 * @return array The headers array and body string.
	 */
	protected static function request( $endpoint, array $data = array() ) {
		// Create the request
		$request = new static::$request_class( $endpoint, $data );

		// Create a response from it
		$response = new static::$response_class( $request );

		// Return it's result
		return $response->result();
	}
}
