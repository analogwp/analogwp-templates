<?php
/**
 * Class Analog\Core\Storage\Transients
 *
 * @package   Analog
 * @copyright 2020 Dashwork Studio Pvt. Ltd.
 */

namespace Analog\Core\Storage;

/**
 * Class providing access to transients.
 *
 * @since n.e.x.t
 * @access private
 * @ignore
 */
final class Transients {

	/**
	 * Constructor.
	 *
	 * @since n.e.x.t
	 */
	public function __construct() {}

	/**
	 * Gets the value of the given transient.
	 *
	 * @since n.e.x.t
	 *
	 * @param string $transient Transient name.
	 * @return mixed Value set for the transient, or false if not set.
	 */
	public function get( $transient ) {
		return get_transient( $transient );
	}

	/**
	 * Sets the value for a transient.
	 *
	 * @since n.e.x.t
	 *
	 * @param string $transient  Transient name.
	 * @param mixed  $value      Transient value. Must be serializable if non-scalar.
	 * @param int    $expiration Optional. Time until expiration in seconds. Default 0 (no expiration).
	 * @return bool True on success, false on failure.
	 */
	public function set( $transient, $value, $expiration = 0 ) {
		return set_transient( $transient, $value, $expiration );
	}

	/**
	 * Deletes the given transient.
	 *
	 * @since n.e.x.t
	 *
	 * @param string $transient Transient name.
	 * @return bool True on success, false on failure.
	 */
	public function delete( $transient ) {
		return delete_transient( $transient );
	}
}

