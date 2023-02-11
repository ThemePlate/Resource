<?php

/**
 * Helper for resource hinting
 *
 * @package ThemePlate
 * @since 0.1.0
 */

namespace ThemePlate\Resource;

use WP_Dependencies;

class Handler {

	private array $storage = array(
		'script' => array(),
		'style'  => array(),
	);
	private array $scripts = array();
	private array $styles  = array();


	public function script( string $handle, string $directive ): void {

		$this->scripts[ $handle ] = $directive;

	}


	public function style( string $handle, string $directive ): void {

		$this->styles[ $handle ] = $directive;

	}


	public function init(): void {

		global $wp_scripts, $wp_styles;

		/** @var WP_Dependencies $dependencies */
		foreach ( array( $wp_scripts, $wp_styles ) as $dependencies ) {
			if ( empty( $dependencies->queue ) || empty( $dependencies->registered ) ) {
				continue;
			}

			$type = get_class( $dependencies );
			$type = strtolower( substr( $type, 3, -1 ) );

			foreach ( $dependencies->registered as $dependency ) {
				$this->storage[ $type ][ $dependency->handle ] = $dependency->src;
			}
		}

	}


	public function action(): void {

		foreach ( array( 'script', 'style' ) as $type ) {
			foreach ( $this->{$type . 's'} as $handle => $directive ) {
				$enqueued = 'script' === $type ? wp_script_is( $handle ) : wp_style_is( $handle );

				if ( array_key_exists( $handle, $this->storage[ $type ] ) && $enqueued ) {
					( new Item( $this->storage[ $type ][ $handle ], $directive ) )
						->extra(
							array(
								'as' => in_array( $directive, array( 'preload', 'prefetch' ), true ) ? $type : '',
							)
						)
						->tag();
				}
			}
		}

	}

}
