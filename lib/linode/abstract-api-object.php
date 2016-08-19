<?php
/**
 * The Linode Object Framework.
 *
 * @package Box_Spawner
 * @subpackage Linode
 *
 * @since 1.0.0
 */
namespace BoxSpawner\Linode;

/**
 * The API Object class.
 *
 * The base interface for all Linode objects.
 *
 * @internal Extended by other Linode object classes.
 *
 * @since 1.0.0
 */
abstract class API_Object extends \BoxSpawner\API_Object {
	/**
	 * Create a new object or retrieve and existing one.
	 *
	 * @since 1.0.0
	 *
	 * @param int|string $id   Either an ID of an existing one, or NULL to create one.
	 * @param int|array  $data Optional Either the attributes for an already fetched one, or the options for creating one.
	 */
	public function __construct( $id, array $data = array() ) {
		// If $id is NULL, create a new one
		if ( is_null( $id ) ) {
			// Perform the create request, get the resulting ID
			$id = $this->create( $data );
			unset( $data );
		}

		// Store the ID
		$this->id = $id;

		// If no $data is present, fetch
		if ( empty( $data ) ) {
			$data = static::fetch( $id );
		}

		// Store the attributes
		$this->attributes = $data;
	}

	/**
	 * Perform a particular action request.
	 *
	 * Uses the class name for the fully formed action name.
	 *
	 * @since 1.0.0
	 *
	 * @param string $action The action to perform.
	 * @param array  $data   Optional The data for the request.
	 *
	 * @return mixed The result of the request.
	 */
	protected static function call_api( $action, array $data = array() ) {
		// If the full action name isn't specified, figure it out
		if ( strpos( $action, '.' ) === false ) {
			// Figure out the name of the object class this was called from
			$object = substr( get_called_class(), strlen( __NAMESPACE__ ) + 1 );

			$action = strtolower( str_replace( '_' , '.', $object ) ) . '.' . $action;
		}

		return API::request( $action, $data );
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
		return static::call_api( 'list', $filter );
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
		$options[ static::ID_ATTRIBUTE ] = $id;

		$result = static::call_api( 'list', $options );

		return $result[0];
	}
}
