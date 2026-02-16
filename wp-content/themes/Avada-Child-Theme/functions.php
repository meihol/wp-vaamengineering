<?php

function theme_enqueue_styles() {
    wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', [] );
}
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles', 20 );

function avada_lang_setup() {
	$lang = get_stylesheet_directory() . '/languages';
	load_child_theme_textdomain( 'Avada', $lang );
}
add_action( 'after_setup_theme', 'avada_lang_setup' );

// Just Load The Template Part
function load_template_part($template_name, $part_name = null, $data = null) {
    ob_start();
    get_template_part($template_name, $part_name, $data);
    $var = ob_get_contents();
    ob_end_clean();
    return $var;
}

require "customization.php";