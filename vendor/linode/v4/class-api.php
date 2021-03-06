<?php
/**
 * The Linode API (version 4).
 *
 * @package Box_Spawner
 * @subpackage Linode
 *
 * @since 1.0.0
 */
namespace BoxSpawner\Linode\V4;

/**
 * The API class.
 *
 * The interface for all API requests.
 *
 * @api
 *
 * @since 1.0.0
 */
class API extends \BoxSpawner\JSON_API implements \BoxSpawner\Linode\API_Framework {
	/**
	 * The base endpoint URL.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	const ENDPOINT_BASE = 'https://api.alpha.linode.com/v4/';

	/**
	 * The token for all requests.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $token;

	/**
	 * The constructor.
	 *
	 * Sets the authentication credentials.
	 *
	 * @since 1.0.0
	 *
	 * @param string|array $options An API key or other options.
	 * @param int|string   $version Optional
	 */
	public function __construct( array $options ) {
		$this->token   = $options['token'];
	}

	// ==================================================
	// ! Request Handling
	// ==================================================

	/**
	 * Make the request, return it's result.
	 *
	 * @since 1.0.0
	 *
	 * @param string $action The api_action
	 * @param array  $data   The data of the request.
	 *
	 * @return mixed The result of the request.
	 */
	public function request( $endpoint, array $data = array() ) {
		$options = array( 'endpoint' => $endpoint );

		return parent::request( $data, $options );
	}

	/**
	 * Determine the headers of the request.
	 *
	 * @since 1.0.0
	 *
	 * @param string|array $data    The data to send in the request.
	 * @param array        $options The options of the request.
	 *
	 * @return array The headers for the request.
	 */
	protected function get_request_headers( $data, $options ) {
		$headers = parent::get_request_headers( $data, $options );

		$headers[] = 'Authorization: token ' . $this->token;

		return $headers;
	}

	/**
	 * Parse the results.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed        $result  The result of the request.
	 * @param resource     $curl    The cURL handle of the request.
	 * @param string|array $data    The data to send in the request. (unused)
	 * @param array        $options The options of the request. (unused)
	 *
	 * @throws InvalidResponseException If the result is missing the DATA section.
	 * @throws ErrorResponseException   If the API returned an error.
	 *
	 * @return mixed The parsed result.
	 *     @option array  "headers" The list of response headers.
	 *     @option string "body"    The body of the response.
	 */
	protected function parse_result( $result, $curl, $data, $options ) {
		// Get the result
		$result = parent::parse_result( $result, $curl, $data, $options );

		return $result['body'];
	}

	// ==================================================
	// ! Read-only Information
	// ==================================================

	// =========================
	// ! - Datacenters
	// =========================

	/**
	 * Retrieve a list of datacenters.
	 *
	 * @since 1.0.0
	 *
	 * @param array $filter Optional Arguments for filtering the list request.
	 *
	 * @return array The list of datacenter.
	 */
	public function list_datacenters( array $filter = array() ) {
		// to be written
	}

	/**
	 * Retrieve a single datacenter.
	 *
	 * @since 1.0.0
	 *
	 * @param int $datacenter_id The ID of the datacenter to retrieve.
	 *
	 * @return array The datacenter information.
	 */
	public function get_datacenter( $datacenter_id ) {
		// to be written
	}

	// =========================
	// ! - Distributions
	// =========================

	/**
	 * Retrieve a list of distributions.
	 *
	 * @since 1.0.0
	 *
	 * @param array $filter Optional Arguments for filtering the list request.
	 *
	 * @return array The list of distributions.
	 */
	public function list_distribution( array $filter = array() ) {
		// to be written
	}

	/**
	 * Retrieve a single datacenter.
	 *
	 * @since 1.0.0
	 *
	 * @param int $distribution_id The ID of the distribution to retrieve.
	 *
	 * @return array The distribution information.
	 */
	public function get_distribution( $distribution_id ) {
		// to be written
	}

	// =========================
	// ! - Kernels
	// =========================

	/**
	 * Retrieve a list of kernels.
	 *
	 * @since 1.0.0
	 *
	 * @param array $filter Optional Arguments for filtering the list request.
	 *
	 * @return array The list of kernels.
	 */
	public function list_kernel( array $filter = array() ) {
		// to be written
	}

	/**
	 * Retrieve a single datacenter.
	 *
	 * @since 1.0.0
	 *
	 * @param int $kernel_id The ID of the kernel to retrieve.
	 *
	 * @return array The kernel information.
	 */
	public function get_kernel( $kernel_id ) {
		// to be written
	}

	// ==================================================
	// ! Editable Objects
	// ==================================================

	// =========================
	// ! - Linode Objects
	// =========================

