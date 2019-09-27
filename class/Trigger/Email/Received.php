<?php
/**
 * Email received trigger
 *
 * @package notification
 */

namespace BracketSpace\Notification\Flamingo\Trigger\Email;

use BracketSpace\Notification\Flamingo\Trigger\Email as EmailTrigger;

/**
 * Email received trigger class
 */
class Received extends EmailTrigger {


	private $form_tags = array();

	/**
	 * Constructor
	 */
	public function __construct( $form ) {

		$this->form = $form;

		parent::__construct(array(
			'slug' => 'flamingo/email/received/' . $form->ID,
			'name' => __( $form->post_title . ': Email received', 'notification-flamingo' ),
		));

		$this->add_action( 'wpcf7_submit', 1, 2 );
	}

	/**
	 * Assigns action callback args to object
	 * Return `false` if you want to abort the trigger execution
	 *
	 * @return mixed void or false if no notifications should be sent
	 */
	public function action( $form, $result ) {

		if ( $result['status'] !== 'mail_sent' ) {
			return false;
		}

		$this->email = new \stdClass();
		$submission        = \WPCF7_Submission::get_instance();
		$this->email->data = $submission->get_posted_data();

		foreach ( $this->email->data as $tag => $value ) {
			if ( substr( $tag, 0, 1 ) !== '_' ) {
				$this->email->content .= $tag . ': ' . $value . '<br>';
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
		 // $this->add_merge_tag( new \BracketSpace\Notification\Flamingo\MergeTag\Service() );
	}

}
