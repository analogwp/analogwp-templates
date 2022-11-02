<?php
/**
 * Options management.
 *
 * @package AnalogWP
 */

namespace Analog;

/**
 * AnalogWP options registration and management.
 */
class Options extends Base {
	const OPTION_KEY = 'ang_options';

	/**
	 * Checks whether or not a value is set for the given option.
	 *
	 * @since 1.6.0
	 *
	 * @param string $option Option name.
	 * @return bool True if value set, false otherwise.
	 */
	public function has( $option ) {
		$options = get_option( self::OPTION_KEY );

		return isset( $options[ $option ] );
	}

	/**
	 * Get a single option, or all if no key is provided.
	 *
	 * @param string|array|bool $key Option key.
	 * @return array|string|bool
	 */
	public function get( $key = false ) {
		$options = get_option( self::OPTION_KEY );

		if ( ! $options || ! is_array( $options ) ) {
			$options = array();
		}

		if ( false !== $key ) {
			return isset( $options[ $key ] ) ? $options[ $key ] : false;
		}

		return $options;
	}

	/**
	 * Update a single option.
	 *
	 * @param string $key Option key.
	 * @param mixed  $value Option value.
	 * @return void
	 */
	public function set( $key, $value ) {
		$options         = $this->get();
		$options[ $key ] = $value;
		update_option( self::OPTION_KEY, $options );
	}

	/**
	 * Delete a single option.
	 *
	 * @param string $key Option key.
	 * @return void
	 */
	public function delete( $key ) {
		$options = $this->get();
		if ( ! isset( $options[ $key ] ) ) {
			return;
		}

		unset( $options[ $key ] );
		update_option( self::OPTION_KEY, $options );
	}
}
