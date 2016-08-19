<?php
/**
 * The Zone Record Object.
 *
 * @package Box_Spawner
 * @subpackage CloudFlare
 *
 * @since 1.0.0
 */
namespace BoxSpawner\CloudFlare;

/**
 * The Zone Record class.
 *
 * An interface for creating/manipulating CloudFlare zone records.
 *
 * @internal Used by the Droplet class.
 *
 * @since 1.0.0
 */
class Zone_Record extends API_Object {
	use \BoxSpawner\REST_Createable, \BoxSpawner\REST_Deleteable, \BoxSpawner\REST_Updateable;

	/**
	 * The endpoint template.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	const ENDPOINT_FORMAT = 'zones/:zone_id/dns_records';
}
