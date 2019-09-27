<?php
/**
 * User Bio merge tag
 *
 * Requirements:
 * - Trigger property `user_object` or any other passed as
 * `property_name` parameter. Must be an object, preferabely WP_User
 *
 * @package notification
 */

namespace BracketSpace\Notification\Flamingo\MergeTag;

use BracketSpace\Notification\Defaults\MergeTag\StringTag;

/**
 * User Bio merge tag class
 */
class Service extends StringTag {

	/**
	 * Trigger property to get the user data from
	 *
	 * @var string
	 */
	protected $property_name = 'email';

	/**
	 * Merge tag constructor
	 *
	 * @since 5.0.0
	 * @param array $params merge tag configuration params.
	 */
	public function __construct( $params = array() ) {

		if ( isset( $params['property_name'] ) && ! empty( $params['property_name'] ) ) {
			$this->property_name = $params['property_name'];
		}

		$args = wp_parse_args(
			$params,
			array(
				'slug'     => 'service',
				'name'     => __( 'Service based tag', 'notification' ),
				'example'  => true,
				'resolver' => function( $trigger ) {
					if( ! $trigger->{ $this->property_name }->data['service']) {
						return;
					}
					return implode( ',', $trigger->{ $this->property_name }->data['service'] );
				},
				'hidden'   => true,
			)
		);

		parent::__construct( $args );

	}
}
