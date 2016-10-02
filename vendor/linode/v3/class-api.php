<?php
/**
 * The Linode API (version 3).
 *
 * @package Box_Spawner
 * @subpackage Linode
 *
 * @since 1.0.0
 */
namespace BoxSpawner\Linode\V3;

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
	 * The API key for all requests.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $api_key;

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
		$this->api_key = $options['api_key'];
	}

	// =========================
	// ! Request Handling
	// =========================

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
		$data['API_ACTION'] = $endpoint;

		return parent::request( $data );
	}

	/**
	 * Set the URL of the request.
	 *
	 * @since 1.0.0
	 *
	 * @param resource     $curl    The cURL handle of the request.
	 */
	protected function get_request_url( $data, $options ) {
		return 'https://api.linode.com/?' . http_build_query( $data );
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
		$headers = array(
			'Authorization: Basic ' . base64_encode( "api_key:{$this->api_key}" ),
		);

		return $headers;
	}

	/**
	 * Determine the body of the request.
	 *
	 * @since 1.0.0
	 *
	 * @param string|array $data    The data to send in the request.
	 * @param array        $options The options of the request.
	 *
	 * @return string|array The body for the request.
	 */
	protected function get_request_body( $data, $options ) {
		return null;
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

		$json = $result['body'];

		if ( ! isset( $json['DATA'] ) ) {
			throw new \BoxSpawner\InvalidResponseException( 'Unrecognized response format. "DATA" entry should be present.' );
		}

		if ( isset( $json['ERRORARRAY'] ) && count( $json['ERRORARRAY'] ) > 0 ) {
			$error = $json['ERRORARRAY'][0];
			if ( $error['ERRORCODE'] !== 0 ) {
				throw new \BoxSpawner\ErrorResponseException( 'Linode API Error: ' . $error['ERRORMESSAGE'] );
			}
		}

		// Get the response data
		$response = $json['DATA'];

		// Standardize the keys of the results to uppercase
		$formatted = array();

		if ( array_keys( $response ) === range( 0, count( $response ) - 1 ) ) {
			// Response is a list, loop through and standardies entries
			foreach ( $response as $entry ) {
				// Convert all keys to uppercase
				$keys = array_map( 'strtoupper', array_keys( $entry ) );
				$values = array_values( $entry );

				$formatted[] = array_combine( $keys, $values );
			}
		} else {
			$keys = array_map( 'strtoupper', array_keys( $response ) );
			$values = array_values( $response );

			$formatted = array_combine( $keys, $values );
		}

		$response = $formatted;

		return $response;
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
	 * @param array $filter Optional UNUSED.
	 *
	 * @return array The list of datacenters.
	 */
	public function list_datacenters( array $filter = array() ) {
		if ( ! isset( $this->cache['datacenters'] ) ) {
			$this->cache['datacenters'] = $this->request( 'avail.datacenters' );
		}

		return $this->cache['datacenters'];
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
		$datacenters = $this->list_datacenters();

		foreach ( $datacenters as $datacenter ) {
			if ( $datacenter['DATACENTERID'] == $datacenter_id ) {
				return $datacenter;
			}
		}

		return false;
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
	public function list_distributions( array $filter = array() ) {
		if ( ! isset( $this->cache['distributions'] ) ) {
			$this->cache['distributions'] = $this->request( 'avail.distributions', $filter );
		}

		return $this->cache['distributions'];
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
		$results = $this->list_distributions( array(
			'DISTRIBUTIONID' => $distribution_id,
		) );

		return $results[0];
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
	public function list_kernels( array $filter = array() ) {
		if ( ! isset( $this->cache['kernels'] ) ) {
			$this->cache['kernels'] = $this->request( 'avail.kernels', $filter );
		}

		return $this->cache['kernels'];
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
		$kernels = $this->list_kernels();

		foreach ( $kernels as $kernel ) {
			if ( $kernels['KERNELID'] == $kernel_id ) {
				return $kernel;
			}
		}

		return false;
	}

	// =========================
	// ! - Plans
	// =========================

	/**
	 * Retrieve a list of plans.
	 *
	 * @since 1.0.0
	 *
	 * @param array $filter Optional Arguments for filtering the list request.
	 *
	 * @return array The list of plans.
	 */
	public function list_plans( array $filter = array() ) {
		if ( ! isset( $this->cache['plans'] ) ) {
			$this->cache['plans'] = $this->request( 'avail.linodeplans', $filter );
		}

		return $this->cache['plans'];
	}

	/**
	 * Retrieve a single plan.
	 *
	 * @since 1.0.0
	 *
	 * @param int $kernel_id The ID of the kernel to retrieve.
	 *
	 * @return array The plan information.
	 */
	public function get_plan( $plan_id ) {
		$plans = $this->list_plans();

		foreach ( $plans as $plan ) {
			if ( $plans['PLANID'] == $plan_id ) {
				return $plan;
			}
		}

		return false;
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
		$data = $this->request( 'linode.list', $filter );

		foreach ( $data as $i => $entry ) {
			$id = $entry[ Linode::ID_ATTRIBUTE ];
			$data[ $i ] = new Linode( $this, $id, $entry );
		}

		return $data;
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
		$data = $this->request( 'linode.list', array(
			Linode::ID_ATTRIBUTE => $linode_id,
		) );

		return new Linode( $this, $linode_id, $data[0] );
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
		return new Linode( $this, null, $data );
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
	public function update_linode( $linode_id, array $data ) {
		$data[ Linode::ID_ATTRIBUTE ] = $linode_id;

		return $this->request( 'linode.update', $data );
	}

	/**
	 * Delete an existing linode.
	 *
	 * @since 1.0.0
	 *
	 * @param int   $linode_id The ID of the linode to delete.
	 * @param array $data      Optional The options for the delete request.
	 *
	 * @return bool Wether or not the delete was successful.
	 */
	public function delete_linode( $linode_id, array $data = array() ) {
		$data[ Linode::ID_ATTRIBUTE ] = $linode_id;

		return $this->request( 'linode.delete', $data );
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
		$data = array(
			Linode::ID_ATTRIBUTE => $linode_id,
		);

		if ( ! is_null( $config_id ) ) {
			$data[ Linode_Config::ID_ATTRIBUTE ] = $config_id;
		}

		return $this->request( 'linode.boot', $data );
	}

	/**
	 * Issue a shutdown request for a linode.
	 *
	 * @since 1.0.0
	 *
	 * @param int $linode_id The ID of the linode to boot.
	 *
	 * @return bool Wether or not the boot request was successful.
	 */
	public function shutdown_linode( $linode_id ) {
		return $this->request( 'linode.shutdown', array(
			Linode::ID_ATTRIBUTE => $linode_id,
		) );
	}

	/**
	 * Issue a reboot request for a linode.
	 *
	 * @since 1.0.0
	 *
	 * @param int $linode_id The ID of the linode to boot.
	 * @param int $config_id Optional The ID of the config to reboot with.
	 *
	 * @return bool Wether or not the boot request was successful.
	 */
	public function reboot_linode( $linode_id, $config_id = null ) {
		$data = array(
			Linode::ID_ATTRIBUTE => $linode_id,
		);

		if ( ! is_null( $config_id ) ) {
			$data[ Linode_Config::ID_ATTRIBUTE ] = $config_id;
		}

		return $this->request( 'linode.reboot', $data );
	}

	/**
	 * Clone a linode.
	 *
	 * @since 1.0.0
	 *
	 * @param int $linode_id     The ID of the linode to clone.
	 * @param int $datacenter_id The datacenter to place the linode.
	 * @param int $plan_id       The plan for the linode.
	 * @param int $payment_term  Optional The subscription term.
	 */
	public function duplicate_linode( $linode_id, $datacenter_id, $plan_id, $payment_term = null ) {
		$data = array(
			$this::ID_ATTRIBUTE => $linode_id,
			'DATACENTERID' => $datacenter_id,
			'PLANID' => $plan_id,
			'PAYMENTTERM' => $payment_term,
		);

		return $this->api->request( 'linode.clone', $data );
	}

	/**
	 * Convert a linode's hpyervisor from Xen to KVM.
	 *
	 * @since 1.0.0
	 */
	public function kvmify_linode( $linode_id ) {
		return $this->api->request( 'linode.clone', array(
			Linode::ID_ATTRIBUTE => $linode_id,
		) );
	}

	/**
	 * Convert a linode's hpyervisor from Xen to KVM.
	 *
	 * @since 1.0.0
	 */
	public function mutate_linode( $linode_id ) {
		return $this->api->request( 'linode.mutate', array(
			Linode::ID_ATTRIBUTE => $linode_id,
		) );
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
		$filter[ Linode::ID_ATTRIBUTE ] = $linode_id;
		$data = $this->request( 'linode.disk.list', $filter );

		foreach ( $data as $i => $entry ) {
			$id = $entry[ Linode_Disk::ID_ATTRIBUTE ];
			$data[ $i ] = new Linode_Disk( $this, $id, $entry, $linode_id );
		}

		return $data;
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
		$data = $this->request( 'linode.disk.list', array(
			Linode::ID_ATTRIBUTE => $linode_id,
			Linode_Disk::ID_ATTRIBUTE => $disk_id,
		) );

		return new Linode_Disk( $this, $disk_id, $data[0], $linode_id );
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
		$data[ Linode::ID_ATTRIBUTE ] = $linode_id;

		return new Linode_Disk( $this, null, $data );
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
	public function update_linode_disk( $linode_id, $disk_id, array $data ) {
		$data[ Linode::ID_ATTRIBUTE ] = $linode_id;
		$data[ Linode_Disk::ID_ATTRIBUTE ] = $disk_id;

		return $this->request( 'linode.disk.update', $data );
	}

	/**
	 * Delete an existing disk for a linode.
	 *
	 * @since 1.0.0
	 *
	 * @param int $linode_id The ID of the linode the disk belongs to.
	 * @param int $disk_id   The ID of the disk to delete.
	 *
	 * @return int The ID of the job handling the request.
	 */
	public function delete_linode_disk( $linode_id, $disk_id ) {
		$result = $this->request( 'linode.disk.update', array(
			Linode::ID_ATTRIBUTE => $linode_id,
			Linode_Disk::ID_ATTRIBUTE => $disk_id,
		) );

		return $response['JOBID'];
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
	 * @return int The ID of the job handling the request.
	 */
	public function resize_linode_disk( $linode_id, $disk_id, $size ) {
		$result = $this->request( 'linode.disk.update', array(
			Linode::ID_ATTRIBUTE => $linode_id,
			Linode_Disk::ID_ATTRIBUTE => $disk_id,
			'size' => $size,
		) );

		return $response['JOBID'];
	}

	/**
	 * Duplicate a disk.
	 *
	 * @since 1.0.0
	 *
	 * @return int The ID of the job handling the request.
	 */
	public function duplicate_linode_disk( $linode_id, $disk_id ) {
		$result = $this->api->request( 'linode.disk.duplicate', array(
			Linode::PARENT_ID_ATTRIBUTE => $linode_id,
			Linode_Disk::ID_ATTRIBUTE => $disk_id,
		) );

		return $result['JOBID'];
	}

	/**
	 * Create an image from a linode disk.
	 *
	 * @since 1.0.0
	 *
	 * @return int The ID of the image created.
	 */
	public function imagize_linode_disk( $linode_id, $disk_id, $data ) {
		$data[ Linode::ID_ATTRIBUTE ] = $linode_id;
		$data[ Linode_Disk::ID_ATTRIBUTE ] = $disk_id;

		$result = $this->api->request( 'linode.disk.imageize', $data );

		return $result['JOBID'];
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
		$filter[ Linode::ID_ATTRIBUTE ] = $linode_id;
		$data = $this->request( 'linode.config.list', $filter );

		foreach ( $data as $i => $entry ) {
			$id = $entry[ Linode_Config::ID_ATTRIBUTE ];
			$data[ $i ] = new Linode_Config( $this, $id, $entry, $linode_id );
		}

		return $data;
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
		$data = $this->request( 'linode.config.list', array(
			Linode::ID_ATTRIBUTE => $linode_id,
			Linode_Config::ID_ATTRIBUTE => $config_id,
		) );

		return new Linode_Config( $this, $config_id, $data[0], $linode_id );
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
		$data[ Linode::ID_ATTRIBUTE ] = $linode_id;

		return new Linode_Config( $this, null, $data );
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
	public function update_linode_config( $linode_id, $config_id, array $data ) {
		$data[ Linode::ID_ATTRIBUTE ] = $linode_id;
		$data[ Linode_Config::ID_ATTRIBUTE ] = $config_id;

		return $this->request( 'linode.config.update', $data );
	}

	/**
	 * Delete an existing config for a linode.
	 *
	 * @since 1.0.0
	 *
	 * @param int $linode_id The ID of the linode the config belongs to.
	 * @param int $config_id The ID of the config to delete.
	 *
	 * @return bool Wether or not the delete was successful.
	 */
	public function delete_linode_config( $linode_id, $config_id ) {
		return $this->request( 'linode.config.update', array(
			Linode::ID_ATTRIBUTE => $linode_id,
			Linode_Config::ID_ATTRIBUTE => $config_id,
		) );
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
		$filter[ Linode::ID_ATTRIBUTE ] = $linode_id;
		$data = $this->request( 'linode.ip.list', $filter );

		foreach ( $data as $i => $entry ) {
			$id = $entry[ Linode_IP::ID_ATTRIBUTE ];
			$data[ $i ] = new Linode_IP( $this, $id, $entry, $linode_id );
		}

		return $data;
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
		$data = $this->request( 'linode.ip.list', array(
			Linode::ID_ATTRIBUTE => $linode_id,
			Linode_IP::ID_ATTRIBUTE => $ip_id,
		) );

		return new Linode_IP( $this, $ip_id, $data[0], $linode_id );
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
		$data[ Linode::ID_ATTRIBUTE ] = $linode_id;

		return new Linode_IP( $this, null, $data );
	}

	/**
	 * Update an existing ip for a linode.
	 *
	 * Just directs to setrnds endpoint.
	 *
	 * @since 1.0.0
	 *
	 * @param int   $linode_id The ID of the linode the ip belongs to.
	 * @param int   $ip_id     The ID of the ip to update.
	 * @param array $data      The properties of the new Linode.
	 *
	 * @return bool Wether or not the update was successful.
	 */
	public function update_linode_ip( $linode_id, $ip_id, array $data ) {
		$data[ Linode::ID_ATTRIBUTE ] = $linode_id;
		$data[ Linode_IP::ID_ATTRIBUTE ] = $ip_id;

		return $this->request( 'linode.ip.setrdns', $data );
	}

	/**
	 * Set the rDNS name of a linode ip.
	 *
	 * Just an easier version of update_linode_ip().
	 *
	 * @since 1.0.0
	 *
	 * @param int    $linode_id The ID of the linode the ip belongs to.
	 * @param int    $ip_id     The ID of the ip to swap.
	 * @param string $hostname  The hostname ot set the rDNS to.
	 */
	public function set_linode_ip_rdns( $linode_id, $ip_id, $hostname ) {
		return $this->update_linode_ip( $linode_id, $ip_id, array(
			'Hostname' => $this->id,
		) );
	}

	/**
	 * Exchange a linode IP with another IP.
	 *
	 * @since 1.0.0
	 *
	 * @param int $linode_id    The ID of the linode the ip belongs to.
	 * @param int $ip_id        The ID of the ip to swap.
	 * @param int $target_ip_id The ID of the IP address to swap with.
	 *
	 * @return array The new relationships between the IPs.
	 */
	public function swap_linode_ip_with( $linode_id, $ip_id, $target_ip_id ) {
		return $this->request( 'linode.ip.swap', array(
			Linode::ID_ATTRIBUTE => $linode_id,
			Linode_IP::ID_ATTRIBUTE => $ip_id,
			'withIPAddressID' => $target_ip_id,
		) );
	}

	/**
	 * Transfer a linode IP to another Linode.
	 *
	 * @since 1.0.0
	 *
	 * @param int $linode_id         The ID of the linode the ip belongs to.
	 * @param int $ip_id             The ID of the ip to transfer.
	 * @param int $$target_linode_id The ID of the linode to transfer to.
	 *
	 * @return array The new relationships between the IPs.
	 */
	public function transfer_linode_ip_to( $linode_id, $ip_id, $target_linode_id ) {
		return $this->request( 'linode.ip.swap', array(
			Linode::ID_ATTRIBUTE => $linode_id,
			Linode_IP::ID_ATTRIBUTE => $ip_id,
			'toLinodeID' => $target_linode_id,
		) );
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
	public function update_domain( $domain_id, array $data ) {
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
	public function update_domain_record( $domain_id, $record_id, array $data ) {
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
	public function update_stackscript( $stackscript_id, array $data ) {
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
