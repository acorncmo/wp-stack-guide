<?php
/**
 * Theme functions -- Timber + Twig + Tailwind
 *
 * This file initializes Timber, registers menus, enqueues styles,
 * and defines the global context available in every Twig template.
 */

// Load Timber via Composer autoload.
if ( ! file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
    wp_die( 'Timber not found. Run <code>composer install</code> in the theme directory.' );
}
require_once __DIR__ . '/vendor/autoload.php';

use Timber\Timber;
use Timber\Site;

// Initialize Timber.
Timber::init();

/**
 * Global context -- data available in EVERY Twig template.
 */
add_filter( 'timber/context', function( $context ) {
    $context['site']    = new Site();

    // Navigation menus (registered below)
    $context['nav_primary'] = Timber::get_menu( 'primary' );
    $context['nav_footer']  = Timber::get_menu( 'footer' );

    // Useful globals
    $context['site_url']  = home_url();
    $context['theme_uri'] = get_template_directory_uri();

    return $context;
} );

/**
 * Theme setup: menus, thumbnails, HTML5 support.
 */
add_action( 'after_setup_theme', function() {
    register_nav_menus( [
        'primary' => __( 'Primary Navigation', 'my-theme' ),
        'footer'  => __( 'Footer Navigation', 'my-theme' ),
    ] );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'html5', [
        'search-form', 'comment-form', 'comment-list',
        'gallery', 'caption',
    ] );
} );

/**
 * Enqueue styles and scripts.
 */
add_action( 'wp_enqueue_scripts', function() {
    $theme_uri = get_template_directory_uri();
    $version   = wp_get_theme()->get( 'Version' );

    // Tailwind compiled CSS
    wp_enqueue_style( 'theme-styles',
        $theme_uri . '/assets/css/app.css', [], $version
    );
} );

/**
 * Tell Timber where to find Twig templates.
 */
add_filter( 'timber/locations', function( $paths ) {
    $paths[] = get_template_directory() . '/templates';
    return $paths;
} );

/**
 * Include additional theme files.
 * Uncomment as you add them.
 */
// require_once __DIR__ . '/inc/blocks.php';
// require_once __DIR__ . '/inc/admin-settings.php';

/** Allow SVG uploads. */
add_filter( 'upload_mimes', function( $mimes ) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
} );
