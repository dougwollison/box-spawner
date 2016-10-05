<?php
/**
 * The CloudFlare API (version 4).
 *
 * @package Box_Spawner
 * @subpackage CloudFlare
 *
 * @since 1.0.0
 */
namespace BoxSpawner\CloudFlare\V4;

/**
 * The API class.
 *
 * The interface for all API requests.
 *
 * @api
 *
 * @since 1.0.0
 */
class API extends \BoxSpawner\REST_API implements \BoxSpawner\CloudFlare\API_Framework {
	use \BoxSpawner\Resolve_Class;

	/**
	 * The base endpoint URL.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	const ENDPOINT_BASE = 'https://api.cloudflare.com/client/v4/';

	/**
	 * The API email for all requests.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $api_email;

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
		$this->api_email = $options['api_email'];
		$this->api_key   = $options['api_key'];
	}

	// =========================
	// ! Request Handling
	// =========================

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

		$headers[] = 'X-Auth-Email: ' . $this->api_email;
		$headers[] = 'X-Auth-Key: '   . $this->api_key;

		return $headers;
	}

	// ==================================================
	// ! Utilities
	// ==================================================

	// =========================
	// ! - Object Utilities
	// =========================

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
		$class = self::resolve_class( $class );

		$data = $this->get( $endpoint, $filter );

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
		$class = self::resolve_class( $class );

		$data = $this->get( "{$endpoint}/{$object_id}" );

		if ( $format == 'object' ) {
			$data = new $class( $this, $object_id, $data );
		}

		return $data;
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
		$class = self::resolve_class( $class );

		$result = $this->post( $endpoint, $data );

		if ( $format == 'object' ) {
			$result = new $class( $this, $result[ $class::ID_ATTRIBUTE ], $result );
		}

		return $result;
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
	 * @return bool Wether or not the delete was successful.
	 */
	protected function delete_object( $endpoint, $class, $object_id, array $data = array() ) {
		$class = self::resolve_class( $class );

		$result = $this->delete( "{$endpoint}/{$object_id}", $data );

		return $result[ $class::ID_ATTRIBUTE ] == $object_id;
	}

	// =========================
	// ! - Asset Utilities
	// =========================

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
		$class = self::resolve_class( $class );

		$endpoint = sprintf( $endpoint, $parent_id );

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
		$class = self::resolve_class( $class );

		$endpoint = sprintf( $endpoint, $parent_id );

		return $this->get_object( $endpoint, $class, $asset_id, $format );
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
		$class = self::resolve_class( $class );

		$endpoint = sprintf( $endpoint, $parent_id );

		return $this->create_object( $endpoint, $class, $data, $format );
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
		$class = self::resolve_class( $class );

		$endpoint = sprintf( $endpoint, $parent_id );

