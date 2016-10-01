<?php
/**
 * The API Object Framework.
 *
 * @package Box_Spawner
 * @subpackage Framework
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
	const ID_ATTRIBUTE = 'id';

	/**
	 * The API the object uses.
	 *
	 * @since 1.0.0
	 *
	 * @var BoxSpawner\API
	 */
	protected $api;

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
	 * @param BoxSpawner\API $api  The API to use for this object.
	 * @param int|string     $id   Either an ID of an existing one, or NULL to create one.
	 * @param int|array      $data Either the attributes for an already fetched one, or the options for creating one.
	 */
	public function __construct( API $api, $id, array $data = array() ) {
		// Store the API
		$this->api = $api;

		if ( is_null( $id ) ) {
			$this->create( $data );
		} elseif ( empty( $data ) ) {
			$this->load( $id );
		} else {
			$this->id = $id;
			$this->attributes = $data;
		}
	}

	/**
	 * Issue a create request for a new object.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data The data for the create request.
	 */
	public function create( array $data ) {
		throw new NotSupportedException( sprintf( 'The class "%s" does not support create requests.', get_class( $this ) ) );
	}

	/**
	 * Issue a load request for a new object.
	 *
	 * @since 1.0.0
	 *
	 * @param string $id The id of the object to request.
	 */
	public function load( $id ) {
		throw new NotSupportedException( sprintf( 'The class "%s" does not support load requests.', get_class( $this ) ) );
	}

	/**
	 * Issue a update request for a new object.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data The data for the update request.
	 */
	public function update( array $data ) {
		throw new NotSupportedException( sprintf( 'The class "%s" does not support update requests.', get_class( $this ) ) );
	}

	/**
	 * Issue a delete request for a new object.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data The data for the delete request.
	 */
	public function delete( array $data ) {
		throw new NotSupportedException( sprintf( 'The class "%s" does not support delete requests.', get_class( $this ) ) );
	}
}
