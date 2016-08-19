<?php
/**
 * The Dependable Framework.
 *
 * @package Box_Spawner
 * @subpackage Framework
 *
 * @since 1.0.0
 */
namespace BoxSpawner;

/**
 * The Dependable trait.
 *
 * @internal Used by certain objects that support child objects.
 *
 * @since 1.0.0
 */
trait Dependable {
	/**
	 * Utility: create a new child object.
	 *
	 * @since 1.0.0
	 *
	 * @param string $type    The type of object to load.
	 * @param array  $options Optional The create request parameters.
	 *
	 * @return API_Object The new object.
	 */
	protected function add_child( $type, array $options = array() ) {
		// Get the class name for the child object
		$class = get_class( $this ) . '_' . ucwords( $type );

		// Add the ID attribute to the options
		$options[ static::ID_ATTRIBUTE ] = $this->id;

		// Create the object
		$object = new $class( $options );

		// Add it to the appropriate list
		$this->{"{$type}s"}[ $config->id ] = $object;

		return $object;
	}

	/**
	 * Utility: get all currently loaded child objects.
	 *
	 * @since 1.0.0
	 *
	 * @return array The list of child objects.
	 */
	protected function get_children( $type ) {
		return $this->{"{$type}s"};
	}

	/**
	 * Utility: get a specific child, fetching if needed/desired.
	 *
	 * @since 1.0.0
	 *
	 * @param string     $type The type of object to get.
	 * @param int|string $id   The ID of the object to get.
	 */
	protected function get_child( $type, $id ) {
		if ( isset( $this->{"{$type}s"}[ $id ] ) ) {
			return $this->{"{$type}s"}[ $id ];
		} else {
			return $this->load_child( $type, $id );
		}
	}

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

		$id_attr = $class::ID_ATTRIBUTE;

		foreach ( $entries as $entry ) {
			$id = $entry[ strtoupper( $id_attr ) ];

			$object = new $class( $id, $entry );

			$this->{"{$type}s"}[ $object->id ] = $object;
		}
	}

	/**
	 * Load a specific child object of a type for this object.
	 *
	 * @since 1.0.0
	 *
	 * @param string $type The type of object to load.
	 * @param int    $id   The ID of the object to load.
	 *
	 * @return API_Object The loaded object.
	 */
	public function load_child( $type, $id ) {
		// Get the class name for the child object
		$class = get_class( $this ) . '_' . ucwords( $type );

		$id_attr = $class::ID_ATTRIBUTE;

		$entries = $class::all( array(
			$id_attr => $id,
			static::ID_ATTRIBUTE => $this->id,
		) );

		$object = new $class( $id, $entry );

		$this->{"{$type}s"}[ $object->id ] = $object;

		return $object;
	}

	/**
	 * Method overloader.
	 *
	 * Handles add_*, get_* and load_* methods that use the same logic.
	 *
	 * @since 1.0.0
	 *
	 * @param string $name The name of the method being called.
	 * @param array  $args The arguments for the method.
	 *
	 * @return mixed The result of the redirected method.
	 */
	public function __call( $name, array $args ) {
		// $args should be a single argument or nothing
		$arg = reset( $args ) ?: array();

		if ( preg_match( '/^add_(\w+)$/', $name, $match ) ) {
			return $this->add_child( $match[1], $arg );
		} else
		if ( preg_match( '/^get_(\w+)s$/', $name, $match ) ) {
			return $this->get_children( $match[1], $arg );
		} else
		if ( preg_match( '/^get_(\w+)$/', $name, $match ) ) {
			return $this->get_child( $match[1], $arg );
		} else
		if ( preg_match( '/^load_(\w+)s$/', $name, $match ) ) {
			return $this->load_children( $match[1], $arg );
		} else
		if ( preg_match( '/^load_(\w+)$/', $name, $match ) ) {
			return $this->load_child( $match[1], $arg );
		}

		throw new Exception( sprintf( 'Invalid method "%s" for class "%s"', $name, get_class( $this ) ) );
	}
}
