<?php
/**
 * The Linode Updatable Framework.
 *
 * @package Box_Spawner
 * @subpackage Linode
 *
 * @since 1.0.0
 */
namespace BoxSpawner\Linode;

/**
 * The Updatable trait.
 *
 * @internal Used by Linode objects that support the standard update request.
 *
 * @since 1.0.0
 */
trait Updatable {
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
		$options[ static::ID_ATTRIBUTE ] = $this->id;

		$result = static::call_api( 'update', $options );

		return $result[ static::ID_ATTRIBUTE ] == $this->id;
	}
}