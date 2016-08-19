<?php
/**
 * The Linode IP Address Object.
 *
 * @package Box_Spawner
 * @subpackage Linode
 *
 * @since 1.0.0
 */
namespace BoxSpawner\Linode;

/**
 * The Linode IP class.
 *
 * An interface for creating/manipulating IP addresses for a linode.
 *
 * @internal Used by the Linode class.
 *
 * @since 1.0.0
 */
class Linode_IP extends API_Object {
	/**
	 * The name of the option to assign the ID to.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	const ID_ATTRIBUTE = 'IPAddresID';

	/**
	 * Create a new IP for a linode.
	 *
	 * @since 1.0.0
	 *
	 * @param array $options A hash of options for the create request.
	 *      @option int    "LinodeID"    The ID of the linode to create from.
	 *      @option bool   "IsPublic"    Wether or not the IP should be public.
	 *
	 * @return int|string The ID of the new object.
	 */
	public static function create( array $options ) {
		// Determine the subaction to use
		$action = 'addpublic';
		if ( isset( $options['IsPublic'] ) && ! $options['IsPublic'] ) {
			$action = 'addprivate';
		}

		return static::call_api( $action, $options );
	}

	/**
	 * Set the rDNS name of a *public* IP.
	 *
	 * Updates the RDNS_NAME attribute when performed.
	 *
	 * @since 1.0.0
	 *
	 * @param string $hostname The hostname to set the reverse DNS to.
	 */
	public function set_rdns( $hostname ) {
		$result = static::call_api( 'setrdns', array(
			'IPAddressID' => $this->id,
			'Hostname'    => $hostname,
		) );

		// Update the RDNS_NAME attribute
		$this->attributes['RDNS_NAME'] = $result['HOSTNAME'];
	}

	/**
	 * Utility for transfer_to and swap_with methods.
	 *
	 * @since 1.0.0
	 *
	 * @param array $options The options for the swap request.
	 */
	protected function swap( $options ) {
		// Perform the swap request
		$result = static::call_api( 'swap', $options );

		// Update the LINODEID attribute
		$this->attributes['LINODEID'] = $result[0]['LINODEID'];
	}

	/**
	 * Transfer the IP Address to another linode.
	 *
	 * @since 1.0.0
	 *
	 * @param int $linode_id The ID of the linode to assign the IP to.
	 */
	public function transfer_to( $linode_id ) {
		$this->swap( array(
			'IPAddressID' => $this->id,
			'toLinodeID'  => $linode_id,
		) );
	}

	/**
	 * Swap the IP Address with another.
	 *
	 * @since 1.0.0
	 *
	 * @param int $ipaddress_id The ID of the IP Address to swap with.
	 */
	public function swap_with( $ipaddress_id ) {
		$this->swap( 'swap', array(
			'IPAddressID'     => $this->id,
			'withIPAddressID' => $ipaddress_id,
		) );
	}
}
