<?php
/**
 * Administrator recipient
 *
 * @package notification
 */

namespace BracketSpace\Notification\Flamingo\Recipient;

use BracketSpace\Notification\Abstracts;
use BracketSpace\Notification\Defaults\Field;

/**
 * Administrator recipient
 */
class Province extends Abstracts\Recipient {

	/**
	 * Recipient constructor
	 *
	 * @since 5.0.0
	 */
	public function __construct() {
		parent::__construct(
			array(
				'slug'          => 'province',
				'name'          => __( 'Province', 'notification' ),
				'default_value' => 'Based on Notification Flamingo Bridge settings inside Provinces tab.',
			)
		);
	}

	/**
	 * Parses saved value something understood by notification
	 * Must be defined in the child class
	 *
	 * @param  string $value raw value saved by the user.
	 * @return array         array of resolved values
	 */
	public function parse_value( $value = '' ) {

		if ( empty( $value ) ) {
			$value = $this->get_default_value();
		}

		$emails = [];

		if ( $provinces_config = get_field( 'notification-flamingo-provinces', 'option' ) ) {
			foreach ( $provinces_config as $province ) {
				if ( $province['province_name'] == $value ) {
					if ( $province['emails'] ) {
						foreach ( $province['emails'] as $email ) {
							$emails[] = $email['email'];
						}
					}
				}
			}
		}

		return $emails;

	}

	/**
	 * Returns input object
	 *
	 * @return object
	 */
	public function input() {

		return new Field\InputField(
			array(
				'label'       => __( 'Recipient', 'notification' ),       // don't edit this!
				'name'        => 'recipient',       // don't edit this!
				'css_class'   => 'recipient-value', // don't edit this!
				'value'       => '{province}',
				'placeholder' => $this->get_default_value(),
				'resolvable'  => true,
			)
		);

	}

}
