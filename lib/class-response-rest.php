<?php
/**
 * The Response Object.
 *
 * @package Box_Spawner
 * @subpackage Framework
 *
 * @since 1.0.0
 */

namespace BoxSpawner;

/**
 * The Response class.
 *
 * An interface for handling the result of cURL requests of RESTful APIs.
 *
 * @api
 *
 * @since 1.0.0
 */
class Response_REST extends Response_JSON {
	/**
	 * Get the results of the request.
	 *
	 * @since 1.0.0
	 *
	 * @return array The JSON decoded result data.
	 */
	public function result() {
		// Get the decoded result
		$result = parent::result();

		$json = $result['body'];

		if ( ! isset( $json['result'] ) ) {
			throw new Exception( 'Unrecognized response format. "result" entry should be present.' );
		}

		if ( isset( $json['errors'] ) && count( $json['errors'] ) > 0 ) {
			$error = $json['errors'][0];
			throw new Exception( 'REST API Error: ' . $error['message'] );
		}

		// Get the response data
		$data = $json['result'];

		return $data;
	}
}
