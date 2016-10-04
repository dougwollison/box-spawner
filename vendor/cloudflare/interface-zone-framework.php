<?php
/**
 * The Zone Object framework.
 * The base framework for the Zone object classes.
 *
 * @since 1.0.0
 */
namespace BoxSpawner\CloudFlare;

/**
 * The Zone Object Framework interface.
 * The base framework for the Zone object classes.
 *
 * @api
 *
 * @since 1.0.0
 */
interface Zone_Framework {
	// ==================================================
	// ! Asset Actions
	// ==================================================

	// =========================
	// ! - Record Assets
	// =========================

	/**
	 * Retrieve a list of records.
	 *
	 * @since 1.0.0
	 *
	 * @param array $filter Optional Arguments for filtering the list request.
	 *
	 * @return array The list of Zone_Record objects.
	 */
	public function list_records( array $filter = array() );

	/**
	 * Retrieve a single record.
	 *
	 * @since 1.0.0
	 *
	 * @param int $record_id The ID of the record to retrieve.
	 *
	 * @return Zone_Record The record object.
	 */
	public function get_record( $record_id );

	/**
	 * Create a new record.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data The properties of the new record.
	 *
	 * @return Zone_Record The record object.
	 */
	public function create_record( $data );

	/**
	 * Update an existing record.
	 *
	 * @since 1.0.0
	 *
	 * @param int   $record_id The ID of the record to update.
	 * @param array $data      The properties of the new CloudFlare.
	 *
	 * @return bool Wether or not the update was successful.
	 */
	public function update_record( $record_id, array $data );

	/**
	 * Delete an existing record.
	 *
	 * @since 1.0.0
	 *
	 * @param int $record_id The ID of the record to delete.
	 *
	 * @return bool Wether or not the delete was successful.
	 */
	public function delete_record( $record_id );
}
