<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; /* Exit if accessed directly */
}

add_shortcode( 'ufg', 'ufg_shortcode_callback' );
function ufg_shortcode_callback( $atts ) {
	ob_start();
	/* print filters */
	include_once 'filters.php';
	include_once 'gallery.php';

	/* defaults */
	$ufg_filters = array();
	$ufg_gallery = array();

	/* get gallery id */
	if ( isset( $atts['id'] ) ) {
		$ufg_gallery_id = $atts['id'];
		$ufg_filters    = get_option( 'ufg_filters_' . $ufg_gallery_id );
		$ufg_gallery    = get_option( 'ufg_gallery_' . $ufg_gallery_id );
		$ufg_setting    = get_option( 'ufg_settings_' . $ufg_gallery_id );

		 /* loading saved settings and shortcode supported settings */
		include 'setting.php';

		/* load required resource CSS and JS */
		wp_enqueue_script( 'jquery' );
		wp_enqueue_style( 'ufg-bootstrap-frontend-css' );
		wp_enqueue_style( 'ufg-fontawesome-css' );
		/* lightbox JS and CSS */
		wp_enqueue_style( 'ufg-lightbox-css' );
		wp_enqueue_script( 'ufg-lightbox-js' );
		?>
		<!-- printing filters start-->
		<?php if ( $ufg_show_filters ) { ?>
		<div class="row">
			<div class="my-1 ufg-filters">
				<?php
				$ufg_fitter_results  = ufg_filters( $ufg_gallery_id, $ufg_filters, $ufg_gallery );
				$ufg_filters_allowed = array(
					'div'    => array(
						'class' => array(),
						'id'    => array(),
					),
					'button' => array(
						'id'      => array(),
						'class'   => array(),
						'value'   => array(),
						'onclick' => array(),
					),
				);
				echo wp_kses( $ufg_fitter_results, $ufg_filters_allowed );
				?>
			</div>
		</div>
		<?php } ?>
		<!-- printing filters end-->

		<input id="ufg_current_clicked_filter_id" name="ufg_current_clicked_filter_id" value="" class="d-none" placeholder="Current Filter">
		<input id="ufg_current_clicked_filter_level" name="ufg_current_clicked_filter_level" value="" class="d-none" placeholder="Current Level">
		<input id="ufg_last_clicked_filter_id" name="ufg_last_clicked_filter_id" value="" class="d-none" placeholder="Last Filter">
		<input id="ufg_last_clicked_filter_level" name="ufg_last_clicked_filter_level" value="" class="d-none" placeholder="Last Level">
		<input id="ufg_current_clicked_parent_filter_id" name="ufg_current_clicked_parent_filter_id" value="" class="d-none" placeholder="Current Parent Filter">
		<input id="ufg_last_clicked_filter_parent_id" name="ufg_last_clicked_filter_parent_id" value="" class="d-none" placeholder="Last Parent Filter">
		
		<!-- printing gallery start-->
		<div class="row ufg-gallery"><?php echo esc_html( ufg_gallery( $ufg_gallery_id, $ufg_gallery ) ); ?></div>
		<!-- printing gallery end-->
		
		<style>
		.ufg-all-filter-button {
			color: <?php echo esc_html( $ufg_all_button_color ); ?> !important;
			background-color: <?php echo esc_html( $ufg_all_button_bg_color ); ?> !important;
			border-color: <?php echo esc_html( $ufg_all_button_bg_color ); ?> !important;
		}
		.ufg-parent-filter-button {
			color: <?php echo esc_html( $ufg_parent_button_color ); ?> !important;
			background-color: <?php echo esc_html( $ufg_parent_button_bg_color ); ?> !important;
			border-color: <?php echo esc_html( $ufg_parent_button_bg_color ); ?> !important;
		}
		.ufg-level-one-button {
			color: <?php echo esc_html( $ufg_l1_button_color ); ?> !important;
			background-color: <?php echo esc_html( $ufg_l1_button_bg_color ); ?> !important;
			border-color: <?php echo esc_html( $ufg_l1_button_bg_color ); ?> !important;
		}
		.ufg-thumbnail-border {
			<?php if ( $ufg_thumbnail_border ) { ?>
			border: <?php echo esc_html( $ufg_thumbnail_border_thickness ); ?>px solid <?php echo esc_html( $ufg_thumbnail_border_color ); ?> !important;
			<?php } ?>
			border-radius: 0.25rem !important;
		}
		.ufg-image-title {
			font-size: <?php echo esc_html( $ufg_image_title_font_size ); ?>px !important;
			color: <?php echo esc_html( $ufg_image_title_color ); ?> !important;
			background-color: <?php echo esc_html( $ufg_image_title_bg_color ); ?> !important;
		}
		</style>
		<?php
	} else {
		echo esc_html( __( 'Error! invalid shortcode.', 'filter-gallery' ) );
	}
	return ob_get_clean();
}