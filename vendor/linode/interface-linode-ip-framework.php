<?php
/**
 * The Linode IP Object framework.
 * The base framework for the Linode Config object classes.
 *
 * @since 1.0.0
 */
namespace BoxSpawner\Linode;

/**
 * The Linode IP Object Framework interface.
 * The base framework for the Linode_IP object classes.
 *
 * @api
 *
 * @since 1.0.0
 */
interface Linode_IP_Framework {
	/**
	 * Set the rDNS name of the ip.
	 *
	 * @since 1.0.0
	 *
	 * @param string $hostname The hostname ot set the rDNS to.
	 */
	public function set_rdns( $hostname );
}
