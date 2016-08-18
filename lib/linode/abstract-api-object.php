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
abstract class API_Object {
	/**
	 * The name of the option to assign the ID to.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	const ID_ATTRIBUTE = '';

	/**
	 * The ID of the object.
	 *
	 * @since 1.0.0
	 *
	 * @var int
	 */
	public $id;

	/**
	 * The attributes of the object.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	public $attributes;

	/**
	 * Create a new object or retrieve and existing one.
	 *
	 * @since 1.0.0
	 *
	 * @param int|array $data Either an the ID of an existing object, or settings to create one with.
	 */
	public function __construct( $data ) {
		if ( is_array( $data ) ) {
			// Create a new one, replacing $data with the ID
			$data = static::create( $data );
		}

		// Store the ID
		$this->id = $data;

		// Fetch the attributes and store
		$this->attributes = static::fetch( $data );
	}

	/**
	 * Perform a particular action request.
	 *
	 * Uses the class name for the fully formed action name.
	 *
	 * @since 1.0.0
	 *
	 * @param string $action The action to perform.
	 * @param array  $data   The data for the request.
	 *
	 * @return mixed The result of the request.
	 */
	protected static function request( $action, array $data = array() ) {
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
		return static::request( 'list', $filter );
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

		$result = static::request( 'list', $options );

		return $result[0];
	}
}
