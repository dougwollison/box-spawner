<?php
/**
 * The REST Deletable Framework.
 *
 * @package Box_Spawner
 * @subpackage Framework
 *
 * @since 1.0.0
 */
namespace BoxSpawner\Linode;

/**
 * The REST Deletable trait.
 *
 * @internal Used by REST objects that support the standard delete request.
 *
 * @since 1.0.0
 */
trait REST_Deletable {
	/**
	 * Delete an existing object.
	 *
	 * @since 1.0.0
	 *
	 * @param int|string $id      The ID of the object being deleted.
	 * @param array      $options Optional A hash of options for the deleted request.
	 *
	 * @return bool The result of the delete request.
	 */
	public function delete( $id, array $options = array() ) {
		$endpoint = static::ENDPOINT_FORMAT . '/' . $this->id;
		return static::call_api( $endpoint, $options, 'DELETE' );
	}
}
