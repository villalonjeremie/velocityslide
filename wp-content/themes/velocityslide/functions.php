<?php
add_action('widgets_init','vs_add_sidebar');


function vs_add_sidebar()
{
  register_sidebar(array(
    'id' => 'right_widget',
    'name' => 'widget',
    'description' => 'right area on single pos',
    'before_widget' => '<aside>',
    'after_widget' => '</aside>',
    'before_title' => '<h1>',
    'after_title' => '</h1>'
  ));
}


add_action('init', 'vs_add_menu');

function vs_add_menu()
{
  register_nav_menu('main_menu', 'Main Menu');
}



/*-----------------------------------------------------------------------------------*/
/* Remove menu && submenu
/*-----------------------------------------------------------------------------------*/
add_action( 'admin_menu', 'remove_menu_pages' );
function remove_menu_pages() {
    remove_menu_page('edit.php?post_type=page');
}

add_action( 'admin_menu', 'adjust_the_wp_menu', 999 );
function adjust_the_wp_menu() {
    remove_submenu_page( 'themes.php', 'nav-menus.php' );
    remove_submenu_page( 'themes.php', 'customize.php?return=%2Fwp-admin%2Fthemes.php%3Factivated%3Dtrue' );
}


/*-----------------------------------------------------------------------------------*/
/* Add support, and configure Thumbnails (for WordPress 2.9+)
/*-----------------------------------------------------------------------------------*/

if ( function_exists('add_theme_support') ) {
    add_theme_support('post-thumbnails');
    set_post_thumbnail_size(200, 200, true); // Normal post thumbnails
    add_image_size('large', 632, 290, true); // Large thumbnails
    add_image_size('small', 125, '', true); // Small thumbnails
    add_image_size('article-thumb', 234, 234, true); // Latest News Thumbnail (appears on the homepage)
    add_image_size('single-post', 980, 523, true); // Large Post Thumbnail (appears on single post)
    add_image_size('archive-post', 980, 523, true); // Large Post Thumbnail (appears on archive pages)
}

/*-----------------------------------------------------------------------------------*/
/* Register & Enqueue JS and CSS
/*-----------------------------------------------------------------------------------*/


function vs_queue_assets()
{
    $data = get_option("velocityslide_options");
    // Enqueue Scripts (Global)
    wp_enqueue_script('modernizr', get_template_directory_uri() . '/assets/js/lib/modernizr-custom.js', false);
    wp_enqueue_script('lib', get_template_directory_uri() . '/assets/js/lib.js', false);
    wp_enqueue_script('js-test', get_template_directory_uri() . '/assets/js/js-test.js', false);
    wp_enqueue_script('ie', get_template_directory_uri() . '/assets/js/ie.js', false);
    wp_enqueue_style('main', get_template_directory_uri() . '/assets/css/main.css', false);
    wp_enqueue_script('options', get_template_directory_uri() .'/assets/css/dynamic-css/option.css', false);
    wp_enqueue_style('jcarousel', get_template_directory_uri() .'/assets/css/jcarousel.css', false);
    wp_enqueue_style('jcarousel', get_template_directory_uri() .'/assets/css/jcarousel.css', false);
    wp_enqueue_style('jcarousel-popup', get_template_directory_uri() .'/assets/css/jcarousel-popup.css', false);
    wp_enqueue_style('jcarousel-article', get_template_directory_uri() .'/assets/css/jcarousel-article.css', false);
    wp_enqueue_script('jquery-migrate-1.2.1', get_template_directory_uri() .'/assets/js/jquery-migrate-1.2.1.js', false);
    wp_enqueue_script('initialize-jcarousel.js', get_template_directory_uri() .'/assets/js/carousel/initialize-jcarousel.js', false);
    wp_enqueue_script('jcarousel.js', get_template_directory_uri() .'/assets/js/carousel/jcarousel.js', false);
    wp_enqueue_script('jcarousel-vision-init.js', get_template_directory_uri() .'/assets/js/carousel/jcarousel-vision-init.js', false);
}

add_action("init", "vs_queue_assets");

/*-----------------------------------------------------------------------------------*/
/* Declaring the theme language domain (for language translations)
/*-----------------------------------------------------------------------------------*/

load_theme_textdomain('velocityslide', get_template_directory().'/lang');

/*-----------------------------------------------------------------------------------*/
/* Slightly Modified Options Framework (SMOF)
/*-----------------------------------------------------------------------------------*/

require_once(get_template_directory() . '/admin/index.php');

/*-----------------------------------------------------------------------------------*/
/* Call Widget Flickr
/*-----------------------------------------------------------------------------------*/

require_once(get_template_directory() . '/functions/widgets/widget-flickr.php');

/*-----------------------------------------------------------------------------------*/
/* Call Custom Post Types
/*-----------------------------------------------------------------------------------*/

require_once(get_template_directory() . '/functions/custom-post-types/services-type.php');
require_once(get_template_directory() . '/functions/custom-post-types/portfolio-type.php');


/*-----------------------------------------------------------------------------------*/
/* Setup custom Metaboxes
/*-----------------------------------------------------------------------------------*/

require_once(get_template_directory() . '/functions/theme-servicesmeta.php');
require_once(get_template_directory() . '/functions/theme-portfoliometa.php');
require_once(get_template_directory() . '/functions/theme-articlemeta.php');

/*-----------------------------------------------------------------------------------*/
/* Shortcodes
/*-----------------------------------------------------------------------------------*/

require_once(get_template_directory() . '/functions/shortcodes.php');

/*-----------------------------------------------------------------------------------*/
/* Custom Theme Functions
/*-----------------------------------------------------------------------------------*/

require_once(get_template_directory() . '/functions/theme-functions.php');

