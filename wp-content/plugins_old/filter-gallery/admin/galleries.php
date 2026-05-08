<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// CSS
wp_enqueue_style( 'bootstrap-admin-css', plugins_url( 'assets/bootstrap-4.6.0/css/bootstrap-admin-min.css', __FILE__ ), array(), '4.0.6', 'all' );
wp_enqueue_style( 'fontawesome-css', plugins_url( 'assets/fontawesome-free-5.3.1-web/css/all.min.css', __FILE__ ), array(), '5.3.1', 'all' );

// get gallery details
global $wpdb;
$ufg_options_table_name = "{$wpdb->prefix}options";
$ufg_gallery_key        = 'ufg_filters_';

// Try to get cached results first
$ufg_cache_key = 'ufg_all_galleries_list';
$ufg_all_galleries = wp_cache_get( $ufg_cache_key, 'filter-gallery' );

if ( false === $ufg_all_galleries ) {
	// reference : https://wordpress.stackexchange.com/questions/8825/how-do-you-properly-prepare-a-like-sql-statement
	// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom options query with caching implemented
	$ufg_all_galleries = $wpdb->get_results(
		$wpdb->prepare( "SELECT option_name FROM `$wpdb->options` WHERE `option_name` LIKE %s ORDER BY option_id ASC", '%' . $ufg_gallery_key . '%' )
	);
	wp_cache_set( $ufg_cache_key, $ufg_all_galleries, 'filter-gallery', HOUR_IN_SECONDS );
}

// get current plugin version
$ufg_current_version = get_option( 'ufg_current_version' );
$ufg_last_version    = get_option( 'ufg_last_version' );
?>
<div class="m-3">
	<div class="row" style="--bs-gutter-x: 0rem;">
		<div class="col-md-6 p-3">
			<h3><?php esc_html_e( 'Filter Gallery Free', 'filter-gallery' ); ?>
			<small>
			  <?php
				if ( $ufg_current_version != '' ) {
					echo esc_html( 'v' );
					echo esc_html($ufg_current_version);
				}
				?>
			</small>
			</h3>
		</div>
		<div class="col-md-6  p-3 text-right">
			<a class="btn btn-primary btn-lg" href="?page=ufg-manage-gallery"><?php esc_html_e( 'Create New Gallery', 'filter-gallery' ); ?></a>
		</div>
		
		<div class="col-md-12">
			<table class="table table-dark">
				<thead>
					<tr>
					<th scope="col">#</th>
					<th scope="col"><?php esc_html_e( 'Title', 'filter-gallery' ); ?></th>
					<th scope="col"><?php esc_html_e( 'Shortcode', 'filter-gallery' ); ?></th>
					<th scope="col"><?php esc_html_e( 'Actions', 'filter-gallery' ); ?></th>
					<th scope="col" class="text-center"><input type="checkbox" id="ufg-select-all" title="<?php esc_attr_e( 'Select All Galleries', 'filter-gallery' ); ?>"></th>
					</tr>
				</thead>
				<tbody id="ufg-tbody">
					<?php
					if ( count( $ufg_all_galleries ) ) {
						$ufg_gallery_counter = 1;
						foreach ( $ufg_all_galleries as $gallery ) {
							$ufg_gallery_key    = $gallery->option_name;
							$ufg_underscore_pos = strrpos( $ufg_gallery_key, '_' );
							$ufg_gallery_id     = substr( $ufg_gallery_key, ( $ufg_underscore_pos + 1 ) );

							// load gallery data
							$gallery = get_option( 'ufg_details_' . $ufg_gallery_id );
							if ( isset( $gallery['gallery_name'] ) ) {
								$ufg_gallery_name = $gallery['gallery_name'];
							} else {
								$ufg_gallery_name = '';
							}
							$ufg_gallery_shortcode = "[ufg id=$ufg_gallery_id]";
							?>
							<tr id="<?php echo esc_attr( $ufg_gallery_id ); ?>">
								<th scope="row"><?php echo esc_html( $ufg_gallery_counter ); ?>.</th>
								<td><?php echo esc_html( $ufg_gallery_name ); ?></td>
								<td>
									<input type="text" id="ufg-shortcode-<?php echo esc_attr( $ufg_gallery_id ); ?>" class="btn btn-info btn-sm" value="<?php echo esc_attr( $ufg_gallery_shortcode ); ?>">
									<button type="button" id="ufg-shortcode-<?php echo esc_attr( $ufg_gallery_id ); ?>" class="btn btn-info btn-sm" title="<?php esc_html_e( 'Click To Copy Gallery Shortcode', 'filter-gallery' ); ?>" onclick="return UFGCopyShortcode('<?php echo esc_attr( $ufg_gallery_id ); ?>');"><?php esc_html_e( 'Copy', 'filter-gallery' ); ?></button>
									<button class="btn btn-sm btn-light d-none ufg-copied-<?php echo esc_attr( $ufg_gallery_id ); ?>"><?php esc_html_e( 'Copied', 'filter-gallery' ); ?></button>
								</td>
								<td>
									<button type="button" id="ufg-clone" class="btn btn-warning btn-sm" title="<?php esc_html_e( 'Clone Gallery', 'filter-gallery' ); ?>" value="<?php $ufg_gallery_id; ?>" onclick="return UFGCloneGallery('<?php echo esc_attr( $ufg_gallery_id ); ?>', '<?php echo esc_attr( $ufg_gallery_counter ); ?>');"><i class="fas fa-copy"></i></button>
									<a href="?page=ufg-manage-gallery&id=<?php echo esc_attr( $ufg_gallery_id ); ?>&ufg-nonce=<?php echo esc_attr( wp_create_nonce( 'edit-gallery' )); ?>" class="btn btn-primary btn-sm" href="#"><i class="fas fa-edit"></i></a>
									<button id="ufg-delete-gallery" class="btn btn-danger btn-sm" title="<?php esc_html_e( 'Delete Gallery', 'filter-gallery' ); ?>" value="<?php echo esc_attr( $ufg_gallery_id ); ?>" onclick="return UFGRemoveGallery('<?php echo esc_attr( $ufg_gallery_id ); ?>', 'single');"><i class="fas fa-trash-alt"></i></button>
								</td>
								<td class="text-center">
									<input type="checkbox" id="ufg-gallery-id" name="ufg-gallery-id" value="<?php echo esc_attr( $ufg_gallery_id ); ?>" title="<?php esc_html_e( 'Select Gallery', 'filter-gallery' ); ?>">
								</td>
							</tr>
							<?php
							$ufg_gallery_counter++;
						} // end of for each
					} // end of count
					?>
				</tbody>
				<thead>
					<tr>
					<th scope="col">#</th>
					<th scope="col"><?php esc_html_e( 'Title', 'filter-gallery' ); ?></th>
					<th scope="col"><?php esc_html_e( 'Shortcode', 'filter-gallery' ); ?></th>
					<th scope="col"><?php esc_html_e( 'Actions', 'filter-gallery' ); ?></th>
					<th scope="col" class="text-center"><button type="button" id="ufg-delete-selected" class="btn btn-danger btn-sm" title="<?php esc_html_e( 'Delete Selected Gallery', 'filter-gallery' ); ?>" onclick="return UFGRemoveGallery('', 'multiple');"><i class="fas fa-trash-alt"></i></button></th>
					</tr>
				</thead>
			</table>
		</div>
	</div>
