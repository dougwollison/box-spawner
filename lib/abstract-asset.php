<?php
/**
 * The Asset Framework.
 *
 * @package Box_Spawner
 * @subpackage Framework
 *
 * @since 1.0.0
 */
namespace BoxSpawner;

/**
 * The Asset class.
 *
 * The base for all object assets.
 *
 * @internal Extended by other vendor-specific asset classes.
 *
 * @since 1.0.0
 */
abstract class Asset implements API {
	/**
	 * The parent object.
	 *
	 * @since 1.0.0
	 *
	 * @var object
	 */
	protected $parent;
}
