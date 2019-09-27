<?php
/**
 * Email accepted trigger
 *
 * @package notification
 */

namespace BracketSpace\Notification\Flamingo\Trigger\Email;

use BracketSpace\Notification\Flamingo\Trigger\Email as EmailTrigger;

/**
 * Email accepted trigger class
 */
class Accepted extends EmailTrigger {


	/**
	 * Constructor
	 */
	public function __construct() {

		parent::__construct(array(
			'slug' => 'flamingo/email/accepted',
			'name' => __( 'Email accepted', 'notification-flamingo' ),
		));

		$this->add_action( 'notification_flamingo_email_accepted', 10, 1 );

	}

	/**
	 * Assigns action callback args to object
	 * Return `false` if you want to abort the trigger execution
	 *
	 * @return mixed void or false if no notifications should be sent
	 */
	public function action( $post_id ) {
		$email = new \Flamingo_Inbound_Message( $post_id );

		if ( is_wp_error( $email ) ) {
			return;
		}

		$this->email = new \stdClass();
		$this->email->data = $email->fields;
		$this->email->content = '';

		foreach ( $email->fields as $tag => $value ) {
			if ( substr( $tag, 0, 1 ) !== '_' ) {
				if( is_array( $value ) ) {
					$this->email->content .= '<b>' . $tag . '</b>: ';
					$this->email->content .= '<ul>';
					foreach( $value as $single ) {
						$this->email->content .= '<li>' . $single . '</li>';
					}
					$this->email->content .= '</ul>';
				} else {
					$this->email->content .= '<b>' . $tag . '</b>: ' . $value . '<br>';
				}
			}
		}
	}

	public function merge_tags() {
		$this->add_merge_tag( new \BracketSpace\Notification\Defaults\MergeTag\HtmlTag( array(
			'slug'     => 'email_content',
			'name'     => __( 'Email content', 'textdomain' ),
			'resolver' => function() {
				return $this->email->content;
			},
		) ) );

		$this->add_merge_tag( new \BracketSpace\Notification\Flamingo\MergeTag\Province() );
		$this->add_merge_tag( new \BracketSpace\Notification\Flamingo\MergeTag\Service() );
	}

}
