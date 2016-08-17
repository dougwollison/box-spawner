<?php
/**
 * The JSON Response Object.
 *
 * @package Box_Spawner
 * @subpackage Linode
 *
 * @since 1.0.0
 */

namespace BoxSpawner;

/**
 * The JSON Response class.
 *
 * An interface for handling the result of cURL requests with JSON encoded bodies.
 *
 * @api
 *
 * @since 1.0.0
 */
class Response_JSON extends Response {
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
	 * Parse the JSON body into an associative array.
	 *
	 * @since 1.0.0
	 */
	public function result() {
		$result = parent::result();

		$data = json_decode( $result['body'], true );

		$error = json_last_error();
		if ( $error !== JSON_ERROR_NONE ) {
			throw new Exception( 'JSON Decoding Error: ' . self::$json_errors[ $error ] );
		}

		$result['body'] = $data;

		return $result;
	}
}