<?php
/**
 * The Domain Resource Object.
 *
 * @package Box_Spawner
 * @subpackage Linode
 *
 * @since 1.0.0
 */
namespace BoxSpawner\Linode;

/**
 * The Domain Resource class.
 *
 * An interface for creating/manipulating Linode domain records.
 *
 * @internal Used by the Domain class.
 *
 * @since 1.0.0
 */
class Domain_Resource extends API_Object {
	use Creatable, Deletable, Updatable;

	/**
	 * The name of the option to assign the ID to.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	const ID_ATTRIBUTE = 'ResourceID';
}
