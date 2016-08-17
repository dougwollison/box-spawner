<?php
/**
 * The Nodebalancer Object.
 *
 * @package Box_Spawner
 * @subpackage Linode
 *
 * @since 1.0.0
 */
namespace BoxSpawner\Linode;

/**
 * The Nodebalancer class.
 *
 * An interface for creating/manipulating Linode nodebalancers.
 *
 * @api
 *
 * @since 1.0.0
 */
class Nodebalancer extends API_Object {
	use Creatable, Deletable, Updatable, Listable;

	/**
	 * The name of the option to assign the ID to.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	const ID_ATTRIBUTE = 'NodeBalancerID';
}
