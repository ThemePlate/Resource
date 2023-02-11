<?php

/**
 * Helper for resource hinting
 *
 * @package ThemePlate
 * @since 0.1.0
 */

namespace ThemePlate\Resource;

class Handler {

	private array $scripts = array();
	private array $styles  = array();


	public function script( string $handle, string $directive ): void {

		$this->scripts[ $handle ] = $directive;

	}


	public function style( string $handle, string $directive ): void {

		$this->styles[ $handle ] = $directive;

	}


	public function init(): void {

		_deprecated_function( __METHOD__, '2.1.0' );

	}


	public function action(): void {

		foreach ( array( 'script', 'style' ) as $type ) {
			foreach ( $this->{$type . 's'} as $handle => $directive ) {
				$enqueued = 'script' === $type ? wp_script_is( $handle ) : wp_style_is( $handle );

				if ( ! $enqueued ) {
					continue;
				}

				$source = 'script' === $type ? Helper::get_script_src( $handle ) : Helper::get_style_src( $handle );

				if ( $source ) {
					( new Item( $source, $directive ) )
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
