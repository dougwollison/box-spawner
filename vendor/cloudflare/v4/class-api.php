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
class API extends \BoxSpawner\API {
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
		// to be written
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
		// to be written
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
		// to be written
	}

	/**
	 * Update an existing zone.
	 *
	 * @since 1.0.0
	 *
	 * @param int   $zone_id The ID of the zone to update.
	 * @param array $data    The properties of the new Zone.
	 *
	 * @return bool Wether or not the update was successful.
	 */
	public function update_zone( $zone_id, array $data ) {
		// to be written
	}

	/**
	 * Delete an existing zone.
	 *
	 * @since 1.0.0
	 *
	 * @param int $zone_id The ID of the zone to delete.
	 *
	 * @return bool Wether or not the delete was successful.
	 */
	public function delete_zone( $zone_id ) {
		// to be written
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
		// to be written
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
		// to be written
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
		// to be written
	}

	/**
	 * Update an existing record for a zone.
	 *
	 * @since 1.0.0
	 *
	 * @param int   $zone_id   The ID of the zone the record belongs to.
	 * @param int   $record_id The ID of the record to update.
	 * @param array $data      The properties of the new Zone.
	 *
	 * @return bool Wether or not the update was successful.
	 */
	public function update_zone_record( $zone_id, $record_id, array $data ) {
		// to be written
	}

	/**
	 * Delete an existing record for a zone.
	 *
	 * @since 1.0.0
	 *
	 * @param int $zone_id   The ID of the zone the record belongs to.
	 * @param int $record_id The ID of the record to delete.
	 *
	 * @return bool Wether or not the delete was successful.
	 */
	public function delete_zone_record( $zone_id, $record_id ) {
		// to be written
	}
}
