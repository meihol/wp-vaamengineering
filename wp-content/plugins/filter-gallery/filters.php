<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; /* Exit if accessed directly */
}

/* this file print filters - filters callback */
if ( ! function_exists( 'ufg_filters' ) ) {
	function ufg_filters( $ufg_gallery_id, $ufg_filters, $ufg_gallery ) {
		if ( is_array( $ufg_filters ) && $filters_count = count( $ufg_filters ) ) {

			$ufg_filters_output = '';

			/* echo $filters_count; */
			/* load settings */
			$ufg_setting = get_option( 'ufg_settings_' . $ufg_gallery_id );
			include 'setting.php';

			/* filters image count */
			$ufg_total_image_count = 0;
			if ( is_array( $ufg_gallery['ufg-attachment-id'] ) ) {
				$ufg_total_image_count = count( $ufg_gallery['ufg-attachment-id'] );
			}

			/* print parent filters */
			$ufg_filters_output      = ( "<div class='ufg-parent-filters'>" );
				$ufg_filters_output .= ( "<div class='col-md-12 my-2'>" );

			if ( $ufg_show_all_button ) {
				$ufg_all_icon_html   = '';
				$ufg_filters_output .= ( "<button id='1evel1-all' class='ufg-all-filter-button ufg-parent-filters ufg-all-filter btn btn-sm btn-danger all mb-3 mr-2' onclick='return filter(this.id, this.value)' value='all'>$ufg_all_icon_html $ufg_all_button_text ($ufg_total_image_count)</button>" );
			}

			for ( $i = 0; $i <= 4; $i++ ) {
				if ( isset( $ufg_filters[ $i ]->text ) ) {
					$parent_filter_name = $ufg_filters[ $i ]->text;
					/* filter icon */
					if ( isset( $ufg_filters[ $i ]->icon ) ) {
						$parent_filter_icon = $ufg_filters[ $i ]->icon;
					}
					if ( $parent_filter_icon != '' ) {
						$parent_icon_html = "<i class='$parent_filter_icon'></i>";
					} else {
						$parent_icon_html = '';
					}
					$parent_icon_html    = '';
					$parent_filter_class = str_replace( ' ', '-', strtolower( $ufg_filters[ $i ]->title ) );
					$ufg_filters_output .= ( "<button id='1evel1-$parent_filter_class' class='ufg-parent-filter-button ufg-parent-filters btn btn-sm btn-primary  mb-3 mr-2 $parent_filter_class' onclick='return filter(this.id, this.value)' value='$parent_filter_class'>$parent_icon_html $parent_filter_name</button>" );
				}
			}
				$ufg_filters_output .= ( '</div>' );
			$ufg_filters_output     .= ( '</div>' );
			/* print parent filters end */
			return $ufg_filters_output;
		}
	}
}