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


	public static function hint( string $directive, $resource ): void {

		self::$storage[ $directive ][] = $resource;

	}


	public static function action(): void {

		self::$handler = new Handler();

		self::$handler->init();

		foreach ( self::$storage as $directive => $resources ) {
			foreach ( $resources as $resource ) {
				if ( ! is_array( $resource ) ) {
					self::handle( $resource, $directive );
				} else {
					( new Item( $resource['href'], $directive ) )->extra( $resource )->tag();
				}
			}
		}

		self::$handler->action();

	}


	private static function handle( string $resource, string $directive ): void {

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

			( new Item( $resource, $directive ) )->tag();
		} else {
			self::$handler->{$type}( $resource, $directive );
		}

	}

}
