<?php

/**
 * Helper for resource hinting
 *
 * @package ThemePlate
 * @since 0.1.0
 */

namespace ThemePlate;

class Resource {

	private static $storage = array();


	public static function hint( $directive, $url ) {

		self::$storage[ $directive ][] = $url;

	}


	public static function init() {

		foreach ( self::$storage as $directive => $urls ) {
			foreach ( $urls as $url ) {
				$item = array(
					'rel'  => $directive,
					'href' => $url,
				);

				self::insert( $item );
			}
		}

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
