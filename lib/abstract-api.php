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
		// Get the calling class' name
		$source = get_called_class();

		// Get it's namespace
		$namespace = preg_replace( '/\\\(\w+)$/', '', $source );

		// Create the request/response class names
		$request_class = $namespace . '\\Request';
		$response_class = $namespace . '\\Response';

		// Create the request
		$request = new $request_class( $endpoint, $data );

		// Create a response from it
		$response = new $response_class( $request );

		// Return it's result
		return $response->result();
	}
}
