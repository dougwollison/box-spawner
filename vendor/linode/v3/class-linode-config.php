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
class Linode_Config extends API_Asset implements \BoxSpawner\Linode\Linode_Config_Framework {
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
		if ( ! $this->parent_id ) {
			throw new Exception( 'LinodeID required when creating a linode config.' );
		}
		if ( ! isset( $data['KERNELID'] ) ) {
			throw new Exception( 'KernelID required when creating a linode config.' );
		}
		if ( ! isset( $data['LABEL'] ) ) {
			throw new Exception( 'Label required when creating a linode config.' );
		}
		if ( ! isset( $data['DISKLIST'] ) ) {
			throw new Exception( 'DiskList required when creating a linode config.' );
		}

		$data[ $this::PARENT_ID_ATTRIBUTE ] = $this->parent_id;

		$result = $this->api->request( 'linode.config.create', $data );

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
		$this->id = $data[ static::ID_ATTRIBUTE ];

		$data = $this->api->request( 'linode.config.list', array(
			$this::PARENT_ID_ATTRIBUTE => $this->parent_id,
			$this::ID_ATTRIBUTE => $this->id,
		) );

		$this->attributes = $data;
	}

	/**
	 * Issue a update request for a new object.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data The data for the update request.
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
	 */
	public function delete( array $data = array() ) {
		return $this->api->delete_linode_config( $this->parent_id, $this->id );
	}
}
