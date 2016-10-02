<?php
/**
 * The Linode Disk Object framework.
 * The base framework for the Linode Disk object classes.
 *
 * @since 1.0.0
 */
namespace BoxSpawner\Linode;

/**
 * The Linode Disk Object Framework interface.
 * The base framework for the Linode_Disk object classes.
 *
 * @api
 *
 * @since 1.0.0
 */
interface Linode_Disk_Framework {
	/**
	 * Resize the disk
	 *
	 * @since 1.0.0
	 *
	 * @param int $size The desired size of the disk (MB).
	 */
	public function resize( $size );
}
