<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; /* Exit if accessed directly */
}

/* load gallery settings */
if ( isset( $atts['show_filters'] ) ) {
	$ufg_show_filters = $atts['show_filters']; /* shortcode */
} else {
	if ( isset( $ufg_setting['show_filters'] ) ) {
		$ufg_show_filters = $ufg_setting['show_filters']; /* saved */
	} else {
		$ufg_show_filters = 1; /* default */
	}
}

if ( isset( $atts['show_filters_count'] ) ) {
	$ufg_show_filters_count = $atts['show_filters_count']; /* shortcode */
} else {
	if ( isset( $ufg_setting['show_filters_count'] ) ) {
		$ufg_show_filters_count = $ufg_setting['show_filters_count'];  /* saved */
	} else {
		$ufg_show_filters_count = 0; /* default */
	}
}

if ( isset( $atts['show_all_button'] ) ) {
	$ufg_show_all_button = $atts['show_all_button']; /* shortcode */
} else {
	if ( isset( $ufg_setting['show_all_button'] ) ) {
		$ufg_show_all_button = $ufg_setting['show_all_button'];  /* saved */
	} else {
		$ufg_show_all_button = 0; /* default */
	}
}

if ( isset( $atts['all_button_text'] ) ) {
	$ufg_all_button_text = $atts['all_button_text']; /* shortcode */
} else {
	if ( isset( $ufg_setting['all_button_text'] ) ) {
		$ufg_all_button_text = $ufg_setting['all_button_text'];  /* saved */
	} else {
		$ufg_all_button_text = __( 'All', 'filter-gallery' ); /* default */
	}
}

if ( isset( $atts['all_button_color'] ) ) {
	$ufg_all_button_color = $atts['all_button_color']; /* shortcode */
} else {
	if ( isset( $ufg_setting['all_button_bg_color'] ) ) {
		$ufg_all_button_color = $ufg_setting['all_button_color'];  /* saved */
	} else {
		$ufg_all_button_color = '#FFFFFF'; /* default */
	}
}

if ( isset( $atts['all_button_bg_color'] ) ) {
	$ufg_all_button_bg_color = $atts['all_button_bg_color']; /* shortcode */
} else {
	if ( isset( $ufg_setting['all_button_bg_color'] ) ) {
		$ufg_all_button_bg_color = $ufg_setting['all_button_bg_color'];  /* saved */
	} else {
		$ufg_all_button_bg_color = '#C82333'; /* default */
	}
}

if ( isset( $atts['parent_button_color'] ) ) {
	$ufg_parent_button_color = $atts['parent_button_color']; /* shortcode */
} else {
	if ( isset( $ufg_setting['parent_button_color'] ) ) {
		$ufg_parent_button_color = $ufg_setting['parent_button_color'];  /* saved */
	} else {
		$ufg_parent_button_color = '#FFFFFF'; /* default */
	}
}

if ( isset( $atts['parent_button_bg_color'] ) ) {
	$ufg_parent_button_bg_color = $atts['parent_button_bg_color']; /* shortcode */
} else {
	if ( isset( $ufg_setting['parent_button_bg_color'] ) ) {
		$ufg_parent_button_bg_color = $ufg_setting['parent_button_bg_color'];  /* saved */
	} else {
		$ufg_parent_button_bg_color = '#007BFF'; /* default */
	}
}

if ( isset( $atts['l1_button_color'] ) ) {
	$ufg_l1_button_color = $atts['l1_button_color']; /* shortcode */
} else {
	if ( isset( $ufg_setting['l1_button_color'] ) ) {
		$ufg_l1_button_color = $ufg_setting['l1_button_color'];  /* saved */
	} else {
		$ufg_l1_button_color = '#6C757D'; /* default */
	}
}

if ( isset( $atts['l1_button_bg_color'] ) ) {
	$ufg_l1_button_bg_color = $atts['l1_button_bg_color']; /* shortcode */
} else {
	if ( isset( $ufg_setting['l1_button_bg_color'] ) ) {
		$ufg_l1_button_bg_color = $ufg_setting['l1_button_bg_color'];  /* saved */
	} else {
		$ufg_l1_button_bg_color = '#0069D9'; /* default */
	}
}

if ( isset( $atts['l2_button_color'] ) ) {
	$ufg_l2_button_color = $atts['l2_button_color']; /* shortcode */
} else {
	if ( isset( $ufg_setting['l2_button_color'] ) ) {
		$ufg_l2_button_color = $ufg_setting['l2_button_color'];  /* saved */
	} else {
		$ufg_l2_button_color = '#FFFFFF'; /* default */
	}
}

