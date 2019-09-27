<?php

namespace BracketSpace\Notification\Flamingo\ContactForm;

class ContactForm {

	/**
	 * Adds custom field tag to render provinces list
	 *
	 * @action wpcf7_init 10 0
	 */
	public function custom_province_select() {
		wpcf7_add_form_tag( 'province_select', array( $this, 'custom_province_handler' ), array( 'name-attr' => true ) );
	}

	/**
	 * Handles
	 *
	 * @param object $tag tag object.
	 */
	public function custom_province_handler( $tag ) {
		$atts          = array();
		$atts['name']  = $tag->name;
		$atts['class'] = $tag->get_class_option();
		$atts['id']    = $tag->get_id_option();
		$atts          = wpcf7_format_atts( $atts );
		$html          = '<select ' . $atts . '>';
		$provinces     = get_field( 'notification-flamingo-provinces', 'option' );
		if ( $provinces ) {
			foreach ( $provinces as $province ) {
				$html .= '<option value="' . $province['province_name'] . '">' . $province['province_name'] . '</option>';
			}
		}
		$html .= '</select>';
		return $html;
	}

	/**
	 * Adds custom field tag to render services list
	 *
	 * @action wpcf7_init 10
	 */
	public function custom_service_select() {
		wpcf7_add_form_tag( 'service_select', array( $this, 'custom_service_handler' ), array( 'name-attr' => true ) );
	}

	/**
	 * Handles
	 *
	 * @param object $tag tag object.
	 */
	public function custom_service_handler( $tag ) {
		$atts             = array();
		$atts['name']     = $tag->name .'[]';
		$atts['class']    = $tag->get_class_option();
		$atts['id']       = $tag->get_id_option();
		$atts['multiple'] = 'multiple';
		$atts             = wpcf7_format_atts( $atts );
		$html             = '<select ' . $atts . '>';
		$services         = get_field( 'notification-flamingo-services', 'option' );
		if ( $services ) {
			foreach ( $services as $service ) {
				$html .= '<option value="' . $service['service_name'] . '">' . $service['service_name'] . '</option>';
			}
		}
		$html .= '</select>';
		return $html;
	}
}
