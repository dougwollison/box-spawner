<?php
/**
 * The Custom Exception.
 *
 * @package Box_Spawner
 * @subpackage Framework
 *
 * @internal Thrown by BoxSpawner and vendor classes.
 *
 * @since 1.0.0
 */
namespace BoxSpawner;

/**
 * The base Exception class.
 *
 * @since 1.0.0
 */
class Exception extends \Exception {}

/**
 * For actions that are not supported...
 *
 * @since 1.0.0
 */
class NotSupportedException extends \Exception {}

/**
 * For errors with PHP resources...
 *
 * @since 1.0.0
 */
class ResourceException extends \Exception {}
