<?php

add_action( 'wp_enqueue_scripts', 'perfect_portfolio_parent_theme_enqueue_styles' );

function perfect_portfolio_parent_theme_enqueue_styles() {
    wp_enqueue_style( 'perfect-portfolio-style', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'code_mgd-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( 'perfect-portfolio-style' )
    );

}
