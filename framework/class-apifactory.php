<?php
/**
 * The API Factory.
 *
 * @package Box_Spawner
 * @subpackage Tools
 *
 * @since 1.0.0
 */
namespace BoxSpawner;

/**
 * The API Factory class.
 *
 * Used to instantiate the desired API class.
 *
 * @api
 *
 * @since 1.0.0
 */
class APIFactory {
	/**
	 * Create an instance of the desired API.
	 *
	 * @since 1.0.0
	 *
	 * @param string       $namespace The sub-namespace of the API class.
	 * @param string|array $options   The options for the API class.
	 * @param int|string   $version   Optional The version of the API to use.
	 *
	 * @return BoxSpawner\API The requested API object.
	 */
	public static function create( $namespace, $options, $version = 'stable' ) {
		// Register the vendor if not already
		require_once( BASEDIR . '/vendor/' . strtolower( $namespace ) . '/_register.php' );

		// Create the version flag (for aliases)
		$version_flag = __NAMESPACE__ . '\\' . $namespace . '\\' . $version;
		// If defined, use it
		if ( defined( $version_flag ) ) {
			$version = constant( $version_flag );
		}

		// Build the class name
		$class = array( __NAMESPACE__, $namespace, $version, 'API' );
		$class = implode( '\\', array_filter( $class ) );

		// Create it
		return new $class( $options );
	}
}
