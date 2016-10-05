<?php
/**
 * The Domain Object (version 3).
 *
 * @package Box_Spawner
 * @subpackage Linode
 *
 * @since 1.0.0
 */
namespace BoxSpawner\Linode\V3;

/**
 * The Domain object class.
 *
 * The an absraction of the Domain api object.
 *
 * @api
 *
 * @since 1.0.0
 */
class Domain extends \BoxSpawner\API_Object implements \BoxSpawner\Linode\Domain_Framework {
	use \BoxSpawner\Case_Insensitive_Object;

	/**
	 * The name of the option to assign the ID to.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	const ID_ATTRIBUTE = 'DOMAINID';

	/**
	 * Issue a create request for a new object.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data The data for the create request.
	 */
	protected function create( array $data ) {
		$result = $this->api->create_domain( $data, 'array' );

		$this->load( $result[ $this::ID_ATTRIBUTE ] );
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
		$this->attributes = $this->api->get_domain( $id, 'array' );
	}

	/**
	 * Issue a update request for a new object.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data The data for the update request.
	 */
	public function update( array $data ) {
		return $this->api->update_domain( $this->id, $data );
	}

	/**
	 * Issue a delete request for a new object.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data Optional UNUSED.
	 */
	public function delete( array $data = array() ) {
		return $this->api->delete_domain( $this->id );
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
	 * @return array The list of Domain_Record objects.
	 */
	public function list_records( array $filter = array() ) {
		return $this->api->list_domain_records( $this->id, $filter );
	}

	/**
	 * Retrieve a single record.
	 *
	 * @since 1.0.0
	 *
	 * @param int $record_id The ID of the record to retrieve.
	 *
	 * @return Domain_Record The record object.
	 */
	public function get_record( $record_id ) {
		return $this->api->get_domain_record( $this->id, $record_id );
	}

	/**
	 * Create a new record.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data The properties of the new record.
	 *
	 * @return Domain_Record The record object.
	 */
	public function create_record( $data ) {
		return $this->api->create_domain_record( $this->id, $data );
	}

	/**
	 * Update an existing record.
	 *
	 * @since 1.0.0
	 *
	 * @param int   $record_id The ID of the record to update.
	 * @param array $data      The properties of the new Linode.
	 *
	 * @return bool Wether or not the update was successful.
	 */
	public function update_record( $record_id, array $data ) {
		return $this->api->update_domain_record( $this->id, $record_id, $data );
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
		return $this->api->delete_domain_record( $this->id, $record_id );
	}
}
