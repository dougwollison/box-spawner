<?php
/**
 * The Resolve_Class Utility.
 *
 * @package Box_Spawner
 * @subpackage Utility
 *
 * @since 1.0.0
 */
namespace BoxSpawner;

/**
 * The Resolve Class trait.
 *
 * @internal Used by API classes to resolve the full name of a class in the same namespace.
 *
 * @since 1.0.0
 */
trait Resolve_Class {
	/**
	 * Resolve a classname by prepending the namespace of the calling class.
	 *
	 * @since 1.0.0
	 *
	 * @param string $class The class to resolve.
	 *
	 * @return string The fully resolved class name.
	 */
	public static function resolve_class( $class ) {
		static $namespace = '';
		if ( ! $namespace ) {
			$called_class = get_called_class();
			$namespace = substr( $called_class, 0, strrpos( $called_class, '\\' ) );
		}

		if ( strpos( $class, '\\' ) === false ){
			$class = $namespace . '\\' . $class;
		}

		return $class;
	}
}
