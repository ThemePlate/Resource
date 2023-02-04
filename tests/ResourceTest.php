<?php

/**
 * @package ThemePlate
 */

namespace Tests;

use ThemePlate\Resource;
use ThemePlate\Tester\Utils;
use WP_UnitTestCase;

class ResourceTest extends WP_UnitTestCase {
	protected function tearDown(): void {
		parent::tearDown();

		Utils::set_inaccessible_property(
			new Resource(),
			'handles',
			array()
		);

		Utils::set_inaccessible_property(
			new Resource(),
			'storage',
			array(
				'handles'   => array(),
				'resources' => array(),
			)
		);
	}

	public function for_hint_with_string_resource(): array {
		return array(
			array(
				'dns-prefetch',
				'cdnjs.cloudflare.com',
			),
			array(
				'preconnect',
				'ajax.cloudflare.com',
			),
			array(
				'prerender',
				'http://my.site/blog',
			),
		);
	}

	/**
	 * @dataProvider for_hint_with_string_resource
	 */
	public function test_hint_with_string_resource( string $directive, string $resource ): void {
		Resource::hint( $directive, $resource );
		ob_start();
		Resource::action();

		$actual   = ob_get_clean();
		$resource = esc_url( $resource );

		$this->assertNotFalse( stripos( $actual, "rel='$directive'" ) );
		$this->assertNotFalse( stripos( $actual, "href='$resource'" ) );
	}

	public function for_hint_with_known_handle(): array {
		return array(
			array(
				'prefetch',
				'jquery-migrate',
			),
			array(
				'preload',
				'jquery-core',
			),
		);
	}

	/**
	 * @dataProvider for_hint_with_known_handle
	 */
	public function test_hint_with_known_handle( string $directive, string $handle ): void {
		Resource::hint( $directive, $handle );
		wp_enqueue_script( $handle );
		ob_start();
		Resource::action();

		$actual = ob_get_clean();

		$this->assertNotFalse( stripos( $actual, "rel='$directive'" ) );
		$this->assertNotFalse( stripos( $actual, "href='/wp-includes/js/jquery/" ) );
		$this->assertNotFalse( stripos( $actual, "as='script'" ) );
	}

	public function test_hint_with_custom_array(): void {
		$directive = 'preload';
		$resource  = array(
			'href' => 'https://fonts.gstatic.com/s/montserrat/v14/JTURjIg1_i6t8kCHKm45_cJD3gTD_u50.woff2',
			'as'   => 'font',
			'type' => 'font/woff2',
		);

		Resource::hint( $directive, $resource );
		ob_start();
		Resource::action();

		$actual = ob_get_clean();

		$this->assertNotFalse( stripos( $actual, "rel='$directive'" ) );
		$this->assertNotFalse( stripos( $actual, "href='{$resource['href']}" ) );
		$this->assertNotFalse( stripos( $actual, "as='{$resource['as']}'" ) );
		$this->assertNotFalse( stripos( $actual, "type='{$resource['type']}'" ) );
	}
}
