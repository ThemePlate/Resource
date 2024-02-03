<?php

namespace Tests;

use ThemePlate\Resource\Handler;
use WP_UnitTestCase;

class HandlerTest extends WP_UnitTestCase {
	protected Handler $handler;
	protected string $expect;
	protected string $directive = 'preload';
	protected string $asset_src = '/name.js';

	protected function setUp(): void {
		parent::setUp();

		$this->handler = new Handler();
		$this->expect  = "<link rel='$this->directive' href='$this->asset_src' as='script' />";
	}

	protected function get_result( string $status = 'enqueued' ): string {
		ob_start();
		$this->handler->action( $status );

		return trim( ob_get_clean() );
	}

	public function for_action_status(): array {
		return array(
			array(
				'wp_register_script',
				'registered',
				false,
			),
			array(
				'wp_register_script',
				'enqueued',
				true,
			),
			array(
				'wp_enqueue_script',
				'registered',
				false,
			),
			array(
				'wp_enqueue_script',
				'enqueued',
				false,
			),
		);
	}

	/**
	 * @dataProvider for_action_status
	 */
	public function test_action_status( string $function, string $status, bool $blank ): void {
		$function( 'script', $this->asset_src );
		$this->handler->script( 'script', $this->directive );

		$actual = $this->get_result( $status );

		if ( $blank ) {
			$this->assertNotSame( $this->expect, $actual );
		} else {
			$this->assertSame( $this->expect, $actual );
		}
	}
}
