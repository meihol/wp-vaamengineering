<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; /* Exit if accessed directly */
}

/**
 * Plugin Name:       Responsive Filterable Gallery
 * Plugin URI:        https://wpfrank.com/
 * Description:       Build a responsive filter gallery for your portfolio. Organize images with tags in a stunning grid or masonry layout easily
 * Version:           0.2.3
 * Requires at least: 4.0
 * Requires PHP:      5.0
 * Author:            farazfrank
 * Author URI:        https://profiles.wordpress.org/farazfrank/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       filter-gallery
 * Domain Path:       /languages

Filter Gallery is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

Filter Gallery is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Filter Gallery. If not, see https://wpfrank.com/.
*/

// custom image size
add_image_size( 'ufg_200_200', 200, 200, true );
add_image_size( 'ufg_300_300', 300, 300, true );
add_image_size( 'ufg_400_400', 400, 400, true );

/* FG activation */
function ufg_activation() {
	/* update current plugin version */
	if ( is_admin() ) {
		if ( ! function_exists( 'get_plugin_data' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}
		$ufg_plugin_data = get_plugin_data( __FILE__ );
		if ( isset( $ufg_plugin_data['Version'] ) ) {
			$ufg_plugin_version = $ufg_plugin_data['Version'];
			update_option( 'ufg_current_version', $ufg_plugin_version );
		}
	}
}
register_activation_hook( __FILE__, 'ufg_activation' );

// FG deactivation
function ufg_deactivation(){
	// update last active plugin version
	$ufg_last_version = get_option('ufg_current_version');
	if($ufg_last_version !== ""){
		update_option('ufg_last_version', $ufg_last_version);
	}
}
register_deactivation_hook( __FILE__, 'ufg_deactivation' );

// FG uninstall
function ufg_uninstall(){
}
register_uninstall_hook(__FILE__, 'ufg_uninstall');

// load translation
function ufg_load_translation() {
	load_plugin_textdomain( 'filter-gallery', false, dirname( plugin_basename(__FILE__) ) .'/languages' );
}
add_action( 'plugins_loaded', 'ufg_load_translation');

// FG menu
function ufg_menu_page() {
	// add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
	add_menu_page(
		__( 'Filter Gallery', 'filter-gallery' ),
		'Filter Gallery',
		'manage_options',
		'filter-gallery',
		'ufg_main',
		'dashicons-format-gallery',
		65
	);
	
	//add_submenu_page( string $parent_slug, string $page_title, string $menu_title, string $capability, string $menu_slug, callable $function = '', int $position )
	add_submenu_page( 'filter-gallery', 'Mange Gallery', 'Mange Gallery', 'manage_options', 'ufg-manage-gallery', 'ufg_manage_gallery' );
}
add_action( 'admin_menu', 'ufg_menu_page' );

// FG main page body
function ufg_main(){
	require 'admin/galleries.php';
}

// FG sub menu filters page body
function ufg_manage_gallery(){
	require 'admin/manage-gallery.php';
}

// create next gallery id
function ufg_get_next_id(){
	global $wpdb;
	$ufg_gallery_key = "ufg_gallery_";
	// reference : https://wordpress.stackexchange.com/questions/8825/how-do-you-properly-prepare-a-like-sql-statement
	// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Query for unique ID generation cannot be cached
	$ufg_gallery_count_res = $wpdb->get_row(
		$wpdb->prepare("SELECT option_name FROM `$wpdb->options` WHERE `option_name` LIKE %s ORDER BY option_id DESC LIMIT 1", '%'.$ufg_gallery_key.'%'), ARRAY_N
	);
	
	if($wpdb->num_rows) {
		$ufg_gallery_last_key = $ufg_gallery_count_res[0];
		$ufg_underscore_pos = strrpos($ufg_gallery_last_key, '_');
		$ufg_last_slider_id = (int) substr($ufg_gallery_last_key, ($ufg_underscore_pos + 1));
		return ($ufg_last_slider_id + 1);
	} else {
		return 1;
	}
}

// 1. save filters ajax
function ufg_gallery_filters_callback(){
	if ( current_user_can( 'manage_options' ) ) {
		if ( isset( $_POST['nonce'] ) && wp_verify_nonce( sanitize_text_field(wp_unslash($_POST['nonce'])), 'add-filters' ) ) {
			// save filters
			//print_r($_POST);
			$filters = array();
			
			$ufg_gallery_id = (isset($_POST['id'])) ? sanitize_text_field( wp_unslash ($_POST['id'] ) ) : '';
			$ufg_gallery_name = (isset($_POST['gallery_name'])) ? sanitize_text_field( wp_unslash ($_POST['gallery_name'] ) ) : '';
			$filters_data = isset($_POST['filters']) ? wp_kses_post(wp_unslash($_POST['filters'])) : '';
			$filters = json_decode($filters_data);
			
			/* echo "<pre>";
			print_r($filters);
			echo "</pre>"; */
			// making same name fitters unique start
			$counter = 1;
			if(is_array($filters) && $filters_count = count($filters)) {
				
				//unset the blank filters
				foreach($filters as $filters_key => $filters_value ){
					$parent_filters = sanitize_text_field($filters[$filters_key]->title);
					$parent_filters_text = sanitize_text_field($filters[$filters_key]->text); // this will be unique all time
					if(strlen($parent_filters)){
						// do nothing
					} else {
						unset($filters[$filters_key]);
					}
				}
				// array_values will recreate all filter index value in as sanding order after unsetting blank filters
				$filters = array_values($filters);
				
				//make unique filters
				foreach($filters as $filters_key => $filters_value ){
					$parent_filters = sanitize_text_field($filters[$filters_key]->title);
					$parent_filters_text = sanitize_text_field($filters[$filters_key]->text); // this will be unique all time
					
					// adding index value at the end of text to make it unique and same name filter can be possible
					$filters[$filters_key]->title = $parent_filters_text."-".$filters_key;
				}
			}
			/* echo "<pre>";
			print_r($filters);
			echo "</pre>"; */
			// making same name fitters unique end
			update_option("ufg_filters_".$ufg_gallery_id, $filters);
			
			$ufg_details = array('ufg_gallery_id' => $ufg_gallery_id, 'gallery_name' => $ufg_gallery_name);
			update_option("ufg_details_".$ufg_gallery_id, $ufg_details);
		} else {
			die;
		}
		die;
	}
}
add_action( 'wp_ajax_ufg_gallery_filters', 'ufg_gallery_filters_callback' );

// 2. add images to the gallery
function ufg_li_generate_ajax_callback() {
	if ( isset( $_POST['nonce'] ) && wp_verify_nonce( sanitize_text_field(wp_unslash($_POST['nonce'])), 'ufg-li-generate' ) ) {
		if ( isset($_POST['attachment_id']) && isset($_POST['ufg_gallery_id']) ) {
			wp_enqueue_script( 'ufg-uploader-js', plugins_url( 'assets/js/ufg-uploader.js', __FILE__ ), array('jquery'), '1.10.0', true );

			//defaults
			$ufg_title = $ufg_alt = $ufg_description = $ufg_url = "";
			//load values
			$ufg_attachment_id = sanitize_text_field(wp_unslash($_POST['attachment_id']));
			$ufg_title = get_the_title($ufg_attachment_id);
			$ufg_alt = get_post_meta($ufg_attachment_id, '_wp_attachment_image_alt', TRUE);
			//wp_get_attachment_image_src ( int $ufg_attachment_id, string|array $size = 'thumbnail', bool $icon = false )
			//thumb, thumbnail, medium, large, post-thumbnail
			$medium = wp_get_attachment_image_src($ufg_attachment_id, 'medium', true); // attachment medium URL
			$attachment = get_post( $ufg_attachment_id );
			$ufg_description = $attachment->post_content; // attachment description
			//get saved filters
			$ufg_gallery_id = sanitize_text_field(wp_unslash($_POST['ufg_gallery_id']));
			$filters = get_option("ufg_filters_".$ufg_gallery_id);
			?>
			<script>
			jQuery(document).ready(function () {
				jQuery(function(jQuery) {
					jQuery('.ufg-image-filters').multiselect({
						buttonWidth: '100%',
						enableFiltering: true,
						nonSelectedText: "<?php esc_html_e( 'Select Filters', 'filter-gallery' ); ?>"
					});
				});
			});
			</script>
			<li class="col-md-2 border border-dark rounded-lg bg-light p-2 m-4 ufg-image-<?php echo esc_attr($ufg_attachment_id); ?>" data-position="<?php echo esc_attr($ufg_attachment_id); ?>">
				<div class="form-group">
					<input type="hidden" class="form-control ufg-attachment-id" id="ufg-attachment-id" name="ufg-attachment-id[<?php echo esc_attr($ufg_attachment_id); ?>]" value="<?php echo esc_attr($ufg_attachment_id); ?>">
					<img src="<?php echo esc_url($medium[0]); ?>" class="card-img-top" width="150px" height="150px">
					<span class="badge badge-primary">Image ID: <?php echo esc_attr($ufg_attachment_id); ?></span>
				</div>
				<div class="form-group">
					<input type="text" class="form-control ufg-title" id="ufg-title" name="ufg-title[<?php echo esc_attr($ufg_attachment_id); ?>]" value="<?php echo esc_attr($ufg_title); ?>" placeholder="<?php esc_html_e( 'Image Title', 'filter-gallery' ); ?>">
				</div>
				<div class="form-group">
					<input type="text" class="form-control ufg-alt" id="ufg-alt" name="ufg-alt[<?php echo esc_attr($ufg_attachment_id); ?>]" value="<?php echo esc_attr($ufg_alt); ?>" placeholder="<?php esc_html_e( 'Image Alternative Text', 'filter-gallery' ); ?>">
				</div>
				<div class="form-group">
					<?php
					$ufg_get_filter_list_results = ufg_get_filter_list($ufg_attachment_id, $filters, array());
					$ufg_get_filter_list_allowed = array(
						'select' => array( 'id' => array(), 'name' => array(), 'class' => array(), 'data-max' => array(), 'multiple' => array() ),
						'option' => array ( 'value' => array(), 'selected' => array()),
					);
					echo wp_kses($ufg_get_filter_list_results, $ufg_get_filter_list_allowed);
					?>
				</div>
				<div class="form-group text-center">
					<button type="button" id="ufg-remove-image" onclick="return removeImage('<?php echo esc_attr($ufg_attachment_id); ?>');" class="btn btn-sm btn-danger"><?php esc_html_e( 'Remove', 'filter-gallery' ); ?></button>
				</div>
			</li>
			<?php
			wp_die(); // this is required to terminate immediately and return a proper response
		}
	}
}
add_action( 'wp_ajax_ufg_image_id', 'ufg_li_generate_ajax_callback' );

// generate filter select list
function ufg_get_filter_list($ufg_attachment_id, $filters, $selected_filters){
	$ufg_filters_list = "";
	if(is_array($filters) && $filters_count = count($filters)) {
		$ufg_filters_list .= '<select id="ufg-image-filters" name="ufg-image-filters['.$ufg_attachment_id.'][]" class="ufg-image-filters" data-max="" multiple="multiple">';
		$filters_count_end = 4; // for free
		if($filters_count < $filters_count_end) $filters_count_end = $filters_count;
		foreach($filters as $filters_key => $filters_value){
			$text_zero = sanitize_text_field($filters[$filters_key]->text);
			$value_zero = str_replace(" ","-", strtolower($filters[$filters_key]->title));
			if(in_array($value_zero, $selected_filters) === TRUE) $selected = "selected=selected"; else $selected = "";
			if($text_zero != "") $ufg_filters_list .= "<option value='$value_zero' $selected>$text_zero</option>";
		}
		$ufg_filters_list .=  '</select>';
		return $ufg_filters_list;
	}
}

// 3. save gallery images
function ufg_save_gallery_callback(){
	if ( current_user_can( 'manage_options' ) ) {
		if ( isset( $_POST['nonce'] ) && wp_verify_nonce( sanitize_text_field(wp_unslash($_POST['nonce'])), 'save-gallery' ) ) {
			// defaults
			$ufg_gallery_id = (isset($_POST['id'])) ? sanitize_text_field( wp_unslash ($_POST['id'] ) ) : '';
			$ufg_image_id = $ufg_image_title = $ufg_image_alt = $ufg_image_filters = $ufg_gallery = [];
			
			// Parse and sanitize POST data
			if (isset($_POST['image_id'])) {
				// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Sanitized via map_deep after parse_str
				parse_str(urldecode_deep(wp_unslash($_POST['image_id'])), $ufg_image_id);
				$ufg_image_id = map_deep($ufg_image_id, 'sanitize_text_field');
			}
			if (isset($_POST['image_title'])) {
				// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Sanitized via map_deep after parse_str
				parse_str(urldecode_deep(wp_unslash($_POST['image_title'])), $ufg_image_title);
				$ufg_image_title = map_deep($ufg_image_title, 'sanitize_text_field');
			}
			if (isset($_POST['image_alt'])) {
				// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Sanitized via map_deep after parse_str
				parse_str(urldecode_deep(wp_unslash($_POST['image_alt'])), $ufg_image_alt);
				$ufg_image_alt = map_deep($ufg_image_alt, 'sanitize_text_field');
			}
			if (isset($_POST['image_filters'])) {
				// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Sanitized via map_deep after parse_str
				parse_str(urldecode_deep(wp_unslash($_POST['image_filters'])), $ufg_image_filters);
				$ufg_image_filters = map_deep($ufg_image_filters, 'sanitize_text_field');
			}
			
			/* echo "<pre>";
			print_r($ufg_image_id);
			print_r($ufg_image_title);
			print_r($ufg_image_alt);
			print_r($ufg_image_filters);
			echo "</pre>"; */
			
			// save gallery
			//update attachment meta - title, alt, description
			$i = 0;
			if(is_array($ufg_image_id['ufg-attachment-id'])){
				foreach($ufg_image_id['ufg-attachment-id'] as $ufg_id) {
					$ufg_title = $ufg_image_title['ufg-title'][$ufg_id];
					$ufg_alt = $ufg_image_alt['ufg-alt'][$ufg_id];
					$ufg_image_update = array(
						'ID'           => $ufg_id,
						'post_title'   => $ufg_title,
					);
					wp_update_post( $ufg_image_update );
					update_post_meta( $ufg_id, '_wp_attachment_image_alt', sanitize_text_field( $ufg_alt ) );
					$i++;
				}
			}
			
			$ufg_gallery = array_merge($ufg_image_id, $ufg_image_title, $ufg_image_alt, $ufg_image_filters);
			update_option("ufg_gallery_".$ufg_gallery_id, $ufg_gallery);
			die;
		} else {
			die;
		}
	}
}
add_action( 'wp_ajax_ufg_save_gallery', 'ufg_save_gallery_callback' );

// 4. load gallery images
function ufg_load_gallery_callback($ufg_gallery_id){
	if ( current_user_can( 'manage_options' ) ) {
		if ( isset( $_POST['nonce'] ) && wp_verify_nonce( sanitize_text_field(wp_unslash($_POST['nonce'])), 'load-gallery' ) ) {
			//get / create next gallery id
			if(isset($_POST['id'])){
				$ufg_gallery_id = (isset($_POST['id'])) ? sanitize_text_field( wp_unslash ($_POST['id'] ) ) : '';
				
				// load filters and gallery options
				$ufg_filters = get_option("ufg_filters_".$ufg_gallery_id);
				$ufg_gallery = get_option("ufg_gallery_".$ufg_gallery_id);
				
				/* echo "<pre>";
				print_r($ufg_gallery);
				echo "</pre>"; */
				
				//defaults
				$ufg_title = $ufg_alt = $ufg_description = $ufg_url = "";
				if(is_array($ufg_gallery['ufg-attachment-id'])){
					foreach($ufg_gallery['ufg-attachment-id'] as $key => $value){
						$ufg_attachment_id = $key;
						//load values
						$ufg_title = get_the_title($ufg_attachment_id);
						$ufg_alt = get_post_meta($ufg_attachment_id, '_wp_attachment_image_alt', TRUE);
						//wp_get_attachment_image_src ( int $ufg_attachment_id, string|array $size = 'thumbnail', bool $icon = false )
						//thumb, thumbnail, medium, large, post-thumbnail
						$medium = wp_get_attachment_image_src($ufg_attachment_id, 'medium', true); // attachment medium URL
						$attachment = get_post( $ufg_attachment_id );
						$ufg_description = $attachment->post_content; // attachment description
						//get saved filters
						$filters = get_option("ufg_filters_".$ufg_gallery_id);
						?>
						<li class="col-md-2 border border-dark rounded-lg bg-light p-2 m-4 ufg-image-<?php echo esc_attr($ufg_attachment_id); ?>" data-position="<?php echo esc_attr($ufg_attachment_id); ?>">
							<div class="form-group">
								<input type="hidden" class="form-control ufg-attachment-id" id="ufg-attachment-id" name="ufg-attachment-id[<?php echo esc_attr($ufg_attachment_id); ?>]" value="<?php echo esc_attr($ufg_attachment_id); ?>">
								<img src="<?php echo esc_url($medium[0]); ?>" class="card-img-top" width="150px" height="150px">
								<span class="badge badge-primary">Image ID: <?php echo esc_attr($ufg_attachment_id); ?></span>
							</div>
							<div class="form-group">
								<input type="text" class="form-control ufg-title" name="ufg-title[<?php echo esc_attr($ufg_attachment_id); ?>]" value="<?php echo esc_attr($ufg_title); ?>" placeholder="<?php esc_html_e( 'Image Title', 'filter-gallery' ); ?>">
							</div>
							<div class="form-group">
								<input type="text" class="form-control ufg-alt" name="ufg-alt[<?php echo esc_attr($ufg_attachment_id); ?>]" value="<?php echo esc_attr($ufg_alt); ?>" placeholder="<?php esc_html_e( 'Image Alternative Text', 'filter-gallery' ); ?>">
							</div>
							<div class="form-group">
								<?php
								if(isset($ufg_gallery['ufg-image-filters'][$key]))
									$selected_filters = $ufg_gallery['ufg-image-filters'][$key];
								else 
									$selected_filters = array();
								?>
								<?php 
								$ufg_get_filter_list_results = ufg_get_filter_list($ufg_attachment_id, $filters, $selected_filters);
								$ufg_get_filter_list_allowed = array(
									'select' => array( 'id' => array(), 'name' => array(), 'class' => array(), 'data-max' => array(), 'multiple' => array() ),
									'option' => array ( 'value' => array(), 'selected' => array()),
								);
								echo wp_kses($ufg_get_filter_list_results, $ufg_get_filter_list_allowed);
								?>
							</div>
							<div class="form-group text-center">
								<button type="button" id="ufg-remove-image" onclick="return removeImage('<?php echo esc_attr($ufg_attachment_id); ?>');" class="btn btn-sm btn-danger"><?php esc_html_e( 'Remove', 'filter-gallery' ); ?></button>
							</div>
						</li>
						<?php
					}
				}
				?>
				<script>
				jQuery(document).ready(function () {
					jQuery(function(jQuery) {
						jQuery('.ufg-image-filters').multiselect ({
							buttonWidth: '100%',
							enableFiltering: true,
							nonSelectedText: "<?php esc_html_e( 'Select Filters', 'filter-gallery' ); ?>",
						});
					});
				});
				</script>
				<?php
				die;
			}
		} else {
			die;
		}
	}
}
add_action( 'wp_ajax_ufg_load_gallery', 'ufg_load_gallery_callback' );


// 5. save gallery settings
function ufg_save_setting_callback(){
	if ( current_user_can( 'manage_options' ) ) {
		if ( isset( $_POST['nonce'] ) && wp_verify_nonce( sanitize_text_field(wp_unslash($_POST['nonce'])), 'save-setting' ) ) {
			$ufg_gallery_id = (isset($_POST['ufg_gallery_id'])) ? sanitize_text_field( wp_unslash ($_POST['ufg_gallery_id'] ) ) : '';
			$show_filters = (isset($_POST['show_filters'])) ? sanitize_text_field( wp_unslash ($_POST['show_filters'] ) ) : '';
			$show_all_button = (isset($_POST['show_all_button'])) ? sanitize_text_field( wp_unslash ($_POST['show_all_button'] ) ) : '';
			$all_button_text = (isset($_POST['all_button_text'])) ? sanitize_text_field( wp_unslash ($_POST['all_button_text'] ) ) : '';
			$all_button_color = (isset($_POST['all_button_color'])) ? sanitize_text_field( wp_unslash ($_POST['all_button_color'] ) ) : '';
			$all_button_bg_color = (isset($_POST['all_button_bg_color'])) ? sanitize_text_field( wp_unslash ($_POST['all_button_bg_color'] ) ) : '';
			$parent_button_color = (isset($_POST['parent_button_color'])) ? sanitize_text_field( wp_unslash ($_POST['parent_button_color'] ) ) : '';
			$parent_button_bg_color = (isset($_POST['parent_button_bg_color'])) ? sanitize_text_field( wp_unslash ($_POST['parent_button_bg_color'] ) ) : '';
			
			$columns_desktop = (isset($_POST['columns_desktop'])) ? sanitize_text_field( wp_unslash ($_POST['columns_desktop'] ) ) : '';
			$columns_tab = (isset($_POST['columns_tab'])) ? sanitize_text_field( wp_unslash ($_POST['columns_tab'] ) ) : '';
			$columns_mobile_landscape = (isset($_POST['columns_mobile_landscape'])) ? sanitize_text_field( wp_unslash ($_POST['columns_mobile_landscape'] ) ) : '';
			$columns_mobile_portrait = (isset($_POST['columns_mobile_portrait'])) ? sanitize_text_field( wp_unslash ($_POST['columns_mobile_portrait'] ) ) : '';
			$thumbnail_image_size = (isset($_POST['thumbnail_image_size'])) ? sanitize_text_field( wp_unslash ($_POST['thumbnail_image_size'] ) ) : '';
			$thumbnail_border = (isset($_POST['thumbnail_border'])) ? sanitize_text_field( wp_unslash ($_POST['thumbnail_border'] ) ) : '';
			$thumbnail_border_thickness = (isset($_POST['thumbnail_border_thickness'])) ? sanitize_text_field( wp_unslash ($_POST['thumbnail_border_thickness'] ) ) : '';
			$thumbnail_border_color = (isset($_POST['thumbnail_border_color'])) ? sanitize_text_field( wp_unslash ($_POST['thumbnail_border_color'] ) ) : '';
			$image_title = (isset($_POST['image_title'])) ? sanitize_text_field( wp_unslash ($_POST['image_title'] ) ) : '';
			$image_title_font_size = (isset($_POST['image_title_font_size'])) ? sanitize_text_field( wp_unslash ($_POST['image_title_font_size'] ) ) : '';
			$image_title_color = (isset($_POST['image_title_color'])) ? sanitize_text_field( wp_unslash ($_POST['image_title_color'] ) ) : '';
			$image_title_bg_color = (isset($_POST['image_title_bg_color'])) ? sanitize_text_field( wp_unslash ($_POST['image_title_bg_color'] ) ) : '';
			$image_sorting = (isset($_POST['image_sorting'])) ? sanitize_text_field( wp_unslash ($_POST['image_sorting'] ) ) : '';
			$custom_css = (isset($_POST['custom_css'])) ? sanitize_text_field( wp_unslash ($_POST['custom_css'] ) ) : '';
			
			$lightbox = (isset($_POST['lightbox'])) ? sanitize_text_field( wp_unslash ($_POST['lightbox'] ) ) : '';
			$lightbox_title = (isset($_POST['lightbox_title'])) ? sanitize_text_field( wp_unslash ($_POST['lightbox_title'] ) ) : '';
			$settings = array(
				//gallery details
				'ufg_gallery_id' => $ufg_gallery_id,
				
				//filters settings
				'show_filters' => $show_filters,
				'show_all_button' => $show_all_button,
				'all_button_text' => $all_button_text,
				'all_button_color' => $all_button_color,
				'all_button_bg_color' => $all_button_bg_color,
				'parent_button_color' => $parent_button_color,
				'parent_button_bg_color' => $parent_button_bg_color,
				
				//gallery settings
				'columns_desktop' => $columns_desktop,
				'columns_tab' => $columns_tab,
				'columns_mobile_landscape' => $columns_mobile_landscape,
				'columns_mobile_portrait' => $columns_mobile_portrait,
				'thumbnail_image_size' => $thumbnail_image_size,
				'thumbnail_border' => $thumbnail_border,
				'thumbnail_border_thickness' => $thumbnail_border_thickness,
				'thumbnail_border_color' => $thumbnail_border_color,
				'image_title' => $image_title,
				'image_title_font_size' => $image_title_font_size,
				'image_title_color' => $image_title_color,
				'image_title_bg_color' => $image_title_bg_color,
				'image_sorting' => $image_sorting,
				'custom_css' => $custom_css,

				//lightbox settings
				'lightbox' => $lightbox,
				'lightbox_title' => $lightbox_title,
			);
			update_option("ufg_settings_".$ufg_gallery_id, $settings);
			die;
		} else {
			die;
		}
	}
}
add_action( 'wp_ajax_ufg_save_setting', 'ufg_save_setting_callback' );

/* 6. remove gallery/galleries start */
function ufg_remove_gallery_callback() {
	if ( current_user_can( 'manage_options' ) ) {
		if ( isset( $_POST['nonce'] ) && wp_verify_nonce( sanitize_text_field(wp_unslash($_POST['nonce'])), 'ufg-remove-gallery' ) ) {
			if(isset($_POST['ufg_gallery_id']) && isset($_POST['do_action'])){

				$ufg_do_action = sanitize_text_field(wp_unslash($_POST['do_action']));
				/* single gallery delete */
				if ( $ufg_do_action == 'single' ) {
					$ufg_gallery_id = sanitize_text_field(wp_unslash($_POST['ufg_gallery_id']));
					delete_option( 'ufg_filters_' . $ufg_gallery_id );
					delete_option( 'ufg_gallery_' . $ufg_gallery_id );
					delete_option( 'ufg_settings_' . $ufg_gallery_id );
				}

				/* multiple gallery delete via array */
				if ( $ufg_do_action == 'multiple' ) {
					$ufg_gallery_ids = map_deep(wp_unslash($_POST['ufg_gallery_id']), 'sanitize_text_field');
					foreach ( $ufg_gallery_ids as $ufg_single_id ) {
						delete_option( 'ufg_filters_' . $ufg_single_id );
						delete_option( 'ufg_gallery_' . $ufg_single_id );
						delete_option( 'ufg_settings_' . $ufg_single_id );
					}
				}
			}
			wp_die();
		} else {
			echo esc_html( 'Nonce not verified.' );
			die;
		}
	}
}
add_action( 'wp_ajax_ufg_remove_gallery', 'ufg_remove_gallery_callback' );
/* 6. remove gallery/galleries end */

// 7. clone gallery start
function ufg_clone_gallery_callback(){
	if ( current_user_can( 'manage_options' ) ) {
		if ( isset( $_POST['nonce'] ) && wp_verify_nonce( sanitize_text_field(wp_unslash($_POST['nonce'])), 'ufg-clone-gallery' ) ) {
			// verified action
			if(isset( $_POST['ufg_gallery_id'] ) && isset( $_POST['ufg_gallery_counter'] )){
				$ufg_gallery_id = (isset($_POST['id'])) ? sanitize_text_field( wp_unslash ($_POST['id'] ) ) : '';
				$ufg_gallery_counter = (isset($_POST['ufg_gallery_counter'])) ? sanitize_text_field( wp_unslash ($_POST['ufg_gallery_counter'] ) ) : '';

				//get cloning gallery data
				$ufg_cloning_filters = get_option("ufg_filters_".$ufg_gallery_id);
				$ufg_cloning_gallery = get_option("ufg_gallery_".$ufg_gallery_id);
				$ufg_cloning_setting = get_option("ufg_settings_".$ufg_gallery_id);
				$ufg_cloning_details = get_option("ufg_details_".$ufg_gallery_id);
				
				//generate new gallery id for clone
				$new_ufg_gallery_edit_nonce = wp_create_nonce( 'edit-gallery' );
				$new_ufg_gallery_id = ufg_get_next_id();
				$new_ufg_gallery_name = sanitize_text_field($ufg_cloning_details['gallery_name'].' - Clone');
				
				// update clone id into gallery data
				foreach($ufg_cloning_gallery as $key => $value){
					$ufg_cloning_setting['ufg_gallery_id'] = sanitize_text_field($new_ufg_gallery_id);
				}
				
				// update gallery details
				$ufg_cloning_details = array('ufg_gallery_id' => $ufg_gallery_id, 'gallery_name' => $new_ufg_gallery_name);
				
				if($new_ufg_gallery_id > $ufg_gallery_id){
					add_option('ufg_filters_'.$new_ufg_gallery_id, $ufg_cloning_filters);
					add_option('ufg_gallery_'.$new_ufg_gallery_id, $ufg_cloning_gallery);
					add_option('ufg_settings_'.$new_ufg_gallery_id, $ufg_cloning_setting);
					update_option('ufg_details_'.$new_ufg_gallery_id, $ufg_cloning_details);
					$ufg_do_action = "'single'";
					
					echo ('
					<tr id='.esc_attr( $new_ufg_gallery_id ).'>
						<th scope="row">'.esc_attr( $ufg_gallery_counter ).'</th>
						<td>'.esc_attr( $new_ufg_gallery_name ).'</td>
						<td>
							<input type="text" id="ufg-shortcode-'.esc_attr( $new_ufg_gallery_id ).'" class="btn btn-info btn-sm" value="[ufg id='.esc_attr($new_ufg_gallery_id).']">
							<button type="button" id="ufg-shortcode-'.esc_attr($new_ufg_gallery_id).'" class="btn btn-info btn-sm" title="Click To Copy Gallery Shortcode" onclick="return UFGCopyShortcode('.esc_attr($new_ufg_gallery_id).')">Copy</button>
							<button class="btn btn-sm btn-success d-none ufg-copied-'.esc_attr($new_ufg_gallery_id).'">Copied</button>
						</td>
						<td>
							<button type="button" id="ufg-clone" class="btn btn-warning btn-sm" title="Clone Gallery" value="'.esc_attr($new_ufg_gallery_id).'" onclick="return UFGCloneGallery('.esc_attr($new_ufg_gallery_id).', '.esc_attr($ufg_gallery_counter).');"><i class="fas fa-copy"></i></button>
							<a href="?page=ufg-manage-gallery&id='.esc_attr($new_ufg_gallery_id).'&ufg-nonce='.esc_attr($new_ufg_gallery_edit_nonce).'" class="btn btn-primary btn-sm" href="#"><i class="fas fa-edit"></i></a>
							<button id="ufg-delete-gallery" class="btn btn-danger btn-sm" title="Delete Gallery" value="'.esc_attr($new_ufg_gallery_id).'" onclick="return UFGRemoveGallery('.esc_attr($new_ufg_gallery_id).', '.esc_attr($ufg_do_action).');"><i class="fas fa-trash-alt"></i></button>
						</td>
						<td class="text-center">
							<input type="checkbox" id="ufg-gallery-id" name="ufg-gallery-id" value="'.esc_attr($new_ufg_gallery_id).'" title="Select Gallery">
						</td>
					</tr>
					');
				}
			}
			wp_die();
		} else {
			echo esc_html("Nonce not verified action.");
			die;
		}
	}
}
add_action( 'wp_ajax_ufg_clone_gallery', 'ufg_clone_gallery_callback' );
// 7. clone gallery end

// register sf scripts
function ufg_register_scripts(){
	wp_enqueue_script('jquery');
	wp_register_style( 'ufg-bootstrap-frontend-css', plugin_dir_url(__FILE__).'admin/assets/bootstrap-4.6.0/css/ufg-bootstrap-frontend-min.css', array(), '4.6.0', 'all');
	wp_register_style( 'ufg-fontawesome-css', plugin_dir_url(__FILE__). 'admin/assets/fontawesome-free-5.3.1-web/css/all.min.css', array(), '5.3.1', 'all');
	//lightbox JS and CSS
	wp_register_style( 'ufg-lightbox-css', plugin_dir_url(__FILE__). 'admin/assets/lightbox/lokesh/css/lightbox.css', array(), '2.11.2', 'all');
	wp_register_script( 'ufg-lightbox-js', plugin_dir_url(__FILE__). 'admin/assets/lightbox/lokesh/js/lightbox.js', array('jquery'), '4.5.2', true );
}
add_action( 'wp_enqueue_scripts', 'ufg_register_scripts' );

include('shortcode.php');

// Gallery Text Widget Support
add_filter( 'widget_text', 'do_shortcode' );

//display pro plugin details
function ufg_pro_details(){
	$ufg_current_page = get_current_screen();
	/* echo "<pre>";
	print_r($ufg_current_page = get_current_screen());
	echo "</pre>"; */
	if($ufg_current_page->id == "toplevel_page_filter-gallery") {
	?>
	<style>
	.ufg-pattern {
		background-color: #000000;
		background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='4' height='4' viewBox='0 0 4 4'%3E%3Cpath fill='%239c7ccf' fill-opacity='0.35' d='M1 3h1v1H1V3zm2-2h1v1H3V1z'%3E%3C/path%3E%3C/svg%3E");
	}
	</style>
	<div class="ufg-pattern m-3">
		<div class="container-fluid">
			<div class="row" style="--bs-gutter-x: 0rem;">
				<div class="col-12 p-3">
					<div class="text-center">
						<h1 class="text-light">Filter Gallery Pro (FEATURES)</h1>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-2 p-3">
					<div class="text-center">
						<button type="buttom" class="btn btn-lg btn-light btn-block"><i class="fas fa-bars"></i> Parent & Child Filters</button>
					</div>
				</div>
				<div class="col-md-2 p-3">
					<div class="text-center">
						<button type="buttom" class="btn btn-lg btn-light btn-block"><i class="fas fa-infinity"></i> Unlimited Filters</button>
					</div>
				</div>
				<div class="col-md-2 p-3">
					<div class="text-center">
						<button type="buttom" class="btn btn-lg btn-light btn-block"><i class="fas fa-th"></i> Grid & Columns</button>
					</div>
				</div>
				<div class="col-md-2 p-3">
					<div class="text-center">
						<button type="buttom" class="btn btn-lg btn-light btn-block"><i class="fas fa-search-plus"></i> Image Lightbox</button>
					</div>
				</div>
				<div class="col-md-2 p-3">
					<div class="text-center">
						<button type="buttom" class="btn btn-lg btn-light btn-block"><i class="fas fa-tablet-alt"></i> Responsive Gallery</button>
					</div>
				</div>
				<div class="col-md-2 p-3">
					<div class="text-center">
						<button type="buttom" class="btn btn-lg btn-light btn-block"><i class="fas fa-link"></i> Linking Option</button>
					</div>
				</div>
				<div class="col-md-2 p-3">
					<div class="text-center">
						<button type="buttom" class="btn btn-lg btn-light btn-block"><i class="fas fa-palette"></i> Color Customization</button>
					</div>
				</div>
				<div class="col-md-2 p-3">
					<div class="text-center">
						<button type="buttom" class="btn btn-lg btn-light btn-block"><i class="fas fa-paint-brush"></i> Interactive Design</button>
					</div>
				</div>
				<div class="col-md-2 p-3">
					<div class="text-center">
						<button type="buttom" class="btn btn-lg btn-light btn-block"><i class="fas fa-tasks"></i> Filter Settings</button>
					</div>
				</div>
				<div class="col-md-2 p-3">
					<div class="text-center">
						<button type="buttom" class="btn btn-lg btn-light btn-block"><i class="fas fa-cogs"></i> Gallery Settings</button>
					</div>
				</div>
				<div class="col-md-2 p-3">
					<div class="text-center">
						<button type="buttom" class="btn btn-lg btn-light btn-block"><i class="fab fa-youtube"></i> Video Docs</button>
					</div>
				</div>
				<div class="col-md-2 p-3">
					<div class="text-center">
						<button type="buttom" class="btn btn-lg btn-light btn-block"><i class="fas fa-headset"></i> Fast Support</button>
					</div>
				</div>
				
			</div>
			<div class="row">
				<div class="col-md-6 p-3">
					<div class="text-center">
						<a href="https://wpfrank.com/demo/filter-gallery-pro/" target="_blank" class="btn btn-lg btn-danger btn-block"><i class="fas fa-desktop"></i> Check Live Demo</a>
					</div>
				</div>
				<div class="col-md-6 p-3">
					<div class="text-center">
						<a href="https://wpfrank.com/account/signup/filter-gallery-pro" target="_blank" class="btn btn-lg btn-danger btn-block"><i class="fas fa-shopping-cart"></i> Buy Pro Plugin</a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
	}
}
add_action('in_admin_header','ufg_pro_details');