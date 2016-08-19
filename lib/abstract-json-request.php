<?php
/**
 * The JSON Request Object.
 *
 * @package Box_Spawner
 * @subpackage Framework
 *
 * @since 1.0.0
 */

namespace BoxSpawner;

/**
 * The JSON Request class.
 *
 * An interface for creating cURL requests to APIs that require a JSON body.
 *
 * @api
 *
 * @since 1.0.0
 */
abstract class JSON_Request {
	/**
	 * Set the options on the cURL handle.
	 *
	 * Convert the data/body to JSON, add the type/length headers.
	 *
	 * @since 1.0.0
	 */
	protected function setup() {
		$this->data = json_encode( $this->data );

		$this->headers[] = 'Content-Type: application/json';
		$this->headers[] = 'Content-Length: ' . strlen( $this->data );

		parent::setup();
	}
}
