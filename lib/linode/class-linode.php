<?php
/**
 * The Linode Object.
 *
 * @package Box_Spawner
 * @subpackage Linode
 *
 * @since 1.0.0
 */
namespace BoxSpawner\Linode;

use \BoxSpawner as Framework;

/**
 * The Linode class.
 *
 * An interface for creating/manipulating Linode boxes.
 *
 * @api
 *
 * @since 1.0.0
 */
class Linode extends API_Object {
	use Creatable, Deletable, Updatable;

	/**
	 * The name of the option to assign the ID to.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	const ID_ATTRIBUTE = 'LinodeID';

	/**
	 * A list of Config objects tied to it.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	protected $configs = array();

	/**
	 * A list of Disk objects tied to it.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	protected $disks = array();

	/**
	 * A list of IP objects tied to it.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	protected $ips = array();

	/**
	 * Create a new Config.
	 *
	 * @since 1.0.0
	 *
	 * @param array $options The config.create request parameters.
	 *
	 * @return Linode_Config The new Config object.
	 */
	public function add_config( array $options ) {
		$options['LinodeID'] = $this->id;

		$config = new Linode_Config( $options );

		$this->configs[ $config->id ] = $config;

		return $config;
	}

	/**
	 * Create a new Disk.
	 *
	 * @since 1.0.0
	 *
	 * @param array $options The disk.create request parameters.
	 *
	 * @return Linode_Disk The new Disk object.
	 */
	public function add_disk( array $options ) {
		$options['LinodeID'] = $this->id;

		$disk = new Linode_Disk( $options );

		$this->disks[ $disk->id ] = $disk;

		return $disk;
	}

	/**
	 * Create a new Config.
	 *
	 * @since 1.0.0
	 *
	 * @param bool $public Optional Wether the IP is public or not (default: TRUE).
	 *
	 * @return Linode_IP The new Config object.
	 */
	public function add_ip( $public = true ) {
		$ip = new Linode_IP( array(
			'LinodeID' => $this->id,
			'IsPublic' => $public,
		) );

		$this->ips[ $ip->id ] = $ip;

		return $ip;
	}
}