	/**
	 * Retrieve a list of linodes.
	 *
	 * @since 1.0.0
	 *
	 * @param array $filter Optional Arguments for filtering the list request.
	 *
	 * @return array The list of Linode objects.
	 */
	public function list_linodes( array $filter = array() ) {
		// to be written
	}

	/**
	 * Retrieve a single linode.
	 *
	 * @since 1.0.0
	 *
	 * @param int $linode_id The ID of the linode to retrieve.
	 *
	 * @return Linode The linode object.
	 */
	public function get_linode( $linode_id ) {
		// to be written
	}

	/**
	 * Create a new linode.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data The properties of the new Linode.
	 *
	 * @return Linode The linode object.
	 */
	public function create_linode( $data ) {
		// to be written
	}

	/**
	 * Update an existing linode.
	 *
	 * @since 1.0.0
	 *
	 * @param int   $linode_id The ID of the linode to update.
	 * @param array $data      The properties of the new Linode.
	 *
	 * @return bool Wether or not the update was successful.
	 */
	public function modify_linode( $linode_id, array $data ) {
		// to be written
	}

	/**
	 * Delete an existing linode.
	 *
	 * @since 1.0.0
	 *
	 * @param int $linode_id The ID of the linode to delete.
	 *
	 * @return bool Wether or not the delete was successful.
	 */
	public function delete_linode( $linode_id ) {
		// to be written
	}

	/**
	 * Issue a boot request for a linode.
	 *
	 * @since 1.0.0
	 *
	 * @param int $linode_id The ID of the linode to boot.
	 * @param int $config_id Optional The ID of the config to boot with.
	 *
	 * @return bool Wether or not the boot request was successful.
	 */
	public function boot_linode( $linode_id, $config_id = null ) {
		// to be written
	}

	/**
	 * Issue a shutdown request for a linode.
	 *
	 * @since 1.0.0
	 *
	 * @param int $linode_id The ID of the linode to boot.
	 * @param int $config_id Optional The ID of the config to reboot with.
	 *
	 * @return bool Wether or not the boot request was successful.
	 */
	public function shutdown_linode( $linode_id, $config_id = null ) {
		// to be written
	}

	/**
	 * Issue a reboot request for a linode.
	 *
	 * @since 1.0.0
	 *
	 * @param int $linode_id The ID of the linode to boot.
	 *
	 * @return bool Wether or not the boot request was successful.
	 */
	public function reboot_linode( $linode_id ) {
		// to be written
	}

	// =========================
	// ! -- Linode Disk Objects
	// =========================

	/**
	 * Retrieve a list of disks for a linode.
	 *
	 * @since 1.0.0
	 *
	 * @param int   $linode_id The ID of the linode to get disks from.
	 * @param array $filter    Optional Arguments for filtering the list request.
	 *
	 * @return array The list of disk objects.
	 */
	public function list_linode_disks( $linode_id, array $filter = array() ) {
		// to be written
	}

	/**
	 * Retrieve a single disk for a linode.
	 *
	 * @since 1.0.0
	 *
	 * @param int $linode_id The ID of the linode the disk belongs to.
	 * @param int $disk_id   The ID of the disk to retrieve.
	 *
	 * @return Linode_Disk The disk object.
	 */
	public function get_linode_disk( $linode_id, $disk_id ) {
		// to be written
	}

	/**
	 * Create a new disk for a linode.
	 *
	 * @since 1.0.0
	 *
	 * @param int   $linode_id The ID of the linode to create the disk for.
	 * @param array $data      The properties of the new disk.
	 *
	 * @return Linode_Disk The disk object.
	 */
	public function create_linode_disk( $linode_id, $data ) {
		// to be written
	}

	/**
	 * Update an existing disk for a linode.
	 *
	 * @since 1.0.0
	 *
	 * @param int   $linode_id The ID of the linode the disk belongs to.
	 * @param int   $disk_id   The ID of the disk to update.
	 * @param array $data      The properties of the new Linode.
	 *
	 * @return bool Wether or not the update was successful.
	 */
	public function modify_linode_disk( $linode_id, $disk_id, array $data ) {
		// to be written
	}

	/**
	 * Delete an existing disk for a linode.
	 *
	 * @since 1.0.0
	 *
	 * @param int $linode_id The ID of the linode the disk belongs to.
	 * @param int $disk_id   The ID of the disk to delete.
	 *
	 * @return bool Wether or not the delete was successful.
	 */
	public function delete_linode_disk( $linode_id, $disk_id ) {
		// to be written
	}

