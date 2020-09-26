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
				echo "<link rel='{$directive}' href='{$url}' />\n";
			}
		}

	}

}
