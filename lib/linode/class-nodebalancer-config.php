<?php
/**
 * The Nodebalancer Config Object.
 *
 * @package Box_Spawner
 * @subpackage Linode
 *
 * @since 1.0.0
 */
namespace BoxSpawner\Linode;

/**
 * The Nodebalancer Config class.
 *
 * An interface for creating/manipulating configs for a nodebalancer.
 *
 * @internal Used by the Nodebalancer class.
 *
 * @since 1.0.0
 */
class Nodebalancer_Config extends API_Object {
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
