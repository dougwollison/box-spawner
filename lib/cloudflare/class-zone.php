<?php
/**
 * The Zone Object.
 *
 * @package Box_Spawner
 * @subpackage CloudFlare
 *
 * @since 1.0.0
 */
namespace BoxSpawner\CloudFlare;

/**
 * The Zone class.
 *
 * An interface for creating/manipulating CloudFlare zones.
 *
 * @api
 *
 * @since 1.0.0
 */
class Zone extends API_Object {
	use \BoxSpawner\REST_Creatable, \BoxSpawner\REST_Deletable, \BoxSpawner\REST_Updatable, \BoxSpawner\Dependable;

	/**
	 * The endpoint template.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	const ENDPOINT_FORMAT = 'zones';

	/**
	 * A list of Zone_Record objects tied to it.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	protected $records = array();
}
