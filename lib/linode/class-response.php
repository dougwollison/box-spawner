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
class Response extends \BoxSpawner\JSON_Response {
	/**
	 * Get the results of the request.
	 *
	 * @since 1.0.0
	 *
	 * @return array The JSON decoded DATA.
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
			if ( $error['ERRORCODE'] !== 0 ) {
				throw new Exception( 'Linode API Error: ' . $error['ERRORMESSAGE'] );
			}
		}

		// Get the response data
		$data = $json['DATA'];

		// Standardize the keys of the DATA entries if needed
		if ( $json['ACTION'] == 'linode.config.list' ) {
			$formatted = array();
			foreach ( $data as $entry ) {
				// Convert all keys to uppercase
				$keys = array_map( 'strtoupper', array_keys( $entry ) );
				$values = array_values( $entry );

				$formatted[] = array_combine( $keys, $values );
			}
			$data = $formatted;
		}

		return $data;
	}
}