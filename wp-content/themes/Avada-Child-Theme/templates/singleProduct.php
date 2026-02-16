<?php
$post_title = get_the_title();
$product_description = get_the_content(); // Get the product description
?>
<div class="productlistdiv fusion-fullwidth fullwidth-box fusion-builder-row-4 fusion-flex-container has-pattern-background has-mask-background nonhundred-percent-fullwidth hundred-percent-height hundred-percent-height-center-content non-hundred-percent-height-scrolling" style="">
    <?php if($args % 2 != 0): ?>
        <div class="awb-background-mask" style="background-image: url(data:image/svg+xml;utf8,%3Csvg%20width%3D%221920%22%20height%3D%22954%22%20fill%3D%22none%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%3E%3Cg%20clip-path%3D%22url%28%23prefix__clip0_75_23031%29%22%20fill%3D%22rgba%289%2C52%2C84%2C1%29%22%3E%3Cpath%20d%3D%22M1321.57%20538C1357.08%20287.516%201273.7%2089.91%201127-.418L1374.18-6c65.77%20100.68-15.89%20431.512-52.61%20544zM312%20955c432.242%200%20746.77-180.667%20850-271-90.34%20157.09-176.766%20246.121-208.688%20271H312z%22%20fill%3D%22rgba%28162%2C173%2C190%2C0%29%22%20fill-opacity%3D%22.25%22%2F%3E%3Cpath%20d%3D%22M1344.5%20427c0-252.4-212.67-390.833-319-428.5H1373c70%2082.4%2010.17%20320-28.5%20428.5z%22%20fill%3D%22rgba%28162%2C173%2C190%2C0%29%22%20fill-opacity%3D%22.1%22%2F%3E%3Cpath%20fill-rule%3D%22evenodd%22%20clip-rule%3D%22evenodd%22%20d%3D%22M839.644%20954C1138.37%20793.549%201337%20508.902%201337%20184.5c0-63.218-7.54-124.926-21.9-184.5H1920v954H839.644zm0%200C676.842%201041.44%20484.311%201092%20278%201092c-584.87%200-1059-406.302-1059-907.5S-306.87-723%20278-723c511.098%200%20937.63%20310.269%201037.1%20723H0v954h839.644z%22%20fill%3D%22rgba%28162%2C173%2C190%2C0%29%22%20fill-opacity%3D%22.5%22%2F%3E%3Cpath%20fill-rule%3D%22evenodd%22%20clip-rule%3D%22evenodd%22%20d%3D%22M1011.55%20954C1221.42%20793.95%201353%20564.007%201353%20308.5c0-108.584-23.76-212.552-67.2-308.5H1920v954h-908.45zm0%200c-187.291%20142.83-436.933%20230-711.05%20230C-280.78%201184-752%20792.025-752%20308.5S-280.78-567%20300.5-567c450.743%200%20835.31%20235.692%20985.3%20567H0v954h1011.55z%22%2F%3E%3C%2Fg%3E%3Cdefs%3E%3CclipPath%20id%3D%22prefix__clip0_75_23031%22%3E%3Cpath%20fill%3D%22%23fff%22%20d%3D%22M0%200h1920v954H0z%22%2F%3E%3C%2FclipPath%3E%3C%2Fdefs%3E%3C%2Fsvg%3E); opacity: 1; transform: scale(1, -1);"></div>
    <?php else: ?>
        <div class="awb-background-mask" style="background-image:  url(data:image/svg+xml;utf8,%3Csvg%20width%3D%221920%22%20height%3D%22954%22%20fill%3D%22none%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%3E%3Cg%20clip-path%3D%22url%28%23prefix__clip0_75_23031%29%22%20fill%3D%22rgba%289%2C52%2C84%2C1%29%22%3E%3Cpath%20d%3D%22M1321.57%20538C1357.08%20287.516%201273.7%2089.91%201127-.418L1374.18-6c65.77%20100.68-15.89%20431.512-52.61%20544zM312%20955c432.242%200%20746.77-180.667%20850-271-90.34%20157.09-176.766%20246.121-208.688%20271H312z%22%20fill%3D%22rgba%28162%2C173%2C190%2C0%29%22%20fill-opacity%3D%22.25%22%2F%3E%3Cpath%20d%3D%22M1344.5%20427c0-252.4-212.67-390.833-319-428.5H1373c70%2082.4%2010.17%20320-28.5%20428.5z%22%20fill%3D%22rgba%28162%2C173%2C190%2C0%29%22%20fill-opacity%3D%22.1%22%2F%3E%3Cpath%20fill-rule%3D%22evenodd%22%20clip-rule%3D%22evenodd%22%20d%3D%22M839.644%20954C1138.37%20793.549%201337%20508.902%201337%20184.5c0-63.218-7.54-124.926-21.9-184.5H1920v954H839.644zm0%200C676.842%201041.44%20484.311%201092%20278%201092c-584.87%200-1059-406.302-1059-907.5S-306.87-723%20278-723c511.098%200%20937.63%20310.269%201037.1%20723H0v954h839.644z%22%20fill%3D%22rgba%28162%2C173%2C190%2C0%29%22%20fill-opacity%3D%22.5%22%2F%3E%3Cpath%20fill-rule%3D%22evenodd%22%20clip-rule%3D%22evenodd%22%20d%3D%22M1011.55%20954C1221.42%20793.95%201353%20564.007%201353%20308.5c0-108.584-23.76-212.552-67.2-308.5H1920v954h-908.45zm0%200c-187.291%20142.83-436.933%20230-711.05%20230C-280.78%201184-752%20792.025-752%20308.5S-280.78-567%20300.5-567c450.743%200%20835.31%20235.692%20985.3%20567H0v954h1011.55z%22%2F%3E%3C%2Fg%3E%3Cdefs%3E%3CclipPath%20id%3D%22prefix__clip0_75_23031%22%3E%3Cpath%20fill%3D%22%23fff%22%20d%3D%22M0%200h1920v954H0z%22%2F%3E%3C%2FclipPath%3E%3C%2Fdefs%3E%3C%2Fsvg%3E);opacity: 1 ;transform: scale(-1, -1);"></div>
    <?php endif; ?>


    <div class="gt_single_product_list fusion-builder-row fusion-row fusion-flex-align-items-center" style="display: flex; align-items: center;">
        <div class="fusion-layout-column fusion_builder_column fusion-builder-column-7 fusion-flex-column fusion-animated <?php if($args % 2 == 0){echo "orderingproduct";} ?>" style="width: 50%;">
            <div
                class="fusion-column-wrapper fusion-column-has-shadow fusion-flex-justify-content-flex-start fusion-content-layout-column">
                <div
                    class="fusion-title title fusion-title-5 fusion-sep-none fusion-title-text fusion-title-size-two">
                    <h2 class="title-heading-left gt_single_title" style="margin: 0">
                        <?php echo $post_title; ?>
                    </h2>
                </div>
                <div
                    class="fusion-text fusion-text-4 fusion-text-no-margin">
                    <p>
                    <?php echo $product_description; ?>
                    </p>
                </div>
            </div>
        </div>
        <div class="fusion-layout-column fusion_builder_column fusion-builder-column-8 fusion-flex-column <?php if($args % 2 == 0){echo "orderingproduct";} ?>" style="width: 50%;">
            <div class="fusion-column-wrapper fusion-column-has-shadow fusion-flex-justify-content-flex-start fusion-content-layout-column">
                <div class="fusion-slider-sc">
                    <div class="flexslider">
                        <ul class="slides">
                            <?php
                            $images = get_field(
                                "image_gallery",
                                $productCat
                            );
                            if ($images): ?>
                            <?php foreach (
                                $images
                                as $image
                            ): ?>
                            <li class="image" style="text-align: center;">
                                <span class="fusion-image-hover-element hover-type-liftup <?php echo $productCat->term_id; ?>">
                                    <?php
                                //echo esc_url($image);
                                ?>
                                    <img  src="<?php echo esc_url($image); ?>" width="800" height="800" class="wp-image-2315" srcset="<?php echo esc_url($image); ?>" sizes="(max-width: 640px) 100vw, 600px" draggable="false"/>
                                </span>
                            </li>
                            <?php endforeach; ?>
                            <?php endif;
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>