<?php
/**
 * The Linode IP Object (version 3).
 *
 * @package Box_Spawner
 * @subpackage Linode
 *
 * @since 1.0.0
 */
namespace BoxSpawner\Linode\V3;

/**
 * The Linode IP object class.
 *
 * The an absraction of the Linode IP api object.
 *
 * @api
 *
 * @since 1.0.0
 */
class Linode_IP extends \BoxSpawner\API_Asset implements \BoxSpawner\Linode\Linode_IP_Framework {
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
	const ID_ATTRIBUTE = 'IPADDRESSID';

	/**
	 * Issue a create request for a new object.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data The data for the create request.
	 */
	protected function create( array $data ) {
		if ( ! $this->parent_id ) {
			throw new Exception( 'LinodeID required when adding a linode ip.' );
		}
		if ( ! isset( $data['Type'] ) ) {
			throw new Exception( 'Type required when adding a linode ip.' );
		} else if ( ! in_array( $data['Type'], array( 'public', 'private' ) ) ) {
			throw new Exception( 'Type must be "public" or "private" when adding a linode ip.' );
		}

		$method = 'add' . $data['Type'];

		$result = $this->api->request( 'linode.ip.' . $method, array(
			$this::PARENT_ID_ATTRIBUTE => $this->parent_id,
		) );

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

		$data = $this->api->request( 'linode.ip.list', array(
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
		return $this->api->update_linode_ip( $this->parent_id, $this->id, $data );
	}
}
