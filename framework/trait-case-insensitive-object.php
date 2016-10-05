<?php
/**
 * The Case-Insensitive Object Utility.
 *
 * @package Box_Spawner
 * @subpackage Utility
 *
 * @since 1.0.0
 */
namespace BoxSpawner;

/**
 * The Case-Insensitive Object trait.
 *
 * @internal Used by API objects to allow case-insensitive attributes.
 *
 * @since 1.0.0
 */
trait Case_Insensitive_Object {
	/**
	 * @see BoxSpawner\API_Object\get()
	 */
	public function get( $attr ) {
		$attr = strtoupper( $attr );

		return parent::get( $attr );
	}

	/**
	 * @see BoxSpawner\API_Object\set()
	 */
	public function set( $attr, $value ) {
		$attr = strtoupper( $attr );

		return parent::set( $attr, $value );
	}
}
