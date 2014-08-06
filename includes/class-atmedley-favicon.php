<?php
/**
 * Favicon settings.
 *
 * @package AudioTheme\Medley
 * @copyright Copyright (c) 2014, AudioTheme, LLC
 * @license GPL-2.0+
 * @since 1.1.0
 */

/**
 * Class to print the favicon and register settings.
 *
 * @package AudioTheme\Medley
 * @since 1.1.0
 */
class ATMedley_Favicon {
	/**
	 * Load the credits functionality.
	 *
	 * @since 1.1.0
	 */
	public function load() {
		add_action( 'wp_head', array( $this, 'print_favicon_tag' ) );
		add_action( 'customize_register', array( $this, 'customize_register' ), 20 );
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'enqueue_customizer_controls_assets' ) );
		add_action( 'customize_save_after', array( $this, 'customize_save' ) );
	}

	/**
	 * Print the link tag to let the browser know where the favicon is located.
	 *
	 * @since 1.1.0
	 */
	public function print_favicon_tag() {
		$url = $this->get_favicon( 'url' );

		if ( ! empty( $url ) ) {
			printf( '<link rel="shortcut icon" href="%s">', esc_url( $url ) );
		}
	}

	/**
	 * Register Customizer settings.
	 *
	 * @since 1.1.0
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer manager instance.
	 */
	public function customize_register( $wp_customize ) {
		$wp_customize->add_setting( 'atmedley[favicon_id]', array(
			'default'           => '',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'absint',
			'type'              => 'option',
		) );

		$wp_customize->add_setting( 'atmedley[favicon_url]', array(
			'default'           => '',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'esc_url_raw',
			'type'              => 'option',
		) );

		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'atmedley_favicon', array(
			'label'       => __( 'Favicon', 'audiotheme-medley' ),
			'description' => __( 'For best results, upload a 32x32 transparent PNG image.', 'audiotheme-medley' ),
			'section'     => 'title_tagline',
			'settings'    => 'atmedley[favicon_url]',
			'priority'    => 20,
		) ) );
	}

	/**
	 * Enqueue assets for handling custom controls.
	 *
	 * Synchronizes an image control with a setting.
	 *
	 * @since 1.1.0
	 */
	public function enqueue_customizer_controls_assets() {
		wp_enqueue_script(
			'atmedley-customize-controls',
			ATMEDLEY_URL . 'assets/js/customize-controls.js',
			array( 'customize-controls' ),
			'1.0.0',
			true
		);
	}

	/**
	 * Update the favicon data and determine if a new one should be generated.
	 *
	 * @since 1.1.0
	 *
	 * @link https://github.com/chrisbliss18/php-ico
	 * @link https://github.com/audreyr/favicon-cheat-sheet
	 *
	 * @param WP_Customizer_Manager Customizer manager instance.
	 */
	public function customize_save( $wp_customize ) {
		$favicon    = $this->get_favicon();
		$favicon_id = $wp_customize->get_setting( 'atmedley[favicon_id]' )->value();

		// Delete the old favicon.
		if ( $favicon['source_id'] != $favicon_id && ! empty( $favicon['path'] ) && file_exists( $favicon['path'] ) ) {
			unlink( $favicon['path'] );
		}

		// Generate and save the new favicon.
		if (
			( $favicon['source_id'] != $favicon_id && ! empty( $favicon_id ) ) ||
			( ! empty( $favicon['path'] ) && ! file_exists( $favicon['path'] ) )
		) {
			$data = $this->generate_favicon( $favicon_id );
			$data['source_id'] = empty( $data['path'] ) ? 0 : $favicon_id;
			update_option( 'atmedley_favicon', $data );
		}
	}

	/**
	 * Retreive data about the favicon.
	 *
	 * @since 1.1.0
	 *
	 * @param string $key Optional. Property to retrieve. Defaults to an array of all properties.
	 * @return mixed An array of data or an individual value.
	 */
	protected function get_favicon( $key = '' ) {
		$data = wp_parse_args( get_option( 'atmedley_favicon', array() ), array(
			'path'      => '',
			'url'       => '',
			'source_id' => 0,
		) );

		return empty( $key ) ? $data : $data[ $key ];
	}

	/**
	 * Generate a favicon from an attachment.
	 *
	 * @since 1.1.0
	 *
	 * @param int $attachment_id Attachment ID.
	 * @return array An array with the path and URL of the generated favicon.
	 */
	protected function generate_favicon( $attachment_id ) {
		require_once( ATMEDLEY_DIR . 'includes/vendor/class-php-ico.php' );

		$upload_dir  = wp_upload_dir();
		$source      = get_attached_file( $attachment_id );
		$filename    = wp_unique_filename( $upload_dir['path'], 'favicon.ico' );
		$destination = $upload_dir['path'] . '/' . $filename;

		$ico = new PHP_ICO( $source, array(
			array( 16, 16 ),
			array( 32, 32 ),
		) );

		$data = array(
			'path' => '',
			'url'  => ''
		);

		if ( $ico->save_ico( $destination ) ) {
			$data = array(
				'path' => $destination,
				'url'  => $upload_dir['url'] . '/' . $filename,
			);
		}

		return $data;
	}
}
