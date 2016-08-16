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

	// Convert to lowercase, hyphenated form
	$name = preg_replace( '/[^a-z\\\]+/', '-', strtolower( $name ) );

	// Loop through each class type and try to load it
	$types = array( 'abstract', 'class', 'interface', 'trait' );
	foreach ( $types as $type ) {
		// Prefix the last part of the name with the type
		$file = preg_replace( '/([a-z\-]+)$/', "{$type}-$1", $name );

		// Create the full path
		$file = __DIR__ . '/lib/' . str_replace( '\\', DIRECTORY_SEPARATOR, $file ) . '.php';

		// Test if the file exists, load if so
		if ( file_exists( $file ) ) {
			require( $file );
			break;
		}
	}
}

spl_autoload_register( __NAMESPACE__ . '\autoload' );