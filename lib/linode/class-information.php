<?php
/**
 * The Information API.
 *
 * @package Box_Spawner
 * @subpackage Linode
 *
 * @since 1.0.0
 */
namespace BoxSpawner\Linode;

/**
 * The Information class.
 *
 * An interface for accessing Linode public information.
 *
 * @api
 *
 * @since 1.0.0
 */
class Information extends API {
	/**
	 * Get the machine-readable API specification.
	 *
	 * @since 1.0.0
	 *
	 * @return array The full specification list of the Linode API.
	 */
	public static function get_api_spec() {
		return static::request( 'api.spec' );
	}

	/**
	 * Get the list of datacenters.
	 *
	 * @since 1.0.0
	 *
	 * @return array The list of datacenters.
	 */
	public static function list_datacenters() {
		return static::request( 'avail.datacenters' );
	}

	/**
	 * Get the list of distributions.
	 *
	 * @since 1.0.0
	 *
	 * @return array The list of distributions.
	 */
	public static function list_distributions() {
		return static::request( 'avail.distributions' );
	}

	/**
	 * Get a single distribution.
	 *
	 * @since 1.0.0
	 *
	 * @return array The information of the requested distribution.
	 */
	public static function get_distribution( $distribution_id ) {
		return static::request( 'avail.distributions', array(
			'DistributionID' => $distribution_id,
		) );
	}

	/**
	 * Get the list of kernels.
	 *
	 * @since 1.0.0
	 *
	 * @param array $filter Optional A hash of isXen or isKVM flags to limit the list to.
	 *
	 * @return array The list of kernels.
	 */
	public static function list_kernels( array $filter = array() ) {
		return static::request( 'avail.kernels', $filter );
	}

	/**
	 * Get the list of Linode plans.
	 *
	 * @since 1.0.0
	 *
	 * @return array The list of plans.
	 */
	public static function list_linodeplans() {
		return static::request( 'avail.linodeplans' );
	}

	/**
	 * Get a single distribution.
	 *
	 * @since 1.0.0
	 *
	 * @return array The information of the requested plan.
	 */
	public static function get_linodeplan( $plan_id ) {
		return static::request( 'avail.linodeplans', array(
			'PlanID' => $plan_id,
		) );
	}

	/**
	 * Get the NodeBalancer pricing.
	 *
	 * @since 1.0.0
	 *
	 * @return array The pricing information.
	 */
	public static function list_nodebalancers() {
		return static::request( 'avail.nodebalancers' );
	}

	/**
	 * Get the list of StackScripts.
	 *
	 * @since 1.0.0
	 *
	 * @param array $filter Optional A hash of distribution ID, vendor, and/or keyword to limit the list to.
	 *
	 * @return array The list of scripts.
	 */
	public static function list_stackscripts( array $filter = array() ) {
		return static::request( 'avail.stackscripts', $filter );
	}
}
