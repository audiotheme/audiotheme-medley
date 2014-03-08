<?php
/**
 * General methods and template tags.
 *
 * @package AudioTheme\Medley
 * @copyright Copyright (c) 2014, AudioTheme, LLC
 * @license GPL-2.0+
 * @since 1.0.0
 */

/**
 * Allow only the $allowedtags array in a string.
 *
 * @since  1.0.0
 *
 * @param string $string Unsanitized string.
 * @return string Sanitized string.
 */
function atmedley_allowed_tags( $text ) {
	$allowedtags = wp_kses_allowed_html();
	$tags['a']['rel']  = true;
	$allowedtags['br'] = array();

	return wp_kses( $text, $allowedtags );
}
