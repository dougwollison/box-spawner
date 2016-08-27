<?php
/**
 * Box Spawner Vendor Registrar
 *
 * @package Box_Spawner
 * @subpackage Root
 *
 * @since 1.0.0
 */

namespace BoxSpawner;

/**
 * "Register" a vendor, creating flags for it's versions.
 *
 * @api Used in each vendor's _register.php file to define versions.
 *
 * @since 1.0.0
 *
 * @param string $namespace The sub namespace to use.
 * @param array  $info      The version information.
 */
function register( $namespace, $info ) {
	if ( ! isset( $info['versions'] ) ) {
		throw new Exception( 'Invalid vendor registration; version list needed.' );
	}
	if ( ! is_array( $info['versions'] ) ) {
		throw new Exception( 'Invalid vendor registration; version list must be an array.' );
	}

	$versions = $info['versions'];

	// Assume latest is last version
	$latest = array_pop( $versions );
	if ( isset( $info['latest'] ) ) {
		$latest = $info['latest'];
	}

	// Assume stable is second last version, otherwise same as latest
	$stable = $versions ? array_pop( $versions ) : $latest;
	if ( isset( $info['stable'] ) ) {
		$latest = $info['stable'];
	}

	// Define the latest and stable flags
	define( __NAMESPACE__ . '\\' . $namespace . '\\latest', $latest );
	define( __NAMESPACE__ . '\\' . $namespace . '\\stable', $stable );
}