</div>
<script>
// copy shortcode to clipboard
function UFGCopyShortcode(id) {
	/* Get the text field */
	var copyShortcode = document.getElementById("ufg-shortcode-" + id);
	//console.log(copyShortcode);
	copyShortcode.select();
	document.execCommand('copy');

	//fade in and out copied message
	jQuery('.ufg-copied-' + id).removeClass('d-none');
	jQuery('.ufg-copied-' + id).fadeIn('2000', 'linear');
	jQuery('.ufg-copied-' + id).fadeOut(3000,'swing');
}

// clone slide start
function UFGCloneGallery(ufg_gallery_id, ufg_gallery_counter){
	console.log(ufg_gallery_id + ufg_gallery_counter);
	jQuery.ajax({
		type: 'POST',
		url: "<?php echo esc_js( admin_url( 'admin-ajax.php' )); ?>",
		data: {
			'action': 'ufg_clone_gallery', //this is the name of the AJAX method called in WordPress
			'nonce': "<?php echo esc_js( wp_create_nonce( 'ufg-clone-gallery' )); ?>",
			//gallery info
			'ufg_gallery_id': ufg_gallery_id,
			'ufg_gallery_counter': ufg_gallery_counter,
		}, 
		success: function (result) {
			//alert(result);
			jQuery("tbody#ufg-tbody").append(result);
		},
		error: function () {
			//alert("error");
		}
	});
}
// clone slide end


//select all sliders
jQuery("#ufg-select-all").click(function () {
	jQuery('input:checkbox').not(this).prop('checked', this.checked);
});
// remove gallery/galleries start
function UFGRemoveGallery(ufg_gallery_id, do_action){
	console.log(ufg_gallery_id);
	console.log(do_action);
	if(do_action == 'multiple' && confirm("Are you sure to delete seleted gallery?")){
		var ufg_gallery_id = [];
			jQuery("input:checkbox[name=ufg-gallery-id]:checked").each(function() {
				ufg_gallery_id.push(jQuery(this).val());
				console.log(ufg_gallery_id);
				//hide selected table row on multiple gallery delete
				jQuery("tr#" + jQuery(this).val()).fadeOut('1500');
			});
			jQuery.ajax({
				type: 'POST',
				url: "<?php echo esc_js(admin_url( 'admin-ajax.php' )); ?>",
				data: {
					'action': 'ufg_remove_gallery', //this is the name of the AJAX method called in WordPress
					'do_action': do_action, //this is the name of the AJAX method called in WordPress
					'nonce': "<?php echo esc_js( wp_create_nonce( 'ufg-remove-gallery' )); ?>",
					//slider info
					'ufg_gallery_id': ufg_gallery_id,
				}, 
				success: function (result) {
					//hide table row on slide slider delete
					
				},
				error: function () {
					//alert("error");
				}
			});
	}
	
	if(do_action == 'single' && confirm("Are you sure to delete gallery?")){
		jQuery.ajax({
			type: 'POST',
			url: "<?php echo esc_js(admin_url( 'admin-ajax.php' )); ?>",
			data: {
				'action': 'ufg_remove_gallery', //this is the name of the AJAX method called in WordPress
				'do_action': do_action, //this is the name of the AJAX method called in WordPress
				'nonce': "<?php echo esc_js( wp_create_nonce( 'ufg-remove-gallery' )); ?>",
				//slider info
				'ufg_gallery_id': ufg_gallery_id,
			}, 
			success: function (result) {
				//hide table row on slide slider delete
				jQuery("tr#" + ufg_gallery_id).fadeOut('1500');
				jQuery(function() {
					setTimeout(function() {
						jQuery("tr#" + ufg_gallery_id).remove();
					}, 1000);
				});
			},
			error: function () {
				//alert("error");
			}
		});
	}
}
// remove gallery/galleries end
</script>