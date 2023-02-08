<?php
namespace HivePress\Controllers;

use HivePress\Helpers as hp;
use HivePress\Models;
use HivePress\Blocks;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Controller class.
 */
final class Hello_World extends Controller {

	/**
	 * Class constructor.
	 *
	 * @param array $args Controller arguments.
	 */
	public function __construct( $args = [] ) {
		$args = hp\merge_arrays(
			[
				'routes' => [
					'hello_world_page' => [
						'title'     => esc_html__( 'Hello World Page', 'hello-world' ),
						'base'      => 'user_account_page',
						'path'      => '/hello-world',
						'redirect'  => [ $this, 'redirect_hello_world_page' ],
						'action'    => [ $this, 'render_hello_world_page' ],
						'paginated' => true,
					],
				],
			],
			$args
		);

		parent::__construct( $args );
	}
	/**
	 * Redirects user account page.
	 *
	 * @return mixed
	 */
	public function redirect_user_account_page() {

		// Check authentication.
		if ( ! is_user_logged_in() ) {
			return hivepress()->router->get_return_url( 'user_login_page' );
		}

		// Get menu items.
		$menu_items = ( new Menus\User_Account() )->get_items();

		if ( $menu_items ) {
			return hp\get_array_value( hp\get_first_array_value( $menu_items ), 'url' );
		}

		return true;
	}

/**
 * Redirects listing feed page.
 *
 * @return mixed
 */
public function redirect_hello_world_page() {

	// Check authentication.
	if ( ! is_user_logged_in() ) {
		return hivepress()->router->get_return_url( 'user_login_page' );
	}

	// Check followed vendors.
	if ( ! hivepress()->request->get_context( 'vendor_follow_ids' ) ) {
		return hivepress()->router->get_url( 'user_account_page' );
	}

	return false;
}

/**
 * Renders listing feed page.
 *
 * @return string
 */
public function render_hello_world_page() {

	// Create listing query.
	$query = Models\Listing::query()->filter(
		[
			'status'     => 'publish',
			'vendor__in' => hivepress()->request->get_context( 'vendor_follow_ids' ),
		]
	)->order( [ 'created_date' => 'desc' ] )
	->limit( get_option( 'hp_listings_per_page' ) )
	->paginate( hivepress()->request->get_page_number() );

	// Set request context.
	hivepress()->request->set_context(
		'post_query',
		$query->get_args()
	);

	// Render page template.
	return ( new Blocks\Template(
		[
			'template' => 'listings_feed_page',

			'context'  => [
				'listings' => [],
			],
		]
	) )->render();

	
}
}