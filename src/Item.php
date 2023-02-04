<?php

/**
 * Helper for resource hinting
 *
 * @package ThemePlate
 * @since 0.1.0
 */

namespace ThemePlate\Resource;

class Item {

	private array $attributes;


	public function __construct( string $url, string $directive ) {

		$this->attributes = array(
			'href' => $url,
			'rel'  => $directive,
		);

	}


	public function extra( array $attributes = array() ): self {

		$this->attributes = array_merge( $attributes, $this->attributes );

		return $this;

	}


	public function tag(): void {

		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo "<link{$this->stringify( $this->attributes )}/>" . PHP_EOL;

	}


	private function stringify( array $attributes ): string {

		$string = '';

		foreach ( array_filter( $attributes ) as $attr => $value ) {
			$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );

			if ( ! is_string( $attr ) ) {
				$string .= " $value";
			} else {
				$string .= " $attr='$value'";
			}
		}

		return $string;

	}

}
