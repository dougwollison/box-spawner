<?php
/**
 * The CloudFlare API framework.
 *
 * @package Box_Spawner
 * @subpackage CloudFlare
 *
 * @since 1.0.0
 */
namespace BoxSpawner\CloudFlare;

/**
 * The API Framework interface.
 *
 * The base framework for CloudFlare API classes.
 *
 * @api
 *
 * @since 1.0.0
 */
interface API_Framework {
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
	public function list_zones( array $filter = array(), $format = 'object' );

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
	public function get_zone( $zone_id, $format = 'object' );

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
	public function create_zone( $data, $format = 'object' );

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
	public function update_zone( $zone_id, array $data );

	/**
	 * Delete an existing zone.
	 *
	 * @since 1.0.0
	 *
	 * @param int $zone_id The ID of the zone to delete.
	 *
	 * @return bool Wether or not the delete was successful.
	 */
	public function delete_zone( $zone_id );

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
	public function list_zone_records( $zone_id, array $filter = array(), $format = 'object' );

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
	public function get_zone_record( $zone_id, $record_id, $format = 'object' );

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
	public function create_zone_record( $zone_id, $data, $format = 'object' );

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
	public function update_zone_record( $zone_id, $record_id, array $data );

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
	public function delete_zone_record( $zone_id, $record_id );
}