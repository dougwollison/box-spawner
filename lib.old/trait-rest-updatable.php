<?php
/**
 * The REST Updatable Framework.
 *
 * @package Box_Spawner
 * @subpackage Framework
 *
 * @since 1.0.0
 */
namespace BoxSpawner;

/**
 * The REST Updatable trait.
 *
 * @internal Used by REST objects that support the standard update request.
 *
 * @since 1.0.0
 */
trait REST_Updatable {
	/**
	 * Update an existing object.
	 *
	 * @since 1.0.0
	 *
	 * @param int|string $id      The ID of the object being updated.
	 * @param array      $options Optional A hash of options for the update request.
	 *
	 * @return bool The result of the update request.
	 */
	public function update( array $options ) {
		$endpoint = static::ENDPOINT_FORMAT . '/' . $this->id;
		return static::call_api( $endpoint, $options, 'PUT' );
	}
}
