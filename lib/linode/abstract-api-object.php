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
abstract class API_Object extends API {
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
		// Figure out the name of the object class this was called from
		$object = substr( get_called_class(), strlen( __NAMESPACE__ ) + 1 );

		$action = strtolower( str_replace( '_' , '.', $object ) ) . '.' . $action;

		return parent::request( $action, $data );
	}
}
