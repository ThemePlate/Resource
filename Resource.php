<?php

/**
 * Helper for resource hinting
 *
 * @package ThemePlate
 * @since 0.1.0
 */

namespace ThemePlate;

class Resource {

	private static $handles = array();
	private static $storage = array();


	public static function hint( $directive, $resource ) {

		$type = in_array( $directive, array( 'prefetch', 'preload' ), true ) ? 'handles' : 'resources';

		self::$storage[ $type ][ $directive ][] = $resource;

	}


	public static function init() {

		foreach ( self::$storage['resources'] as $directive => $resources ) {
			foreach ( $resources as $resource ) {
				$item = array(
					'rel'  => $directive,
					'href' => $resource,
				);

				self::insert( $item );
			}
		}

		self::prepare();

		foreach ( self::$storage['handles'] as $directive => $handles ) {
			foreach ( $handles as $handle ) {
				if ( self::check( $handle ) ) {
					continue;
				}

				$item = array( 'rel' => $directive ) + $handle;

				self::insert( $item );
			}
		}

	}


	private static function prepare() {

		global $wp_scripts, $wp_styles;

		foreach ( array( $wp_scripts, $wp_styles ) as $dependencies ) {
			if ( empty( $dependencies->queue ) ) {
				continue;
			}

			$type = get_class( $dependencies );

			foreach ( $dependencies->registered as $dependency ) {
				self::$handles[ $dependency->handle ] = array(
					'href' => $dependency->src,
					'as'   => strtolower( substr( $type, 3, -1 ) ),
				);
			}
		}

	}


	private static function check( &$handle ) {

		$retval = false;

		if ( ! is_array( $handle ) ) {
			$handle = self::$handles[ $handle ] ?? array();
		}

		if ( empty( $handle ) ) {
			$retval = true;
		}

		return $retval;

	}


	private static function insert( $item ) {

		$html = '';

		foreach ( $item as $attr => $value ) {
			$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );

			if ( ! is_string( $attr ) ) {
				$html .= " $value";
			} else {
				$html .= " $attr='$value'";
			}
		}

		$html = trim( $html );

		echo "<link $html />\n";

	}

}
