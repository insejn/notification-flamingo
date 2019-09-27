<?php

namespace BracketSpace\Notification\Flamingo\Utils;

class Mods {
	/**
	 *
	 * @action admin_post_notification_flamingo_accept_email
	 */

	public function accept_email() {
		if ( $_GET['action'] != 'notification_flamingo_accept_email' ) {
			wp_die();
		}

		if ( ! isset( $_GET['email_id'] ) ) {
			wp_die();
		}

		$email_id = $_GET['email_id'];

		$post = get_post( $email_id );

		if ( is_wp_error( $post ) ) {
			wp_die();
		}

		$post->post_status = 'draft';
		$post              = wp_update_post( $post );
		do_action( 'notification_flamingo_email_accepted', $post );
		wp_redirect( admin_url( 'admin.php?page=flamingo_inbound' ) );

	}
}
