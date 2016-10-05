<?php
/**
 * The Linode IP Object (version 3).
 *
 * @package Box_Spawner
 * @subpackage Linode
 *
 * @since 1.0.0
 */
namespace BoxSpawner\Linode\V3;

/**
 * The Linode IP object class.
 *
 * The an absraction of the Linode IP api object.
 *
 * @api
 *
 * @since 1.0.0
 */
class Linode_IP extends \BoxSpawner\API_Asset implements \BoxSpawner\Linode\Linode_IP_Framework {
	use \BoxSpawner\Case_Insensitive_Object;

	/**
	 * The name of the option to assign the ID to.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	const PARENT_ID_ATTRIBUTE = Linode::ID_ATTRIBUTE;

	/**
	 * The name of the option to assign the ID to.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	const ID_ATTRIBUTE = 'IPADDRESSID';

	/**
	 * Issue a create request for a new object.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data The data for the create request.
	 */
	protected function create( array $data ) {
		$result = $this->api->create_linode_ip( $this->parent_id, $data, 'array' );

		$this->load( $result[ $this::ID_ATTRIBUTE ] );
	}

	/**
	 * Issue a load request for a new object.
	 *
	 * @since 1.0.0
	 *
	 * @param string $id The id of the object to load.
	 */
	protected function load( $id ) {
		$this->id = $id;
		$this->attributes = $this->api->get_linode_ip( $this->parent_id, $id, 'array' );
	}

	/**
	 * Issue a update request for a new object.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data The data for the update request.
	 */
	public function update( array $data ) {
		return $this->api->update_linode_ip( $this->parent_id, $this->id, $data );
	}

	// =========================
	// ! Specialty Actions
	// =========================

	/**
	 * Check if the IP is public or not.
	 *
	 * @since 1.0.0
	 *
	 * @return bool Wether or not the IP is public.
	 */
	public function is_public() {
		return (bool) $this->attributes['ISPUBLIC'];
	}

	/**
	 * Set the rDNS name of the ip.
	 *
	 * @since 1.0.0
	 *
	 * @param string $hostname The hostname ot set the rDNS to.
	 */
	public function set_rdns( $hostname ) {
		$result = $this->api->set_linode_ip_rdns( $this->parent_id, $this->id, $hostname );
	}

	/**
	 * Helper for swap_with() and transfer_to().
	 *
	 * Handle's updating the parent_id on success.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data The data for the swap request.
	 *
	 * @return array The new relationships between the IPs.
	 */
	protected function handle_swap_result( $result ) {
		foreach ( $result as $ip ) {
			if ( $ip[ $this::ID_ATTRIBUTE ] == $this->id ) {
				$this->parent_id = $ip[ $this::PARENT_ID_ATTRIBUTE ];
			}
		}

		return $result;
	}

	/**
	 * Exchange the IP with another IP.
	 *
	 * Will update it's parent_id on success.
	 *
	 * @since 1.0.0
	 *
	 * @param int $ip_id The ID of the IP address to swap with.
	 *
	 * @return array The new relationships between the IPs.
	 */
	public function swap_with( $ip_id ) {
		$result = $this->api->swap_linode_ip_with( $this->parent_id, $this->id, $ip_id );

		return $this->handle_swap_result( $result );
	}

	/**
	 * Transfer the IP to another Linode.
	 *
	 * Will update it's parent_id on success.
	 *
	 * @since 1.0.0
	 *
	 * @param int $linode_id The ID of the linode to transfer to.
	 *
	 * @return array The new relationships between the IPs.
	 */
	public function transfer_to( $linode_id ) {
		$result = $this->api->transfer_linode_ip_to( $this->parent_id, $this->id, $linode_id );

		return $this->handle_swap_result( $result );
	}
}
