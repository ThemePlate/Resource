<?php

/**
 * Helper for resource hinting
 *
 * @package ThemePlate
 * @since 0.1.0
 */

namespace ThemePlate;

use ThemePlate\Resource\Handler;
use ThemePlate\Resource\Item;

class Resource {

	private static array $storage = array();

	private static Handler $handler;


	public static function hint( string $directive, $resource, array $extra = array() ): void {

		self::$storage[ $directive ][] = compact( 'resource', 'extra' );

	}


	public static function action(): void {

		self::$handler = new Handler();

		foreach ( self::$storage as $directive => $values ) {
			foreach ( $values as $value ) {
				if ( ! is_array( $value['resource'] ) ) {
					self::handle( $value['resource'], $directive, $value['extra'] );
				} else {
					( new Item( $value['resource']['href'], $directive ) )
						->extra( array_merge( $value['resource'], $value['extra'] ) )->tag();
				}
			}
		}

		self::$handler->action();

	}


	private static function handle( string $resource, string $directive, array $attributes ): void {

		$type = 'url';

		if ( wp_script_is( $resource ) ) {
			$type = 'script';
		} elseif ( wp_style_is( $resource ) ) {
			$type = 'style';
		}

		if ( 'url' === $type ) {
			$parsed = wp_parse_url( $resource );

			if ( empty( $parsed['host'] ) ) {
				return;
			}

			( new Item( $resource, $directive ) )->extra( $attributes )->tag();
		} else {
			self::$handler->{$type}( $resource, $directive, $attributes );
		}

	}

}
