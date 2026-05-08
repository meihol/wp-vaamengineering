<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; /* Exit if accessed directly */
}

/* this file print gallery gallery callback */
function ufg_gallery( $ufg_gallery_id, $ufg_gallery ) {
	if ( is_array( $ufg_gallery ) && $gallery_image_count = count( $ufg_gallery ) ) {

		/* load settings */
		$ufg_setting = get_option( 'ufg_settings_' . $ufg_gallery_id );
		/* print_r($ufg_setting); */
		include 'setting.php';

		/* image sorting */
		if ( $ufg_image_sorting == 1 ) {
			ksort( $ufg_gallery['ufg-title'] ); /* ascending image id */
		}
		if ( $ufg_image_sorting == 2 ) {
			krsort( $ufg_gallery['ufg-title'] ); /* descending image id */
		}

		/* keys: ufg-attachment-id / ufg-title / ufg-alt / ufg-description / ufg-url / ufg-image-filters */
		/* defaults */
		$ufg_title = $ufg_alt = $ufg_description = $ufg_url = '';
		foreach ( $ufg_gallery['ufg-title'] as $key => $value ) {
			/* load values */
			$attachment_id = $key;
			$ufg_title     = get_the_title( $attachment_id );
			$ufg_alt       = get_post_meta( $attachment_id, '_wp_attachment_image_alt', true );
			/* wp_get_attachment_image_src ( int $attachment_id, string|array $size = 'thumbnail', bool $icon = false ) */
			/* thumb, thumbnail, medium, large, post-thumbnail */
			$medium     = wp_get_attachment_image_src( $attachment_id, $ufg_thumbnail_image_size, true ); /* attachment medium URL */
			$full       = wp_get_attachment_image_src( $attachment_id, 'full', true ); /* attachment medium URL */
			$attachment = get_post( $attachment_id );

			if ( isset( $ufg_gallery['ufg-image-filters'][ $attachment_id ] ) && count( $ufg_gallery['ufg-image-filters'][ $attachment_id ] ) ) {
				$filters = $ufg_gallery['ufg-image-filters'][ $attachment_id ];
			} else {
				$filters = array();
			}
			?>
			<div id="ufg-thumbnail" class="col-<?php echo esc_attr( $ufg_columns_mobile_portrait ); ?> col-sm-<?php echo esc_attr( $ufg_columns_mobile_landscape ); ?> col-md-<?php echo esc_attr( $ufg_columns_tab ); ?> col-lg-<?php echo esc_attr( $ufg_columns_desktop ); ?> mb-4 <?php echo esc_attr( implode( ' ', $filters )); ?>">
				<div class="ufg-thumbnail-border">
					<?php if ( $ufg_lightbox ) { ?>
					<a href="<?php echo esc_url( $full[0] ); ?>" class="ufg-lightbox <?php echo esc_attr( implode( ' ', $filters ) ); ?>" data-title="<?php if ( $ufg_lightbox_title ) { echo esc_attr( $ufg_title ); } ?>" data-lightbox="ufg-lightbox" data-alt="<?php echo esc_attr( $ufg_alt ); ?>">
						<img src="<?php echo esc_url( $medium[0] ); ?>" class="ufg-thumbnail-img img-fluid mx-auto d-block" alt="<?php echo esc_attr( $ufg_alt ); ?>">
					</a>
					<?php } else { ?>
						<img src="<?php echo esc_url( $medium[0] ); ?>" class="ufg-thumbnail-img img-fluid mx-auto d-block" alt="<?php echo esc_attr( $ufg_alt ); ?>">
					<?php } ?>
					<div class="ufg-image-content">
						<?php if ( $ufg_image_title ) { ?>
						<p class="ufg-image-title pr-2 pl-2"><?php echo esc_html( $ufg_title ); ?></p>
						<?php } ?>
					</div>
				</div>
			</div>
			<?php
		}
		?>
		<script>
		jQuery('button.ufg-level-one-button').fadeOut(); //hide all level 1 buttons
		
		function filter(id, value) {
			//console.log(id); */
			//console.log(value); */
			var ufg_btn_text = "";
			var ufg_btn_text2 = "";
			var ufg_current_clicked_filter_id = "";
			var ufg_current_clicked_filter_level = "";
			var ufg_last_clicked_filter_id = "";
			var ufg_last_clicked_filter_level = "";
			var ufg_last_clicked_filter_parent_id = "";
			
			ufg_current_clicked_filter_id = jQuery("#ufg_current_clicked_filter_id").val(); /* get last clicked filter id */
			ufg_current_clicked_filter_level = jQuery("#ufg_current_clicked_filter_level").val(); /* set current clicked filter level*/
			ufg_last_clicked_filter_id = jQuery("#ufg_last_clicked_filter_id").val(); /* get last clicked filter id*/
			ufg_last_clicked_filter_level = jQuery("#ufg_last_clicked_filter_level").val(); /* get last clicked filter level*/
			
			ufg_current_clicked_parent_filter_id = jQuery("#ufg_current_clicked_parent_filter_id").val(); /* get current clicked parent filter id*/
			ufg_last_clicked_filter_parent_id = jQuery("#ufg_last_clicked_filter_parent_id").val(); /* get last clicked parent filter id*/
			
			/* set current - initials case */
			if(ufg_current_clicked_filter_id == "") {
				//get current clicked filter level */
				//console.log(id.match("1evel1")); */
				if(id.match("1evel1")){ ufg_current_clicked_filter_level = "1evel1"; }
				//console.log(ufg_current_clicked_filter_level); */
				if(id.match("level2")){ ufg_current_clicked_filter_level = "level2"; }
				//console.log(ufg_current_clicked_filter_level); */
				if(id.match("level3")){ ufg_current_clicked_filter_level = "level3"; }
				//console.log(ufg_current_clicked_filter_level); */
				
				/* set filter id and get */
				jQuery("#ufg_current_clicked_filter_id").val(id);
				jQuery("#ufg_current_clicked_filter_level").val(ufg_current_clicked_filter_level);
				
				jQuery("#ufg_last_clicked_filter_id").val(id);
				jQuery("#ufg_last_clicked_filter_level").val(ufg_current_clicked_filter_level);
				
				console.log(ufg_last_clicked_filter_parent_id);
				if(ufg_current_clicked_filter_level == "1evel1") {
					jQuery("#ufg_last_clicked_filter_parent_id").val(id);
				}
				
				/* set current last clicked parent filter id */
				jQuery("#ufg_current_clicked_parent_filter_id").val(id);
				jQuery("#ufg_last_clicked_filter_parent_id").val(id);
				
			} else {
				
				/* transfer current filter to last filter (transfer before getting filter level) */
				jQuery("#ufg_last_clicked_filter_id").val(ufg_current_clicked_filter_id);
				jQuery("#ufg_last_clicked_filter_level").val(ufg_current_clicked_filter_level);
				
				//get current clicked filter level */
				//console.log(id.match("1evel1")); */
				if(id.match("1evel1")){ ufg_current_clicked_filter_level = "1evel1"; }
				//console.log(ufg_current_clicked_filter_level); */
				if(id.match("level2")){ ufg_current_clicked_filter_level = "level2"; }
				//console.log(ufg_current_clicked_filter_level); */
				if(id.match("level3")){ ufg_current_clicked_filter_level = "level3"; }
				//console.log(ufg_current_clicked_filter_level); */
				
				/* set current filters */
				jQuery("#ufg_current_clicked_filter_id").val(id);
				jQuery("#ufg_current_clicked_filter_level").val(ufg_current_clicked_filter_level);
				
				ufg_last_clicked_filter_id = jQuery("#ufg_last_clicked_filter_id").val(); /* get last clicked filter id */
				ufg_last_clicked_filter_level = jQuery("#ufg_last_clicked_filter_level").val(); /* get last clicked filter level */
				
				/* remove check icon on last clicked filter - if same level2 filter button clicked */
				if(ufg_current_clicked_filter_level == "level2" && ufg_last_clicked_filter_level == "level2"){
					/* remove check icon from last clicked filter button */
					ufg_btn_text = jQuery("#" + ufg_last_clicked_filter_id).html(); /* get html value */
					//console.log(ufg_btn_text);
					ufg_btn_text = ufg_btn_text.replace(' <i class="fas fa-check"></i>', ''); /* remove icon */
					//console.log(ufg_btn_text);
					jQuery("#" + ufg_last_clicked_filter_id).html(ufg_btn_text); /* set remove icon */
				}
				
				/* when transferring filter from level2 to level1 */
				if(ufg_current_clicked_filter_level === "1evel1") {
					/* transfer last clicked parent filter id to current  */
					jQuery("#ufg_current_clicked_parent_filter_id").val(id);
					jQuery("#ufg_last_clicked_parent_filter_id").val(ufg_current_clicked_parent_filter_id);
					
					/* remove check icon from last clicked filter button */
					ufg_btn_text = jQuery("#" + ufg_current_clicked_parent_filter_id).html(); /* get html value */
					//console.log(ufg_btn_text);
					ufg_btn_text = ufg_btn_text.replace(' <i class="fas fa-check"></i>', ''); /* remove icon */
					//console.log(ufg_btn_text);
					jQuery("#" + ufg_current_clicked_parent_filter_id).html(ufg_btn_text); /* set remove icon */
					
					/* remove check icon from last clicked first filter button */
					ufg_btn_text = jQuery("#" + ufg_last_clicked_filter_id).html(); /* get html value */
					//console.log(ufg_btn_text);
					ufg_btn_text = ufg_btn_text.replace(' <i class="fas fa-check"></i>', ''); /* remove icon */
					//console.log(ufg_btn_text);
					jQuery("#" + ufg_last_clicked_filter_id).html(ufg_btn_text); /* set remove icon */
				}
			}
			
			/* add check icon on current clicked filter */
			ufg_btn_text = jQuery("#" + id).html(); /* get html value */
			//console.log(ufg_btn_text);
			jQuery("#" + id).html(ufg_btn_text + ' <i class="fas fa-check"></i>');
			
			/* hide all level 2 button */
			if(ufg_current_clicked_filter_level != "level2"){ /* display only level on filter accordingly parent filters clicked */
				jQuery('button.ufg-level-one-button').fadeOut(); //hide all level 1 buttons */
			}
			
			//filtering */
			if(value == "all") {
				/* show all filters */
				jQuery('button.ufg-parent-filters ').fadeIn( "slow" ); //display all filters */
				
				/* show all images */
				jQuery('div#ufg-thumbnail').fadeIn( "slow" ); //display all images */
				
				/* lightbox - remove data attribute and dynamic add lightbox data-lightbox to anchor tag */
				<?php if ( $ufg_lightbox ) { ?>
				jQuery('.ufg-lightbox').removeData();
				jQuery('.ufg-lightbox').attr('data-lightbox', 'ufg-lightbox'); /* add data-lightbox for all images cycle in lightbox */
				<?php } ?>
			} else {
				/* remove data-lightbox attribute from all ufg-thumbnail */
				<?php if ( $ufg_lightbox ) { ?>
				jQuery('a.ufg-lightbox').removeAttr('data-lightbox');
				<?php } ?>
				
				/* show hide images */
				jQuery('div#ufg-thumbnail').fadeOut( "slow" ); //hide all visible images */
				jQuery('div.' + value).fadeIn( "slow" ); //display only clicked images //and filters button */
				jQuery('button.' + value).fadeIn( "slow" ); //display only clicked filters button and images */
				
				/* dynamically add lightbox data-filter classes accordingly parent and sub filter clicked */
				<?php if ( $ufg_lightbox ) { ?>
				var lighbox_class_name = "ufg-lightbox-" + value;
				jQuery('.' + value ).attr('data-lightbox', lighbox_class_name); /* add data filter for parent filters */
				<?php } ?>
			}
		}

		<?php if ( $ufg_lightbox ) { ?>
		jQuery(document).ready(function(){
			lightbox.option({
				'fadeDuration' : 600,
				'fitImagesInViewport' : true,
				'imageFadeDuration' : 600,
				'positionFromTop' : 50,
				'resizeDuration' : 700,
				'wrapAround': true,
			});
		});
		<?php } ?>
		</script>
		<?php
	}
}
?>