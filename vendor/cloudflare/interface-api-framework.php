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
	// ! - Zones
	// =========================

	/**
	 * Retrieve a list of zones.
	 *
	 * @since 1.0.0
	 *
	 * @param array $filter Optional Arguments for filtering the list request.
	 * @param bool  $format Optional The format to return (CloudFlare object or attributes array).
	 *
	 * @return array The list of CloudFlare objects.
	 */
	public function list_zones( array $filter = array(), $format = 'object' );

	/**
	 * Retrieve a single zone.
	 *
	 * @since 1.0.0
	 *
	 * @param int  $zone_id The ID of the zone to retrieve.
	 * @param bool $format    Optional The format to return (CloudFlare object or attributes array).
	 *
	 * @return CloudFlare|array The zone object.
	 */
	public function get_zone( $zone_id, $format = 'object' );

	/**
	 * Create a new zone.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data   The properties of the new CloudFlare.
	 * @param bool  $format Optional The format to return (CloudFlare object or attributes array).
	 *
	 * @return CloudFlare|array The zone object.
	 */
	public function create_zone( $data, $format = 'object' );

	/**
	 * Update an existing zone.
	 *
	 * @since 1.0.0
	 *
	 * @param int   $zone_id The ID of the zone to update.
	 * @param array $data    The properties of the new CloudFlare.
	 *
	 * @return bool Wether or not the update was successful.
	 */
	public function update_zone( $zone_id, array $data );

	/**
	 * Delete an existing zone.
	 *
	 * @since 1.0.0
	 *
	 * @param int   $zone_id The ID of the zone to delete.
	 *
	 * @return bool Wether or not the delete was successful.
	 */
	public function delete_zone( $zone_id );
}