<?php
/**
 * The REST Object Framework.
 *
 * @package Box_Spawner
 * @subpackage Framework
 *
 * @since 1.0.0
 */
namespace BoxSpawner;

/**
 * The REST Object class.
 *
 * The basis for all API_Object classes.
 *
 * @internal Extended by other vendor-specific API_Object classes.
 *
 * @since 1.0.0
 */
abstract class REST_Object extends API_Object {
	/**
	 * The endpoint template.
	 *
	 * Replaces :[key] instances with matching attributes.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	const ENDPOINT_FORMAT = '';

	/**
	 * Perform a particular action request.
	 *
	 * Uses the class name for the fully formed action name.
	 *
	 * @since 1.0.0
	 *
	 * @param string $action The action to perform.
	 * @param array  $data   The data for the request.
	 * @param string $method Optional The request method to use.
	 *
	 * @return mixed The result of the request.
	 */
	protected static function call_api( $endpoint, array $data = array(), $method = 'GET' ) {
		// Process the endpoint, placing the arguments in matching placeholders
		foreach ( $data as $key => $value ) {
			if ( strpos( $endpoint, ":{$key}" ) !== false ) {
				$endpoint = str_replace( ":{$key}", $value, $endpoint );
			}
		}

		// Get it's namespace
		$namespace = preg_replace( '/\\\(\w+)$/', '', get_called_class() );

		// Get the API class to use
		$api_class = $namespace . '\\API';

		return $api_class::request( $endpoint, $data, array(), $method );
	}

	/**
	 * List all existing objects.
	 *
	 * @since 1.0.0
	 *
	 * @param array $filter A hash of options for the list request.
	 *
	 * @return array The list of objects.
	 */
	public static function all( array $filter = array() ) {
		return static::call_api( static::ENDPOINT_FORMAT, $filter );
	}

	/**
	 * Get a specific object.
	 *
	 * @since 1.0.0
	 *
	 * @param int|string $id      The ID of the object being fetched.
	 * @param array      $options Optional A hash of options for the get request.
	 *
	 * @return int|string The ID of the new object.
	 */
	public static function fetch( $id, array $options = array() ) {
		$endpoint = static::ENDPOINT_FORMAT . '/' . $id;
		return static::call_api( $endpoint, $options );
	}
}
