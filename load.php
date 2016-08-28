<?php
/**
 * Box Spawner Autoloader
 *
 * @package Box_Spawner
 * @subpackage Root
 *
 * @since 1.0.0
 */

namespace BoxSpawner;

define( __NAMESPACE__ . '\\BASEDIR', __DIR__ );

// Load the register utility
require( BASEDIR . '/register.php' );

// Load the exceptions
require( BASEDIR . '/exceptions.php' );

/**
 * The class autoloader.
 *
 * @since 1.0.0
 *
 * @param string $fullname The full name of the class to load.
 */
function autoload( $fullname ) {
	// Trim preceding backslash
	$fullname = ltrim( $fullname, '\\' );

	// Abort if not within the root namespace
	if ( strpos( $fullname, __NAMESPACE__ ) !== 0 ) {
		return;
	}

	// Remove the root namespace
	$name = substr( $fullname, strlen( __NAMESPACE__ ) + 1 );

	// Default to framework directory
	$basesdir = BASEDIR . '/framework/';

	// Switch to vendor directory if there's a sub-namespace
	if ( strpos( $name, '\\' ) !== false ) {
		$basesdir = BASEDIR . '/vendor/';
	}

	// Convert to lowercase, hyphenated form
	$name = preg_replace( '/[^\w+\\\]+/', '-', strtolower( $name ) );

	// Loop through each class type and try to load it
	$types = array( 'abstract', 'class', 'interface', 'trait' );
	foreach ( $types as $type ) {
		// Prefix the last part of the name with the type
		$file = preg_replace( '/([\w\-]+)$/', "{$type}-$1", $name );

		// Create the full path
		$file = $basesdir . str_replace( '\\', DIRECTORY_SEPARATOR, $file ) . '.php';

		// Test if the file exists, load if so
		if ( file_exists( $file ) ) {
			require( $file );
			break;
		}
	}
}

spl_autoload_register( __NAMESPACE__ . '\autoload' );