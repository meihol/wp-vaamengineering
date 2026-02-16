var FusionPageBuilder = FusionPageBuilder || {};

( function() {

	jQuery( document ).ready( function() {
		FusionPageBuilder.fusion_woo_order_additional_info = FusionPageBuilder.ElementView.extend( {

			/**
			 * Modify template attributes.
			 *
			 * @since 3.5
			 * @return {Object}
			 */
			filterTemplateAtts: function( values ) {
				var attributes = {},
					atts;

				// Create attribute objects.
				attributes.label       = window.fusionAllElements[ this.model.get( 'element_type' ) ].name;
				attributes.icon        = window.fusionAllElements[ this.model.get( 'element_type' ) ].icon;

				atts = {
					'class': 'fusion-builder-placeholder-preview',
					'style': ''
				};
				attributes.elementAttr = _.fusionAnimations( values.values, atts );

				return attributes;
			}

		} );
	} );
}( jQuery ) );
