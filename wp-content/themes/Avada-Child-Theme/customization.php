<?php
function enqueuePageJs(){
    wp_enqueue_script('contact', get_template_directory_uri().'-Child-Theme/js/custom-child.js', array('jquery'), null,true );
    if( is_page(1735) ):
        wp_enqueue_script('contact', get_template_directory_uri().'-Child-Theme/js/contact.js', array('jquery'), null,true );

        // Get all terms from a specific taxonomy
        $taxonomy_terms = get_terms( array(
            'taxonomy' => 'category', // replace with your taxonomy slug
            'hide_empty' => false, // include empty terms
        ) );

        // Use arrow functions to retrieve term names
        $terms = array_map( fn( $term ) => $term->name, $taxonomy_terms );
        $terms = array_filter( $terms ); // remove any empty terms
        // Localize your script with the data

        $data = array(
            'terms' => $terms
        );
        wp_localize_script( 'contact', 'post', $data );

    endif;

    if(is_tax('productcat')){
        wp_enqueue_script('productcat', get_template_directory_uri().'-Child-Theme/js/productcat.js', array('jquery'), null,true );

        //  Get current selected term

        $term = get_queried_object();

        // Get all the child product categories for the parent product category
        $child_product_cats = get_terms( array(
            'taxonomy' => 'productcat',
            'hide_empty' => false,
            'parent' => $term->term_id,
        ) );

        $image = [];
        foreach ( $child_product_cats as $child_product_cat ) {
            $img = get_field('banner_image', $child_product_cat) ?? get_field('feature_image', $child_product_cat);
            array_push($image, $img);
        }

        $data = array(
            'images' => $image
        );
        wp_localize_script( 'productcat', 'term', $data );
    }

    if( get_queried_object()->term_id == 17 ){
        wp_enqueue_style( 'imggallery-css', get_template_directory_uri().'-Child-Theme/css/lightgallery.css');
        wp_enqueue_script( 'imggallery-js', get_template_directory_uri().'-Child-Theme/js/lightgallery.js');
        wp_enqueue_script( 'imggallery-jss', get_template_directory_uri().'-Child-Theme/js/lg-pager.js');
        wp_enqueue_script( 'imggallery-jsss', get_template_directory_uri().'-Child-Theme/js/lg-autoplay.js');
        wp_enqueue_script( 'imggallery-jsssss', get_template_directory_uri().'-Child-Theme/js/lg-fullscreen.js');
        wp_enqueue_script( 'imggallery-jssssss', get_template_directory_uri().'-Child-Theme/js/lg-zoom.js');
        wp_enqueue_script( 'imggallery-jsssssss', get_template_directory_uri().'-Child-Theme/js/lg-hash.js');    }

}
add_action('wp_enqueue_scripts','enqueuePageJs');

