<?php
/**
 * The REST Creatable Framework.
 *
 * @package Box_Spawner
 * @subpackage Framework
 *
 * @since 1.0.0
 */
namespace BoxSpawner;

/**
 * The REST Creatable trait.
 *
 * @internal Used by REST objects that support the standard create request.
 *
 * @since 1.0.0
 */
trait REST_Creatable {
	/**
	 * Create a new object.
	 *
	 * @since 1.0.0
	 *
	 * @param array $options A hash of options for the create request.
	 *
	 * @return int|string The ID of the new object.
	 */
	public function create( array $options = array() ) {
		return static::call_api( static::ENDPOINT_FORMAT, $options, 'POST' );
	}
}
