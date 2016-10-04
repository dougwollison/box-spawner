<?php
/**
 * The CloudFlare Record Object (version 4).
 *
 * @package Box_Spawner
 * @subpackage CloudFlare
 *
 * @since 1.0.0
 */
namespace BoxSpawner\CloudFlare\V4;

/**
 * The CloudFlare Record object class.
 *
 * The an absraction of the CloudFlare Record api object.
 *
 * @api
 *
 * @since 1.0.0
 */
class Zone_Record extends \BoxSpawner\API_Asset implements \BoxSpawner\CloudFlare\Zone_Record_Framework {
	/**
	 * The name of the option to assign the ID to.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	const PARENT_ID_ATTRIBUTE = 'zone_id';

	/**
	 * Issue a create request for a new object.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data The data for the create request.
	 */
	protected function create( array $data ) {
		$result = $this->api->create_domain_record( $this->parent_id, $data, 'array' );

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
		$this->attributes = $this->api->get_domain_record( $this->parent_id, $id, 'array' );
	}

	/**
	 * Issue a update request for a new object.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data The data for the update request.
	 */
	public function update( array $data ) {
		return $this->api->update_domain_record( $this->parent_id, $this->id, $data );
	}

	/**
	 * Issue a delete request for a new object.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data Optional UNUSED.
	 */
	public function delete( array $data = array() ) {
		return $this->api->delete_domain_record( $this->parent_id, $this->id );
	}
}
