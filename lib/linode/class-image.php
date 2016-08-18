<?php
/**
 * The Image Object.
 *
 * @package Box_Spawner
 * @subpackage Linode
 *
 * @since 1.0.0
 */
namespace BoxSpawner\Linode;

/**
 * The Image class.
 *
 * An interface for accessing/manipulating Linode images.
 *
 * @api
 *
 * @since 1.0.0
 */
class Image extends API_Object {
	use Deletable, Updatable;

	/**
	 * The name of the option to assign the ID to.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	const ID_ATTRIBUTE = 'ImageID';

	/**
	 * Create a new image from a linode's disk.
	 *
	 * @since 1.0.0
	 *
	 * @param array $options A hash of options for the create request.
	 *      @option int    "LinodeID"    The ID of the linode to create from.
	 *      @option int    "DiskID"      The ID of the source disk to create from.
	 *      @option string "Description" Optional A description of the image.
	 *      @option string "Label"       Optional A name for the image.
	 *
	 * @return int|string The ID of the new object.
	 */
	public static function create( array $options = array() ) {
		return static::call_api( 'linode.disk.imageize', $options );
	}
}
