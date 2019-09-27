<?php

namespace BracketSpace\Notification\Flamingo\Flamingo;

class View {

	/**
	 *
	 * @filter manage_flamingo_inbound_posts_columns
	 */
	public function set_custom_edit_book_columns( $columns ) {
		$columns['province'] = __( 'Province', 'notification-flamingo-bridge' );
		$columns['services'] = __( 'Services', 'notification-flamingo-bridge' );
		$columns['status']   = __( 'Status', 'notification-flamingo-bridge' );
		$columns['accept']   = __( 'Accept', 'notification-flamingo-bridge' );

		return $columns;
	}

	/**
	 *
	 * @action manage_flamingo_inbound_posts_custom_column
	 */
	public function custom_book_column( $column, $post_id ) {
		switch ( $column ) {

			case 'province':
				echo get_post_meta( $post_id, '_field_province', true );
				break;

			case 'services':
				echo $this->prepare_services_list( get_post_meta( $post_id, '_field_service', true ) );
				break;

			case 'status':
				echo $this->translate_status( get_post_status( $post_id ) );
				break;

			case 'accept':
				echo $this->show_accept_button( get_post_status( $post_id ), $post_id );
				break;

		}
	}

	public function translate_status( $status ) {
		switch ( $status ) {
			case 'publish':
				return 'pending';
				break;

			case 'draft':
				return 'accepted';
				break;
		}
	}

	public function show_accept_button( $status, $post_id ) {
		switch ( $status ) {
			case 'publish':
				return $this->acceptance_form( $post_id );
				break;

			case 'draft':
				return 'Email has been accepted';
				break;
		}
	}

	public function prepare_services_list( $meta ) {
		if(is_array( $meta )) {
			return implode( ', ', $meta );
		} else {
			return $meta;
		}
	}

	public function acceptance_form( $post_id ) {
		$btn = '<a href="' . admin_url( 'admin-post.php' ) . '?action=notification_flamingo_accept_email&email_id=' . $post_id . '" class="notification_flamingo_email_accept button button-primary">Accept</button>';
		return $btn;
	}
}
