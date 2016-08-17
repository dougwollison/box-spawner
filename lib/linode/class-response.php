<?php
/**
 * The Linode Response Object.
 *
 * @package Box_Spawner
 * @subpackage Linode
 *
 * @since 1.0.0
 */

namespace BoxSpawner\Linode;

/**
 * The Response class.
 *
 * An interface for handling the result of Linode requests.
 *
 * @api
 *
 * @since 1.0.0
 */
class Response extends \BoxSpawner\Response_JSON {
	/**
	 * Get the results of the request.
	 *
	 * Return an array contianing all headers (parsed) and the body.
	 *
	 * @since 1.0.0
	 */
	public function result() {
		// Get the decoded result
		$result = parent::result();

		$json = $result['body'];

		if ( ! isset( $json['DATA'] ) ) {
			throw new Exception( 'Unrecognized response format. "DATA" entry should be present.' );
		}

		if ( isset( $json['ERRORARRAY'] ) && count( $json['ERRORARRAY'] ) > 0 ) {
			$error = $json['ERRORARRAY'][0];
			throw new Exception( 'Linode API Error: ' . $json['ERRORMESSAGE'] );
		}

		return $json['DATA'];
	}
}