function tabsCp(){
    $term = get_queried_object();

    // Get all the child product categories for the parent product category
    $child_product_cats = get_terms( array(
        'taxonomy' => 'productcat',
        'hide_empty' => false,
        'parent' => $term->term_id,
    ) );



    // Start building the output string
    $output = '<div class="termidtab-'.$term->term_id.' fusion-tabs fusion-tabs-1 classic nav-is-justified horizontal-tabs icon-position-left mobile-mode-accordion"
    style="--awb-title-border-radius-top-left:0px;--awb-title-border-radius-top-right:0px;--awb-title-border-radius-bottom-right:0px;--awb-title-border-radius-bottom-left:0px;--awb-alignment:start;--awb-inactive-color:var(--awb-color2);--awb-background-color:var(--awb-color1);--awb-border-color:var(--awb-color3);--awb-active-border-color:var(--awb-color5);">
    <div class="nav">
        <ul class="nav-tabs nav-justified" role="tablist">';

    // Loop through each child product category and add it to the output string
    $counter = 0;
    foreach ( $child_product_cats as $child_product_cat ) {
        if( $counter == 0){
            $output .= '<li role="presentation" class="active">';
        }else{
            $output .= '<li role="presentation">';
        }
        $counter++;
        $output .= '<a class="tab-link" data-toggle="tab" role="tab" aria-controls="tab-'.$child_product_cat->slug.'" aria-selected="true" id="fusion-tab-'.$child_product_cat->slug.'" href="#tab-'.$child_product_cat->slug.'"><h4 class="fusion-tab-heading fusion-responsive-typography-calculated"
        style="--fontSize: 28.43; line-height: 1.4;" data-fontsize="28.43" data-lineheight="39.8px">' . $child_product_cat->name . '</h4></a></li>';
    }

    // Close the unordered list
    $output .= '</ul>
    </div>
    <div class="tab-content">';
    $counter = 0;
    foreach ( $child_product_cats as $child_product_cat ) {
        $output .= '<div class="nav fusion-mobile-tab-nav">
        <ul class="nav-tabs nav-justified" role="tablist">';
        if( $counter == 0){
            $output .= '<li role="presentation" class="active">';
        }else{
            $output .= '<li role="presentation">';
        }
        $output .= '<a class="tab-link" data-toggle="tab" role="tab"
                    aria-controls="tab-'.$child_product_cat->slug.'" aria-selected="true"
                    id="mobile-fusion-tab-'.$child_product_cat->slug.'" href="#tab-'.$child_product_cat->slug.'" tabindex="-1">
                    <h4 class="fusion-tab-heading fusion-responsive-typography-calculated"
                        style="--fontSize: 28.43; line-height: 1.4;" data-fontsize="28.43"
                        data-lineheight="39.8px">'.$child_product_cat->name.'</h4>
                </a></li>
        </ul>
    </div>';
        if( $counter == 0){
            $output .= '<div class="tab-pane fade fusion-clearfix active in" role="tabpanel" tabindex="0"
                aria-labelledby="fusion-tab-'.$child_product_cat->slug.'" id="tab-'.$child_product_cat->slug.'">';
        }else{
            $output .= '<div class="tab-pane fade fusion-clearfix" role="tabpanel" tabindex="0"
                aria-labelledby="fusion-tab-'.$child_product_cat->slug.'" id="tab-'.$child_product_cat->slug.'">';
        }
        $counter++;
        $output .= load_template_part( 'templates/productcat', null, $child_product_cat);
        $output .= '</div>';

    }
    $output .= '</div>';
    $output .= '
    <script>
        jQuery(document).ready(function() {
            let selectecPage = getUrlVariable();
            jQuery(`[data-filter=".${selectecPage}"]`).trigger("click");
            jQuery(".fusion-filters").remove();
        })
        const getUrlVariable = () => {
            return window.location.pathname.match(/\/([^/]+)\/?$/)[1];
        }
    </script>
    ';

    if( count( $child_product_cats ) == 0 ){
        $output .= '
        <style>
            #catSlider,.fusion-tabs .nav{
                display:none !important;
            }
        </style>
        ';
        $termid = get_queried_object()->term_id;
        $args = array(
            "post_type" => "product",   // Replace 'post' with your custom post type if needed.
            "tax_query" => array(
                array(
                    "taxonomy" => "productcat",
                    "field" => "term_id",
                    "terms" => $termid,
                ),
            ),
        );
        // Execute the query.
        $query = new WP_Query($args);
        if ($query->have_posts()) {
            $counter = 0;
            while ($query->have_posts()) {
                $query->the_post();
                $counter ++;
                $output .= load_template_part( 'templates/singleProduct','',$counter);
            
                $output .= '<script>
                    jQuery(document).ready(function(){
                        jQuery(".flexslider").flexslider({
                            animation: "slide"
                        });
                    });
                    </script>';
            }
            wp_reset_postdata();
        } else {
            $output .= 'No products found for this category.';
        }
    }
    
    return $output;
}
add_shortcode( 'tabscp', 'tabsCp' );

add_action('user_register', function(){
    wp_die('User registration is disabled.');
});