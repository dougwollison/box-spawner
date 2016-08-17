<?php
/**
 * The Nodebalancer Node Asset.
 *
 * @package Box_Spawner
 * @subpackage Linode
 *
 * @since 1.0.0
 */
namespace BoxSpawner\Linode;

/**
 * The Nodebalancer Node class.
 *
 * An interface for creating/manipulating nodes for a nodebalancer.
 *
 * @internal Used by the Nodebalancer class.
 *
 * @since 1.0.0
 */
class Nodebalancer_Node extends Asset {
	use Creatable, Deletable, Updatable, Listable;

	/**
	 * The name of the option to assign the ID to.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	const ID_ATTRIBUTE = 'NodeID';
}
