<?php
/**
 * The REST Dependable Framework.
 *
 * @package Box_Spawner
 * @subpackage Framework
 *
 * @since 1.0.0
 */
namespace BoxSpawner;

/**
 * The REST Dependable trait.
 *
 * @internal Used by REST objects that support child objects.
 *
 * @since 1.0.0
 */
trait REST_Dependable {
	use Dependable;

	/**
	 * Load all child objects of a type for this object.
	 *
	 * All returned objects will be added to the matching list.
	 *
	 * @since 1.0.0
	 *
	 * @param string $type The type of object to load.
	 */
	public function load_children( $type ) {
		// Get the class name for the child object
		$class = get_class( $this ) . '_' . ucwords( $type );

		$entries = $class::all( array(
			static::ID_ATTRIBUTE => $this->id,
		) );

		foreach ( $entries as $entry ) {
			$id = $entry['id'];

			$object = new $class( $id, $entry );

			$this->{"{$type}s"}[ $object->id ] = $object;
		}
	}
}
