<?php
/**
 * Email trigger abstract
 *
 * @package notification
 */

namespace BracketSpace\Notification\Flamingo\Trigger;

use BracketSpace\Notification\Abstracts;
use BracketSpace\Notification\Defaults\MergeTag;

/**
 * Email trigger class
 */
abstract class Email extends Abstracts\Trigger {


	/**
	 * Constructor
	 *
	 * @param array $params trigger configuration params.
	 */
	public function __construct( $params = array() ) {

		if ( ! isset( $params['slug'], $params['name'] ) ) {
			trigger_error( 'Email trigger requires slug and name params.', E_USER_ERROR );
		}

		parent::__construct( $params['slug'], $params['name'] );

		$this->set_group( __( 'Flamingo : Email', 'notification-flamingo' ) );

	}

	/**
	 * Registers attached merge tags
	 *
	 * @return void
	 */
	public function merge_tags() {

		$this->add_merge_tag(new MergeTag\Post\PostID(array(
			'post_type' => 'topic',
			'name'      => __( 'Topic ID', 'notification-bbpress' ),
		)));
	}
}
