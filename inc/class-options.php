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
