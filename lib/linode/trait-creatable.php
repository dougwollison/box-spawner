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
	public static function create( array $options = array() ) {
		return static::request( 'create', $options );
	}
}
