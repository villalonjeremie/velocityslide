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
/* Declaring the theme language domain (for language translations)
/*-----------------------------------------------------------------------------------*/

//load_theme_textdomain('velocityslide', get_template_directory().'/lang');

/*-----------------------------------------------------------------------------------*/
/* Register & Enqueue JS and CSS
/*-----------------------------------------------------------------------------------*/


function gt_queue_assets()
{
    $data = get_option("velocityslide_options");

    // Enqueue Scripts (Global)
    wp_enqueue_script('modernizr', get_template_directory_uri() . '/assets/js/lib/modernizr-custom.js', false);
    wp_enqueue_script('lib', get_template_directory_uri() . '/assets/js/lib.js', false);
    wp_enqueue_script('js-test', get_template_directory_uri() . '/assets/js/js-test.js', false);
    wp_enqueue_script('ie', get_template_directory_uri() . '/assets/js/ie.js', false);
    wp_enqueue_style('main', get_template_directory_uri() . '/assets/css/main.css', false);
    wp_enqueue_script('options', get_template_directory_uri() .'/assets/css/dynamic-css/option.css', false);
    wp_enqueue_script('jcarousel', get_template_directory_uri() .'/assets/css/jcarousel.css', false);
    wp_enqueue_script('jcarousel-popup', get_template_directory_uri() .'/assets/css/jcarousel-popup.css', false);
    wp_enqueue_script('jquery-migrate-1.2.1', get_template_directory_uri() .'/assets/js/jquery-migrate-1.2.1.js', false);
    wp_enqueue_script('initialize-jcarousel.js', get_template_directory_uri() .'/assets/js/carousel/initialize-jcarousel.js', false);
    wp_enqueue_script('jcarousel.js', get_template_directory_uri() .'/assets/js/carousel/jcarousel.js', false);
    wp_enqueue_script('jcarousel-vision-init.js', get_template_directory_uri() .'/assets/js/carousel/jcarousel-vision-init.js', false);

}

add_action("init", "gt_queue_assets");

/*-----------------------------------------------------------------------------------*/
/* Declaring the theme language domain (for language translations)
/*-----------------------------------------------------------------------------------*/

load_theme_textdomain('kula', get_template_directory().'/lang');

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

/*-----------------------------------------------------------------------------------*/
/* Setup custom Metaboxes
/*-----------------------------------------------------------------------------------*/

require_once(get_template_directory() . '/functions/theme-servicesmeta.php');

/*-----------------------------------------------------------------------------------*/
/* Shortcodes
/*-----------------------------------------------------------------------------------*/

require_once(get_template_directory() . '/functions/shortcodes.php');

/*-----------------------------------------------------------------------------------*/
/* Custom Theme Functions
/*-----------------------------------------------------------------------------------*/

require_once(get_template_directory() . '/functions/theme-functions.php');

