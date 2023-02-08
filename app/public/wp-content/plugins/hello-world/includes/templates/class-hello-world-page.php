<?php
namespace HivePress\Templates;

use HivePress\Helpers as hp;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Template class.
 */
class Hello_World_Page extends User_Account_Page {

	/**
	 * Class constructor.
	 *
	 * @param array $args Template arguments.
	 */
	public function __construct( $args = [] ) {
		$args = hp\merge_trees(
			[
				'blocks' => [
					'page_content' => [
						'blocks' => [
							'listings'               => [
								'type'    => 'listings',
								'columns' => 2,
								'_order'  => 10,
							],

							'listing_pagination'     => [
								'type'   => 'part',
								'path'   => 'page/pagination',
								'_order' => 20,
							],
						],
					],
					'vendors_unfollow_link' => [
						'type'   => 'part',
						'path'   => 'vendor/follow/vendors-unfollow-link',
						'_order' => 30,
					],
					
					'vendors_unfollow_modal' => [
						'title'  => esc_html__( 'Unfollow Vendors', 'foo-followers' ),
						'type'   => 'modal',
					
						'blocks' => [
							'vendors_unfollow_form' => [
								'type' => 'form',
								'form' => 'vendors_unfollow',
							],
						],
					],
				],
			],
			$args
		);

		parent::__construct( $args );
	}
	/**
 * Follows or unfollows vendor.
 *
 * @param WP_REST_Request $request API request.
 * @return WP_Rest_Response
 */
public function follow_vendor( $request ) {

	// Check authentication.
	if ( ! is_user_logged_in() ) {
		return hp\rest_error( 401 );
	}

	// Get vendor.
	$vendor = Models\Vendor::query()->get_by_id( $request->get_param( 'vendor_id' ) );

	if ( ! $vendor || $vendor->get_status() !== 'publish' ) {
		return hp\rest_error( 404 );
	}

	// Get follows.
	$follows = Models\Follow::query()->filter(
		[
			'user'   => get_current_user_id(),
			'vendor' => $vendor->get_id(),
		]
	)->get();

	if ( $follows->count() ) {

		// Delete follows.
		$follows->delete();
	} else {

		// Add new follow.
		$follow = ( new Models\Follow() )->fill(
			[
				'user'   => get_current_user_id(),
				'vendor' => $vendor->get_id(),
			]
		);

		if ( ! $follow->save() ) {
			return hp\rest_error( 400, $follow->_get_errors() );
		}
	}

	return hp\rest_response(
		200,
		[
			'data' => [],
		]
	);
}

/**
 * Unfollows all vendors.
 *
 * @param WP_REST_Request $request API request.
 * @return WP_Rest_Response
 */
public function unfollow_vendors( $request ) {

	// Check authentication.
	if ( ! is_user_logged_in() ) {
		return hp\rest_error( 401 );
	}

	// Delete follows.
	$follows = Models\Follow::query()->filter(
		[
			'user' => get_current_user_id(),
		]
	)->delete();

	return hp\rest_response(
		200,
		[
			'data' => [],
		]
	);
}
}