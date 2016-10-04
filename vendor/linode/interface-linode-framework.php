<?php
/**
 * The Linode Object framework.
 * The base framework for the Linode object classes.
 *
 * @since 1.0.0
 */
namespace BoxSpawner\Linode;

/**
 * The Linode Object Framework interface.
 * The base framework for the Linode object classes.
 *
 * @api
 *
 * @since 1.0.0
 */
interface Linode_Framework {
	// ==================================================
	// ! Main Object Specialty Actions
	// ==================================================

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
	public function list_configs( array $filter = array() );

	/**
	 * Retrieve a single config.
	 *
	 * @since 1.0.0
	 *
	 * @param int $config_id The ID of the config to retrieve.
	 *
	 * @return Linode_Config The config object.
	 */
	public function get_config( $config_id );

	/**
	 * Create a new config.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data The properties of the new config.
	 *
	 * @return Linode_Config The config object.
	 */
	public function create_config( $data );

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
	public function update_config( $config_id, array $data );

	/**
	 * Delete an existing config.
	 *
	 * @since 1.0.0
	 *
	 * @param int $config_id The ID of the config to delete.
	 *
	 * @return bool Wether or not the delete was successful.
	 */
	public function delete_config( $config_id );

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
	public function list_disks( array $filter = array() );

	/**
	 * Retrieve a single disk.
	 *
	 * @since 1.0.0
	 *
	 * @param int $disk_id The ID of the disk to retrieve.
	 *
	 * @return Linode_Disk The disk object.
	 */
	public function get_disk( $disk_id );

	/**
	 * Create a new disk.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data The properties of the new disk.
	 *
	 * @return Linode_Disk The disk object.
	 */
	public function create_disk( $data );
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
	public function update_disk( $disk_id, array $data );

	/**
	 * Delete an existing disk.
	 *
	 * @since 1.0.0
	 *
	 * @param int $disk_id The ID of the disk to delete.
	 *
	 * @return int The ID of the job handling the request.
	 */
	public function delete_disk( $disk_id );

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
	public function resize_disk( $disk_id, $size );

	/**
	 * Duplicate an existing disk.
	 *
	 * @since 1.0.0
	 *
	 * @param int $disk_id The ID of the disk to duplicate.
	 *
	 * @return int The ID of the job handling the request.
	 */
	public function duplicate_disk( $disk_id );

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
	public function imagize_disk( $disk_id, $data = array() );

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
	public function list_ips( array $filter = array() );

	/**
	 * Retrieve a single ip.
	 *
	 * @since 1.0.0
	 *
	 * @param int $ip_id The ID of the ip to retrieve.
	 *
	 * @return Linode_IP The ip object.
	 */
	public function get_ip( $ip_id );

	/**
	 * Create a new ip.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data The properties of the new ip.
	 *
	 * @return Linode_IP The ip object.
	 */
	public function create_ip( $data );

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
	public function update_ip( $ip_id, array $data );

	/**
	 * Set the rDNS name of an existing ip.
	 *
	 * @since 1.0.0
	 *
	 * @param int    $ip_id    The ID of the ip to update.
	 * @param string $hostname The hostname ot set the rDNS to.
	 */
	public function set_ip_rdns( $ip_id, $hostname );

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
	public function swap_with( $ip_id, $target_ip_id );

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
	public function transfer_to( $ip_id, $target_linode_id );
}
