<?php
/**
 * The Nodebalancer Config Object.
 *
 * @package Box_Spawner
 * @subpackage Linode
 *
 * @since 1.0.0
 */
namespace BoxSpawner\Linode;

/**
 * The Nodebalancer Config class.
 *
 * An interface for creating/manipulating configs for a nodebalancer.
 *
 * @internal Used by the Nodebalancer class.
 *
 * @since 1.0.0
 */
class Nodebalancer_Config extends API_Object {
	use Creatable, Deletable, Updatable, \BoxSpawner\Dependable;

	/**
	 * The name of the option to assign the ID to.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	const ID_ATTRIBUTE = 'ConfigID';

	/**
	 * A list of Nodebancer_Node objects tied to it.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	protected $nodes = array();

	/**
	 * Create a new child Node.
	 *
	 * @since 1.0.0
	 *
	 * @param array $options Optional The create request parameters.
	 *
	 * @return Nodebancer_Node The new object.
	 */
	protected function add_node( $options = array() ) {
		// Add the ID attribute to the options
		$options['ConfigID'] = $this->id;

		// Create the object
		$object = new Nodebalancer_Node( $options );

		// Add it to the appropriate list
		$this->nodes[ $config->id ] = $object;

		return $object;
	}

	/**
	 * Load all child Nodes for this Config.
	 *
	 * All returned Nodes will be added to the nodes list.
	 *
	 * @since 1.0.0
	 */
	public function load_nodes() {
		$entries = $class::all( array(
			'ConfigID' => $this->id,
		) );

		foreach ( $entries as $entry ) {
			$id = $entry['NODEID'];

			$object = new Nodebalancer_Node( $id, $entry );

			$this->nodes[ $object->id ] = $object;
		}
	}

	/**
	 * Load a specific Node for this Config.
	 *
	 * @since 1.0.0
	 *
	 * @param int $id The ID of the Node to load.
	 *
	 * @return Nodebancer_Node The node object.
	 */
	public function load_node( $id ) {
		$entries = Nodebalancer_Node::fetch( $id, array(
			'ConfigID' => $this->id,
		) );

		$object = new Nodebalancer_Node( $id, $entry );

		$this->nodes[ $object->id ] = $object;

		return $object;
	}
}
