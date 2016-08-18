<?php
namespace BoxSpawner\Linode;

trait Deletable {
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
		$options[ static::ID_ATTRIBUTE ] = $id;

		$result = static::call_api( 'delete', $options );

		return $result[ static::ID_ATTRIBUTE ] == $this->id;
	}
}
