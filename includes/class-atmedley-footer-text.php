<?php
/**
 * Functionality to update the footer text in themes.
 *
 * @package AudioTheme\Medley
 * @copyright Copyright (c) 2014, AudioTheme, LLC
 * @license GPL-2.0+
 * @since 1.0.0
 */

/**
 * Footer text class.
 *
 * @package AudioTheme\Medley
 * @since 1.0.0
 */
class ATMedley_Footer_Text {
	/**
	 * Load the footer text functionality.
	 *
	 * @since 1.0.0
	 */
	public function load() {
		add_action( 'customize_register', array( $this, 'customize_register' ), 20 );

		// @todo Standardize the filter in the theme for forward compatibility.
		add_filter( 'americanaura_footer_text', array( $this, 'footer_text_filter' ), 1000 );
		add_filter( 'nowell_footer_text', array( $this, 'footer_text_filter' ), 1000 );
		add_filter( 'promenade_footer_text', array( $this, 'footer_text_filter' ), 1000 );
		add_filter( 'shakenencore_footer_text', array( $this, 'footer_text_filter' ), 1000 );
	}

	/**
	 * Update the footer text.
	 *
	 * @since 1.0.0
	 *
	 * @param string $text Footer text.
	 * @return string
	 */
	public function footer_text_filter( $text ) {
		$settings = wp_parse_args( (array) get_option( 'atmedley' ), array(
			'placement' => '',
			'text'      => '',
		) );

		switch ( $settings['placement'] ) {
			case 'after' :
				$text .= '<br>' . $settings['text'];
				break;
			case 'before' :
				$text = $settings['text'] . '<br>' . $text;
				break;
			case 'remove' :
				$text = '';
				break;
			case 'replace' :
				$text = $settings['text'];
				break;
		}

		$search = array( '{{year}}' );
		$replace = array( date( 'Y' ) );
		$text = str_replace( $search, $replace, $text );

		$text = wptexturize( trim( $text ) );
		$text = convert_chars( $text );
		$text = wp_kses_post( $text );

		return $text;
	}

	/**
	 * Register Theme Customizer settings.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
	 */
	public function customize_register( $wp_customize ) {
		require_once( ATMEDLEY_DIR . '/includes/class-atmedley-customize-control-textarea.php' );

		$wp_customize->add_section( 'audiotheme_theme_footer', array(
			'title'    => __( 'Theme Footer', 'audiotheme-medley' ),
			'priority' => 1000,
		) );

		$wp_customize->add_setting( 'atmedley[text]', array(
			'default'           => '',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'wp_kses_data',
			'type'              => 'option',
		) );

		$wp_customize->add_control( new ATMedley_Customize_Control_Textarea( $wp_customize, 'at_footer_text', array(
			'label'    => __( 'Footer Text', 'audiotheme-medley' ),
			'rows'     => 3,
			'section'  => 'audiotheme_theme_footer',
			'settings' => 'atmedley[text]',
		) ) );

		$wp_customize->add_setting( 'atmedley[placement]', array(
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field',
			'type'              => 'option',
		) );

		$wp_customize->add_control( 'at_disable_credits', array(
			'choices'  => array(
				''        => '',
				'before'  => __( 'Before Credits', 'audiotheme-medley' ),
				'after'   => __( 'After Credits', 'audiotheme-medley' ),
				'replace' => __( 'Replace Credits', 'audiotheme-medley' ),
				'remove' => __( 'Remove All', 'audiotheme-medley' )
			),
			'label'    => __( 'Placement', 'audiotheme-theme-footer' ),
			'section'  => 'audiotheme_theme_footer',
			'settings' => 'atmedley[placement]',
			'type'     => 'select',
		) );
	}
}
