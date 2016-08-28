<?php
/**
 * The Linode API (version 3).
 *
 * @package Box_Spawner
 * @subpackage Linode
 *
 * @since 1.0.0
 */
namespace BoxSpawner\Linode\V3;

/**
 * The API class.
 *
 * The interface for all API requests.
 *
 * @api
 *
 * @since 1.0.0
 */
class API extends \BoxSpawner\JSON_API {
	/**
	 * Make the request, return it's result.
	 *
	 * @since 1.0.0
	 *
	 * @param string $action The api_action
	 * @param array  $data   The data of the request.
	 *
	 * @return mixed The result of the request.
	 */
	public function request( $endpoint, $data = array() ) {
		$data['api_action'] = $endpoint;

		return parent::request( $data );
	}

	/**
	 * Set the URL of the request.
	 *
	 * @since 1.0.0
	 *
	 * @param resource     $curl    The cURL handle of the request.
	 */
	protected function set_request_url( $curl ) {
		curl_setopt( $curl, CURLOPT_URL, 'https://api.linode.com/' );
	}

	/**
	 * Set the method of the request.
	 *
	 * @since 1.0.0
	 *
	 * @param resource     $curl    The cURL handle of the request.
	 */
	protected function set_request_method( $curl ) {
		curl_setopt( $curl, CURLOPT_CUSTOMREQUEST, 'POST' );
	}

	/**
	 * Parse the results.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed        $result  The result of the request.
	 * @param resource     $curl    The cURL handle of the request.
	 * @param array        $options The options of the request. (unused)
	 * @param string|array $data    The data to send in the request. (unused)
	 *
	 * @throws InvalidResponseException If the result is missing the DATA section.
	 * @throws ErrorRepsonseException   If the API returned an error.
	 *
	 * @return mixed The parsed result.
	 *     @option array  "headers" The list of response headers.
	 *     @option string "body"    The body of the response.
	 */
	protected function parse_result( $result, $curl, $options, $data ) {
		// Get the result
		$result = parent::parse_result( $result, $curl, $options, $data );

		$json = $result['body'];

		if ( ! isset( $json['DATA'] ) ) {
			throw new InvalidResponseException( 'Unrecognized response format. "DATA" entry should be present.' );
		}

		if ( isset( $json['ERRORARRAY'] ) && count( $json['ERRORARRAY'] ) > 0 ) {
			$error = $json['ERRORARRAY'][0];
			if ( $error['ERRORCODE'] !== 0 ) {
				throw new ErrorRepsonseException( 'Linode API Error: ' . $error['ERRORMESSAGE'] );
			}
		}

		// Get the response data
		$response = $json['DATA'];

		// Standardize the keys of the DATA entries if needed
		if ( $json['ACTION'] == 'linode.config.list' ) {
			$formatted = array();
			foreach ( $response as $entry ) {
				// Convert all keys to uppercase
				$keys = array_map( 'strtoupper', array_keys( $entry ) );
				$values = array_values( $entry );

				$formatted[] = array_combine( $keys, $values );
			}
			$response = $formatted;
		}

		return $response;
	}
}
