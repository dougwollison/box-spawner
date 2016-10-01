<?php
/**
 * The Linode IP Object (version 3).
 *
 * @package Box_Spawner
 * @subpackage Linode
 *
 * @since 1.0.0
 */
namespace BoxSpawner\Linode\V3;

/**
 * The Linode IP object class.
 *
 * The an absraction of the Linode IP api object.
 *
 * @api
 *
 * @since 1.0.0
 */
class Linode_IP extends \BoxSpawner\API_Asset implements \BoxSpawner\Linode\Linode_IP_Framework {
	/**
	 * The name of the option to assign the ID to.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	const ID_ATTRIBUTE = 'IPADDRESSID';
}
