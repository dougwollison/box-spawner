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
 * The base for all API classes that rely on a JSON request/response body.
 *
 * @internal Extended by other API classes.
 *
 * @since 1.0.0
 */
abstract class JSON_API extends API {
	/**
	 * A reference of JSON deconding errors.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	private $json_errors = array(
		JSON_ERROR_NONE             => 'No error has occurred',
		JSON_ERROR_DEPTH            => 'The maximum stack depth has been exceeded',
		JSON_ERROR_STATE_MISMATCH   => 'Invalid or malformed JSON',
		JSON_ERROR_CTRL_CHAR        => 'Control character error, possibly incorrectly encoded',
		JSON_ERROR_SYNTAX           => 'Syntax error',
		JSON_ERROR_UTF8             => 'Malformed UTF-8 characters, possibly incorrectly encoded',
		JSON_ERROR_RECURSION        => 'One or more recursive references in the value to be encoded',
		JSON_ERROR_INF_OR_NAN       => 'One or more NAN or INF values in the value to be encoded',
		JSON_ERROR_UNSUPPORTED_TYPE => 'A value of a type that cannot be encoded was given',
	);

	/**
	 * Determine the headers of the request.
	 *
	 * @since 1.0.0
	 *
	 * @param string|array $data    The data to send in the request.
	 * @param array        $options The options of the request.
	 *
	 * @return array The headers for the request.
	 */
	protected function get_request_headers( $data, $options ) {
		$headers = array(
			'Content-Type: application/json',
			'Content-Length: ' . strlen( json_encode( $data ) ),
		);

		if ( isset( $options['headers'] ) ) {
			$headers = $options['headers'];
		}

		return $headers;
	}

	/**
	 * Determine the body of the request.
	 *
	 * @since 1.0.0
	 *
	 * @param string|array $data    The data to send in the request.
	 * @param array        $options The options of the request.
	 *
	 * @return string|array The body for the request.
	 */
	protected function get_request_body( $data, $options ) {
		return json_encode( $data );
	}

	/**
	 * Parse the results.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed        $result  The result of the request.
	 * @param resource     $curl    The cURL handle of the request.
	 * @param string|array $data    The data to send in the request. (unused)
	 * @param array        $options The options of the request. (unused)
	 *
	 * @throws Exception If there is an error with the cURL request.
	 *
	 * @return mixed The parsed result.
	 *     @option array  "headers" The list of response headers.
	 *     @option string "body"    The body of the response.
	 */
	protected function parse_result( $result, $curl, $data , $options ) {
		$result = parent::parse_result( $result, $curl, $data , $options);

		$data = json_decode( $result['body'], true );

		$error = json_last_error();
		if ( $error !== JSON_ERROR_NONE ) {
			throw new ResourceException( 'JSON Decoding Error: ' . self::$json_errors[ $error ] );
		}

		$result['body'] = $data;

		return $result;
	}
}
