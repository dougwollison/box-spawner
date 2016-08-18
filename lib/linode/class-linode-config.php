<?php
/**
 * The Linode Config Object.
 *
 * @package Box_Spawner
 * @subpackage Linode
 *
 * @since 1.0.0
 */
namespace BoxSpawner\Linode;

/**
 * The Linode Config class.
 *
 * An interface for creating/manipulating configs for a linode.
 *
 * @internal Used by the Linode Class.
 *
 * @since 1.0.0
 */
class Linode_Config extends API_Object {
	use Creatable, Deletable, Updatable;

	/**
	 * The name of the option to assign the ID to.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	const ID_ATTRIBUTE = 'ConfigID';
}
