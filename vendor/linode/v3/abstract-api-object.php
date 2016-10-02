<?php
/**
 * The Linode API Object Framework (version 3).
 *
 * @package Box_Spawner
 * @subpackage Linode
 *
 * @since 1.0.0
 */
namespace BoxSpawner\Linode\V3;

/**
 * The API Object class.
 *
 * The basis for all API_Object classes.
 *
 * @internal Extended by Linode\V3 API_Object classes.
 *
 * @since 1.0.0
 */
abstract class API_Object extends \BoxSpawner\API_Object {
	/**
	 * Create a new object or retrieve and existing one.
	 *
	 * Simply lowercases the key names for consistency,
	 * since the API is case-insensitive with parameters.
	 *
	 * @since 1.0.0
	 *
	 * @see BoxSpawner\API_Object for detials.
	 */
	public function __construct( API $api, $id, array $data = array() ) {
		$keys = array_keys( $data );
		$values = array_values( $data );

		$keys = array_map( 'strtoupper', $keys );

		$data = array_combine( $keys, $values );

		parent::__construct( $api, $id, $data );
	}
}
