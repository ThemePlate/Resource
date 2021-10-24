<?php

/**
 * Helper for resource hinting
 *
 * @package ThemePlate
 * @since 0.1.0
 */

namespace ThemePlate;

class Resource {

	private static array $handles = array();
	private static array $storage = array(
		'handles'   => array(),
		'resources' => array(),
	);


	public static function hint( string $directive, $resource ): void {

		$type = in_array( $directive, array( 'prefetch', 'preload' ), true ) ? 'handles' : 'resources';

		self::$storage[ $type ][ $directive ][] = $resource;

		if ( did_action( 'init' ) ) {
			self::init( 2 );
		} else {
			add_action(
				'init',
				function () {
					self::init( 2 );
				}
			);
		}
	}


	public static function init( int $priority ): void {

		if ( ! has_action( 'wp_head', array( __CLASS__, 'action' ) ) ) {
			add_action( 'wp_head', array( __CLASS__, 'action' ), $priority );
		}

	}


	public static function action(): void {

		self::prepare();

		foreach ( self::$storage['resources'] as $directive => $resources ) {
			foreach ( $resources as $resource ) {
				$item = array(
					'rel'  => $directive,
					'href' => $resource,
				);

				self::insert( $item );
			}
		}

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


	private static function prepare(): void {

		global $wp_scripts, $wp_styles;

		foreach ( array( $wp_scripts, $wp_styles ) as $dependencies ) {
			if ( empty( $dependencies->queue ) || empty( $dependencies->registered ) ) {
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


	private static function check( &$handle ): bool {

		$retval = false;

		if ( ! is_array( $handle ) ) {
			$handle = self::$handles[ $handle ] ?? array();
		}

		if ( empty( $handle ) ) {
			$retval = true;
		}

		return $retval;

	}


	private static function insert( array $item ): void {

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

		echo "<link $html />\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	}

}
