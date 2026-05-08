<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

wp_enqueue_script( 'jquery' );
wp_enqueue_script( 'jquery-ui-sortable' );
wp_enqueue_script('media-upload');
wp_enqueue_media();
wp_enqueue_style( 'wp-color-picker' );
wp_enqueue_script('wp-color-picker');

//CSS
wp_enqueue_style( 'ufg-admin-style-css', plugins_url( 'assets/style.css', __FILE__ ), array(), '1.0', 'all');
wp_enqueue_style( 'ufg-bootstrap-admin-css', plugins_url( 'assets/bootstrap-4.6.0/css/bootstrap-admin.css', __FILE__ ), array(), '4.0.6', 'all');
wp_enqueue_style( 'ufg-fontawesome-css', plugins_url( 'assets/fontawesome-free-5.3.1-web/css/all.css', __FILE__ ), array(), '5.3.1', 'all');
wp_enqueue_style( 'ufg-bootstrap-iconpicker-css', plugins_url( 'assets/drag-drop-menu/bootstrap-iconpicker/css/bootstrap-iconpicker.css', __FILE__ ), array(), '1.10.0', 'all');
wp_enqueue_style( 'ufg-bootstrap-multiselect-css', plugins_url( 'assets/css/bootstrap-multiselect.css', __FILE__ ), array(), '1.10.0', 'all');

//JS
wp_enqueue_script( 'ufg-popper-js', plugins_url( 'assets/js/popper.js', __FILE__ ), array('jquery'), '2.5.3', true );
wp_enqueue_script( 'ufg-bootstrap-js', plugins_url( 'assets/bootstrap-4.6.0/js/bootstrap.js', __FILE__ ), array('jquery'), '4.6.0', true );
wp_enqueue_script( 'ufg-bootstrap-bundle-js', plugins_url( 'assets/bootstrap-4.6.0/js/bootstrap.bundle.js', __FILE__ ), array('jquery'), '4.6.0', true );
wp_enqueue_script( 'ufg-jquery-menu-editor-js', plugins_url( 'assets/drag-drop-menu/jquery-menu-editor.js', __FILE__ ), array('jquery'), '1.0.0', true );
wp_enqueue_script( 'ufg-iconset-fontawesome-js', plugins_url( 'assets/drag-drop-menu/bootstrap-iconpicker/js/iconset/fontawesome5-3-1.js', __FILE__ ), array('jquery'), '5.3.1', true );
wp_enqueue_script( 'ufg-bootstrap-iconpicker-js', plugins_url( 'assets/drag-drop-menu/bootstrap-iconpicker/js/bootstrap-iconpicker.min.js', __FILE__ ), array('jquery'), '1.10.0', true );
wp_enqueue_script( 'ufg-uploader-js', plugins_url( 'assets/js/ufg-uploader.js', __FILE__ ), array('jquery'), '1.0.0', true );
// reference: https://www.jqueryscript.net/form/advanced-multiselect-easy.html

// reference: progress bar - https://www.codeply.com/go/bp/106527
wp_enqueue_script( 'ufg-bootstrap-multiselect-js', plugins_url( 'assets/js/bootstrap-multiselect.min.js', __FILE__ ), array('jquery'), '1.0', 'all' );

