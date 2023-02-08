<?php
namespace HivePress\Menus;

use HivePress\Helpers as hp;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

class Hello_World extends Menu {
	public function __construct( $args = [] ) {
		$args = hp\merge_arrays(
			[
				// Define the menu items.
				'items' => [
					'first_item'  => [
						'label'  => 'First Item',
						'route'  => 'hello_world_page',
						'_order' => 123,
					],

					'second_item' => [
						'label'  => 'Second Item',
						'url'    => 'https://example.com',
						'_order' => 321,
					],
				],
			],
			$args
		);

		parent::__construct( $args );
	}
}