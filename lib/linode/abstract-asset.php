<?php
/**
 * The Linode Asset Framework.
 *
 * @package Box_Spawner
 * @subpackage Linode
 *
 * @since 1.0.0
 */
namespace BoxSpawner\Linode;

/**
 * The Asset class.
 *
 * The base interface for all Linode object assets.
 *
 * @internal Extended by other Linode asset classes.
 *
 * @since 1.0.0
 */
abstract class Asset extends API_Object {
	/**
	 * The parent object.
	 *
	 * @since 1.0.0
	 *
	 * @var object
	 */
	protected $parent;

	/**
	 * Create a new object or retrieve and existing one.
	 *
	 * @since 1.0.0
	 *
	 * @param int|array $data Either an the ID of an existing object, or settings to create one with.
	 */
	public function __construct( $data, API $parent ) {
		// Store the parent
		$this->parent = $parent;

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
