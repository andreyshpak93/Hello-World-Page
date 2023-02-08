<?php
namespace HivePress\Components;

use HivePress\Helpers as hp;
use HivePress\Models;
use HivePress\Emails;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Component class.
 */
final class Hello_World extends Component {

	/**
	 * Class constructor.
	 *
	 * @param array $args Component arguments.
	 */
	public function __construct( $args = [] ) {
		add_action('template_redirect', [ $this, 'redirect_listing_page']);
		// Attach functions to hooks here (e.g. add_action, add_filter).

		parent::__construct( $args );
	}
	public function redirect_listing_page() {
		if(is_singular('hp_listing') && ! is_user_logged_in()) {
			wp_safe_redirect(home_url('/register-now'));

			exit;
		}
	}
	// Implement the attached functions here.
}