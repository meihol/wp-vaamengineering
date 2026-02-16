<?php
    $productCat = $args;
    $image = get_field('feature_image', $productCat);
    $broucher = get_field('broucher', $productCat);
    $productCatLink = get_term_link($productCat->term_id, 'productcat');
?>
<div class="fusion-fullwidth fullwidth-box fusion-builder-row-3 fusion-flex-container has-pattern-background has-mask-background nonhundred-percent-fullwidth non-hundred-percent-height-scrolling" style="--link_hover_color: var(--awb-color5);--link_color: var(--awb-color5);--awb-background-position:right top;--awb-background-blend-mode:overlay;--awb-border-color:var(--awb-color1);--awb-border-radius-top-left:0px;--awb-border-radius-top-right:0px;--awb-border-radius-bottom-right:0px;--awb-border-radius-bottom-left:0px;--awb-padding-top:90px;--awb-padding-bottom:90px;--awb-padding-top-medium:130px;--awb-padding-top-small:70px;--awb-padding-right-small:0px;--awb-padding-bottom-small:70px;--awb-padding-left-small:0px;--awb-margin-bottom-medium:0px;--awb-background-color:#ffffff;">
    <div class="fusion-builder-row fusion-row fusion-flex-align-items-center" style="margin:0 auto;">
        <div class="fusion-layout-column fusion_builder_column fusion-builder-column-44 fusion-flex-column" style="--awb-bg-size:cover;--awb-width-large:45%;--awb-margin-top-large:0px;--awb-spacing-right-large:72px;--awb-margin-bottom-large:0px;--awb-spacing-left-large:4.2666666666667%;--awb-width-medium:40%;--awb-order-medium:0;--awb-spacing-right-medium:70px;--awb-spacing-left-medium:4.8%;--awb-width-small:100%;--awb-order-small:0;--awb-spacing-right-small:1.92%;--awb-margin-bottom-small:20px;--awb-spacing-left-small:1.92%;">
            <div class="fusion-column-wrapper fusion-column-has-shadow fusion-flex-justify-content-flex-start fusion-content-layout-column">
                <div class="fusion-image-element " style="--awb-aspect-ratio: 100 / 112;--awb-margin-bottom-small:0px;--awb-caption-title-font-family:var(--h2_typography-font-family);--awb-caption-title-font-weight:var(--h2_typography-font-weight);--awb-caption-title-font-style:var(--h2_typography-font-style);--awb-caption-title-size:var(--h2_typography-font-size);--awb-caption-title-transform:var(--h2_typography-text-transform);--awb-caption-title-line-height:var(--h2_typography-line-height);--awb-caption-title-letter-spacing:var(--h2_typography-letter-spacing);">
                    <span class="fusion-imageframe imageframe-none imageframe-22 hover-type-none has-aspect-ratio fusion-animated" style="--awb-animation-color: var(--awb-color4); visibility: visible; animation-duration: 1.3s;" data-animationtype="revealInLeft" data-animationduration="1.3" data-animationoffset="top-into-view">
                        <?php if($image): ?>
                        <img decoding="async" alt="info-9" title="Woman smiling" src="<?php echo $image;?>" data-orig-src="<?php echo $image;?>" class="img-responsive wp-image-869 img-with-aspect-ratio ls-is-cached lazyloaded" width="842" height="842">
                        <?php endif; ?>
                    </span>
                </div>
            </div>
        </div>
        <div class="fusion-layout-column fusion_builder_column fusion-builder-column-45 fusion-flex-column fusion-animated" style="padding-left: 0px; --awb-padding-bottom-medium: 0px; --awb-bg-size: cover; --awb-width-large: 55%; --awb-margin-top-large: 0px; --awb-spacing-right-large: 3.4909090909091%; --awb-margin-bottom-large: 9px; --awb-spacing-left-large: 55px; --awb-width-medium: 60%; --awb-order-medium: 0; --awb-spacing-right-medium: 3.2%; --awb-spacing-left-medium: 20px; --awb-width-small: 100%; --awb-order-small: 0; --awb-spacing-right-small: 1.92%; --awb-spacing-left-small: 20px; visibility: visible; animation-duration: 1.3s;" ata-animationtype="fadeInRight" data-animationduration="1.3" data-animationoffset="top-into-view">
            <div class="fusion-column-wrapper fusion-column-has-shadow fusion-flex-justify-content-flex-start fusion-content-layout-column">
                <div class="fusion-title title fusion-title-50 fusion-sep-none fusion-title-text fusion-title-size-two" style="--awb-margin-top:20px;--awb-margin-bottom:30px;">
                    <h2 class="title-heading-left gt_cat_title fusion-responsive-typography-calculated" style="margin:0;--fontSize:42;line-height:var(--awb-typography1-line-height);">
                        <?php echo $productCat->name; ?><br>
                    </h2>
                </div>
                <div class="fusion-text fusion-text-42 fusion-text-no-margin" style="--awb-margin-bottom:0px;">
                    <p><?php echo $productCat->description; ?></p>
                </div>
                <div style="margin-top: 30px;">
                    <a class="fusion-button button-flat fusion-button-default-size button-default fusion-button-default button-3 fusion-button-default-span fusion-button-default-type" target="_self" href="<?php echo $productCatLink; ?>"><span class="fusion-button-text">Read More</span>
                    </a>
                </div>
                <?php
                
                $images = get_field('gallery',$productCat);
                    if( $images ): ?>
                <div class="imagegallery" id="imageGallery<?php echo $productCat->term_id; ?>">
                    <?php foreach( $images as $image ): ?>
                        <li data-src="<?php echo esc_url($image); ?>">
                            <a href="">
                                <img class="img-responsive" src="<?php echo esc_url($image); ?>">
                            </a>
                        </li>
                    <?php endforeach; ?>
                </div>
                <script>
                    lightGallery(document.getElementById('imageGallery<?php echo $productCat->term_id; ?>'));
                </script>
                <?php endif; ?>
                <?php
                /*
                    $args = array(
                        'post_type' => 'product',       // Replace 'post' with your custom post type if needed.
                        'tax_query' => array(
                            array(
                                'taxonomy' => 'productcat',
                                'field' => 'term_id',
                                'terms' => $productCat->term_id,
                            ),
                        ),
                    );
                    // Execute the query.
                    $query = new WP_Query($args);
                    // Check if there are any posts found.
                    if ($query->have_posts()) {
                        while ($query->have_posts()) {
                            $query->the_post();
                            // Here, you can access the post data using WordPress template tags or functions.
                            // For example, to get the post title:
                            $post_title = get_the_title();
                            $broucher = get_field('broucher');

                            // Get the parent category's term ID
                            //$parent_term_id = $productCat->parent;

                            // Get the parent category's permalink using the term ID
                            
                            $child_category_permalink = get_term_link($productCat->term_id, 'productcat');

                            echo "<a style='font-weight:bold;' download href='" . $broucher . "' >" . $post_title . ' - Click to view</a><br>';
                            
                        }
                        // Restore the global post data.
                        wp_reset_postdata();
                    }*/
                ?>
            </div>
        </div>
    </div>
</div>