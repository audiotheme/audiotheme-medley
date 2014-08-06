<?php
/**
 * Functionality to update the credits text in themes.
 *
 * @package AudioTheme\Medley
 * @copyright Copyright (c) 2014, AudioTheme, LLC
 * @license GPL-2.0+
 * @since 1.0.0
 */

/**
 * Theme credits class.
 *
 * @package AudioTheme\Medley
 * @since 1.0.0
 */
class ATMedley_Credits {
	/**
	 * Load the credits functionality.
	 *
	 * @since 1.0.0
	 */
	public function load() {
		add_action( 'customize_register', array( $this, 'customize_register' ), 20 );

		$template = get_template();
		add_filter( $template . '_credits', array( $this, 'credits_text' ), 1000 );

		// Backwards compatibility.
		add_filter( 'americanaura_footer_text', array( $this, 'credits_text' ), 1000 );
		add_filter( 'nowell_footer_text', array( $this, 'credits_text' ), 1000 );
	}

	/**
	 * Update the credits text.
	 *
	 * @since 1.0.0
	 *
	 * @param string $text Credits text.
	 * @return string
	 */
	public function credits_text( $text ) {
		$settings = wp_parse_args( (array) get_option( 'atmedley' ), array(
			'credits_placement' => '',
			'credits_text'      => '',
		) );

		switch ( $settings['credits_placement'] ) {
			case 'after' :
				$text .= ' ' . $settings['credits_text'];
				break;
			case 'before' :
				$text = $settings['credits_text'] . ' ' . $text;
				break;
			case 'remove' :
				$text = '';
				break;
			case 'replace' :
				$text = $settings['credits_text'];
				break;
		}

		$search = array( '{{year}}' );
		$replace = array( date( 'Y' ) );
		$text = str_replace( $search, $replace, $text );

		$text = wptexturize( trim( $text ) );
		$text = convert_chars( $text );
		$text = atmedley_allowed_tags( $text );

		return $text;
	}

	/**
	 * Register Customizer settings.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer object.
	 */
	public function customize_register( $wp_customize ) {
		require_once( ATMEDLEY_DIR . '/includes/class-atmedley-customize-control-textarea.php' );

		$wp_customize->add_section( 'atmedley_credits', array(
			'title'    => __( 'Theme Credits', 'audiotheme-medley' ),
			'priority' => 1000,
		) );

		$wp_customize->add_setting( 'atmedley[credits_text]', array(
			'default'           => '',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'atmedley_allowed_tags',
			'type'              => 'option',
		) );

		$wp_customize->add_control( new ATMedley_Customize_Control_Textarea( $wp_customize, 'atmedley_credits_text', array(
			'label'    => __( 'Credits', 'audiotheme-medley' ),
			'rows'     => 3,
			'section'  => 'atmedley_credits',
			'settings' => 'atmedley[credits_text]',
		) ) );

		$wp_customize->add_setting( 'atmedley[credits_placement]', array(
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field',
			'type'              => 'option',
		) );

		$wp_customize->add_control( 'atmedley_credits_placement', array(
			'choices'  => array(
				''        => '',
				'before'  => __( 'Before', 'audiotheme-medley' ),
				'after'   => __( 'After', 'audiotheme-medley' ),
				'replace' => __( 'Replace', 'audiotheme-medley' ),
				'remove'  => __( 'Remove All', 'audiotheme-medley' )
			),
			'label'    => __( 'Placement', 'audiotheme-medley' ),
			'section'  => 'atmedley_credits',
			'settings' => 'atmedley[credits_placement]',
			'type'     => 'select',
		) );
	}
}
