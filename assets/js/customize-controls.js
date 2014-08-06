(function( $, _, api, undefined ) {
	'use strict';

	function syncAttachmentId( controlId, settingId ) {
		api.control( controlId, function( control ) {
			var attachments = [],
				syncSetting = api( settingId );

			// Update the syncSetting when the control setting value is changed.
			control.setting.bind(function( value ) {
				var attachment;

				if ( value ) {
					// Look up the attachment ID in our local collection of uploaded images.
					attachment = _.findWhere( attachments, { url: value });
				}

				if ( ! _.isNull( attachment ) && ! _.isUndefined( attachment ) ) {
					syncSetting.set( attachment.id );
				} else {
					syncSetting.set( 0 );
				}
			});

			// Proxy the ImageControl success callback.
			control.uploader.success = function( attachment ) {
				var props = _.pick( attachment.toJSON(), 'id', 'url' );

				api.ImageControl.prototype.success.call( control, attachment );

				attachments.push( props );
				syncSetting.set( props.id );
			};
		});
	};

	jQuery( document ).ready(function() {

		syncAttachmentId( 'atmedley_favicon', 'atmedley[favicon_id]' );

	});

})( jQuery, _, wp.customize );
