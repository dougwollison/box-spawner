<?php
/**
 * The CloudFlare Request Object.
 *
 * @package Box_Spawner
 * @subpackage CloudFlare
 *
 * @since 1.0.0
 */

namespace BoxSpawner\CloudFlare;

/**
 * The Request class.
 *
 * An interface for creating CloudFlare requests.
 *
 * @api
 *
 * @since 1.0.0
 */
class Request extends \BoxSpawner\Request_REST {
	/**
	 * The base endpoint URL.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	const ENDPOINT_BASE = 'https://api.cloudflare.com/client/v4/';
}
