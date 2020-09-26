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


	public static function hint( $resource ) {

		self::$storage[] = $resource;

	}


	public static function init() {

		foreach ( self::$storage as $resource ) {
			echo "<link rel='dns-prefetch' href='{$resource}' />\n";
		}

	}

}
