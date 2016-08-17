<?php
/**
 * The Linode IP Address Asset.
 *
 * @package Box_Spawner
 * @subpackage Linode
 *
 * @since 1.0.0
 */
namespace BoxSpawner\Linode;

/**
 * The Linode IP class.
 *
 * An interface for creating/manipulating IP addresses for a linode.
 *
 * @internal Used by the Linode class.
 *
 * @since 1.0.0
 */
class Linode_IP extends Asset {
	use Listable;

	/**
	 * The name of the option to assign the ID to.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	const ID_ATTRIBUTE = 'IPAddresID';

	/**
	 * Create a new IP for a linode.
	 *
	 * @since 1.0.0
	 *
	 * @param array $options A hash of options for the create request.
	 *      @option int    "LinodeID"    The ID of the linode to create from.
	 *      @option bool   "IsPublic"    Wether or not the IP should be public.
	 *
	 * @return int|string The ID of the new object.
	 */
	public static function create( array $options ) {
		// Determine the subaction to use
		$action = $options['IsPublic'] ? 'addpublic' : 'addprivate';

		return static::request( 'linode.ip.' . $action, $options );
	}
}
