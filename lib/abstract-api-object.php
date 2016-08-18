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
	 * @param int|string $id   Either an ID of an existing one, or NULL to create one.
	 * @param int|array  $data Either the attributes for an already fetched one, or the options for creating one.
	 */
	public function __construct( $id, array $data = array() ) {
		// If $id is NULL, create a new one
		if ( is_null( $id ) ) {
			// Replace $id with the result and empty $data
			$id = $this->create( $data );
			$data = array();
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
}
