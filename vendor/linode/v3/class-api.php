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
	// ! Utilities
	// =========================

	/**
	 * Format the provided data, namely converting all keys to UPPERCASE.
	 *
	 * @since 1.0.0
	 *
	 * @param array &$data The data to sanitize, by reference.
	 */
	public static function sanitize_data( &$data ) {
		$keys = array_map( 'strtoupper', array_keys( $data ) );
		$values = array_values( $data );

		$data = array_combine( $keys, $values );
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
		if ( array_keys( $response ) === range( 0, count( $response ) - 1 ) ) {
			// Response is a list, loop through and standardies entries
			foreach ( $response as $entry ) {
				self::sanitize_data( $entry );
			}
		} else {
			self::sanitize_data( $response );
		}

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
	 * @param bool  $format Optional The format to return (Linode object or attributes array).
	 *
	 * @return array The list of Linode objects.
	 */
	public function list_linodes( array $filter = array(), $format = 'object' ) {
		$data = $this->request( 'linode.list', $filter );

		foreach ( $data as $i => $entry ) {
			$id = $entry[ Linode::ID_ATTRIBUTE ];

			if ( $format == 'object' ) {
				$entry = new Linode( $this, $id, $entry );
			}

			$data[ $i ] = $entry;
		}

		return $data;
	}

	/**
	 * Retrieve a single linode.
	 *
	 * @since 1.0.0
	 *
	 * @param int  $linode_id The ID of the linode to retrieve.
	 * @param bool $format    Optional The format to return (Linode object or attributes array).
	 *
	 * @return Linode|array The linode object.
	 */
	public function get_linode( $linode_id, $format = 'object' ) {
		$result = $this->list_linodes( array(
			Linode::ID_ATTRIBUTE => $linode_id,
		), $format );

		return $result[0];
	}

	/**
	 * Create a new linode.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data   The properties of the new Linode.
	 * @param bool  $format Optional The format to return (Linode object or attributes array).
	 *
	 * @return Linode|array The linode object.
	 */
	public function create_linode( $data, $format = 'object' ) {
		self::sanitize_data( $data );

		if ( ! isset( $data['DATACENTERID'] ) ) {
			throw new \BoxSpawner\MissingParameterException( 'DATACENTERID required when creating a linode.' );
		}
		if ( ! isset( $data['PLANID'] ) ) {
			throw new \BoxSpawner\MissingParameterException( 'PLANID required when creating a linode.' );
		}

		$result = $this->request( 'linode.create', $data );

		if ( $format == 'object' ) {
			return new Linode( $this, $result[ Linode::ID_ATTRIBUTE ] );
		} else {
			return $result;
		}
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

		$result = $this->request( 'linode.update', $data );

		return $result[ Linode::ID_ATTRIBUTE ] == $linode_id;
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

		$result = $this->request( 'linode.delete', $data );

		return $result[ Linode::ID_ATTRIBUTE ] == $linode_id;
	}

	/**
	 * Issue a boot request for a linode.
	 *
	 * @since 1.0.0
	 *
	 * @param int $linode_id The ID of the linode to boot.
	 * @param int $config_id Optional The ID of the config to boot with.
	 *
	 * @return int The ID of the job handling the request.
	 */
	public function boot_linode( $linode_id, $config_id = null ) {
		$data = array(
			Linode::ID_ATTRIBUTE => $linode_id,
		);

		if ( ! is_null( $config_id ) ) {
			$data[ Linode_Config::ID_ATTRIBUTE ] = $config_id;
		}

		$result = $this->request( 'linode.boot', $data );

		return $result['JOBID'];
	}

	/**
	 * Issue a shutdown request for a linode.
	 *
	 * @since 1.0.0
	 *
	 * @param int $linode_id The ID of the linode to boot.
	 *
	 * @return int The ID of the job handling the request.
	 */
	public function shutdown_linode( $linode_id ) {
		$result = $this->request( 'linode.shutdown', array(
			Linode::ID_ATTRIBUTE => $linode_id,
		) );

		return $result['JOBID'];
	}

	/**
	 * Issue a reboot request for a linode.
	 *
	 * @since 1.0.0
	 *
	 * @param int $linode_id The ID of the linode to boot.
	 * @param int $config_id Optional The ID of the config to reboot with.
	 *
	 * @return int The ID of the job handling the request.
	 */
	public function reboot_linode( $linode_id, $config_id = null ) {
		$data = array(
			Linode::ID_ATTRIBUTE => $linode_id,
		);

		if ( ! is_null( $config_id ) ) {
			$data[ Linode_Config::ID_ATTRIBUTE ] = $config_id;
		}

		$result = $this->request( 'linode.reboot', $data );

		return $result['JOBID'];
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
	 *
	 * @return bool Wether or not the cloning was successful.
	 */
	public function duplicate_linode( $linode_id, $datacenter_id, $plan_id, $payment_term = null ) {
		self::sanitize_data( $data );

		$data = array(
			Linode::ID_ATTRIBUTE => $linode_id,
			'DATACENTERID' => $datacenter_id,
			'PLANID' => $plan_id,
			'PAYMENTTERM' => $payment_term,
		);

		$result = $this->request( 'linode.clone', $data );

		return $result[ Linode::ID_ATTRIBUTE ] == $linode_id;
	}

	/**
	 * Convert a linode's hpyervisor from Xen to KVM.
	 *
	 * @since 1.0.0
	 */
	public function kvmify_linode( $linode_id ) {
		$this->request( 'linode.kvmify', array(
			Linode::ID_ATTRIBUTE => $linode_id,
		) );
	}

	/**
	 * Convert a linode's hpyervisor from Xen to KVM.
	 *
	 * @since 1.0.0
	 */
	public function mutate_linode( $linode_id ) {
		$this->request( 'linode.mutate', array(
			Linode::ID_ATTRIBUTE => $linode_id,
		) );
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
	 * @param bool  $format    Optional The format to return (Linode object or attributes array).
	 *
	 * @return array The list of Linode_Config objects.
	 */
	public function list_linode_configs( $linode_id, array $filter = array(), $format = 'object' ) {
		$filter[ Linode_Config::PARENT_ID_ATTRIBUTE ] = $linode_id;
		$data = $this->request( 'linode.config.list', $filter );

		foreach ( $data as $i => $entry ) {
			$id = $entry[ Linode_Config::ID_ATTRIBUTE ];

			if ( $format == 'object' ) {
				$entry = new Linode_Config( $this, $id, $entry, $linode_id );
			}

			$data[ $i ] = $entry;
		}

		return $data;
	}

	/**
	 * Retrieve a single config for a linode.
	 *
	 * @since 1.0.0
	 *
	 * @param int  $linode_id The ID of the linode the config belongs to.
	 * @param int  $config_id The ID of the config to retrieve.
	 * @param bool $format    Optional The format to return (Linode_Config object or attributes array).
	 *
	 * @return Linode_Config|array The config object.
	 */
	public function get_linode_config( $linode_id, $config_id, $format = 'object' ) {
		$result = $this->list_linode_configs( array(
			Linode_Config::PARENT_ID_ATTRIBUTE => $linode_id,
			Linode_Config::ID_ATTRIBUTE => $config_id,
		), $format );

		return $result[0];
	}

	/**
	 * Create a new config for a linode.
	 *
	 * @since 1.0.0
	 *
	 * @param int   $linode_id The ID of the linode to create the config for.
	 * @param array $data      The properties of the new config.
	 * @param bool  $format    Optional The format to return (Linode_Config object or attributes array).
	 *
	 * @return Linode_Config|array The config object.
	 */
	public function create_linode_config( $linode_id, $data, $format = 'object' ) {
		self::sanitize_data( $data );

		if ( ! isset( $data['KERNELID'] ) ) {
			throw new \BoxSpawner\MissingParameterException( 'KERNELID is required when creating a linode config.' );
		}
		if ( ! isset( $data['LABEL'] ) ) {
			throw new \BoxSpawner\MissingParameterException( 'LABEL is required when creating a linode config.' );
		}
		if ( ! isset( $data['DISKLIST'] ) ) {
			throw new \BoxSpawner\MissingParameterException( 'DISKLIST is required when creating a linode config.' );
		}

		$data[ Linode_Config::PARENT_ID_ATTRIBUTE ] = $linode_id;

		$result = $this->request( 'linode.config.create', $data );

		if ( $format == 'object' ) {
			return new Linode_Config( $this, $result[ Linode_Config::ID_ATTRIBUTE ], null, $linode_id );
		} else {
			return $result;
		}
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
		$data[ Linode_Config::PARENT_ID_ATTRIBUTE ] = $linode_id;
		$data[ Linode_Config::ID_ATTRIBUTE ] = $config_id;

		$result = $this->request( 'linode.config.update', $data );

		return $result[ Linode_Config::ID_ATTRIBUTE ] == $config_id;
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
		$result = $this->request( 'linode.config.delete', array(
			Linode_Config::PARENT_ID_ATTRIBUTE => $linode_id,
			Linode_Config::ID_ATTRIBUTE => $config_id,
		) );

		return $result[ Linode_Config::ID_ATTRIBUTE ] == $config_id;
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
	 * @param bool  $format    Optional The format to return (Linode object or attributes array).
	 *
	 * @return array The list of disk objects.
	 */
	public function list_linode_disks( $linode_id, array $filter = array(), $format = 'object' ) {
		$filter[ Linode_Disk::PARENT_ID_ATTRIBUTE ] = $linode_id;
		$data = $this->request( 'linode.disk.list', $filter );

		foreach ( $data as $i => $entry ) {
			$id = $entry[ Linode_Disk::ID_ATTRIBUTE ];

			if ( $format == 'object' ) {
				$entry = new Linode_Disk( $this, $id, $entry, $linode_id );
			}

			$data[ $i ] = $entry;
		}

		return $data;
	}

	/**
	 * Retrieve a single disk for a linode.
	 *
	 * @since 1.0.0
	 *
	 * @param int  $linode_id The ID of the linode the disk belongs to.
	 * @param int  $disk_id   The ID of the disk to retrieve.
	 * @param bool $format    Optional The format to return (Linode_Disk object or attributes array).
	 *
	 * @return Linode_Disk|array The disk object.
	 */
	public function get_linode_disk( $linode_id, $disk_id, $format = 'object' ) {
		$result = $this->get_linode_disk( array(
			Linode_Disk::PARENT_ID_ATTRIBUTE => $linode_id,
			Linode_Disk::ID_ATTRIBUTE => $disk_id,
		), $format );

		return $result[0];
	}

	/**
	 * Create a new disk for a linode.
	 *
	 * @since 1.0.0
	 *
	 * @param int   $linode_id The ID of the linode to create the disk for.
	 * @param array $data      The properties of the new disk.
	 * @param bool  $format    Optional The format to return (Linode_Disk object or attributes array).
	 *
	 * @return Linode_Disk|array The disk object.
	 */
	public function create_linode_disk( $linode_id, $data, $format = 'object' ) {
		self::sanitize_data( $data );

		$type = null;
		if ( isset( $data['DISTRIBUTIONID'] ) ) {
			$type = 'distribution';
			$required = array( 'DISTRIBUTIONID', 'LABEL', 'SIZE', 'ROOTPASS' );
		} else if ( isset( $data['IMAGEID'] ) ) {
			$type = 'image';
			$required = array( 'IMAGEID' );
		} else if ( isset( $data['STACKSCRIPTID'] ) ) {
			$type = 'stackscript';
			$required = array( 'STACKSCRIPTID', 'STACKSCRIPTUDFRESPONSES', 'LABEL', 'SIZE', 'ROOTPASS' );
		} else {
			$required = array( 'LABEL', 'TYPE', 'SIZE' );
		}

		foreach ( $required as $key ) {
			if ( ! isset( $data[ $key ] ) ) {
				throw new \BoxSpawner\MissingParameterException( "{$key} is required when creating a linode disk" . ( $type ?  " from a {$type}" : '' ) . "." );
			}
		}

		$method = 'create' . ( $type ? "from{$type}" : '' );

		$data[ Linode_Disk::PARENT_ID_ATTRIBUTE ] = $linode_id;

		$result = $this->request( 'linode.disk.' . $method, $data );

		if ( $format == 'object' ) {
			return new Linode_Disk( $this, $result[ Linode_Disk::ID_ATTRIBUTE ], null, $linode_id );
		} else {
			return $result;
		}
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
		$data[ Linode_Disk::PARENT_ID_ATTRIBUTE ] = $linode_id;
		$data[ Linode_Disk::ID_ATTRIBUTE ] = $disk_id;

		$result = $this->request( 'linode.disk.update', $data );

		return $result[ Linode_Disk::ID_ATTRIBUTE ] == $disk_id;
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
		$result = $this->request( 'linode.disk.delete', array(
			Linode_Disk::PARENT_ID_ATTRIBUTE => $linode_id,
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
		$result = $this->request( 'linode.disk.resize', array(
			Linode_Disk::PARENT_ID_ATTRIBUTE => $linode_id,
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
		$result = $this->request( 'linode.disk.duplicate', array(
			Linode_Disk::PARENT_ID_ATTRIBUTE => $linode_id,
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
		$data[ Linode_Disk::PARENT_ID_ATTRIBUTE ] = $linode_id;
		$data[ Linode_Disk::ID_ATTRIBUTE ] = $disk_id;

		$result = $this->request( 'linode.disk.imageize', $data );

		return $result['JOBID'];
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
	 * @param bool  $format    Optional The format to return (Linode object or attributes array).
	 *
	 * @return array The list of Linode_IP objects.
	 */
	public function list_linode_ips( $linode_id, array $filter = array(), $format = 'object' ) {
		$filter[ Linode_IP::PARENT_ID_ATTRIBUTE ] = $linode_id;
		$data = $this->request( 'linode.ip.list', $filter );

		foreach ( $data as $i => $entry ) {
			$id = $entry[ Linode_IP::ID_ATTRIBUTE ];

			if ( $format == 'object' ) {
				$entry = new Linode_IP( $this, $id, $entry, $linode_id );
			}

			$data[ $i ] = $entry;
		}

		return $data;
	}

	/**
	 * Retrieve a single ip for a linode.
	 *
	 * @since 1.0.0
	 *
	 * @param int  $linode_id The ID of the linode the ip belongs to.
	 * @param int  $ip_id     The ID of the ip to retrieve.
	 * @param bool $format    Optional The format to return (Linode_IP object or attributes array).
	 *
	 * @return Linode_IP|array The ip object.
	 */
	public function get_linode_ip( $linode_id, $ip_id, $format = 'object' ) {
		$result = $this->list_linode_ips( array(
			Linode_IP::PARENT_ID_ATTRIBUTE => $linode_id,
			Linode_IP::ID_ATTRIBUTE => $ip_id,
		), $format );

		return $result[0];
	}

	/**
	 * Create a new ip for a linode.
	 *
	 * @since 1.0.0
	 *
	 * @param int   $linode_id The ID of the linode to create the ip for.
	 * @param array $data      The properties of the new ip.
	 * @param bool  $format    Optional The format to return (Linode_IP object or attributes array).
	 *
	 * @return Linode_IP|array The ip object.
	 */
	public function create_linode_ip( $linode_id, $data, $format = 'object' ) {
		self::sanitize_data( $data );

		if ( ! isset( $data['TYPE'] ) ) {
			throw new \BoxSpawner\MissingParameterException( 'TYPE is required when adding a linode ip.' );
		} else if ( ! in_array( $data['TYPE'], array( 'public', 'private' ) ) ) {
			throw new \BoxSpawner\InvalidParameterException( 'TYPE must be "public" or "private" when adding a linode ip.' );
		}

		$method = 'add' . $data['TYPE'];

		$result = $this->request( 'linode.ip.' . $method, array(
			Linode_IP::PARENT_ID_ATTRIBUTE => $linode_id,
		) );

		if ( $format == 'object' ) {
			return new Linode_IP( $this, $result[ Linode_IP::ID_ATTRIBUTE ], null, $linode_id );
		} else {
			return $result;
		}
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
		$data[ Linode_IP::PARENT_ID_ATTRIBUTE ] = $linode_id;
		$data[ Linode_IP::ID_ATTRIBUTE ] = $ip_id;

		$result = $this->request( 'linode.ip.setrdns', $data );

		return $result[ Linode_IP::ID_ATTRIBUTE ] == $ip_id;
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
			Linode_IP::PARENT_ID_ATTRIBUTE => $linode_id,
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
			Linode_IP::PARENT_ID_ATTRIBUTE => $linode_id,
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
	 * @param bool  $format Optional The format to return (Linode object or attributes array).
	 *
	 * @return array The list of Domain objects.
	 */
	public function list_domains( array $filter = array(), $format = 'object' ) {
		$data = $this->request( 'domain.list', $filter );

		foreach ( $data as $i => $entry ) {
			$id = $entry[ Domain::ID_ATTRIBUTE ];

			if ( $format == 'object' ) {
				$entry = new Domain( $this, $id, $entry );
			}

			$data[ $i ] = $entry;
		}

		return $data;
	}

	/**
	 * Retrieve a single domain.
	 *
	 * @since 1.0.0
	 *
	 * @param int  $domain_id The ID of the domain to retrieve.
	 * @param bool $format    Optional The format to return (Domain object or attributes array).
	 *
	 * @return Domain|array The domain object.
	 */
	public function get_domain( $domain_id, $format = 'object' ) {
		$result = $this->list_domains( array(
			Domain::ID_ATTRIBUTE => $domain_id,
		), $format );

		return $result[0];
	}

	/**
	 * Create a new domain.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data   The properties of the new Domain.
	 * @param bool  $format Optional The format to return (Domain object or attributes array).
	 *
	 * @return Domain|array The domain object.
	 */
	public function create_domain( $data, $format = 'object' ) {
		self::sanitize_data( $data );

		if ( ! isset( $data['DOMAIN'] ) ) {
			throw new \BoxSpawner\MissingParameterException( 'DOMAIN required when creating a domain.' );
		}
		if ( ! isset( $data['TYPE'] ) ) {
			throw new \BoxSpawner\MissingParameterException( 'TYPE required when creating a domain.' );
		} else if ( ! in_array( $data['TYPE'], array( 'master', 'slave' ) ) ) {
			throw new \BoxSpawner\InvalidParameterException( 'TYPE must be "master" or "slave" when creating a domain.' );
		}

		$result = $this->request( 'domain.create', $data );

		if ( $format == 'object' ) {
			return new Domain( $this, $result[ Domain::ID_ATTRIBUTE ] );
		} else {
			return $result;
		}
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
		$data[ Domain::ID_ATTRIBUTE ] = $linode_id;

		$result = $this->request( 'domain.update', $data );

		return $result[ Domain::ID_ATTRIBUTE ] == $domain_id;
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
		$result = $this->request( 'domain.delete', array(
			Domain::ID_ATTRIBUTE => $domain_id,
		) );

		return $result[ Domain::ID_ATTRIBUTE ] == $domain_id;
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
	 * @param array $filter    Optional Arguments for filtering the list request.
	 * @param bool  $format    Optional The format to return (Linode object or attributes array).
	 *
	 * @return array The list of Domain_Record objects.
	 */
	public function list_domain_records( $domain_id, array $filter = array(), $format = 'object' ) {
		$filter[ Domain_Record::PARENT_ID_ATTRIBUTE ] = $linode_id;
		$data = $this->request( 'domain.resource.list', $filter );

		foreach ( $data as $i => $entry ) {
			$id = $entry[ Domain_Record::ID_ATTRIBUTE ];

			if ( $format == 'object' ) {
				$entry = new Domain_Record( $this, $id, $entry, $linode_id );
			}

			$data[ $i ] = $entry;
		}

		return $data;
	}

	/**
	 * Retrieve a single record for a domain.
	 *
	 * @since 1.0.0
	 *
	 * @param int  $domain_id The ID of the domain the record belongs to.
	 * @param int  $record_id The ID of the record to retrieve.
	 * @param bool $format    Optional The format to return (Domain_Record object or attributes array).
	 *
	 * @return Domain_Record|array The record object.
	 */
	public function get_domain_record( $domain_id, $record_id, $format = 'object' ) {
		$result = $this->list_domain_records( array(
			Domain_Record::PARENT_ID_ATTRIBUTE => $domain_id,
			Domain_Record::ID_ATTRIBUTE => $record_id,
		), $format );

		return $result[0];
	}

	/**
	 * Create a new record for a domain.
	 *
	 * @since 1.0.0
	 *
	 * @param int   $domain_id The ID of the domain to create the record for.
	 * @param array $data      The properties of the new record.
	 * @param bool  $format    Optional The format to return (Domain_Record object or attributes array).
	 *
	 * @return Domain_Record|array The record object.
	 */
	public function create_domain_record( $domain_id, $data, $format = 'object' ) {
		self::sanitize_data( $data );

		if ( ! isset( $data['TYPE'] ) ) {
			throw new \BoxSpawner\MissingParameterException( 'TYPE required when creating a domain record.' );
		} else if ( ! in_array( $data['TYPE'], array( 'NS', 'MX', 'A', 'AAAA', 'CNAME', 'TXT', 'SRV' ) ) ) {
			throw new \BoxSpawner\InvalidParameterException( 'TYPE must be one of NS, MX, A, AAAA, CNAME, TXT, or SRV when creating a domain record.' );
		}

		$data[ Domain_Record::PARENT_ID_ATTRIBUTE ] = $domain_id;

		$result = $this->request( 'domain.resource.create', $data );

		if ( $format == 'object' ) {
			return new Linode_Config( $this, $result[ Domain_Record::ID_ATTRIBUTE ], null, $domain_id );
		} else {
			return $result;
		}
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
		$data[ Domain_Record::PARENT_ID_ATTRIBUTE ] = $domain_id;
		$data[ Domain_Record::ID_ATTRIBUTE ] = $record_id;

		$result = $this->request( 'domain.resource.update', $data );

		return $result[ Domain_Record::ID_ATTRIBUTE ] == $record_id;
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
		$data[ Domain_Record::PARENT_ID_ATTRIBUTE ] = $domain_id;
		$data[ Domain_Record::ID_ATTRIBUTE ] = $record_id;

		$result = $this->request( 'domain.resource.delete', $data );

		return $result[ Domain_Record::ID_ATTRIBUTE ] == $record_id;
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
	 * @param bool  $format Optional The format to return (Linode object or attributes array).
	 *
	 * @return array The list of StackScript objects.
	 */
	public function list_stackscripts( array $filter = array(), $format = 'object' ) {
		$data = $this->request( 'stackscript.list', $filter );

		foreach ( $data as $i => $entry ) {
			$id = $entry[ StackScript::ID_ATTRIBUTE ];

			if ( $format == 'object' ) {
				$entry = new StackScript( $this, $id, $entry );
			}

			$data[ $i ] = $entry;
		}

		return $data;
	}

	/**
	 * Retrieve a single stackscript.
	 *
	 * @since 1.0.0
	 *
	 * @param int  $stackscript_id The ID of the stackscript to retrieve.
	 * @param bool $format         Optional The format to return (Linode object or attributes array).
	 *
	 * @return StackScript|array The stackscript object.
	 */
	public function get_stackscript( $stackscript_id, $format = 'object' ) {
		$result = $this->list_stackscripts( array(
			StackScript::ID_ATTRIBUTE => $stackscript_id,
		), $format );

		return $result[0];
	}

	/**
	 * Create a new stackscript.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data   The properties of the new StackScript.
	 * @param bool  $format Optional The format to return (Linode object or attributes array).
	 *
	 * @return StackScript|array The stackscript object.
	 */
	public function create_stackscript( $data, $format = 'object' ) {
		self::sanitize_data( $data );

		if ( ! isset( $data['LABEL'] ) ) {
			throw new \BoxSpawner\MissingParameterException( 'LABEL required when creating a stackscript.' );
		}
		if ( ! isset( $data['DISTRIBUTIONIDLIST'] ) ) {
			throw new \BoxSpawner\MissingParameterException( 'DatacenterID required when creating a stackscript.' );
		}
		if ( ! isset( $data['SCRIPT'] ) ) {
			throw new \BoxSpawner\MissingParameterException( 'SCRIPT required when creating a stackscript.' );
		}

		$result = $this->request( 'stackscript.create', $data );

		if ( $format == 'object' ) {
			return new StackScript( $this, $result[ StackScript::ID_ATTRIBUTE ] );
		} else {
			return $result;
		}
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
		$data[ StackScript::ID_ATTRIBUTE ] = $stackscript_id;

		$result = $this->request( 'stackscript.update', $data );

		return $result[ StackScript::ID_ATTRIBUTE ] == $stackscript_id;
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
		$result = $this->request( 'stackscript.delete', array(
			StackScript::ID_ATTRIBUTE => $stackscript_id,
		) );

		return $result[ StackScript::ID_ATTRIBUTE ] == $stackscript_id;
	}
}
