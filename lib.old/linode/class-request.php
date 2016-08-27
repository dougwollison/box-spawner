<?php
/**
 * The Linode Request Object.
 *
 * @package Box_Spawner
 * @subpackage Linode
 *
 * @since 1.0.0
 */

namespace BoxSpawner\Linode;

/**
 * The Request class.
 *
 * An interface for creating Linode requests.
 *
 * @api
 *
 * @since 1.0.0
 */
class Request extends \BoxSpawner\Request {
	/**
	 * The options for curl_setopt_array().
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	protected $options = array(
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_VERBOSE        => true,
		CURLOPT_HEADER         => true,
		CURLOPT_URL            => 'https://api.linode.com/',
	);

	/**
	 * Set the options on the cURL handle.
	 *
	 * Adds the api_action value.
	 *
	 * @since 1.0.0
	 */
	protected function setup() {
		$this->data['api_action'] = $this->endpoint;

		parent::setup();
	}
}