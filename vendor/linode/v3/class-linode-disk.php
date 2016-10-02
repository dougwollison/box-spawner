<?php
/**
 * The Linode Disk Object (version 3).
 *
 * @package Box_Spawner
 * @subpackage Linode
 *
 * @since 1.0.0
 */
namespace BoxSpawner\Linode\V3;

/**
 * The Linode Disk object class.
 *
 * The an absraction of the Linode Disk api object.
 *
 * @api
 *
 * @since 1.0.0
 */
class Linode_Disk extends API_Asset implements \BoxSpawner\Linode\Linode_Disk_Framework {
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
	const ID_ATTRIBUTE = 'DISKID';

	// =========================
	// ! Main Object Actions
	// =========================

	/**
	 * Issue a create request for a new object.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data The data for the create request.
	 */
	protected function create( array $data ) {
		if ( ! $this->parent_id ) {
			throw new Exception( 'LINODEID required when creating a linode disk.' );
		}

		$type = null;
		if ( isset( $data['DISTRIBUTIONID'] ) ) {
			$type = 'distribution';
			$required = array( 'DISTRIBUTIONID', 'LABEL', 'SIZE', 'ROOTPASS' );
		} else if ( isset( $data['IMAGEID'] ) ) {
			$type = 'image';
			$required = array( 'IMAGEID' );
		} else if ( isset( $data['STACKSCRIPTID'] ) ) {
			$type = 'stackscript';
			$required = array( 'STACKSCRIPTID', 'STACKSCRIPTUDFRESPONSES', 'LABEL', 'SIZE', 'ROOTPASS' );
		} else {
			$required = array( 'LABEL', 'TYPE', 'SIZE' );
		}

		foreach ( $required as $key ) {
			if ( ! isset( $data[ $key ] ) ) {
				throw new Exception( "{$key} is required when creating a linode disk" . ( $type ?  " from a {$type}" : '' ) . "." );
			}
		}

		$method = 'create' . ( $type ? "from{$type}" : '' );

		$data[ $this::PARENT_ID_ATTRIBUTE ] = $this->parent_id;

		$result = $this->api->request( 'linode.disk.' . $method, $data );

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

		$data = $this->api->request( 'linode.disk.list', array(
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
		return $this->api->update_linode_disk( $this->parent_id, $this->id, $data );
	}

	/**
	 * Issue a delete request for a new object.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data Optional UNUSED.
	 */
	public function delete( array $data = array() ) {
		return $this->api->delete_linode_disk( $this->parent_id, $this->id );
	}

	// =========================
	// ! Specialty Actions
	// =========================

	/**
	 * Resize the disk.
	 *
	 * @since 1.0.0
	 *
	 * @param int $size The desired size of the disk (MB).
	 *
	 * @return int The ID of the job handling the request.
	 */
	public function resize( $size ) {
		return $this->api->resize_linode_disk( $this->parent_id, $this->id, $size );
	}

	/**
	 * Duplicate the disk.
	 *
	 * @since 1.0.0
	 *
	 * @return int The ID of the job handling the request.
	 */
	public function duplicate() {
		return $this->api->duplicate_linode_disk( $this->parent_id, $this->id );
	}

	/**
	 * Create an image from the disk.
	 *
	 * @since 1.0.0
	 *
	 * @return int The ID of the image created.
	 */
	public function imagize( $data ) {
		return $this->api->imagize_linode_disk( $this->parent_id, $this->id );
	}
}
