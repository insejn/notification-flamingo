<?php

namespace BracketSpace\Notification\Flamingo\Utils;

class Fields {

	public function __construct() {
		include_once NOTIFICATION_FLAMINGO_BRIDGE_PATH . '/vendor/acf/acf.php';

		$this->register_options_page();
	}

	/**
	 *
	 * @filter acf/settings/path
	 */
	public function fields_settings_path( $path ) {

		$path = NOTIFICATION_FLAMINGO_BRIDGE_PATH . '/vendor/acf/';

		return $path;
	}

	/**
	 *
	 * @filter acf/settings/dir
	 */
	public function fields_settings_dir( $dir ) {

		$dir = NOTIFICATION_FLAMINGO_BRIDGE_DIR . '/vendor/acf/';

		return $dir;

	}

	// add_filter('acf/settings/show_admin', '__return_false');

	/**
	 *
	 * @filter acf/settings/save_json
	 */

	public function fields_json_save_point( $path ) {

		$path = NOTIFICATION_FLAMINGO_BRIDGE_PATH . '/vendor/fields-json';

		return $path;

	}

	/**
	 *
	 * @filter acf/settings/load_json
	 */

	public function fields_json_load_point( $paths ) {

		unset( $paths[0] );

		$paths[] = NOTIFICATION_FLAMINGO_BRIDGE_PATH . '/vendor/fields-json';

		return $paths;

	}

	public function register_options_page() {
		if ( ! function_exists( 'acf_add_options_page' ) ) {
			return;
		}

		$option_page = acf_add_options_page(array(
			'page_title'  => 'Notification Flamingo',
			'menu_title'  => 'Notification Flamingo Settings',
			'menu_slug'   => 'notification-flamingo-bridge-settings',
			'parent_slug' => 'edit.php?post_type=notification',
			'capability'  => 'edit_posts',
			'redirect'    => false,
		));

	}
}
