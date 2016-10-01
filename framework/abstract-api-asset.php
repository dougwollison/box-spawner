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
	 * The name of the option to assign the parent ID to.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	const PARENT_ID_ATTRIBUTE = 'id';

	/**
	 * The ID of the parent object.
	 *
	 * @since 1.0.0
	 *
	 * @var int
	 */
	public $parent_id;

	/**
	 * Create a new object or retrieve and existing one.
	 *
	 * @since 1.0.0
	 *
	 * @param BoxSpawner\API $api       The API to use for this object.
	 * @param int|string     $id        Either an ID of an existing one, or NULL to create one.
	 * @param int|array      $data      Either the attributes for an already fetched one, or the options for creating one.
	 * @param int|string     $parent_id The parent object to tie to.
	 */
	public function __construct( API $api, $id, array $data = array(), $parent_id = null ) {
		// Store the parent
		if ( ! is_null( $parent_id ) ) {
			$this->parent_id = $parent_id;
		} else if ( isset( $data[ $this::PARENT_ID_ATTRIBUTE ] ) ) {
			$this->parent_id = $data[ $this::PARENT_ID_ATTRIBUTE ];
		}

		parent::__construct( $api, $id, $data );
	}
}