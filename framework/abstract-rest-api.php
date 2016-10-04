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
	 * Make the request, return it's result.
	 *
	 * @since 1.0.0
	 *
	 * @param string $method   The HTTP method for the request.
	 * @param string $endpoint The the endpoint for the request.
	 * @param array  $data     The data of the request.
	 *
	 * @return mixed The result of the request.
	 */
	public function request( $method, $endpoint, array $data = array() ) {
		$options = array(
			'method' => $method,
			'endpoint' => $endpoint,
		);

		return parent::do_request( $data, $options );
	}

	/**
	 * Perform a GET request.
	 *
	 * @since 1.0.0
	 *
	 * @param string $endpoint The the endpoint for the request.
	 * @param array  $data     The data of the request.
	 *
	 * @return array The result of the request.
	 */
	public function get( $endpoint, $data ) {
		return $this->request( 'GET', $endpoint, $data );
	}

	/**
	 * Perform a POST request.
	 *
	 * @since 1.0.0
	 *
	 * @param string $endpoint The the endpoint for the request.
	 * @param array  $data     The data of the request.
	 *
	 * @return array The result of the request.
	 */
	public function post( $endpoint, $data ) {
		return $this->request( 'POST', $endpoint, $data );
	}

	/**
	 * Perform a PUT request.
	 *
	 * @since 1.0.0
	 *
	 * @param string $endpoint The the endpoint for the request.
	 * @param array  $data     The data of the request.
	 *
	 * @return array The result of the request.
	 */
	public function put( $endpoint, $data ) {
		return $this->request( 'PUT', $endpoint, $data );
	}

	/**
	 * Perform a DELETE request.
	 *
	 * @since 1.0.0
	 *
	 * @param string $endpoint The the endpoint for the request.
	 * @param array  $data     The data of the request.
	 *
	 * @return array The result of the request.
	 */
	public function delete( $endpoint, $data ) {
		return $this->request( 'DELETE', $endpoint, $data );
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
	 * @throws Exception If there is an error with the cURL request.
	 *
	 * @return array The "result" portion of the JSON response.
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
		$result_data = $response['result'];

		return $result_data;
	}
}
