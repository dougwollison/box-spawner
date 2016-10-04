<?php
/**
 * The Linode Object (version 3).
 *
 * @package Box_Spawner
 * @subpackage Linode
 *
 * @since 1.0.0
 */
namespace BoxSpawner\Linode\V3;

/**
 * The Linode object class.
 *
 * The an absraction of the Linode api object.
 *
 * @api
 *
 * @since 1.0.0
 */
class Linode extends \BoxSpawner\API_Object implements \BoxSpawner\Linode\Linode_Framework {
	/**
	 * The name of the option to assign the ID to.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	const ID_ATTRIBUTE = 'LINODEID';

	// ==================================================
	// ! Main Object Actions
	// ==================================================

	/**
	 * Issue a create request for a new object.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data The data for the create request.
	 */
	protected function create( array $data ) {
		$result = $this->api->create_linode( $data, 'array' );

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
		$this->attributes = $this->api->get_linode( $id, 'array' );
	}

	/**
	 * Issue a update request for a new object.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data The data for the update request.
	 */
	public function update( array $data ) {
		return $this->api->update_linode( $this->id, $data );
	}

	/**
	 * Issue a delete request for a new object.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data Optional The data for the delete request.
	 */
	public function delete( array $data = array() ) {
		return $this->api->delete_linode( $this->id, $data );
	}

	// =========================
	// ! - Specialty Actions
	// =========================

	/**
	 * Issue a boot job for the linode.
	 *
	 * @since 1.0.0
	 *
	 * @param int $config_id Optional the ID of a config to boot with.
	 *
	 * @return int The ID of the job handling the request.
	 */
	public function boot( $config_id = null ) {
		return $this->api->boot_linode( $this->id, $config_id );
	}

	/**
	 * Issue a shutdown job for the linode.
	 *
	 * @since 1.0.0
	 *
	 * @return int The ID of the job handling the request.
	 */
	public function shutdown() {
		return $this->api->shutdown_linode( $this->id );
	}

	/**
	 * Issue a reboot job for the linode.
	 *
	 * @since 1.0.0
	 *
	 * @param int $config_id Optional the ID of a config to boot with.
	 *
	 * @return int The ID of the job handling the request.
	 */
	public function reboot( $config_id = null ) {
		return $this->api->reboot_linode( $this->id, $config_id );
	}

	/**
	 * Clone the linode.
	 *
	 * @since 1.0.0
	 *
	 * @param int $datacenter_id The datacenter to place the linode.
	 * @param int $plan_id       The plan for the linode.
	 * @param int $payment_term  Optional The subscription term.
	 *
	 * @return bool Wether or not the cloning was successful.
	 */
	public function duplicate( $datacenter_id, $plan_id, $payment_term = null ) {
		return $this->api->duplicate_linode( $this->id, $datacenter_id, $plan_id, $payment_term );
	}

	/**
	 * Convert the hpyervisor from Xen to KVM.
	 *
	 * @since 1.0.0
	 */
	public function kvmify() {
		$this->api->kvmify_linode( $this->id );
	}

	/**
	 * Upgrade to it's next generation.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function mutate() {
		$this->api->mutate_linode( $this->id );
	}

	// ==================================================
	// ! Asset Actions
	// ==================================================

	// =========================
	// ! - Config Assets
	// =========================

	/**
	 * Retrieve a list of configs.
	 *
	 * @since 1.0.0
	 *
	 * @param array $filter Optional Arguments for filtering the list request.
	 *
	 * @return array The list of Linode_Config objects.
	 */
	public function list_configs( array $filter = array() ) {
		return $this->api->list_linode_configs( $this->id, $filter );
	}

	/**
	 * Retrieve a single config.
	 *
	 * @since 1.0.0
	 *
	 * @param int $config_id The ID of the config to retrieve.
	 *
	 * @return Linode_Config The config object.
	 */
	public function get_config( $config_id ) {
		return $this->api->get_linode_config( $this->id, $config_id );
	}

	/**
	 * Create a new config.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data The properties of the new config.
	 *
	 * @return Linode_Config The config object.
	 */
	public function create_config( $data ) {
		return $this->api->create_linode_config( $this->id, $data );
	}

	/**
	 * Update an existing config.
	 *
	 * @since 1.0.0
	 *
	 * @param int   $config_id The ID of the config to update.
	 * @param array $data      The properties of the new Linode.
	 *
	 * @return bool Wether or not the update was successful.
	 */
	public function update_config( $config_id, array $data ) {
		return $this->api->update_linode_config( $this->id, $config_id, $data );
	}

	/**
	 * Delete an existing config.
	 *
	 * @since 1.0.0
	 *
	 * @param int $config_id The ID of the config to delete.
	 *
	 * @return bool Wether or not the delete was successful.
	 */
	public function delete_config( $config_id ) {
		return $this->api->delete_linode_config( $this->id, $config_id );
	}

	// =========================
	// ! - Disk Assets
	// =========================

	/**
	 * Retrieve a list of disks.
	 *
	 * @since 1.0.0
	 *
	 * @param array $filter Optional Arguments for filtering the list request.
	 *
	 * @return array The list of disk objects.
	 */
	public function list_disks( array $filter = array() ) {
		return $this->api->list_linode_disks( $this->id, $filter );
	}

