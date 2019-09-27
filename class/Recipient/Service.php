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
class Service extends Abstracts\Recipient {

	/**
	 * Recipient constructor
	 *
	 * @since 5.0.0
	 */
	public function __construct() {
		parent::__construct(
			array(
				'slug'          => 'service',
				'name'          => __( 'Service', 'notification' ),
				'default_value' => 'Based on Notification Flamingo Bridge settings inside Services tab.',
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
		$value = explode( ',', $value );
		if ( $services_config = get_field( 'notification-flamingo-services', 'option' ) ) {
			foreach ( $services_config as $service ) {
				if ( in_array( $service['service_name'], $value ) ) {
					if ( $service['emails'] ) {
						foreach ( $service['emails'] as $email ) {
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
				'value'       => '{service}',
				'placeholder' => $this->get_default_value(),
				'resolvable'  => true,
			)
		);

	}

}
