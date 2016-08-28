<?php
/**
 * The root API Framework.
 *
 * @package Box_Spawner
 * @subpackage Framework
 *
 * @since 1.0.0
 */
namespace BoxSpawner;

/**
 * The JSON API class.
 *
 * The base for all API classes that deal with RESTful responses.
 *
 * @internal Extended by other API classes.
 *
 * @since 1.0.0
 */
abstract class REST_API extends JSON_API {
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
	 * @throws Exception If there is an error with the cURL request.
	 *
	 * @return mixed The parsed result.
	 *     @option array  "headers" The list of response headers.
	 *     @option string "body"    The body of the response.
	 */
	protected function parse_result( $result, $curl, $options, $data ) {
		$result = parent::parse_result( $result, $curl, $options, $data );

		$response = $result['body'];

		if ( ! isset( $response['success'] ) ) {
			throw new Exception( 'Unrecognized response format. "result" entry should be present.' );
		}

		if ( isset( $response['errors'] ) && count( $response['errors'] ) > 0 ) {
			$error = $response['errors'][0];
			throw new Exception( 'REST API Error: ' . $error['message'] );
		}

		// Get the response data
		$response = $response['result'];

		return $data;
	}
}
