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
	use Creatable, Deletable, Updatable, Listable;

	/**
	 * The name of the option to assign the ID to.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	const ID_ATTRIBUTE = 'LinodeID';
}
