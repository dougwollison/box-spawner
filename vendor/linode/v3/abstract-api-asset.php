<?php
/**
 * The Linode API Asset Framework (version 3).
 *
 * @package Box_Spawner
 * @subpackage Linode
 *
 * @since 1.0.0
 */
namespace BoxSpawner\Linode\V3;

/**
 * The API Asset class.
 *
 * The basis for all API_Asset classes.
 *
 * @internal Extended by Linode\V3 API_Asset classes.
 *
 * @since 1.0.0
 */
abstract class API_Asset extends \BoxSpawner\API_Asset {
	/**
	 * Create a new object or retrieve and existing one.
	 *
	 * Simply uppercases the key names for consistency,
	 * since the API is case-insensitive with parameters.
	 *
	 * @since 1.0.0
	 *
	 * @see BoxSpawner\API_Asset for detials.
	 */
	public function __construct( API $api, $id, array $data = array(), $parent_id = null ) {
		$keys = array_keys( $data );
		$values = array_values( $data );

		$keys = array_map( 'strtoupper', $keys );

		$data = array_combine( $keys, $values );

		parent::__construct( $api, $id, $data );
	}
}
