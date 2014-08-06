<?php
/**
 * AudioTheme Medley
 *
 * @package AudioTheme\Medley
 * @author Brady Vercher
 * @copyright Copyright (c) 2014, AudioTheme, LLC
 * @license GPL-2.0+
 *
 * @todo Add a method for automatic updates. GitHub Updater will work for now: https://github.com/afragen/github-updater
 *
 * @wordpress-plugin
 * Plugin Name: AudioTheme Medley
 * Plugin URI: http://audiotheme.com/
 * Description: Assorted enhancements for AudioTheme themes.
 * Version: 1.0.0
 * Author: AudioTheme
 * Author URI: http://audiotheme.com/
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: audiotheme-medley
 * Domain Path: /languages
 */

if ( ! defined( 'ATMEDLEY_DIR' ) ) {
	/**
	 * Path directory path.
	 *
	 * @since 1.0.0
	 * @type string ATMEDLEY_DIR
	 */
	define( 'ATMEDLEY_DIR', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'ATMEDLEY_URL' ) ) {
	/**
	 * URL to the plugin's root directory.
	 *
	 * Includes trailing slash.
	 *
	 * @since 1.0.0
	 * @type string ATMEDLEY_URL
	 */
	define( 'ATMEDLEY_URL', plugin_dir_url( __FILE__ ) );
}

require( ATMEDLEY_DIR . '/includes/class-atmedley-credits.php' );
require( ATMEDLEY_DIR . '/includes/class-atmedley-favicon.php' );
require( ATMEDLEY_DIR . '/includes/functions.php' );

$atmedley_credits = new ATMedley_Credits();
$atmedley_credits->load();

$atmedley_favicon = new ATMedley_Favicon();
$atmedley_favicon->load();