//get / create next gallery id
if (isset($_GET['id'])) {
	if (isset($_GET['ufg-nonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_GET['ufg-nonce'])), 'edit-gallery')) {
		$ufg_gallery_id = sanitize_text_field(wp_unslash($_GET['id']));
		$ufg_filters = get_option("ufg_filters_" . $ufg_gallery_id);
		$ufg_gallery = get_option("ufg_gallery_" . $ufg_gallery_id);
		$ufg_settings = get_option("ufg_settings_" . $ufg_gallery_id);
		$ufg_details = get_option("ufg_details_" . $ufg_gallery_id);
	} else {
		die;
	}
} else {
    $ufg_gallery_id = ufg_get_next_id();
    $ufg_filters = array();
    $ufg_gallery = array();
}
?>
<div class="container-fluid py-2">

	<input type="hidden" class="form-control item-menu" name="ufg-gallery-id" id="ufg-gallery-id" value="<?php echo esc_attr($ufg_gallery_id); ?>">

	<!--gallery steps one-->
	<div class="row ufg-step-1 rounded-lg py-3">
		<div class="col-md-12">
			<h2><?php esc_html_e( 'Create and Manage Filters For Gallery', 'filter-gallery' ); ?></h2>
			<hr />
		</div>
		<div class="col-md-6">
			<h5 class="card-title"><?php esc_html_e( 'Gallery Name / Title', 'filter-gallery' ); ?></h5>
			<?php if(isset($ufg_details['gallery_name'])) $ufg_gallery_name = $ufg_details['gallery_name']; else $ufg_gallery_name = ""; ?>
			<div class="py-3">
				<input type="text" name="gallery_name" id="gallery_name" class="w-50 border border-primary" value="<?php echo esc_attr($ufg_gallery_name); ?>">
			</div>
		</div>
		<div class="col-md-12">
			<h5 class="card-title"><?php esc_html_e( 'Filters', 'filter-gallery' ); ?></h5>
		</div>
		<div class="col-md-6">
			<div class="border border-primary py-3">
				<ul id="UFG_FilterEditor" class="sortableLists list-group p-3"></ul>
			</div>
			<div class="py-3">
				<h5 class="card-title"><?php esc_html_e( 'Video Tutorial (watch in fullscreen)', 'filter-gallery' ); ?></h5>
				<iframe width="100%" height="525" src="https://www.youtube.com/embed/tV4AvFGgC2U" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
			</div>
		</div>
		
		<div class="col-md-6">
			<div class="border border-primary p-3 mr-3">
				<div class="bg-primary text-white p-2"><?php esc_html_e( 'Filter Name', 'filter-gallery' ); ?></div>
				<form id="frmEdit" class="form-horizontal">
					<div class="form-group">
						<label for="text"></label>
						<div class="input-group">
							<input type="text" class="form-control item-menu" name="text" id="filter-name" placeholder="<?php esc_html_e( 'Type Filter Name', 'filter-gallery' ); ?>" onkeyup="return UFGCopyfilterkey(this.value);">
							
							<div class="input-group-append">
								<button type="button" id="UFG_FilterEditor_icon" class="btn btn-outline-secondary" disabled>Pro Only</button>
							</div>
						</div>
						<div class="input-group">
							<input type="text" name="title" class="form-control item-menu" id="filter-key" placeholder="Filter Key" hidden>
							<input type="hidden" name="icon" class="item-menu">
						</div>
					</div>
				</form>
				<button type="button" id="btnUpdate" class="btn btn-lg btn-primary" disabled><i class="fas fa-sync-alt"></i> <?php esc_html_e( 'Update', 'filter-gallery' ); ?></button>
				<button type="button" id="btnAdd" class="btn btn-lg btn-success"><i class="fas fa-plus"></i> <?php esc_html_e( 'Add', 'filter-gallery' ); ?></button>
				
				<div class="alert alert-info my-3" role="alert">
					<strong><?php esc_html_e( 'Important Note', 'filter-gallery' ); ?></strong><br />
					<?php esc_html_e( "Only the FIRST FIVE FILTERS will be usable and displayed in the output gallery.", 'filter-gallery' ); ?><br />
					<strong>1.</strong> <?php esc_html_e( "Create only parent-level filters. Child filters are a Pro feature.", 'filter-gallery' ); ?><br />
					<strong>2.</strong> <?php esc_html_e( "You can create five filters only. Upgrade to Pro for unlimited.", 'filter-gallery' ); ?><br />
					<strong>3.</strong> <?php esc_html_e( "Deleting and re-positioning the filter after assigning it to images will lead to reassign.", 'filter-gallery' ); ?><br />
					<strong>4.</strong> <?php esc_html_e( "Do not use any symbol in filter name like", 'filter-gallery' ); ?>: exclamation mark &#33; quotation mark	&#34; apostrophe &#39; plus sign &#43; asterisk	&#42; comma	&#44; hyphen &#45; period &#46; slash &#47;
				</div>
				
			</div>
		</div>
		<div class="col-md-12">
			<div class="py-3 ufg-steps">
				<div id="ufg-filter-save-process" class="spinner-grow m-3 text-dark d-none" role="status">
					<span class="visually-hidden"></span>
				</div>
				<button type="button" id="ufg-save-filters" class="btn btn-success" value="save-filters">
					<h2 class="text-white"><i class="far fa-save"></i> <?php esc_html_e( 'Save', 'filter-gallery' ); ?></h2>
				</button>
				<button type="button" id="step-2-btn-next" class="btn btn-primary">
					<h2 class="text-white"><i class="far fa-arrow-alt-circle-right"></i> <?php esc_html_e( 'Gallery', 'filter-gallery' ); ?></h2>
				</button>
			</div>
		</div>
	</div>

	<!--gallery steps two-->
	<div class="row ufg-step-2 py-3 d-none">
		<div class="col-md-12">
			<h2><?php esc_html_e( 'Upload Images to Gallery And Apply Filters', 'filter-gallery' ); ?></h2>
			<hr />
		</div>
		<div class="border border-primary py-3 w-100 mx-3">
			<div class="px-4 align-right w-100">
				<button type="button" id="ufg-remove-all-image" class="btn btn-lg btn-danger ">
					<h2 class="text-white"><i class="fas fa-trash"></i> <?php esc_html_e( 'Remove All', 'filter-gallery' ); ?></h2>
				</button>
			</div>
			<!-- gallery columns start-->
			<ul id="ufg-gallery" class="ufg-gallery py-3"></ul>
		</div>

		<div class="col-md-12 p-3">
			<button type="button" id="ufg-upload-images" class="btn btn-block btn-lg btn-secondary">
				<p><img src="<?php echo esc_url(plugins_url( 'assets/img/upload-image.png', __FILE__ )); ?>"></p>
				<h2 class="text-white"><?php esc_html_e( 'Upload Images', 'filter-gallery' ); ?></h2>
			</button>
			<input type="hidden" id="ufg_li_generate_nonce" name="ufg_li_generate_nonce" value="<?php echo esc_attr( wp_create_nonce( 'ufg-li-generate' ) ); ?>">
		</div>
		
		<!-- step navigation start-->
		<div class="py-3 ufg-steps">
			<div id="ufg-gallery-save-process" class="spinner-grow m-3 text-dark d-none" role="status">
				<span class="visually-hidden"></span>
			</div>
			<button type="button" id="step-1-btn-back" class="btn btn-primary">
				<h2 class="text-white"><i class="far fa-arrow-alt-circle-left"></i> <?php esc_html_e( 'Filters', 'filter-gallery' ); ?></h2>
			</button>
			<button type="button" id="ufg-save-gallery" class="btn btn-success" value="save-gallery">
				<h2 class="text-white"><i class="far fa-save"></i> <?php esc_html_e( 'Save', 'filter-gallery' ); ?></h2>
			</button>
			<button type="button" id="step-3-btn" class="btn btn-primary">
				<h2 class="text-white"><i class="far fa-arrow-alt-circle-right"></i> <?php esc_html_e( 'Settings', 'filter-gallery' ); ?></h2>
			</button>
		</div>
		<!-- step navigation end-->
	</div>
	
	<!--gallery steps three-->
	<div class="row ufg-step-3 py-3 d-none">
		<div class="col-md-12">
			<h2><?php esc_html_e( 'Configure Settings', 'filter-gallery' ); ?></h2>
			<hr />
		</div>
		<div class="col-md-4">
			<h4><?php esc_html_e( 'Filter Settings', 'filter-gallery' ); ?></h4>
			<?php
			$setting = get_option("ufg_settings_".$ufg_gallery_id);
			?>
			<div class="card bg-light mb-3">
				<div class="card-body">
					
					<h5 class="card-title"><?php esc_html_e( 'Filters', 'filter-gallery' ); ?></h5>
					<?php if(isset($setting['show_filters'])) $show_filters = $setting['show_filters']; else $show_filters = 1; ?>
					<div class="btn-group btn-group-toggle" data-toggle="buttons">
						<label class="btn btn-secondary active">
						<input type="radio" name="show_filters" id="show_filters1" value="1" <?php if($show_filters == 1) echo esc_attr("checked"); ?>> <?php esc_html_e( 'Show', 'filter-gallery' ); ?>
						</label>
						<label class="btn btn-secondary">
						<input type="radio" name="show_filters" id="show_filters2" value="0" <?php if($show_filters == 0) echo esc_attr("checked"); ?>> <?php esc_html_e( 'Hide', 'filter-gallery' ); ?>
						</label>
					</div>
					<button type="button" class="btn btn-light float-right" title="<?php esc_html_e( 'hide and show filter above gallery', 'filter-gallery' ); ?>"><i class="fas fa-info"></i></button>
					<p class="card-text"></p>
					
					
					<h5 class="card-title"><?php esc_html_e( 'All Button', 'filter-gallery' ); ?></h5>
					<?php if(isset($setting['show_all_button'])) $show_all_button = $setting['show_all_button']; else $show_all_button = 1; ?>
					<div class="btn-group btn-group-toggle" data-toggle="buttons">
						<label class="btn btn-secondary active">
						<input type="radio" name="show_all_button" id="show_all_button1" value="1" <?php if($show_all_button == 1) echo esc_attr("checked"); ?>> <?php esc_html_e( 'Show', 'filter-gallery' ); ?>
						</label>
						<label class="btn btn-secondary">
						<input type="radio" name="show_all_button" id="show_all_button2" value="0" <?php if($show_all_button == 0) echo esc_attr("checked"); ?>> <?php esc_html_e( 'Hide', 'filter-gallery' ); ?>
						</label>
					</div>
					<button type="button" class="btn btn-light float-right" title="<?php esc_html_e( 'hide and show all filter button', 'filter-gallery' ); ?>"><i class="fas fa-info"></i></button>
					<p class="card-text"></p>
					
					
					<h5 class="card-title"><?php esc_html_e( 'All Button Title', 'filter-gallery' ); ?></h5>
					<?php if(isset($setting['all_button_text'])) $all_button_text = $setting['all_button_text']; else $all_button_text = __( 'All', 'filter-gallery' ); ?>
					<div class="btn-group btn-group-toggle" data-toggle="buttons">
						<input type="text" class="form-control" name="all-button-text" id="all-button-text" value="<?php echo esc_attr($all_button_text); ?>" placeholder="<?php esc_html_e( 'type name', 'filter-gallery' ); ?>"> 
					</div>
					<button type="button" class="btn btn-light float-right" title="<?php esc_html_e( 'set all filter button title', 'filter-gallery' ); ?>"><i class="fas fa-info"></i></button>
					<p class="card-text"></p>
					
					
					<h5 class="card-title"><?php esc_html_e( 'All Button Color', 'filter-gallery' ); ?></h5>
					<?php if(isset($setting['all_button_color'])) $all_button_color = $setting['all_button_color']; else $all_button_color = "#FFFFFF"; ?>
					<div class="btn-group btn-group-toggle">
						<input type="text" class="form-control" id="all-button-color" name="all-button-color" value="<?php echo esc_attr($all_button_color); ?>" default-color="#FFFFFF">
					</div>
					<button type="button" class="btn btn-light float-right" title="<?php esc_html_e( 'set background color of all filter button', 'filter-gallery' ); ?>"><i class="fas fa-info"></i></button>
					<p class="card-text"></p>
					
					
					<h5 class="card-title"><?php esc_html_e( 'All Button Background Color', 'filter-gallery' ); ?></h5>
					<?php if(isset($setting['all_button_bg_color'])) $all_button_bg_color = $setting['all_button_bg_color']; else $all_button_bg_color = "#C82333"; ?>
					<div class="btn-group btn-group-toggle">
						<input type="text" class="form-control" id="all-button-bg-color" name="all-button-bg-color" value="<?php echo esc_attr($all_button_bg_color); ?>" default-color="#C82333">
					</div>
					<button type="button" class="btn btn-light float-right" title="<?php esc_html_e( 'set background color of all filter button', 'filter-gallery' ); ?>"><i class="fas fa-info"></i></button>
					<p class="card-text"></p>
					
					<h5 class="card-title"><?php esc_html_e( 'Parent Filter Button Color', 'filter-gallery' ); ?></h5>
					<?php if(isset($setting['parent_button_color'])) $parent_button_color = $setting['parent_button_color']; else $parent_button_color = "#FFFFFF"; ?>
					<div class="btn-group btn-group-toggle">
						<input type="text" class="form-control" id="parent-button-color" name="parent-button-color" value="<?php echo esc_attr($parent_button_color); ?>" default-color="#FFFFFF">
					</div>
					<button type="button" class="btn btn-light float-right" title="<?php esc_html_e( 'set background color of parent filter button', 'filter-gallery' ); ?>"><i class="fas fa-info"></i></button>
					<p class="card-text"></p>
					
					<h5 class="card-title"><?php esc_html_e( 'Parent Filters Button Background Color', 'filter-gallery' ); ?></h5>
					<?php if(isset($setting['parent_button_bg_color'])) $parent_button_bg_color = $setting['parent_button_bg_color']; else $parent_button_bg_color = "#007BFF"; ?>
					<div class="btn-group btn-group-toggle">
						<input type="text" class="form-control" id="parent-button-bg-color" name="parent-button-bg-color" value="<?php echo esc_attr($parent_button_bg_color); ?>" default-color="#007BFF">
					</div>
					<button type="button" class="btn btn-light float-right" title="<?php esc_html_e( 'set background color of parent filter background button.', 'filter-gallery' ); ?>"><i class="fas fa-info"></i></button>
					<p class="card-text"></p>
				</div>
			</div>
		</div>
		
		<div class="col-md-4">
			<h4><?php esc_html_e( 'Gallery Settings', 'filter-gallery' ); ?></h4>
			<div class="card bg-light mb-3">
				<div class="card-body">
				
					<h5 class="card-title"><?php esc_html_e( 'Columns On Desktop', 'filter-gallery' ); ?></h5>
					<?php if(isset($setting['columns_desktop'])) $columns_desktop = $setting['columns_desktop']; else $columns_desktop = 4; ?>
					<div class="btn-group btn-group-toggle" data-toggle="buttons">
						<label class="btn btn-secondary">
						<input type="radio" name="columns-desktop" id="columns-desktop3" value="4" <?php if($columns_desktop == 4) echo esc_attr("checked"); ?>> <?php esc_html_e( '3', 'filter-gallery' ); ?>
						</label>
						<label class="btn btn-secondary">
						<input type="radio" name="columns-desktop" id="columns-desktop4" value="3" <?php if($columns_desktop == 3) echo esc_attr("checked"); ?>> <?php esc_html_e( '4', 'filter-gallery' ); ?>
						</label>
						<label class="btn btn-secondary">
						<input type="radio" name="columns-desktop" id="columns-desktop5" value="2" <?php if($columns_desktop == 2) echo esc_attr("checked"); ?>> <?php esc_html_e( '6', 'filter-gallery' ); ?>
						</label>
					</div>
					<button type="button" class="btn btn-light float-right" title="<?php esc_html_e( 'columns to display on desktop or pc device', 'filter-gallery' ); ?>"><i class="fas fa-info"></i></button>
					<p class="card-text"></p>
					
					
					<h5 class="card-title"><?php esc_html_e( 'Columns On Tablet', 'filter-gallery' ); ?></h5>
					<?php if(isset($setting['columns_tab'])) $columns_tab = $setting['columns_tab']; else $columns_tab = 4; ?>
					<div class="btn-group btn-group-toggle" data-toggle="buttons">
						<label class="btn btn-secondary active">
						<input type="radio" name="columns-tab" id="columns-tab1" value="12" <?php if($columns_tab == 12) echo esc_attr("checked"); ?>> <?php esc_html_e( '1', 'filter-gallery' ); ?>
						</label>
						<label class="btn btn-secondary active">
						<input type="radio" name="columns-tab" id="columns-tab2" value="6" <?php if($columns_tab == 6) echo esc_attr("checked"); ?>> <?php esc_html_e( '2', 'filter-gallery' ); ?>
						</label>
						<label class="btn btn-secondary">
						<input type="radio" name="columns-tab" id="columns-tab3" value="4" <?php if($columns_tab == 4) echo esc_attr("checked"); ?>> <?php esc_html_e( '3', 'filter-gallery' ); ?>
						</label>
					</div>
					<button type="button" class="btn btn-light float-right" title="<?php esc_html_e( 'columns to display on tablet device', 'filter-gallery' ); ?>"><i class="fas fa-info"></i></button>
					<p class="card-text"></p>
					
					
					<h5 class="card-title"><?php esc_html_e( 'Columns On Mobile - Landscape Mode', 'filter-gallery' ); ?></h5>
					<?php if(isset($setting['columns_mobile_landscape'])) $columns_mobile_landscape = $setting['columns_mobile_landscape']; else $columns_mobile_landscape = 4; ?>
					<div class="btn-group btn-group-toggle" data-toggle="buttons">
						<label class="btn btn-secondary active">
						<input type="radio" name="columns-mobile-landscape" id="columns-mobile-landscape1" value="12" <?php if($columns_mobile_landscape == 12) echo esc_attr("checked"); ?>> <?php esc_html_e( '1', 'filter-gallery' ); ?>
						</label>
						<label class="btn btn-secondary active">
						<input type="radio" name="columns-mobile-landscape" id="columns-mobile-landscape2" value="6" <?php if($columns_mobile_landscape == 6) echo esc_attr("checked"); ?>> <?php esc_html_e( '2', 'filter-gallery' ); ?>
						</label>
						<label class="btn btn-secondary">
						<input type="radio" name="columns-mobile-landscape" id="columns-mobile-landscape3" value="4" <?php if($columns_mobile_landscape == 4) echo esc_attr("checked"); ?>> <?php esc_html_e( '3', 'filter-gallery' ); ?>
						</label>
					</div>
					<button type="button" class="btn btn-light float-right" title="<?php esc_html_e( 'columns to display on mobile device with landscape mode', 'filter-gallery' ); ?>"><i class="fas fa-info"></i></button>
					<p class="card-text"></p>
					
					
					<h5 class="card-title"><?php esc_html_e( 'Columns On Mobile - Portrait Mode', 'filter-gallery' ); ?></h5>
					<?php if(isset($setting['columns_mobile_portrait'])) $columns_mobile_portrait = $setting['columns_mobile_portrait']; else $columns_mobile_portrait = 6; ?>
					<div class="btn-group btn-group-toggle" data-toggle="buttons">
						<label class="btn btn-secondary active">
						<input type="radio" name="columns-mobile-portrait" id="columns-mobile-portrait1" value="12" <?php if($columns_mobile_portrait == 12) echo esc_attr("checked"); ?>> <?php esc_html_e( '1', 'filter-gallery' ); ?>
						</label>
						<label class="btn btn-secondary active">
						<input type="radio" name="columns-mobile-portrait" id="columns-mobile-portrait2" value="6" <?php if($columns_mobile_portrait == 6) echo esc_attr("checked"); ?>> <?php esc_html_e( '2', 'filter-gallery' ); ?>
						</label>
					</div>
					<button type="button" class="btn btn-light float-right" title="<?php esc_html_e( 'columns to display on mobile device with portrait mode', 'filter-gallery' ); ?>"><i class="fas fa-info"></i></button>
					<p class="card-text"></p>
					
					
					<h5 class="card-title"><?php esc_html_e( 'Thumbnail Image Size', 'filter-gallery' ); ?></h5>
					<?php if(isset($setting['thumbnail_image_size'])) $thumbnail_image_size = $setting['thumbnail_image_size']; else $thumbnail_image_size = "large"; ?>
					<div class="btn-group">
						<select name="thumbnail-image-size" id="thumbnail-image-size" class="custom-select">
							<option value="thumbnail" <?php if($thumbnail_image_size == "thumbnail") echo esc_attr("selected=selected"); ?>><?php esc_html_e( 'Small', 'filter-gallery' ); ?></option>
							<option value="medium" <?php if($thumbnail_image_size == "medium") echo esc_attr("selected=selected"); ?>><?php esc_html_e( 'Medium', 'filter-gallery' ); ?></option>
							<option value="large" <?php if($thumbnail_image_size == "large") echo esc_attr("selected=selected"); ?>><?php esc_html_e( 'Large', 'filter-gallery' ); ?></option>
							<option value="full" <?php if($thumbnail_image_size == "full") echo esc_attr("selected=selected"); ?>><?php esc_html_e( 'Original', 'filter-gallery' ); ?></option>
							<option value="ufg_200_200" <?php if($thumbnail_image_size == "ufg_200_200") echo esc_attr("selected=selected"); ?>><?php esc_html_e( 'Same Size', 'filter-gallery' ); ?> 200x200px</option>
							<option value="ufg_300_300" <?php if($thumbnail_image_size == "ufg_300_300") echo esc_attr("selected=selected"); ?>><?php esc_html_e( 'Same Size', 'filter-gallery' ); ?> 300x300px</option>
							<option value="ufg_400_400" <?php if($thumbnail_image_size == "ufg_400_400") echo esc_attr("selected=selected"); ?>><?php esc_html_e( 'Same Size', 'filter-gallery' ); ?> 400x400px</option>
						</select>
					</div>
					<button type="button" class="btn btn-light float-right" title="<?php esc_html_e( 'set thumbnail image size', 'filter-gallery' ); ?>"><i class="fas fa-info"></i></button>
					<p class="card-text">
					<strong><?php esc_html_e( 'Note', 'filter-gallery' ); ?>:</strong><br />
					<?php esc_html_e( 'The same size setting will only work with new images uploaded after the plugin installation.', 'filter-gallery' ); ?><br />
					<?php esc_html_e( 'It will not work with previously uploaded images.', 'filter-gallery' ); ?>
					</p>
					
					
					<h5 class="card-title"><?php esc_html_e( 'Thumbnail Border', 'filter-gallery' ); ?></h5>
					<?php if(isset($setting['thumbnail_border'])) $thumbnail_border = $setting['thumbnail_border']; else $thumbnail_border = 0; ?>
					<div class="btn-group btn-group-toggle" data-toggle="buttons">
						<label class="btn btn-secondary active">
						<input type="radio" name="thumbnail-border" id="thumbnail-border1" value="1" <?php if($thumbnail_border == 1) echo esc_attr("checked"); ?>> <?php esc_html_e( 'Show', 'filter-gallery' ); ?>
						</label>
						<label class="btn btn-secondary">
						<input type="radio" name="thumbnail-border" id="thumbnail-border2" value="0" <?php if($thumbnail_border == 0) echo esc_attr("checked"); ?>> <?php esc_html_e( 'Hide', 'filter-gallery' ); ?>
						</label>
					</div>
					<button type="button" class="btn btn-light float-right" title="<?php esc_html_e( 'show or hide thumbnail border', 'filter-gallery' ); ?>"><i class="fas fa-info"></i></button>
					<p class="card-text"></p>
					
					
					<h5 class="card-title"><?php esc_html_e( 'Thumbnail Border Thickness', 'filter-gallery' ); ?></h5>
					<?php if(isset($setting['thumbnail_border_thickness'])) $thumbnail_border_thickness = $setting['thumbnail_border_thickness']; else $thumbnail_border_thickness = 0; ?>
					<div class="btn-group btn-group-toggle">
						<input type="range" class="custom-range" name="thumbnail-border-thickness" id="thumbnail-border-thickness1" min="0" max="25" step="1" value="<?php echo esc_attr($thumbnail_border_thickness); ?>" oninput="printRange(this.id, this.value);">
					</div>
					<button class="btn btn-sm btn-secondary pl-2" id="thumbnail-border-thickness1-value"><?php echo esc_html($thumbnail_border_thickness); ?></button>px
					<button type="button" class="btn btn-light float-right" title="<?php esc_html_e( 'set thumbnail border thickness', 'filter-gallery' ); ?>"><i class="fas fa-info"></i></button>
					<p class="card-text"></p>
					
					
					<h5 class="card-title"><?php esc_html_e( 'Thumbnail Border Color', 'filter-gallery' ); ?></h5>
					<?php if(isset($setting['thumbnail_border_color'])) $thumbnail_border_color = $setting['thumbnail_border_color']; else $thumbnail_border_color = "#0069D9"; ?>
					<div class="btn-group btn-group-toggle">
						<input type="text" class="form-control" id="thumbnail-border-color" name="thumbnail-border-color" value="<?php echo esc_attr($thumbnail_border_color); ?>" default-color="#0069D9">
					</div>
					<button type="button" class="btn btn-light float-right" title="<?php esc_html_e( 'set thumbnail border color', 'filter-gallery' ); ?>"><i class="fas fa-info"></i></button>
					<p class="card-text"></p>
					
					
					<h5 class="card-title"><?php esc_html_e( 'Image Title', 'filter-gallery' ); ?></h5>
					<?php if(isset($setting['image_title'])) $image_title = $setting['image_title']; else $image_title = 0; ?>
					<div class="btn-group btn-group-toggle" data-toggle="buttons">
						<label class="btn btn-secondary active">
						<input type="radio" name="image-title" id="image-title1" value="1" <?php if($image_title == 1) echo esc_attr("checked"); ?>> <?php esc_html_e( 'Show', 'filter-gallery' ); ?>
						</label>
						<label class="btn btn-secondary">
						<input type="radio" name="image-title" id="image-title2" value="0" <?php if($image_title == 0) echo esc_attr("checked"); ?>> <?php esc_html_e( 'Hide', 'filter-gallery' ); ?>
						</label>
					</div>
					<button type="button" class="btn btn-light float-right" title="<?php esc_html_e( 'hide or show image title', 'filter-gallery' ); ?>"><i class="fas fa-info"></i></button>
					<p class="card-text"></p>
					
					
					<h5 class="card-title"><?php esc_html_e( 'Image Title Font Size', 'filter-gallery' ); ?></h5>
					<?php if(isset($setting['image_title_font_size'])) $image_title_font_size = $setting['image_title_font_size']; else $image_title_font_size = 24; ?>
					<div class="btn-group btn-group-toggle">
						<input type="range" class="custom-range" name="image-title-font-size" id="image-title-font-size1" min="1" max="72" step="1" value="<?php echo esc_attr($image_title_font_size); ?>" oninput="printRange(this.id, this.value);">
					</div>
					<button class="btn btn-sm btn-secondary pl-2" id="image-title-font-size1-value"><?php echo esc_html($image_title_font_size); ?></button>px
					<button type="button" class="btn btn-light float-right" title="<?php esc_html_e( 'set image title font size', 'filter-gallery' ); ?>"><i class="fas fa-info"></i></button>
					<p class="card-text"></p>
					
					
					<h5 class="card-title"><?php esc_html_e( 'Image Title Color', 'filter-gallery' ); ?></h5>
					<?php if(isset($setting['image_title_color'])) $image_title_color = $setting['image_title_color']; else $image_title_color = "#FFFFFF"; ?>
					<div class="btn-group btn-group-toggle">
						<input type="text" class="form-control" id="image-title-color" name="image-title-color" value="<?php echo esc_attr($image_title_color); ?>" default-color="#FFFFFF">
					</div>
					<button type="button" class="btn btn-light float-right" title="<?php esc_html_e( 'set image title text color', 'filter-gallery' ); ?>"><i class="fas fa-info"></i></button>
					<p class="card-text"></p>
					
					
					<h5 class="card-title"><?php esc_html_e( 'Image Title Background Color', 'filter-gallery' ); ?></h5>
					<?php if(isset($setting['image_title_bg_color'])) $image_title_bg_color = $setting['image_title_bg_color']; else $image_title_bg_color = "#000000"; ?>
					<div class="btn-group btn-group-toggle">
						<input type="text" class="form-control" id="image-title-bg-color" name="image-title-bg-color" value="<?php echo esc_attr($image_title_bg_color); ?>" default-color="#000000">
					</div>
					<button type="button" class="btn btn-light float-right" title="<?php esc_html_e( 'set image title background color', 'filter-gallery' ); ?>"><i class="fas fa-info"></i></button>
					<p class="card-text"></p>
					
					
					<h5 class="card-title"><?php esc_html_e( 'Image Sorting', 'filter-gallery' ); ?></h5>
					<?php if(isset($setting['image_sorting'])) $image_sorting = $setting['image_sorting']; else $image_sorting = 5; ?>
					<div class="btn-group">
						<select name="image-sorting" id="image-sorting" class="custom-select">
							<option value="1" <?php if($image_sorting == 1) echo esc_attr("selected=selected"); ?>><?php esc_html_e( 'Image ID - Ascending', 'filter-gallery' ); ?></option>
							<option value="2" <?php if($image_sorting == 2) echo esc_attr("selected=selected"); ?>><?php esc_html_e( 'Image ID - Descending', 'filter-gallery' ); ?></option>
							<option value="5" <?php if($image_sorting == 5) echo esc_attr("selected=selected"); ?>><?php esc_html_e( 'None', 'filter-gallery' ); ?></option>
						</select>
					</div>
					<button type="button" class="btn btn-light float-right" title="<?php esc_html_e( 'set gallery image sorting', 'filter-gallery' ); ?>"><i class="fas fa-info"></i></button>
					<p class="card-text"></p>
				</div>
			</div>
		</div>
		
		<div class="col-md-4">
			<h4><?php esc_html_e( 'Lightbox Settings', 'filter-gallery' ); ?></h4>
			<div class="card bg-light mb-3">
				<div class="card-body">
					
					<h5 class="card-title"><?php esc_html_e( 'Lightbox', 'filter-gallery' ); ?></h5>
					<?php if(isset($setting['lightbox'])) $lightbox = $setting['lightbox']; else $lightbox = 1; ?>
					<div class="btn-group btn-group-toggle" data-toggle="buttons">
						<label class="btn btn-secondary">
						<input type="radio" name="lightbox" id="lightbox1" value="1" <?php if($lightbox == 1) echo esc_attr("checked"); ?>> <?php esc_html_e( 'Enable', 'filter-gallery' ); ?>
						</label>
						<label class="btn btn-secondary">
						<input type="radio" name="lightbox" id="lightbox2" value="0" <?php if($lightbox == 0) echo esc_attr("checked"); ?>> <?php esc_html_e( 'Disable', 'filter-gallery' ); ?>
						</label>
					</div>
					<button type="button" class="btn btn-light float-right" title="<?php esc_html_e( 'set lightbox functionality on gallery thumbnail for larger preview', 'filter-gallery' ); ?>"><i class="fas fa-info"></i></button>
					<p class="card-text"></p>
					
					
					<h5 class="card-title"><?php esc_html_e( 'Title', 'filter-gallery' ); ?></h5>
					<?php if(isset($setting['lightbox_title'])) $lightbox_title = $setting['lightbox_title']; else $lightbox_title = 1; ?>
					<div class="btn-group btn-group-toggle" data-toggle="buttons">
						<label class="btn btn-secondary active">
						<input type="radio" name="lightbox-title" id="lightbox-title1" value="1" <?php if($lightbox_title == 1) echo esc_attr("checked"); ?>> <?php esc_html_e( 'Show', 'filter-gallery' ); ?>
						</label>
						<label class="btn btn-secondary">
						<input type="radio" name="lightbox-title" id="lightbox-title2" value="0" <?php if($lightbox_title == 0) echo esc_attr("checked"); ?>> <?php esc_html_e( 'Hide', 'filter-gallery' ); ?>
						</label>
					</div>
					<button type="button" class="btn btn-light float-right" title="<?php esc_html_e( 'show image title under the lightbox', 'filter-gallery' ); ?>"><i class="fas fa-info"></i></button>
					<p class="card-text"></p>
				</div>
			</div>
		</div>
		
		<div class="py-3 ufg-steps">
			<div id="ufg-setting-save-process" class="spinner-grow m-3 text-dark d-none" role="status">
				<span class="visually-hidden"></span>
			</div>
			<button type="button" id="step-2-btn-back" class="btn btn-primary">
				<h2 class="text-white"><i class="far fa-arrow-alt-circle-left"></i> <?php esc_html_e( 'Gallery', 'filter-gallery' ); ?></h2>
			</button>
			<button type="button" id="ufg-save-setting" class="btn btn-success" value="save-setting">
				<h2 class="text-white"><i class="far fa-save"></i> <?php esc_html_e( 'Save', 'filter-gallery' ); ?></h2>
			</button>
			<button type="button" id="get-shortcode" class="btn btn-primary">
				<h2 class="text-white"><i class="fas fa-code"></i> <?php esc_html_e( 'Shortcode', 'filter-gallery' ); ?></h2>
			</button>
		</div>
	</div>
	
	<!--gallery steps four start-->
	<div class="row ufg-step-4 py-3 d-none">
		<div class="col-md-12">
			<h2><?php esc_html_e( 'Gallery Shortcode', 'filter-gallery' ); ?></h2>
			<hr />
		</div>
		<div class="col-md-6 py-3">
			<div class="form-group">
				<input type="text" class="form-control w-25" name="ufg-shortcode" id="ufg-shortcode" value="[ufg id=<?php echo esc_attr($ufg_gallery_id); ?>]">
			</div>
			<div class="form-group">
				<button type="button" class="btn btn-success btn-lg w-25" onclick="return UFGCopyShortcode();"><i class="far fa-copy"></i> <?php esc_html_e( 'Copy Shortcode', 'filter-gallery' ); ?></button>
				<button class="btn btn-sm btn-success d-none ufg-copied ml-2"><?php esc_html_e('Shortcode Copied', 'filter-gallery'); ?></button>
			</div>
		</div>
		<div class="py-3 ufg-steps">
			<button type="button" id="step-3-btn-back" class="btn btn-primary">
				<h2 class="text-white"><i class="far fa-arrow-alt-circle-left"></i> <?php esc_html_e( 'Settings', 'filter-gallery' ); ?></h2>
			</button>
		</div>
	</div>
	<!--gallery steps four end-->
	
</div>
<style>
	#ufg-gallery { }
	#ufg-gallery li { float: left; cursor: move; width: 100%;}
	.scrolableDiv li { float: unset !important; }
</style>
<script>
// copy gallery shortcode
function UFGCopyShortcode() {
	/* Get the text field */
	var UFGShortcode = document.getElementById("ufg-shortcode");
	//console.log(UFGShortcode);
	UFGShortcode.select();
	document.execCommand('copy');

	//fade in and out copied message
	jQuery('.ufg-copied').removeClass('d-none');
	jQuery('.ufg-copied').fadeIn(2000, 'linear');
	jQuery('.ufg-copied').fadeOut(3000,'swing');
}

// image sorting
jQuery( function() {
	jQuery( "#ufg-gallery" ).sortable();
	jQuery( "#ufg-gallery" ).disableSelection();
});

function UFGSorts(order) {
	if(order == "ASC") {
		jQuery(".ufg-gallery li").sort(sort_li).appendTo('.ufg-gallery');
		function sort_li(a, b) {
			return (jQuery(b).data('position')) > (jQuery(a).data('position')) ? 1 : -1;
		}
	}
	if(order == "DESC") {
		jQuery(".ufg-gallery li").sort(sort_li).appendTo('.ufg-gallery');
		function sort_li(a, b) {
			return (jQuery(b).data('position')) < (jQuery(a).data('position')) ? 1 : -1;
		}
	}
	jQuery('.ufg-image-filters').multiselect ({
		buttonWidth: '100%',
		enableFiltering: true,
		nonSelectedText: "<?php esc_html_e( 'Select Filters', 'filter-gallery' ); ?>"
	});
}

// remove single image
function removeImage(id) {
	//alert("li.ufg-image-" + id);
	jQuery("li.ufg-image-" + id).fadeOut(700, function() {
		jQuery("li.ufg-image-" + id).remove();
	});
}

// copy filter name to key (make string in lowercase and replace white space with dash)
function UFGCopyfilterkey(filter_name) {
	var filter_key = filter_name.replace(/\s+/g, '-').toLowerCase();
	//console.log(filter_key);
	jQuery("#filter-key").val(filter_key);
}

// filter creating and managing code
jQuery(document).ready(function () {
	// advanced multi-select options
	jQuery(function(jQuery) {
		jQuery('.ufg-image-filters').multiselect ({
			buttonWidth: '100%',
			enableFiltering: true,
			nonSelectedText: "<?php esc_html_e( 'Select Filters', 'filter-gallery' ); ?>"
		});
	});
	
	// add / update / delete filters start
	// var arrayjson = [{"href":"http://home.com","icon":"fas fa-home","text":"Home", "target": "_top", "title": "My Home"},{"icon":"fas fa-chart-bar","text":"Opcion2"},{"icon":"fas fa-bell","text":"Opcion3"},{"icon":"fas fa-crop","text":"Opcion4"},{"icon":"fas fa-flask","text":"Opcion5"},{"icon":"fas fa-map-marker","text":"Opcion6"},{"icon":"fas fa-search","text":"Opcion7","children":[{"icon":"fas fa-plug","text":"Opcion7-1","children":[{"icon":"fas fa-filter","text":"Opcion7-1-1"}]}]}];
	// icon picker options
	var iconPickerOptions = {labelHeader: "{0}/{1}", footer: true};
	// sortable list options
	var sortableListOptions = {
		placeholderCss: {'background-color': "#cccccc"}
	};

	var editor = new MenuEditor('UFG_FilterEditor', {listOptions: sortableListOptions, iconPicker: iconPickerOptions});
	editor.setForm(jQuery('#frmEdit'));
	editor.setUpdateButton(jQuery('#btnUpdate'));
	
	//load saved filters
	//console.log(<?php echo wp_json_encode($ufg_filters); ?>);
	var filtersarrayjson = <?php echo wp_json_encode($ufg_filters); ?>;
	editor.setData(filtersarrayjson);

	jQuery("#btnUpdate").click(function(){
		editor.update();
	});

	jQuery('#btnAdd').click(function(){
		if(jQuery("#filter-name").val() != "") {
			editor.add();
		} else {
			jQuery("#filter-name").focus();
		}
	});
	// add / update / delete filters end

	// go back to step-1
	jQuery('#step-1-btn-back').click(function(){
		jQuery( ".ufg-step-2" ).addClass( "d-none" );
		
		jQuery( ".ufg-step-1" ).removeClass( "d-none" );
		
		// clear the ul data (will load again)
		jQuery("ul#ufg-gallery").empty();
	});
	
	// go to step-2 and save filters ajax and load saved gallery start
	jQuery('#step-2-btn-next, #ufg-save-filters').click(function(){
		//console.log(this.value)
		var ufg_event_value = this.value;
		// show loading start
		if(ufg_event_value == "save-filters") {
			jQuery('button#ufg-save-filters, button#step-2-btn-next').addClass('d-none');
			jQuery('div#ufg-filter-save-process').removeClass('d-none');
		}
		// show loading end
		
		var filters = editor.getString();
		var id = jQuery("#ufg-gallery-id").val();
		var gallery_name = jQuery("#gallery_name").val();
		if(filters != "" && id != "") {
			jQuery.ajax({
				type: 'POST',
				url: "<?php echo esc_js( admin_url( 'admin-ajax.php' ) ); ?>",
				data: {
					'action': 'ufg_gallery_filters', //this is the name of the AJAX method called in WordPress
					'id': id,
					'gallery_name': gallery_name,
					'filters': filters,
					'nonce': "<?php echo esc_js( wp_create_nonce( 'add-filters' ) ); ?>",
				}, 
				success: function (result) {
					if(ufg_event_value == "save-filters") {
						// hide loading start
						jQuery(function() {
							// it will wait for 5 sec. and then will fire
							// $("#successMessage").hide() function
							setTimeout(function() {
								// hide processing icon and show button
								jQuery('button#ufg-save-filters, button#step-2-btn-next').removeClass('d-none');
								jQuery('div#ufg-filter-save-process').addClass('d-none');
							}, 1500);
						});
						// hide loading end
					} else {
						//alert(result);
						jQuery( ".ufg-step-1" ).addClass( "d-none" );
						jQuery( ".ufg-step-2" ).removeClass( "d-none" );
						
						// call load gallery callback
						ufgLoadGallery(id);
					}
				},
				error: function () {
					//alert("error");
				}
			});
		} else {
			jQuery("#filter-name").focus();
		}
	});
	// go to step-2 and save filters ajax and load saved gallery end
	
	// go back to step-3 and save gallery
	jQuery('#step-3-btn, #ufg-save-gallery').click(function() {
		
		//console.log(this.value)
		var ufg_event_value = this.value;
		// show loading start
		if(ufg_event_value == "save-gallery") {
			jQuery('button#ufg-save-gallery, button#step-1-btn-back, button#step-3-btn').addClass('d-none');
			jQuery('div#ufg-gallery-save-process').removeClass('d-none');
		} else {
			jQuery( ".ufg-step-2" ).addClass( "d-none" );
			jQuery( ".ufg-step-3" ).removeClass( "d-none" );
		}
		
		// save gallery ajax call
		var id = jQuery("#ufg-gallery-id").val();
		var image_id = jQuery('.ufg-attachment-id').serialize();
		var image_title = jQuery('input:text.ufg-title').serialize();
		var image_alt = jQuery('input:text.ufg-alt').serialize();
		var image_description = jQuery('.ufg-description').serialize();
		var image_url = jQuery('.ufg-url').serialize();
		var image_filters = jQuery('.ufg-image-filters').serialize();
		
		jQuery.ajax({
			type: 'POST',
			url: "<?php echo esc_js( admin_url( 'admin-ajax.php' ) ); ?>",
			data: {
				'action': 'ufg_save_gallery', //this is the name of the AJAX method called in WordPress
				'id': id,
				'nonce': "<?php echo esc_js( wp_create_nonce( 'save-gallery' ) ); ?>",
				'image_id': image_id,
				'image_title': image_title,
				'image_alt': image_alt,
				'image_description': image_description,
				'image_url': image_url,
				'image_filters': image_filters,
			}, 
			success: function (result) {
				if(ufg_event_value == "save-gallery") {
					jQuery(function() {
						setTimeout(function() {
							// hide processing icon and show button
							jQuery('button#ufg-save-gallery, button#step-1-btn-back, button#step-3-btn').removeClass('d-none');
							jQuery('div#ufg-gallery-save-process').addClass('d-none');
						}, 1500);
					});
					// hide loading end
				} else {
					jQuery( "#step-2-btn-next" ).removeClass( "d-none" );
				}
			},
			error: function () {
				//alert("error");
			}
		});
	});
	
	// back to step-2
	jQuery('#step-2-btn-back').click(function(){
		jQuery( ".ufg-step-3" ).addClass( "d-none" );
		jQuery( ".ufg-step-2" ).removeClass( "d-none" );
	});
	
	// save setting and get shortcode
	jQuery('#get-shortcode, #ufg-save-setting').click(function(){
		
		//console.log(this.value)
		var ufg_event_value = this.value;
		// show loading start
		if(ufg_event_value == "save-setting") {
			jQuery('button#ufg-save-setting, button#step-2-btn-back, button#get-shortcode').addClass('d-none');
			jQuery('div#ufg-setting-save-process').removeClass('d-none');
		} else {
			jQuery( ".ufg-step-3" ).addClass( "d-none" );
			jQuery( ".ufg-step-4" ).removeClass( "d-none" );
		}
		
		// save setting ajax
		var id = jQuery("#ufg-gallery-id").val();
		
		var show_filters = jQuery("input[name='show_filters']:checked").val();
		var show_all_button = jQuery("input[name='show_all_button']:checked").val();
		var all_button_text = jQuery("#all-button-text").val();
		var all_button_color = jQuery("#all-button-color").val();
		var all_button_bg_color = jQuery("#all-button-bg-color").val();
		var parent_button_color = jQuery("#parent-button-color").val();
		var parent_button_bg_color = jQuery("#parent-button-bg-color").val();
		
		var columns_desktop = jQuery("input[name='columns-desktop']:checked").val();
		var columns_tab = jQuery("input[name='columns-tab']:checked").val();
		var columns_mobile_landscape = jQuery("input[name='columns-mobile-landscape']:checked").val();
		var columns_mobile_portrait = jQuery("input[name='columns-mobile-portrait']:checked").val();
		var thumbnail_image_size = jQuery("#thumbnail-image-size").val();
		var thumbnail_border = jQuery("input[name='thumbnail-border']:checked").val();
		var thumbnail_border_thickness = jQuery("#thumbnail-border-thickness1").val();
		var thumbnail_border_color = jQuery("#thumbnail-border-color").val();
		var image_title = jQuery("input[name='image-title']:checked").val();
		var image_title_font_size = jQuery("#image-title-font-size1").val();
		var image_title_color = jQuery("#image-title-color").val();
		var image_title_bg_color = jQuery("#image-title-bg-color").val();
		var image_sorting = jQuery("#image-sorting").val();

		//lightbox settings
		var lightbox = jQuery("input[name='lightbox']:checked").val();
		var lightbox_title = jQuery("input[name='lightbox-title']:checked").val();
	
		jQuery.ajax({
			type: 'POST',
			url: "<?php echo esc_js( admin_url( 'admin-ajax.php' ) ); ?>",
			data: {
				'action': 'ufg_save_setting', //this is the name of the AJAX method called in WordPress
				'ufg_gallery_id': id,
				'nonce': "<?php echo esc_js( wp_create_nonce( 'save-setting' ) ); ?>",
				// filters settings
				'show_filters': show_filters,
				'show_all_button': show_all_button,
				'all_button_text': all_button_text,
				'all_button_color': all_button_color,
				'all_button_bg_color': all_button_bg_color,
				'parent_button_color': parent_button_color,
				'parent_button_bg_color': parent_button_bg_color,

				// gallery settings
				'columns_desktop': columns_desktop,
				'columns_tab': columns_tab,
				'columns_mobile_landscape': columns_mobile_landscape,
				'columns_mobile_portrait': columns_mobile_portrait,
				'thumbnail_image_size': thumbnail_image_size,
				'thumbnail_border': thumbnail_border,
				'thumbnail_border_thickness': thumbnail_border_thickness,
				'thumbnail_border_color': thumbnail_border_color,
				'image_title': image_title,
				'image_title_font_size': image_title_font_size,
				'image_title_color': image_title_color,
				'image_title_bg_color': image_title_bg_color,
				'image_sorting': image_sorting,

				//lightbox
				'lightbox': lightbox,
				'lightbox_title': lightbox_title,
			}, 
			success: function (result) {
				//alert(result);
				
				
				//alert(result);
				if(ufg_event_value == "save-setting") {
					// hide loading start
					jQuery(function() {
						// it will wait for 5 sec. and then will fire
						// $("#successMessage").hide() function
						setTimeout(function() {
							// hide processing icon and show button
							jQuery('button#ufg-save-setting, button#step-2-btn-back, button#get-shortcode').removeClass('d-none');
							jQuery('div#ufg-setting-save-process').addClass('d-none');
						}, 1500);
					});
					// hide loading end
				} else {
					jQuery( "#step-3-btn-next" ).removeClass( "d-none" );
				}
			},
			error: function () {
				//alert("error");
			}
		});
	});
	
	// back to step-3
	jQuery('#step-3-btn-back').click(function(){
		jQuery( ".ufg-step-4" ).addClass( "d-none" );
		jQuery( ".ufg-step-3" ).removeClass( "d-none" );
	});
	
	// tooltip
	//jQuery('[data-toggle="tooltip"]').tooltip();
});

// load gallery via ajax
function ufgLoadGallery(id){
	jQuery.ajax({
		type: 'POST',
		url: "<?php echo esc_js( admin_url( 'admin-ajax.php' ) ); ?>",
		data: {
			'action': 'ufg_load_gallery', //this is the name of the AJAX method called in WordPress
			'id': id,
			'nonce': "<?php echo esc_js( wp_create_nonce( 'load-gallery' ) ); ?>",
		}, 
		success: function (response) {
			//console.log(response);
			jQuery("ul#ufg-gallery").append(response);
		},
		error: function () {
			//alert("error");
		}
	});
}

//color-picker
(function( jQuery ) {
	jQuery(function() {
		// Add Color Picker to all inputs that have 'color-field' class
		jQuery('#all-button-color, #all-button-bg-color, #parent-button-color, #parent-button-bg-color, #l1-button-color, #l1-button-bg-color, #l2-button-color, #l2-button-bg-color, #l3-button-color, #l3-button-bg-color, #l4-button-color, #l4-button-bg-color, #l5-button-color, #l5-button-bg-color, #thumbnail-border-color,  #thumbnail-bg-color, #image-title-color, #image-title-bg-color, #image-description-color, #image-description-bg-color, #read-more-button-color, #read-more-button-bg-color, #load-more-button-color').wpColorPicker();
	});
})( jQuery );
jQuery(document).ajaxComplete(function() {
	jQuery('#all-button-color, #all-button-bg-color, #parent-button-color, #parent-button-bg-color, #l1-button-color, #l1-button-bg-color, #l2-button-color, #l2-button-bg-color, #l3-button-color, #l3-button-bg-color, #l4-button-color, #l4-button-bg-color, #l5-button-color, #l5-button-bg-color, #thumbnail-border-color,  #thumbnail-bg-color, #image-title-color, #image-title-bg-color, #image-description-color, #image-description-bg-color, #read-more-button-color, #read-more-button-bg-color, #load-more-button-color').wpColorPicker();
});

// print range call back
function printRange(id, value){
	//console.log(id + value);
	ufg_field_name = '#' + id + '-value';
	//console.log(ufg_field_name);
	jQuery(ufg_field_name).text(value);
}
</script>