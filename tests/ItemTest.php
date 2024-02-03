<?php

namespace Tests;

use ThemePlate\Resource\Item;
use WP_UnitTestCase;

class ItemTest extends WP_UnitTestCase {
	protected function get_result( Item $item ): string {
		ob_start();
		$item->tag();

		return trim( ob_get_clean() );
	}

	public function test_with_simple_resource(): void {
		$src = '/next/index.html';
		$dir = 'prefetch';

		$this->assertSame(
			"<link rel='$dir' href='$src' />",
			$this->get_result( new Item( $src, $dir ) )
		);
	}

	public function test_with_extra_attributes(): void {
		$src = '/fonts/custom.woff2';
		$dir = 'preload';
		$arr = array(
			'as'   => 'font',
			'type' => 'font/woff2',
		);
		$str = "as='font' type='font/woff2'";

		$this->assertSame(
			"<link rel='$dir' href='$src' $str />",
			$this->get_result( ( new Item( $src, $dir ) )->extra( $arr ) )
		);
	}
}
