<?php
/**
 * The Linode Object framework.
 * The base framework for the Linode object classes.
 *
 * @since 1.0.0
 */
namespace BoxSpawner\Linode;

/**
 *  The Linode Object Framework interface.
 *  The base framework for the Linode object classes.
 *
 *  @api
 *
 *  @since 1.0.0
 */
interface Linode_Framework {
	/**
	 * Issue a boot job for the linode.
	 *
	 * @since 1.0.0
	 *
	 * @param int $config_id Optional the ID of a config to boot with.
	 */
	public function boot( $config_id = null );

	/**
	 * Issue a shutdown job for the linode.
	 *
	 * @since 1.0.0
	 */
	public function shutdown();

	/**
	 * Issue a reboot job for the linode.
	 *
	 * @since 1.0.0
	 *
	 * @param int $config_id Optional the ID of a config to boot with.
	 */
	public function reboot( $config_id = null );
}
