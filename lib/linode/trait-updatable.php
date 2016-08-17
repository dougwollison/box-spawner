<?php
namespace BoxSpawner\Linode;

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
	public static function update( $id, array $options ) {
		$options[ static::ID_ATTRIBUTE ] = $id;

		return static::request( 'update', $options );
	}
}