		return $this->delete_object( $endpoint, $class, $asset_id, $data );
	}

	// ==================================================
	// ! Editable Objects
	// ==================================================

	// =========================
	// ! - Zone Objects
	// =========================

	/**
	 * Retrieve a list of zones.
	 *
	 * @since 1.0.0
	 *
	 * @param array $filter Optional Arguments for filtering the list request.
	 * @param bool  $format Optional The format to return (Linode object or attributes array).
	 *
	 * @return array The list of Zone objects.
	 */
	public function list_zones( array $filter = array(), $format = 'object' ) {
		return $this->list_objects( 'zones', 'Zone', $filter, $format );
	}

	/**
	 * Retrieve a single zone.
	 *
	 * @since 1.0.0
	 *
	 * @param int  $zone_id The ID of the zone to retrieve.
	 * @param bool $format  Optional The format to return (Zone object or attributes array).
	 *
	 * @return Zone|array The zone object.
	 */
	public function get_zone( $zone_id, $format = 'object' ) {
		return $this->get_object( 'zones', 'Zone', $zone_id, $format );
	}

	/**
	 * Create a new zone.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data   The properties of the new Zone.
	 * @param bool  $format Optional The format to return (Zone object or attributes array).
	 *
	 * @return Zone|array The zone object.
	 */
	public function create_zone( $data, $format = 'object' ) {
		return $this->create_object( 'zones', 'Zone', $data, $format );
	}

	/**
	 * Update an existing zone.
	 *
	 * @since 1.0.0
	 *
	 * @param int   $zone_id The ID of the zone to update.
	 * @param array $data    The properties to update.
	 *
	 * @return array The resulting zone data.
	 */
	public function update_zone( $zone_id, array $data ) {
		return $this->put( "zones/$zone_id", $data );
	}

	/**
	 * Delete an existing zone.
	 *
	 * @since 1.0.0
	 *
	 * @param int   $zone_id The ID of the zone to delete.
	 * @param array $data    Optinoal Any custom parameters for the delete request.
	 *
	 * @return bool Wether or not the delete was successful.
	 */
	public function delete_zone( $zone_id, array $data = array() ) {
		return $this->delet_object( 'zones', 'Zone', $zone_id, $data );
	}

	// =========================
	// ! -- Zone Record Objects
	// =========================

	/**
	 * Retrieve a list of records for a zone.
	 *
	 * @since 1.0.0
	 *
	 * @param int   $zone_id The ID of the zone to get records from.
	 * @param array $filter  Optional Arguments for filtering the list request.
	 * @param bool  $format  Optional The format to return (Linode object or attributes array).
	 *
	 * @return array The list of Zone_Record objects.
	 */
	public function list_zone_records( $zone_id, array $filter = array(), $format = 'object' ) {
		return $this->list_assets( 'zones/%s/dns_records', 'Zone_Record', $zone_id, $filter, $format );
	}

	/**
	 * Retrieve a single record for a zone.
	 *
	 * @since 1.0.0
	 *
	 * @param int  $zone_id   The ID of the zone the record belongs to.
	 * @param int  $record_id The ID of the record to retrieve.
	 * @param bool $format    Optional The format to return (Zone_Record object or attributes array).
	 *
	 * @return Zone_Record|array The record object.
	 */
	public function get_zone_record( $zone_id, $record_id, $format = 'object' ) {
		return $this->get_asset( 'zones/%s/dns_records', 'Zone_Record', $zone_id, $record_id, $format );
	}

	/**
	 * Create a new record for a zone.
	 *
	 * @since 1.0.0
	 *
	 * @param int   $zone_id The ID of the zone to create the record for.
	 * @param array $data    The properties of the new record.
	 * @param bool  $format  Optional The format to return (Zone_Record object or attributes array).
	 *
	 * @return Zone_Record|array The record object.
	 */
	public function create_zone_record( $zone_id, $data, $format = 'object' ) {
		return $this->create_asset( 'zones/%s/dns_records', 'Zone_Record', $zone_id, $data, $format );
	}

	/**
	 * Update an existing record for a zone.
	 *
	 * @since 1.0.0
	 *
	 * @param int   $zone_id   The ID of the zone the record belongs to.
	 * @param int   $record_id The ID of the record to update.
	 * @param array $data      The properties to update.
	 *
	 * @return array The resulting record data.
	 */
	public function update_zone_record( $zone_id, $record_id, array $data ) {
		return $this->put( "zones/$zone_id/dns_records/$record_id", $data );
	}

	/**
	 * Delete an existing record for a zone.
	 *
	 * @since 1.0.0
	 *
	 * @param int   $zone_id   The ID of the zone the record belongs to.
	 * @param int   $record_id The ID of the record to delete.
	 * @param array $data      Optinoal Any custom parameters for the delete request.
	 *
	 * @return bool Wether or not the delete was successful.
	 */
	public function delete_zone_record( $zone_id, $record_id, array $data = array() ) {
		return $this->delet_asset( 'zones/%s/dns_records', 'Zone_Record', $zone_id, $record_id, $data );
	}
}
