<?php
namespace BoxSpawner\Linode;

trait Creatable {
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
		$result = static::call_api( 'create', $options );

		return $result[ static::ID_ATTRIBUTE ];
	}
}
