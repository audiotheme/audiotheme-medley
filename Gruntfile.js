/*global exports:false, module:false, require:false */

module.exports = function( grunt ) {
	'use strict';

	grunt.loadNpmTasks( 'grunt-wp-i18n' );

	grunt.initConfig({

		makepot: {
			plugin: {
				options: {
					mainFile: 'audiotheme-medley.php',
					type: 'wp-plugin'
				}
			}
		}

	});

};