if ( isset( $atts['l2_button_bg_color'] ) ) {
	$ufg_l2_button_bg_color = $atts['l2_button_bg_color']; /* shortcode */
} else {
	if ( isset( $ufg_setting['l2_button_bg_color'] ) ) {
		$ufg_l2_button_bg_color = $ufg_setting['l2_button_bg_color']; /* saved */
	} else {
		$ufg_l2_button_bg_color = '#DC3545'; /* default */
	}
}

if ( isset( $atts['l3_button_color'] ) ) {
	$ufg_l3_button_color = $atts['l3_button_color']; /* shortcode */
} else {
	if ( isset( $ufg_setting['l3_button_color'] ) ) {
		$ufg_l3_button_color = $ufg_setting['l3_button_color'];  /* saved */
	} else {
		$ufg_l3_button_color = '#FFFFFF'; /* default */
	}
}

if ( isset( $atts['l3_button_bg_color'] ) ) {
	$ufg_l3_button_bg_color = $atts['l3_button_bg_color']; /* shortcode */
} else {
	if ( isset( $ufg_setting['l3_button_bg_color'] ) ) {
		$ufg_l3_button_bg_color = $ufg_setting['l3_button_bg_color'];  /* saved */
	} else {
		$ufg_l3_button_bg_color = '#218838'; /* default */
	}
}

if ( isset( $atts['l4_button_color'] ) ) {
	$ufg_l4_button_color = $atts['l4_button_color']; /* shortcode */
} else {
	if ( isset( $ufg_setting['l4_button_color'] ) ) {
		$ufg_l4_button_color = $ufg_setting['l4_button_color'];  /* saved */
	} else {
		$ufg_l4_button_color = '#FFFFFF'; /* default */
	}
}

if ( isset( $atts['l4_button_bg_color'] ) ) {
	$ufg_l4_button_bg_color = $atts['l4_button_bg_color']; /* shortcode */
} else {
	if ( isset( $ufg_setting['l4_button_bg_color'] ) ) {
		$ufg_l4_button_bg_color = $ufg_setting['l4_button_bg_color'];  /* saved */
	} else {
		$ufg_l4_button_bg_color = '#000000'; /* default */
	}
}

if ( isset( $atts['l5_button_color'] ) ) {
	$ufg_l5_button_color = $atts['l5_button_color']; /* shortcode */
} else {
	if ( isset( $ufg_setting['l5_button_color'] ) ) {
		$ufg_l5_button_color = $ufg_setting['l5_button_color'];  /* saved */
	} else {
		$ufg_l5_button_color = '#FFFFFF'; /* default */
	}
}

if ( isset( $atts['l5_button_bg_color'] ) ) {
	$ufg_l5_button_bg_color = $atts['l5_button_bg_color']; /* shortcode */
} else {
	if ( isset( $ufg_setting['l5_button_bg_color'] ) ) {
		$ufg_l5_button_bg_color = $ufg_setting['l5_button_bg_color'];  /* saved */
	} else {
		$ufg_l5_button_bg_color = '#138496'; /* default */
	}
}

if ( isset( $atts['columns_desktop'] ) ) {
	$ufg_columns_desktop = $atts['columns_desktop']; /* shortcode */
} else {
	if ( isset( $ufg_setting['columns_desktop'] ) ) {
		$ufg_columns_desktop = $ufg_setting['columns_desktop'];  /* saved */
	} else {
		$ufg_columns_desktop = 4; /* default */
	}
}

if ( isset( $atts['columns_tab'] ) ) {
	$ufg_columns_tab = $atts['columns_tab']; /* shortcode */
} else {
	if ( isset( $ufg_setting['columns_tab'] ) ) {
		$ufg_columns_tab = $ufg_setting['columns_tab'];  /* saved */
	} else {
		$ufg_columns_tab = 4; /* default */
	}
}

if ( isset( $atts['columns_mobile_landscape'] ) ) {
	$ufg_columns_mobile_landscape = $atts['columns_mobile_landscape']; /* shortcode */
} else {
	if ( isset( $ufg_setting['columns_mobile_landscape'] ) ) {
		$ufg_columns_mobile_landscape = $ufg_setting['columns_mobile_landscape'];  /* saved */
	} else {
		$ufg_columns_mobile_landscape = 3; /* default */
	}
}

if ( isset( $atts['columns_mobile_portrait'] ) ) {
	$ufg_columns_mobile_portrait = $atts['columns_mobile_portrait']; /* shortcode */
} else {
	if ( isset( $ufg_setting['columns_mobile_portrait'] ) ) {
		$ufg_columns_mobile_portrait = $ufg_setting['columns_mobile_portrait'];  /* saved */
	} else {
		$ufg_columns_mobile_portrait = 3; /* default */
	}
}

