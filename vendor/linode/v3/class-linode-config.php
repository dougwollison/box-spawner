<?php
/**
 * The Linode Config Object (version 3).
 *
 * @package Box_Spawner
 * @subpackage Linode
 *
 * @since 1.0.0
 */
namespace BoxSpawner\Linode\V3;

/**
 * The Linode Config object class.
 *
 * The an absraction of the Linode Config api object.
 *
 * @api
 *
 * @since 1.0.0
 */
class Linode_Config extends \BoxSpawner\API_Asset implements \BoxSpawner\Linode\Linode_Config_Framework {
	/**
	 * The name of the option to assign the ID to.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	const ID_ATTRIBUTE = 'CONFIGID';
}
