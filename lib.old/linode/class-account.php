<?php
/**
 * The Account API.
 *
 * @package Box_Spawner
 * @subpackage Linode
 *
 * @since 1.0.0
 */
namespace BoxSpawner\Linode;

/**
 * The Account class.
 *
 * An interface for accessing Linode account information.
 *
 * @api
 *
 * @since 1.0.0
 */
class Account {
	/**
	 * List information about the current account.
	 *
	 * @since 1.0.0
	 *
	 * @return array The account information (opened date, balance, network usage, etc.).
	 */
	public static function get_info() {
		return API::request( 'account.info' );
	}

	/**
	 * Get an invoice estimate.
	 *
	 * @since 1.0.0
	 *
	 * @param string $mode The estimate type ('linode_new', 'linode_resize', 'nodebalancer_new').
	 * @param int    $arg1 The Plan ID (for *_new) or Linode ID (for linode_resize).
	 * @param int    $arg2 The Plan ID (for linode_resize) or Payment Term (for *_new).
	 *
	 * @return array A hash of the invoice date and amount.
	 */
	public static function get_estimate( $mode, $arg1, $arg2 ) {
		$data = array(
			'mode' => $mode,
		);

		switch ( $mode ) {
			case 'linode_resize':
				$data['LinodeID'] = $arg1;
				$data['PlanID'] = $arg2;
				break;

			default:
				$data['PlanID'] = $arg1;
				$data['PaymentTerm'] = $arg2;
		}

		return API::request( 'account.estimateinvoice', $data );
	}

	/**
	 * Get an API key for a specified user.
	 *
	 * @since 1.0.0
	 *
	 * @param string $username The name of the user the API key is for.
	 * @param string $password The password of the user.
	 * @param array  $options  Optional A hash of options:
	 *     @option string "token"   Optional The two-factor authentication token if applicable.
	 *     @option int    "expires" Optional The number of hours (0 - 8760) the key is valid for (0 = infinite).
	 *     @option string "label"   Optional A label for this key.
	 *
	 * @return string The new API key requested.
	 */
	public static function new_api_key( $username, $password, array $options = array() ) {
		$options['username'] = $username;
		$options['password'] = $password;

		$result = API::request( 'user.getapikey', $options );

		return $result['API_KEY'];
	}
}
