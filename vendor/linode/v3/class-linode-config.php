<?php
/**
 * The Linode Config Object (version 3).
 *
 * @package Box_Spawner
 * @subpackage Linode
 *
 * @since 1.0.0
 */
namespace BoxSpawner\Linode\V3;

/**
 * The Linode Config object class.
 *
 * The an absraction of the Linode Config api object.
 *
 * @api
 *
 * @since 1.0.0
 */
class Linode_Config extends \BoxSpawner\API_Asset implements \BoxSpawner\Linode\Linode_Config_Framework {
	use \BoxSpawner\Case_Insensitive_Object;

	/**
	 * The name of the option to assign the ID to.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	const PARENT_ID_ATTRIBUTE = Linode::ID_ATTRIBUTE;

	/**
	 * The name of the option to assign the ID to.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	const ID_ATTRIBUTE = 'CONFIGID';

	/**
	 * Issue a create request for a new object.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data The data for the create request.
	 */
	protected function create( array $data ) {
		$result = $this->api->create_linode_config( $this->parent_id, $data, 'array' );

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
		$this->attributes = $this->api->get_linode_config( $this->parent_id, $id, 'array' );
	}

	/**
	 * Issue a update request for a new object.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data The data for the update request.
	 *
	 * @return bool Wether or not the update was successful.
	 */
	public function update( array $data ) {
		return $this->api->update_linode_config( $this->parent_id, $this->id, $data );
	}

	/**
	 * Issue a delete request for a new object.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data Optional UNUSED.
	 *
	 * @return bool Wether or not the delete was successful.
	 */
	public function delete( array $data = array() ) {
		return $this->api->delete_linode_config( $this->parent_id, $this->id );
	}
}
