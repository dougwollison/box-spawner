<?php
/**
 * The Linode Disk Asset.
 *
 * @package Box_Spawner
 * @subpackage Linode
 *
 * @since 1.0.0
 */
namespace BoxSpawner\Linode;

/**
 * The Linode Disk class.
 *
 * An interface for creating/manipulating disks for a linode.
 *
 * @internal Used by the Linode class.
 *
 * @since 1.0.0
 */
class Linode_Disk extends Asset {
	use Creatable, Deletable, Updatable;

	/**
	 * The name of the option to assign the ID to.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	const ID_ATTRIBUTE = 'DiskID';
}
