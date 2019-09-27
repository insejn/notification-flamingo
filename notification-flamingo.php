<?php
/**
 * Plugin Name: Notification : Flamingo
 * Description: Extension for Notification plugin
 * Plugin URI: https://localhost
 * Author: Piotr Konicki
 * Author URI: https://localhost
 * Version: 1.0.1
 * License: GPL3
 * Text Domain: notification-flamingo
 * Domain Path: /languages
 *
 * @package notification/flamingo
 */

/**
 * Plugin's autoload function
 *
 * @param  string $class class name.
 * @return mixed         false if not plugin's class or void
 */
function notification_flamingo_autoload( $class ) {

	$parts      = explode( '\\', $class );
	$namespaces = array( 'BracketSpace', 'Notification', 'Flamingo' );

	foreach ( $namespaces as $namespace ) {
		if ( array_shift( $parts ) !== $namespace ) {
			return false;
		}
	}

	$file = trailingslashit( dirname( __FILE__ ) ) . trailingslashit( 'class' ) . implode( '/', $parts ) . '.php';

	if ( file_exists( $file ) ) {
		require_once $file;
	}

}
spl_autoload_register( 'notification_flamingo_autoload' );

define( 'NOTIFICATION_FLAMINGO_BRIDGE_PATH', plugin_dir_path( __FILE__ ) );
define( 'NOTIFICATION_FLAMINGO_BRIDGE_DIR', plugin_dir_url( __FILE__ ) );

/**
 * Boot up the plugin in theme's action just in case the Notification
 * is used as a bundle.
 */
add_action( 'plugins_loaded', function() {

	if ( ! function_exists( 'notification_runtime' ) ) {
		return;
	}

	/**
	 * Requirements check
	 */
	$requirements = new BracketSpace\Notification\Flamingo\Utils\Requirements( __( 'Notification : Flamingo', 'notification-flamingo' ), array(
		'php'          => '5.6',
		'wp'           => '4.9',
		'notification' => true,
		'plugins'      => array(
			'flamingo/flamingo.php' => array(
				'name'    => 'Flamingo',
				'version' => '0',
			),
		),
	) );

	/**
	 * Tests if Notification plugin is active
	 * We have to do it like this in case the plugin
	 * is loaded as a bundle.
	 *
	 * @param string $comparsion value to test.
	 * @param object $r          requirements.
	 * @return void
	 */
	function notification_flamingo_check_base_plugin( $comparsion, $r ) {
		if ( ! function_exists( 'notification_runtime' ) ) {
			$r->add_error( __( '<a href="https://wordpress.org/plugins/notification/" target="_blank">Notification</a> plugin active', 'notification-flamingo-bridge' ) );
			return;
		}

		if ( true !== $comparsion ) {
			if ( ! defined( 'NOTIFICATION_VERSION' ) ) {
				$r->add_error( __( 'Notification plugin updated to the latest version', 'notification-flamingo-bridge' ) );
			} elseif ( version_compare( $comparsion, NOTIFICATION_VERSION, '>' ) ) {
				// translators: version number.
				$r->add_error( sprintf( __( 'Notification plugin in version at least %s', 'notification-flamingo-bridge' ), $comparsion ) );
			}
		}
	}

	$requirements->add_check( 'notification', 'notification_flamingo_check_base_plugin' );

	if ( ! $requirements->satisfied() ) {
		add_action( 'admin_notices', array( $requirements, 'notice' ) );
		return;
	}

	$files_class = new BracketSpace\Notification\Utils\Files( __FILE__ );
	$view_class  = new BracketSpace\Notification\Utils\View( $files_class );
	$scripts     = notification_add_doc_hooks( new BracketSpace\Notification\Flamingo\Scripts( $files_class ) );

	$contact_form = notification_add_doc_hooks( new BracketSpace\Notification\Flamingo\ContactForm\ContactForm() );
	$fields       = notification_add_doc_hooks( new BracketSpace\Notification\Flamingo\Utils\Fields() );
	$mods         = notification_add_doc_hooks( new BracketSpace\Notification\Flamingo\Utils\Mods() );
	$view         = notification_add_doc_hooks( new BracketSpace\Notification\Flamingo\Flamingo\View() );

	notification_register_recipient( 'email', new BracketSpace\Notification\Flamingo\Recipient\Province() );
	notification_register_recipient( 'email', new BracketSpace\Notification\Flamingo\Recipient\Service() );

	notification_register_trigger( new BracketSpace\Notification\Flamingo\Trigger\Email\Accepted() );

	$forms = get_posts( array(
		'post_type'      => 'wpcf7_contact_form',
		'posts_per_page' => -1,
	) );
	foreach ( $forms as $form ) {
		notification_register_trigger( new BracketSpace\Notification\Flamingo\Trigger\Email\Received( $form ) );
	}

} );

