<?php
/**
 * The Domain Object.
 *
 * @package Box_Spawner
 * @subpackage Linode
 *
 * @since 1.0.0
 */
namespace BoxSpawner\Linode;

/**
 * The Domain class.
 *
 * An interface for creating/manipulating Linode domains.
 *
 * @api
 *
 * @since 1.0.0
 */
class Domain extends API_Object {
	use Creatable, Deletable, Updatable;

	/**
	 * The name of the option to assign the ID to.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	const ID_ATTRIBUTE = 'DomainID';

	/**
	 * A list of Domain_Resource objects tied to it.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	protected $resources = array();
}
