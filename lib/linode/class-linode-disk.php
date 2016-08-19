<?php
/**
 * The Linode Disk Object.
 *
 * @package Box_Spawner
 * @subpackage Linode
 *
 * @since 1.0.0
 */
namespace BoxSpawner\Linode;

/**
 * The Linode Disk class.
 *
 * An interface for creating/manipulating disks for a linode.
 *
 * @internal Used by the Linode class.
 *
 * @since 1.0.0
 */
class Linode_Disk extends API_Object {
	use Creatable, Deletable, Updatable;

	/**
	 * The name of the option to assign the ID to.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	const ID_ATTRIBUTE = 'DiskID';

	/**
	 * Create a new disk for a linode.
	 *
	 * Pass a "from" option to specify a createfrom(distribution/image/stackscript) action.
	 *
	 * @since 1.0.0
	 *
	 * @param array $options A hash of options for the create request.
	 *
	 * @return int|string The ID of the new object.
	 */
	public static function create( array $options ) {
		// Determine the subaction to use
		$action = 'create';
		if ( isset( $options['from'] ) ) {
			$action .= 'from' . $options['from'];
			unset( $options['from'] );
		}

		return static::call_api( $action, $options );
	}

	/**
	 * Create a copy of the disk image.
	 *
	 * Will autopopulate the LinodeID option.
	 *
	 * @since 1.0.0
	 *
	 * @return int The duplicated disk's ID (same as this one).
	 */
	public function duplicate() {
		$options = array(
			'LinodeID' => $this->attributes['LINODEID'],
			'DiskID' => $this->id,
		);

		$result = static::call_api( 'duplicate', $options );

		return $result['DiskID'];
	}

	/**
	 * Resize the disk.
	 *
	 * @since 1.0.0
	 *
	 * @param int $size The new size of the disk, in MB.
	 */
	public function resize( $size ) {
		$options = array(
			'LinodeID' => $this->attributes['LINODEID'],
			'DiskID' => $this->id,
			'size' => $size,
		);

		static::call_api( 'resize', $options );
	}

	/**
	 * Create a gold-master image from the disk.
	 *
	 * @since 1.0.0
	 *
	 * @param array $options A hash of options for the imagize request.
	 *
	 * @return int The ID of the newly created image.
	 */
	public function imagize( $options ) {
		$options['LinodeID'] = $this->attributes['LINODEID'];
		$options['DiskID'] = $this->id;

		$result = static::call_api( 'imageize', $options );

		return $result['ImageID'];
	}
}