if ( isset( $atts['thumbnail_image_size'] ) ) {
	$ufg_thumbnail_image_size = $atts['thumbnail_image_size']; /* shortcode */
} else {
	if ( isset( $ufg_setting['thumbnail_image_size'] ) ) {
		$ufg_thumbnail_image_size = $ufg_setting['thumbnail_image_size'];  /* saved */
	} else {
		$ufg_thumbnail_image_size = 'large'; /* default */
	}
}

if ( isset( $atts['thumbnail_border'] ) ) {
	$ufg_thumbnail_border = $atts['thumbnail_border']; /* shortcode */
} else {
	if ( isset( $ufg_setting['thumbnail_border'] ) ) {
		$ufg_thumbnail_border = $ufg_setting['thumbnail_border'];  /* saved */
	} else {
		$ufg_thumbnail_border = 0; /* default */
	}
}

if ( isset( $atts['thumbnail_border_thickness'] ) ) {
	$ufg_thumbnail_border_thickness = $atts['thumbnail_border_thickness']; /* shortcode */
} else {
	if ( isset( $ufg_setting['thumbnail_border_thickness'] ) ) {
		$ufg_thumbnail_border_thickness = $ufg_setting['thumbnail_border_thickness'];  /* saved */
	} else {
		$ufg_thumbnail_border_thickness = 0; /* default */
	}
}

if ( isset( $atts['thumbnail_border_color'] ) ) {
	$ufg_thumbnail_border_color = $atts['thumbnail_border_color']; /* shortcode */
} else {
	if ( isset( $ufg_setting['thumbnail_border_color'] ) ) {
		$ufg_thumbnail_border_color = $ufg_setting['thumbnail_border_color'];  /* saved */
	} else {
		$ufg_thumbnail_border_color = '#0069D9'; /* default */
	}
}

if ( isset( $atts['image_title'] ) ) {
	$ufg_image_title = $atts['image_title']; /* shortcode */
} else {
	if ( isset( $ufg_setting['image_title'] ) ) {
		$ufg_image_title = $ufg_setting['image_title']; /* saved */
	} else {
		$ufg_image_title = 0; /* default */
	}
}

if ( isset( $atts['image_title_font_size'] ) ) {
	$ufg_image_title_font_size = $atts['image_title_font_size']; /* shortcode */
} else {
	if ( isset( $ufg_setting['image_title_font_size'] ) ) {
		$ufg_image_title_font_size = $ufg_setting['image_title_font_size']; /* saved */
	} else {
		$ufg_image_title_font_size = 45; /* default */
	}
}

if ( isset( $atts['image_title_color'] ) ) {
	$ufg_image_title_color = $atts['image_title_color']; /* shortcode */
} else {
	if ( isset( $ufg_setting['image_title_color'] ) ) {
		$ufg_image_title_color = $ufg_setting['image_title_color']; /* saved */
	} else {
		$ufg_image_title_color = '#FFFFFF'; /* default */
	}
}

if ( isset( $atts['image_title_bg_color'] ) ) {
	$ufg_image_title_bg_color = $atts['image_title_bg_color']; /* shortcode */
} else {
	if ( isset( $ufg_setting['image_title_bg_color'] ) ) {
		$ufg_image_title_bg_color = $ufg_setting['image_title_bg_color']; /* saved */
	} else {
		$ufg_image_title_bg_color = '#000000'; /* default */
	}
}

if ( isset( $atts['image_sorting'] ) ) {
	$ufg_image_sorting = $atts['image_sorting']; /* shortcode */
} else {
	if ( isset( $ufg_setting['image_sorting'] ) ) {
		$ufg_image_sorting = $ufg_setting['image_sorting']; /* saved */
	} else {
		$ufg_image_sorting = 4; /* default */
	}
}

if ( isset( $atts['lightbox'] ) ) {
	$ufg_lightbox = $atts['lightbox']; /* shortcode */
} else {
	if ( isset( $ufg_setting['lightbox'] ) ) {
		$ufg_lightbox = $ufg_setting['lightbox']; /* saved */
	} else {
		$ufg_lightbox = 1; /* default */
	}
}

if ( isset( $atts['lightbox_title'] ) ) {
	$ufg_lightbox_title = $atts['lightbox_title']; /* shortcode */
} else {
	if ( isset( $ufg_setting['lightbox_title'] ) ) {
		$ufg_lightbox_title = $ufg_setting['lightbox_title']; /* saved */
	} else {
		$ufg_lightbox_title = 1; /* default */
	}
}