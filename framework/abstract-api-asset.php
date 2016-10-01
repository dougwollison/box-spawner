<?php
/**
 * The API Asset Framework.
 *
 * @package Box_Spawner
 * @subpackage Framework
 *
 * @since 1.0.0
 */
namespace BoxSpawner;

/**
 * The API Asset class.
 *
 * The basis for all API_Asset classes; API_Objects with parent relations.
 *
 * @internal Extended by other vendor-specific API_Asset classes.
 *
 * @since 1.0.0
 */
abstract class API_Asset extends API_Object {
	/**
	 * The ID of the object.
	 *
	 * @since 1.0.0
	 *
	 * @var int
	 */
	public $parent;

	/**
	 * Create a new object or retrieve and existing one.
	 *
	 * @since 1.0.0
	 *
	 * @param BoxSpawner\API        $api    The API to use for this object.
	 * @param int|string            $id     Either an ID of an existing one, or NULL to create one.
	 * @param int|array             $data   Either the attributes for an already fetched one, or the options for creating one.
	 * @param BoxSpawner\API_Object $parent The parent object to tie to.
	 */
	public function __construct( API $api, $id, array $data = array(), $parent = null ) {
		// Store the parent
		$this->parent = $parent;

		parent::__construct( $api, $id, $data );
	}
}