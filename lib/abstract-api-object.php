<?php
/**
 * The API Object Framework.
 *
 * @package Box_Spawner
 * @subpackage DigitalOcean
 *
 * @since 1.0.0
 */
namespace BoxSpawner;

/**
 * The API Object class.
 *
 * The basis for all API_Object classes.
 *
 * @internal Extended by other vendor-specific API_Object classes.
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
}
