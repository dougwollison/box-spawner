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
	use \BoxSpawner\REST_Createable, \BoxSpawner\REST_Deleteable, \BoxSpawner\REST_Updateable, \BoxSpawner\Dependable;

	/**
	 * The endpoint template.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	const ENDPOINT_FORMAT = 'zones';
}
