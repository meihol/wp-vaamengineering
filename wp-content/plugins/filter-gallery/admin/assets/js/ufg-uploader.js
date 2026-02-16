/**
 * @ufg uploader v1.0.0 - MIT License
 */
jQuery(
	function(jQuery) {
		var file_frame,
		UFG = {
			ul: '',
			init: function() {
				this.ul = jQuery( '#ufg-gallery' );
				this.ul.sortable(
					{
						placeholder: '',
						revert: true,
					}
				);

				/**
				 * Add Image Callback Function
				 */
				jQuery( '#ufg-upload-images' ).on(
					'click',
					function(event) {
						event.preventDefault();
						if (file_frame) {
							file_frame.open();
							return;
						}
						file_frame = wp.media.frames.file_frame = wp.media(
							{
								multiple: true
							}
						);

						file_frame.on(
							'select',
							function() {
								var images = file_frame.state().get( 'selection' ).toJSON(),
									length = images.length;
								for (var i = 0; i < length; i++) {
									UFG.get_thumbnail( images[i]['id'] );
								}
							}
						);
						file_frame.open();
					}
				);

				/**
				 * Remove Image Callback Function
				 */
				/* this.ul.on('click', '#ufg-remove-image', function() {
				if (confirm('Are sure to delete this images?')) {
					jQuery(this).parent().fadeOut(700, function() {
						jQuery(this).remove();
					});
				}
				return false;
				}); */

				/**
				 * Remove All Images Callback Function
				 */
				jQuery( '#ufg-remove-all-image' ).on(
					'click',
					function() {
						if (confirm( 'Are sure to delete all images?' )) {
							UFG.ul.empty();
						}
						return false;
					}
				);

			},
			get_thumbnail: function(id, cb) {
				cb                 = cb || function() {
				};
				var ufg_gallery_id = jQuery( "#ufg-gallery-id" ).val();
				var ufg_li_generate_nonce = jQuery( "#ufg_li_generate_nonce" ).val();
				//console.log( ufg_gallery_id );
				//console.log( ufg_li_generate_nonce );
				var data = {
					action: 'ufg_image_id',
					attachment_id: id,
					ufg_gallery_id: ufg_gallery_id,
					nonce: ufg_li_generate_nonce,
				};
				jQuery.post(
					ajaxurl,
					data,
					function(response) {
						UFG.ul.append( response );
						cb();
						// BindMultiSelect();
					}
				);
			}
		};
		UFG.init();
	}
);
