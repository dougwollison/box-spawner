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
	 * Resolve a classname by prepending the namespace.
	 *
	 * @since 1.0.0
	 *
	 * @param string &$class The class to resolve.
	 */
	public static function resolve_class( &$class ) {
		if ( strpos( $class, '\\' ) === false ){
			$class = __NAMESPACE__ . '\\' . $class;
		}
	}

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

		return parent::do_request( $data );
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
	 * @return array The "DATA" portion of the JSON response.
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

	/**
	 * Shared logic for avail.* list methods.
	 *
	 * @since 1.0.0
	 *
	 * @param string $list The type of objects (plural) to request.
	 *
	 * @return array The list of objects.
	 */
	protected function list_avails( $list ) {
		if ( ! isset( $this->cache[ $list ] ) ) {
			$this->cache[ $list ] = $this->request( "avail.{$list}" );
		}

		return $this->cache[ $list ];
	}

	/**
	 * Shared loginc for avail.* get methods.
	 *
	 * @since 1.0.0
	 *
	 * @param string $list    The list to use.
	 * @param string $id_attr The ID attribute to use.
	 * @param int    $id      The ID of the object to retrieve.
	 *
	 * @param array The object information.
	 */
	protected function get_avail( $list, $id_attr, $id ) {
		$objects = $this->list_avails( $list );

		foreach ( $objects as $object ) {
			if ( $object[ $id_attr ] == $id ) {
				return $object;
			}
		}

		return false;
	}

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
		return $this->list_avails( 'datacenters' );
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
		return $this->get_avail( 'datacenters', 'DATACENTERID', $datacenter_id );
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
		return $this->list_avails( 'datacenters' );
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
		return $this->get_avail( 'datacenters', 'DATACENTERID', $datacenter_id );
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
		return $this->list_avails( 'kernels' );
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
		return $this->get_avail( 'kernels', 'KERNELID', $datacenter_id );
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
		return $this->list_avails( 'plans' );
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
		return $this->get_avail( 'plans', 'PLANID', $plan_id );
	}

	// ==================================================
	// ! Editable Objects
	// ==================================================

	/**
	 * Shared logic for object list methods.
	 *
	 * @since 1.0.0
	 *
	 * @param string $endpoint The endpoint prefix.
	 * @param string $class    The class to use.
	 * @param array  $filter   Optional Arguments for filtering the list request.
	 * @param bool   $format   Optional The format to return (Linode object or attributes array).
	 *
	 * @return array The list of objects.
	 */
	protected function list_objects( $endpoint, $class, array $filter = array(), $format = 'object' ) {
		self::resolve_class( $class );

		$endpoint = "{$endpoint}.list";
		$data = $this->request( $endpoint, $filter );

		if ( $format == 'object' ) {
			// Convert to list of objects
			foreach ( $data as $i => $entry ) {
				$id = $entry[ $class::ID_ATTRIBUTE ];
				$entry = new $class( $this, $id, $entry );

				$data[ $i ] = $entry;
			}
		}

		return $data;
	}

	/**
	 * Shared logic for object get methods.
	 *
	 * @since 1.0.0
	 *
	 * @param string $endpoint  The endpoint prefix.
	 * @param string $class     The class to use.
	 * @param string $object_id The id of the object to get.
	 * @param bool   $format    Optional The format to return (objects or attribute arrays).
	 *
	 * @return array The list of objects.
	 */
	protected function get_object( $endpoint, $class, $object_id, $format = 'object' ) {
		self::resolve_class( $class );

		// Call list_objects, filtering by object id
		$result = $this->list_objects( $endpoint, $class, array(
			$class::ID_ATTRIBUTE => $object_id,
		), $format );

		return $result[0];
	}

	/**
	 * Shared logic for object create methods.
	 *
	 * @since 1.0.0
	 *
	 * @param string $endpoint The endpoint prefix.
	 * @param string $class    The class to use.
	 * @param array  $data     The properties of the new object.
	 * @param bool   $format   Optional The format to return (object or attributes array).
	 *
	 * @return array The list of objects.
	 */
	protected function create_object( $endpoint, $class, array $data, $format = 'object' ) {
		self::resolve_class( $class );

		self::sanitize_data( $data );

		$endpoint = "{$endpoint}.create";
		$result = $this->request( $endpoint, $data );

		if ( $format == 'object' ) {
			return new $class( $this, $result[ $class::ID_ATTRIBUTE ] );
		} else {
			return $result;
		}
	}

	/**
	 * Shared logic for object update methods.
	 *
	 * @since 1.0.0
	 *
	 * @param string $endpoint  The endpoint prefix.
	 * @param string $class     The class to use.
	 * @param string $object_id The id of the object to update.
	 * @param array  $data      The properties to update.
	 *
	 * @return array The list of objects.
	 */
	protected function update_object( $endpoint, $class, $object_id, array $data ) {
		self::resolve_class( $class );

		$data[ $class::ID_ATTRIBUTE ] = $linode_id;

		$endpoint = "{$endpoint}.update";
		$result = $this->request( $endpoint, $data );

		return $result[ $class::ID_ATTRIBUTE ] == $linode_id;
	}

	/**
	 * Shared logic for object delete methods.
	 *
	 * @since 1.0.0
	 *
	 * @param string $endpoint  The endpoint prefix.
	 * @param string $class     The class to use.
	 * @param string $object_id The id of the object to update.
	 * @param array  $data      The properties to update.
	 *
	 * @return array The list of objects.
	 */
	protected function delete_object( $endpoint, $class, $object_id, array $data = array() ) {
		self::resolve_class( $class );

		$data[ $class::ID_ATTRIBUTE ] = $object_id;

		$endpoint = "{$endpoint}.delete";
		$result = $this->request( $endpoint, $data );

		return $result[ $class::ID_ATTRIBUTE ] == $object_id;
	}

	/**
	 * Shared logic for asset list methods.
	 *
	 * @since 1.0.0
	 *
	 * @param string $endpoint  The endpoint prefix.
	 * @param string $class     The class to use.
	 * @param int    $parent_id The ID of the parent object.
	 * @param array  $filter    Optional Arguments for filtering the list request.
	 * @param bool   $format    Optional The format to return (Linode asset or attributes array).
	 *
	 * @return array The list of assets.
	 */
	protected function list_assets( $endpoint, $class, $parent_id, $filter, $format ) {
		self::resolve_class( $class );

		$filter[ $class::PARENT_ID_ATTRIBUTE ] = $parent_id;

		return $this->list_objects( $endpoint, $class, $filter, $format );
	}

	/**
	 * Shared logic for asset get methods.
	 *
	 * @since 1.0.0
	 *
	 * @param string $endpoint  The endpoint prefix.
	 * @param string $class     The class to use.
	 * @param int    $parent_id The ID of the parent object.
	 * @param string $asset_id  The id of the asset to get.
	 * @param bool   $format    Optional The format to return (assets or attribute arrays).
	 *
	 * @return array The list of assets.
	 */
	protected function get_asset( $endpoint, $class, $parent_id, $asset_id, $format = 'asset' ) {
		self::resolve_class( $class );

		$result = $this->list_assets( $endpoint, $class, $parent_id, array(
			$class::ID_ATTRIBUTE => $disk_id,
		), $format );

		return $result[0];
	}

	/**
	 * Shared logic for asset create methods.
	 *
	 * @since 1.0.0
	 *
	 * @param string $endpoint  The endpoint prefix.
	 * @param string $class     The class to use.
	 * @param int    $parent_id The ID of the parent object.
	 * @param array  $data      The properties of the new asset.
	 * @param bool   $format    Optional The format to return (asset or attributes array).
	 *
	 * @return array The list of assets.
	 */
	protected function create_asset( $endpoint, $class, $parent_id, array $data, $format = 'asset' ) {
		self::resolve_class( $class );

		$data[ $class::PARENT_ID_ATTRIBUTE ] = $parent_id;

		return $this->create_object( $endpoint, $class, $data, $format );
	}

	/**
	 * Shared logic for asset update methods.
	 *
	 * @since 1.0.0
	 *
	 * @param string $endpoint  The endpoint prefix.
	 * @param string $class     The class to use.
	 * @param int    $parent_id The ID of the parent object.
	 * @param string $asset_id  The id of the asset to update.
	 * @param array  $data      The properties to update.
	 *
	 * @return array The list of assets.
	 */
	protected function update_asset( $endpoint, $class, $parent_id, $asset_id, array $data ) {
		self::resolve_class( $class );

		$data[ $class::PARENT_ID_ATTRIBUTE ] = $parent_id;

		return $this->update_object( $endpoint, $class, $asset_id, $data );
	}

	/**
	 * Shared logic for asset delete methods.
	 *
	 * @since 1.0.0
	 *
	 * @param string $endpoint  The endpoint prefix.
	 * @param string $class     The class to use.
	 * @param int    $parent_id The ID of the parent object.
	 * @param string $asset_id  The id of the asset to update.
	 * @param array  $data      The properties to update.
	 *
	 * @return array The list of assets.
	 */
	protected function delete_asset( $endpoint, $class, $parent_id, $asset_id, array $data = array() ) {
		self::resolve_class( $class );

		$data[ $class::PARENT_ID_ATTRIBUTE ] = $parent_id;

		return $this->delete_object( $endpoint, $class, $asset_id, $data );
	}

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
		return $this->list_objects( 'linode', 'Linode', $filter, $format );
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
		return $this->get_object( 'linode', 'Linode', $linode_id, $format );
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
		return $this->create_object( 'linode', 'Linode', $data, $format );
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
		return $this->update_object( 'linode', 'Linode', $linode_id, $data );
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
		return $this->delete_object( 'linode', 'Linode', $linode_id, $data );
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
		return $this->list_assets( 'linode.config', 'Linode_Config', $linode_id, $filter, $format );
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
		return $this->get_asset( 'linode.config', 'Linode_Config', $linode_id, $format );
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
		return $this->create_asset( 'linod.config', 'Linode_Config', $linode_id, $data, $format );
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
		return $this->list_assets( 'linode.disk', 'Linode_Disk', $linode_id, $filter, $format );
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
		return $this->get_asset( 'linode.disk', 'Linode_Disk', $linode_id, $format );
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
		} else if ( isset( $data['IMAGEID'] ) ) {
			$type = 'image';
		} else if ( isset( $data['STACKSCRIPTID'] ) ) {
			$type = 'stackscript';
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
		return $this->delete_asset( 'linode.disk', 'Linode_Disk', $linode_id, $disk_id, $data );
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
		return $this->list_assets( 'linode.ip', 'Linode_IP', $linode_id, $filter, $format );
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
		return $this->get_asset( 'linode.ip', 'Linode_IP', $linode_id, $format );
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
	 * @param bool  $format Optional The format to return (Domain object or attributes array).
	 *
	 * @return array The list of Domain objects.
	 */
	public function list_domains( array $filter = array(), $format = 'object' ) {
		return $this->list_objects( 'domain', 'Domain', $filter, $format );
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
		return $this->get_object( 'domain', 'Domain', $domain_id, $format );
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
		return $this->create_object( 'domain', 'Domain', $data, $format );
	}

	/**
	 * Update an existing domain.
	 *
	 * @since 1.0.0
	 *
	 * @param int   $domain_id The ID of the domain to update.
	 * @param array $data      The properties of the new Domain.
	 *
	 * @return bool Wether or not the update was successful.
	 */
	public function update_domain( $domain_id, array $data ) {
		return $this->update_object( 'domain', 'Domain', $domain_id, $data );
	}

	/**
	 * Delete an existing domain.
	 *
	 * @since 1.0.0
	 *
	 * @param int   $domain_id The ID of the domain to delete.
	 * @param array $data      Optional The options for the delete request.
	 *
	 * @return bool Wether or not the delete was successful.
	 */
	public function delete_domain( $domain_id, array $data = array() ) {
		return $this->delete_object( 'domain', 'Domain', $domain_id, $data );
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
		return $this->list_assets( 'domain.resource', 'Domain_Record', $domain_id, $filter, $format );
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
		return $this->get_asset( 'domain.resource', 'Domain_Record', $domain_id, $format );
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
		return $this->create_asset( 'domain.resource', 'Domain_Record', $domain_id, $data, $format );
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
		return $this->update_asset( 'domain.resource', 'Domain_Record', $domain_id, $record_id, $data );
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
		return $this->delete_asset( 'domain.resource', 'Domain_Record', $domain_id, $record_id, $data );
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
	 * @param bool  $format Optional The format to return (Stackscript object or attributes array).
	 *
	 * @return array The list of Stackscript objects.
	 */
	public function list_stackscripts( array $filter = array(), $format = 'object' ) {
		return $this->list_objects( 'stackscript', 'Stackscript', $filter, $format );
	}

	/**
	 * Retrieve a single stackscript.
	 *
	 * @since 1.0.0
	 *
	 * @param int  $stackscript_id The ID of the stackscript to retrieve.
	 * @param bool $format         Optional The format to return (Stackscript object or attributes array).
	 *
	 * @return Stackscript|array The stackscript object.
	 */
	public function get_stackscript( $stackscript_id, $format = 'object' ) {
		return $this->get_object( 'stackscript', 'Stackscript', $stackscript_id, $format );
	}

	/**
	 * Create a new stackscript.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data   The properties of the new Stackscript.
	 * @param bool  $format Optional The format to return (Stackscript object or attributes array).
	 *
	 * @return Stackscript|array The stackscript object.
	 */
	public function create_stackscript( $data, $format = 'object' ) {
		return $this->create_object( 'stackscript', 'Stackscript', $data, $format );
	}

	/**
	 * Update an existing stackscript.
	 *
	 * @since 1.0.0
	 *
	 * @param int   $stackscript_id The ID of the stackscript to update.
	 * @param array $data           The properties of the new Stackscript.
	 *
	 * @return bool Wether or not the update was successful.
	 */
	public function update_stackscript( $stackscript_id, array $data ) {
		return $this->update_object( 'stackscript', 'Stackscript', $stackscript_id, $data );
	}

	/**
	 * Delete an existing stackscript.
	 *
	 * @since 1.0.0
	 *
	 * @param int   $stackscript_id The ID of the stackscript to delete.
	 * @param array $data           Optional The options for the delete request.
	 *
	 * @return bool Wether or not the delete was successful.
	 */
	public function delete_stackscript( $stackscript_id, array $data = array() ) {
		return $this->delete_object( 'stackscript', 'Stackscript', $stackscript_id, $data );
	}
}