	/**
	 * Delete an existing disk for a linode.
	 *
	 * @since 1.0.0
	 *
	 * @param int $linode_id The ID of the linode the disk belongs to.
	 * @param int $disk_id   The ID of the disk to delete.
	 * @param int $size      The new size of the disk in MB.
	 *
	 * @return bool Wether or not the delete was successful.
	 */
	public function resize_linode_disk( $linode_id, $disk_id, $size ) {
		// to be written
	}

	// =========================
	// ! -- Linode Config Objects
	// =========================

	/**
	 * Retrieve a list of configs for a linode.
	 *
	 * @since 1.0.0
	 *
	 * @param int   $linode_id The ID of the linode to get configs from.
	 * @param array $filter    Optional Arguments for filtering the list request.
	 *
	 * @return array The list of Linode_Config objects.
	 */
	public function list_linode_configs( $linode_id, array $filter = array() ) {
		// to be written
	}

	/**
	 * Retrieve a single config for a linode.
	 *
	 * @since 1.0.0
	 *
	 * @param int $linode_id The ID of the linode the config belongs to.
	 * @param int $config_id The ID of the config to retrieve.
	 *
	 * @return Linode_Config The config object.
	 */
	public function get_linode_config( $linode_id, $config_id ) {
		// to be written
	}

	/**
	 * Create a new config for a linode.
	 *
	 * @since 1.0.0
	 *
	 * @param int   $linode_id The ID of the linode to create the config for.
	 * @param array $data      The properties of the new config.
	 *
	 * @return Linode_Config The config object.
	 */
	public function create_linode_config( $linode_id, $data ) {
		// to be written
	}

	/**
	 * Update an existing config for a linode.
	 *
	 * @since 1.0.0
	 *
	 * @param int   $linode_id The ID of the linode the config belongs to.
	 * @param int   $config_id The ID of the config to update.
	 * @param array $data      The properties of the new Linode.
	 *
	 * @return bool Wether or not the update was successful.
	 */
	public function modify_linode_config( $linode_id, $config_id, array $data ) {
		// to be written
	}

	/**
	 * Delete an existing config for a linode.
	 *
	 * @since 1.0.0
	 *
	 * @param int   $linode_id The ID of the linode the config belongs to.
	 * @param int   $config_id The ID of the config to delete.
	 *
	 * @return bool Wether or not the delete was successful.
	 */
	public function delete_linode_config( $linode_id, $config_id ) {
		// to be written
	}

	// =========================
	// ! -- Linode IP Objects
	// =========================

	/**
	 * Retrieve a list of ips for a linode.
	 *
	 * @since 1.0.0
	 *
	 * @param int   $linode_id The ID of the linode to get ips from.
	 * @param array $filter    Optional Arguments for filtering the list request.
	 *
	 * @return array The list of Linode_IP objects.
	 */
	public function list_linode_ips( $linode_id, array $filter = array() ) {
		// to be written
	}

	/**
	 * Retrieve a single ip for a linode.
	 *
	 * @since 1.0.0
	 *
	 * @param int $linode_id The ID of the linode the ip belongs to.
	 * @param int $ip_id     The ID of the ip to retrieve.
	 *
	 * @return Linode_IP The ip object.
	 */
	public function get_linode_ip( $linode_id, $ip_id ) {
		// to be written
	}

	/**
	 * Create a new ip for a linode.
	 *
	 * @since 1.0.0
	 *
	 * @param int   $linode_id The ID of the linode to create the ip for.
	 * @param array $data      The properties of the new ip.
	 *
	 * @return Linode_IP The ip object.
	 */
	public function create_linode_ip( $linode_id, $data ) {
		// to be written
	}

	/**
	 * Update an existing ip for a linode.
	 *
	 * @since 1.0.0
	 *
	 * @param int   $linode_id The ID of the linode the ip belongs to.
	 * @param int   $ip_id     The ID of the ip to update.
	 * @param array $data      The properties of the new Linode.
	 *
	 * @return bool Wether or not the update was successful.
	 */
	public function modify_linode_ip( $linode_id, $ip_id, array $data ) {
		// to be written
	}

	/**
	 * Delete an existing ip for a linode.
	 *
	 * @since 1.0.0
	 *
	 * @param int   $linode_id The ID of the linode the ip belongs to.
	 * @param int   $ip_id     The ID of the ip to delete.
	 *
	 * @return bool Wether or not the delete was successful.
	 */
	public function delete_linode_ip( $linode_id, $ip_id ) {
		// to be written
	}

	// =========================
	// ! - Domain Objects
	// =========================

	/**
	 * Retrieve a list of domains.
	 *
	 * @since 1.0.0
	 *
	 * @param array $filter Optional Arguments for filtering the list request.
	 *
	 * @return array The list of Domain objects.
	 */
	public function list_domains( array $filter = array() ) {
		// to be written
	}

