<?php
/**
 * The StackScript Object.
 *
 * @package Box_Spawner
 * @subpackage Linode
 *
 * @since 1.0.0
 */
namespace BoxSpawner\Linode;

/**
 * The StackScript class.
 *
 * An interface for creating/manipulating Linode stackscripts.
 *
 * @api
 *
 * @since 1.0.0
 */
class StackScript extends API_Object {
	use Creatable, Deletable, Updatable, \BoxSpawner\Dependable;

	/**
	 * The name of the option to assign the ID to.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	const ID_ATTRIBUTE = 'StackScriptID';
}
