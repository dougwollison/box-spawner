<?php
/**
 * The CloudFlare API (version 4).
 *
 * @package Box_Spawner
 * @subpackage CloudFlare
 *
 * @since 1.0.0
 */
namespace BoxSpawner\CloudFlare\V4;

/**
 * The API class.
 *
 * The interface for all API requests.
 *
 * @api
 *
 * @since 1.0.0
 */
class API extends \BoxSpawner\API {
	/**
	 * The base endpoint URL.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	const ENDPOINT_BASE = 'https://api.cloudflare.com/client/v4/';
}