	/**
	 * Retrieve a single domain.
	 *
	 * @since 1.0.0
	 *
	 * @param int $domain_id The ID of the domain to retrieve.
	 *
	 * @return Domain The domain object.
	 */
	public function get_domain( $domain_id ) {
		// to be written
	}

	/**
	 * Create a new domain.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data The properties of the new Domain.
	 *
	 * @return Domain The domain object.
	 */
	public function create_domain( $data ) {
		// to be written
	}

	/**
	 * Update an existing domain.
	 *
	 * @since 1.0.0
	 *
	 * @param int   $domain_id The ID of the domain to update.
	 * @param array $data    The properties of the new Domain.
	 *
	 * @return bool Wether or not the update was successful.
	 */
	public function modify_domain( $domain_id, array $data ) {
		// to be written
	}

	/**
	 * Delete an existing domain.
	 *
	 * @since 1.0.0
	 *
	 * @param int $domain_id The ID of the domain to delete.
	 *
	 * @return bool Wether or not the delete was successful.
	 */
	public function delete_domain( $domain_id ) {
		// to be written
	}

	// =========================
	// ! -- Domain Record Objects
	// =========================

	/**
	 * Retrieve a list of records for a domain.
	 *
	 * @since 1.0.0
	 *
	 * @param int   $domain_id The ID of the domain to get records from.
	 * @param array $filter  Optional Arguments for filtering the list request.
	 *
	 * @return array The list of Domain_Record objects.
	 */
	public function list_domain_records( $domain_id, array $filter = array() ) {
		// to be written
	}

	/**
	 * Retrieve a single record for a domain.
	 *
	 * @since 1.0.0
	 *
	 * @param int $domain_id   The ID of the domain the record belongs to.
	 * @param int $record_id The ID of the record to retrieve.
	 *
	 * @return Domain_Record The record object.
	 */
	public function get_domain_record( $domain_id, $record_id ) {
		// to be written
	}

	/**
	 * Create a new record for a domain.
	 *
	 * @since 1.0.0
	 *
	 * @param int   $domain_id The ID of the domain to create the record for.
	 * @param array $data    The properties of the new record.
	 *
	 * @return Domain_Record The record object.
	 */
	public function create_domain_record( $domain_id, $data ) {
		// to be written
	}

	/**
	 * Update an existing record for a domain.
	 *
	 * @since 1.0.0
	 *
	 * @param int   $domain_id   The ID of the domain the record belongs to.
	 * @param int   $record_id The ID of the record to update.
	 * @param array $data      The properties of the new Domain.
	 *
	 * @return bool Wether or not the update was successful.
	 */
	public function modify_domain_record( $domain_id, $record_id, array $data ) {
		// to be written
	}

	/**
	 * Delete an existing record for a domain.
	 *
	 * @since 1.0.0
	 *
	 * @param int $domain_id   The ID of the domain the record belongs to.
	 * @param int $record_id The ID of the record to delete.
	 *
	 * @return bool Wether or not the delete was successful.
	 */
	public function delete_domain_record( $domain_id, $record_id ) {
		// to be written
	}

	// =========================
	// ! - StackScript Objects
	// =========================

	/**
	 * Retrieve a list of stackscripts.
	 *
	 * @since 1.0.0
	 *
	 * @param array $filter Optional Arguments for filtering the list request.
	 *
	 * @return array The list of StackScript objects.
	 */
	public function list_stackscripts( array $filter = array() ) {
		// to be written
	}

	/**
	 * Retrieve a single stackscript.
	 *
	 * @since 1.0.0
	 *
	 * @param int $stackscript_id The ID of the stackscript to retrieve.
	 *
	 * @return StackScript The stackscript object.
	 */
	public function get_stackscript( $stackscript_id ) {
		// to be written
	}

	/**
	 * Create a new stackscript.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data The properties of the new StackScript.
	 *
	 * @return StackScript The stackscript object.
	 */
	public function create_stackscript( $data ) {
		// to be written
	}

	/**
	 * Update an existing stackscript.
	 *
	 * @since 1.0.0
	 *
	 * @param int   $stackscript_id The ID of the stackscript to update.
	 * @param array $data           The properties of the new StackScript.
	 *
	 * @return bool Wether or not the update was successful.
	 */
	public function modify_stackscript( $stackscript_id, array $data ) {
		// to be written
	}

	/**
	 * Delete an existing stackscript.
	 *
	 * @since 1.0.0
	 *
	 * @param int $stackscript_id The ID of the stackscript to delete.
	 *
	 * @return bool Wether or not the delete was successful.
	 */
	public function delete_stackscript( $stackscript_id ) {
		// to be written
	}
}