	/**
	 * Retrieve a single disk.
	 *
	 * @since 1.0.0
	 *
	 * @param int $disk_id The ID of the disk to retrieve.
	 *
	 * @return Linode_Disk The disk object.
	 */
	public function get_disk( $disk_id ) {
		return $this->api->get_linode_disk( $this->id, $disk_id );
	}

	/**
	 * Create a new disk.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data The properties of the new disk.
	 *
	 * @return Linode_Disk The disk object.
	 */
	public function create_disk( $data ) {
		return $this->api->create_linode_disk( $this->id, $data );
	}

	/**
	 * Update an existing disk.
	 *
	 * @since 1.0.0
	 *
	 * @param int   $disk_id The ID of the disk to update.
	 * @param array $data    The properties of the new Linode.
	 *
	 * @return bool Wether or not the update was successful.
	 */
	public function update_disk( $disk_id, array $data ) {
		return $this->api->update_linode_disk( $this->id, $disk_id, $data );
	}

	/**
	 * Delete an existing disk.
	 *
	 * @since 1.0.0
	 *
	 * @param int $disk_id The ID of the disk to delete.
	 *
	 * @return int The ID of the job handling the request.
	 */
	public function delete_disk( $disk_id ) {
		return $this->api->delete_linode_disk( $this->id, $disk_id );
	}

	/**
	 * Delete an existing disk.
	 *
	 * @since 1.0.0
	 *
	 * @param int $disk_id The ID of the disk to delete.
	 * @param int $size    The new size of the disk in MB.
	 *
	 * @return int The ID of the job handling the request.
	 */
	public function resize_disk( $disk_id, $size ) {
		return $this->api->resize_linode_disk( $this->id, $disk_id, $size );
	}

	/**
	 * Duplicate the disk.
	 *
	 * @since 1.0.0
	 *
	 * @param int $disk_id The ID of the disk to duplicate.
	 *
	 * @return int The ID of the job handling the request.
	 */
	public function duplicate_disk( $disk_id ) {
		return $this->api->duplicate_linode_disk( $this->id, $disk_id );
	}

	/**
	 * Create an image from the disk.
	 *
	 * @since 1.0.0
	 *
	 * @param int   $disk_id The ID of the disk to delete.
	 * @param array $data    Optional The label/description for the image.
	 *
	 * @return int The ID of the image created.
	 */
	public function imagize_disk( $disk_id, $data = array() ) {
		return $this->api->imagize_linode_disk( $this->id, $disk_id, $data );
	}

	// =========================
	// ! - IP Assets
	// =========================

	/**
	 * Retrieve a list of ips.
	 *
	 * @since 1.0.0
	 *
	 * @param array $filter Optional Arguments for filtering the list request.
	 *
	 * @return array The list of Linode_IP objects.
	 */
	public function list_ips( array $filter = array() ) {
		return $this->api->list_linode_ips( $this->id, $filter );
	}

	/**
	 * Retrieve a single ip.
	 *
	 * @since 1.0.0
	 *
	 * @param int $ip_id The ID of the ip to retrieve.
	 *
	 * @return Linode_IP The ip object.
	 */
	public function get_ip( $ip_id ) {
		return $this->api->get_linode_ip( $this->id, $ip_id );
	}

	/**
	 * Create a new ip.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data The properties of the new ip.
	 *
	 * @return Linode_IP The ip object.
	 */
	public function create_ip( $data ) {
		return $this->api->create_linode_ip( $this->id, $data );
	}

	/**
	 * Update an existing ip.
	 *
	 * @since 1.0.0
	 *
	 * @param int   $ip_id The ID of the ip to update.
	 * @param array $data  The properties of the new Linode.
	 *
	 * @return bool Wether or not the update was successful.
	 */
	public function update_ip( $ip_id, array $data ) {
		return $this->api->update_linode_ip( $this->id, $ip_id, $data );
	}

	/**
	 * Set the rDNS name of an existing ip.
	 *
	 * @since 1.0.0
	 *
	 * @param int    $ip_id    The ID of the ip to update.
	 * @param string $hostname The hostname ot set the rDNS to.
	 */
	public function set_ip_rdns( $ip_id, $hostname ) {
		return $this->api->set_linode_ip_rdns( $this->id, $ip_id, $hostname );
	}

	/**
	 * Exchange an existing IP with another IP.
	 *
	 * @since 1.0.0
	 *
	 * @param int $ip_id        The ID of the ip to swap.
	 * @param int $target_ip_id The ID of the ip to swap with.
	 *
	 * @return array The new relationships between the IPs.
	 */
	public function swap_with( $ip_id, $target_ip_id ) {
		return $this->api->swap_linode_ip_with( $this->parent_id, $ip_id, $target_ip_id );
	}

	/**
	 * Transfer an existing IP to another Linode.
	 *
	 * @since 1.0.0
	 *
	 * @param int $ip_id        The ID of the ip to transfer.
	 * @param int $target_ip_id The ID of the linode to transfer to.
	 *
	 * @return array The new relationships between the IPs.
	 */
	public function transfer_to( $ip_id, $target_linode_id ) {
		return $this->api->transfer_linode_ip_to( $this->parent_id, $ip_id, $target_linode_id );
	}
}
