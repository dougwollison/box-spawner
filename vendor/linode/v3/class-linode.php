<?php
/**
 * The Linode Object (version 3).
 *
 * @package Box_Spawner
 * @subpackage Linode
 *
 * @since 1.0.0
 */
namespace BoxSpawner\Linode\V3;

/**
 * The Linode object class.
 *
 * The an absraction of the Linode api object.
 *
 * @api
 *
 * @since 1.0.0
 */
class Linode extends \BoxSpawner\API_Object implements \BoxSpawner\Linode\Linode_Framework {
	/**
	 * The name of the option to assign the ID to.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	const ID_ATTRIBUTE = 'LINODEID';

	/**
	 * Issue a create request for a new object.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data The data for the create request.
	 */
	public function create( array $data ) {
		if ( ! isset( $data['DatacenterID'] ) ) {
			throw new Exception( 'DatacenterID required when creating a linode.' );
		}
		if ( ! isset( $data['PlanID'] ) ) {
			throw new Exception( 'PlanID required when creating a linode.' );
		}

		$result = $this->api->request( 'linode.create', $data );

		$this->id = $result[ static::ID_ATTRIBUTE ];

		$this->load( array(
			static::ID_ATTRIBUTE => $result[ static::ID_ATTRIBUTE ],
		) );
	}

	/**
	 * Issue a load request for a new object.
	 *
	 * @since 1.0.0
	 *
	 * @param string $id The id of the object to load.
	 */
	public function load( $id ) {
		$this->id = $data[ static::ID_ATTRIBUTE ];

		$data = $this->api->request( 'linode.list', array(
			static::ID_ATTRIBUTE => $this->id
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
		$this->api->update_linode( $this->id, $data );
	}

	/**
	 * Issue a delete request for a new object.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data The data for the delete request.
	 */
	public function delete( array $data ) {
		$this->api->delete_linode( $this->id, $data );
	}

	/**
	 * Issue a boot job for the linode.
	 *
	 * @since 1.0.0
	 *
	 * @param int $config_id Optional the ID of a config to boot with.
	 */
	public function boot( $config_id = null ) {
		$this->api->boot_linode( $this->id, $config_id );
	}

	/**
	 * Issue a shutdown job for the linode.
	 *
	 * @since 1.0.0
	 */
	public function shutdown() {
		$this->api->shutdown_linode( $this->id );
	}

	/**
	 * Issue a reboot job for the linode.
	 *
	 * @since 1.0.0
	 *
	 * @param int $config_id Optional the ID of a config to boot with.
	 */
	public function reboot( $config_id = null ) {
		$this->api->reboot_linode( $this->id, $config_id );
	}
}