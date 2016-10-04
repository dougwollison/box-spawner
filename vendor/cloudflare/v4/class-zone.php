<?php
/**
 * The Zone Object (version 4).
 *
 * @package Box_Spawner
 * @subpackage CloudFlare
 *
 * @since 1.0.0
 */
namespace BoxSpawner\CloudFlare\V4;

/**
 * The Zone object class.
 *
 * The an absraction of the Zone api object.
 *
 * @api
 *
 * @since 1.0.0
 */
class Zone extends \BoxSpawner\API_Object implements \BoxSpawner\CloudFlare\Zone_Framework {
	/**
	 * Issue a create request for a new object.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data The data for the create request.
	 */
	protected function create( array $data ) {
		$result = $this->api->create_zone( $data, 'array' );

		$this->id = $result[ $this::ID_ATTRIBUTE ];
		$this->attrbiutes = $result;
	}

	/**
	 * Issue a load request for a new object.
	 *
	 * @since 1.0.0
	 *
	 * @param string $id The id of the object to load.
	 */
	protected function load( $id ) {
		$this->id = $id;
		$this->attributes = $this->api->get_zone( $id, 'array' );
	}

	/**
	 * Issue a update request for a new object.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data The data for the update request.
	 */
	public function update( array $data ) {
		$this->attributes = $this->api->update_zone( $this->id, $data );
		return true;
	}

	/**
	 * Issue a delete request for a new object.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data Optional UNUSED.
	 */
	public function delete( array $data = array() ) {
		return $this->api->delete_zone( $this->id );
	}

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
	public function list_records( array $filter = array() ) {
		return $this->api->list_zone_records( $this->id, $filter );
	}

	/**
	 * Retrieve a single record.
	 *
	 * @since 1.0.0
	 *
	 * @param int $record_id The ID of the record to retrieve.
	 *
	 * @return Zone_Record The record object.
	 */
	public function get_record( $record_id ) {
		return $this->api->get_zone_record( $this->id, $record_id );
	}

	/**
	 * Create a new record.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data The properties of the new record.
	 *
	 * @return Zone_Record The record object.
	 */
	public function create_record( $data ) {
		return $this->api->create_zone_record( $this->id, $data );
	}

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
	public function update_record( $record_id, array $data ) {
		return $this->api->update_zone_record( $this->id, $record_id, $data );
	}

	/**
	 * Delete an existing record.
	 *
	 * @since 1.0.0
	 *
	 * @param int $record_id The ID of the record to delete.
	 *
	 * @return bool Wether or not the delete was successful.
	 */
	public function delete_record( $record_id ) {
		return $this->api->delete_zone_record( $this->id, $record_id );
	}
}
