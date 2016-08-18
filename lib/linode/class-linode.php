<?php
/**
 * The Linode Object.
 *
 * @package Box_Spawner
 * @subpackage Linode
 *
 * @since 1.0.0
 */
namespace BoxSpawner\Linode;

use \BoxSpawner as Framework;

/**
 * The Linode class.
 *
 * An interface for creating/manipulating Linode boxes.
 *
 * @api
 *
 * @since 1.0.0
 */
class Linode extends API_Object {
	use Creatable, Deletable, Updatable;

	/**
	 * The name of the option to assign the ID to.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	const ID_ATTRIBUTE = 'LinodeID';

	/**
	 * A list of Config objects tied to it.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	protected $configs = array();

	/**
	 * A list of Disk objects tied to it.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	protected $disks = array();

	/**
	 * A list of IP objects tied to it.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	protected $ips = array();
}
