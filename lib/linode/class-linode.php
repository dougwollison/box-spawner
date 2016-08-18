<?php
/**
 * The Linode Object.
 *
 * @package Box_Spawner
 * @subpackage Linode
 *
 * @since 1.0.0
 */
namespace BoxSpawner\Linode;

use \BoxSpawner as Framework;

/**
 * The Linode class.
 *
 * An interface for creating/manipulating Linode boxes.
 *
 * @api
 *
 * @since 1.0.0
 */
class Linode extends API_Object {
	use Creatable, Deletable, Updatable;

	/**
	 * The name of the option to assign the ID to.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	const ID_ATTRIBUTE = 'LinodeID';

	/**
	 * A list of Config objects tied to it.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	protected $configs = array();

	/**
	 * A list of Disk objects tied to it.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	protected $disks = array();

	/**
	 * A list of IP objects tied to it.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	protected $ips = array();

	/**
	 * Create a new Config.
	 *
	 * @since 1.0.0
	 *
	 * @param bool $public Optional Wether the IP is public or not (default: TRUE).
	 *
	 * @return Linode_IP The new Config object.
	 */
	public function add_ip( $public = true ) {
		$ip = new Linode_IP( array(
			'LinodeID' => $this->id,
			'IsPublic' => $public,
		) );

		$this->ips[ $ip->id ] = $ip;

		return $ip;
	}

	/**
	 * Issue a boot job.
	 *
	 * @since 1.0.0
	 *
	 * @param int $config_id Optional A configuration to boot with.
	 *
	 * @return int The ID of the boot job.
	 */
	public function boot( $config_id = null ) {
		$options = array(
			'LinodeID' => $this->id,
		);

		if ( ! is_null( $config_id ) ) {
			$options['ConfigID'] = $config_id;
		}

		$result = static::call_api( 'boot', $options );

		return $result['JobID'];
	}

	/**
	 * Issue a reboot job.
	 *
	 * @since 1.0.0
	 *
	 * @param int $config_id Optional A configuration to reboot with.
	 *
	 * @return int The ID of the boot job.
	 */
	public function reboot( $config_id = null ) {
		$options = array(
			'LinodeID' => $this->id,
		);

		if ( ! is_null( $config_id ) ) {
			$options['ConfigID'] = $config_id;
		}

		$result = static::call_api( 'boot', $options );

		return $result['JobID'];
	}

	/**
	 * Issue a shutdown job.
	 *
	 * @since 1.0.0
	 *
	 * @return int The ID of the boot job.
	 */
	public function shutdown() {
		$result = static::call_api( 'shutdown', array(
			'LinodeID' => $this->id,
		) );

		return $result['JobID'];
	}

	/**
	 * Resize the Linode to a new plan.
	 *
	 * @since 1.0.0
	 *
	 * @param int $plan_id The ID of the new plan for the linode.
	 */
	public function resize( $plan_id ) {
		$options = array(
			'LinodeID' => $this->id,
			'PlanID' => $plan_id,
		);

		static::call_api( 'resize', $options );
	}

	/**
	 * Clone the LinodeID to a new Linode.
	 *
	 * @since 1.0.0
	 *
	 * @param array $options The clone request parameters.
	 *     @option int "DatacenterID" Optional The ID of the datacenter to clone to (defaults to same).
	 *     @option int "PlanID"       Optional The ID of the plan for the linode clone (defaults to same).
	 *     @option int "PaymentTerm"  Optional the subscribtion term (in months).
	 *
	 * @return int The cloned linode's ID (same as this one).
	 */
	public function duplicate( $options ) {
		$defaults = array(
			'LinodeID' => $this->id,
			'DatacenterID' => $this->attributes['DATACENTERID'],
			'PlanID' => $this->attributes['PLANID'],
		);
		$options = array_merge( $defaults, $options );

		$result = static::call_api( 'clone', $options );

		return $result['LinodeID'];
	}

	/**
	 * Change the hypevisor from Xen to KVM.
	 *
	 * @since 1.0.0
	 */
	public function kvmify() {
		static::call_api( 'kvmify', array(
			'LinodeID' => $this->id,
		) );
	}
}
