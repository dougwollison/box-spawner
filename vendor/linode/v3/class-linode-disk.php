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
class Linode_Disk extends \BoxSpawner\API_Asset implements \BoxSpawner\Linode\Linode_Disk_Framework {
	/**
	 * The name of the option to assign the ID to.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	const ID_ATTRIBUTE = 'DISKID';

	/**
	 * Resize the disk
	 *
	 * @since 1.0.0
	 *
	 * @param int $size The desired size of the disk (MB).
	 */
	public function resize( $size ) {
		return $this->api->resize_linode_disk( $this->parent_id, $this->id, $size );
	}
}
