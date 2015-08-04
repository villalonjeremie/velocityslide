<?php

if ( function_exists( 'add_theme_support' ) ) {
    add_theme_support( 'post-thumbnails' );
    set_post_thumbnail_size( 250, 250, true );
}

add_action('init', 'portfolio_register');  

function portfolio_register() {
    $labels = array(
        'name' => __('Portfolio', 'velocityslide'),
        'add_new' => __('Add New', 'velocityslide'),
        'add_new_item' => __('Add New Portfolio Item', 'velocityslide'),
        'edit_item' => __('Edit Portfolio Item', 'velocityslide'),
        'new_item' => __('New Portfolio Item', 'velocityslide'),
        'view_item' => __('View Portfolio Item', 'velocityslide'),
        'search_items' => __('Search Portfolio Items', 'velocityslide'),
        'not_found' => __('No items found', 'velocityslide'),
        'not_found_in_trash' => __('No items found in Trash', 'velocityslide'), 
        'parent_item_colon' => '',
        'menu_name' => 'Portfolio'
        );
    
    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'rewrite' => array('slug' => 'project', 'with_front' => false),
        'supports' => array('title', 'editor', 'thumbnail')
       );  

    register_post_type( 'portfolio' , $args );
}

add_action('contextual_help', 'portfolio_help_text', 10, 3);
function portfolio_help_text($contextual_help, $screen_id, $screen) {
    if ('portfolio' == $screen->id) {
        $contextual_help =
        '<h3>' . __('Things to remember when adding a Portfolio item:', 'velocityslide') . '</h3>' .
        '<ul>' .
        '<li>' . __('Give the item a title. The title will be used as the item\'s headline.', 'velocityslide') . '</li>' .
        '<li>' . __('Enter your portfolio image overview into the thumbnail area. The text will appear for your project overview.', 'velocityslide') . '</li>' .
        '</ul>';
    }
    elseif ('edit-portfolio' == $screen->id) {
        $contextual_help = '<p>' . __('A list of all Portfolio items appear below. To edit an item, click on the items\'s title.', 'kula') . '</p>';
    }
    return $contextual_help;
}

function enable_portfolio_sort() {
    add_submenu_page('edit.php?post_type=portfolio', 'Sort Portfolio', 'Sort Portfolio Items', 'edit_posts', basename(__FILE__), 'sort_portfolio');
}
add_action('admin_menu' , 'enable_portfolio_sort'); 
 
function sort_portfolio() {
    $portfolios = new WP_Query('post_type=portfolio&posts_per_page=-1&orderby=menu_order&order=ASC');
?>
    <div class="wrap">
    <div id="icon-tools" class="icon32"><br /></div>
    <h2><?php _e('Sort Portfolio Items', 'kula'); ?> <img src="<?php echo home_url(); ?>/wp-admin/images/loading.gif" id="loading-animation" /></h2>
    <p><?php _e('Click, drag, re-order. Repeat as neccessary. Portfolio item at the top will appear first on your page.', 'kula'); ?></p>
    <ul id="post-list">
    <?php while ( $portfolios->have_posts() ) : $portfolios->the_post(); ?>
        <li id="<?php the_id(); ?>"><?php the_title(); ?></li>          
    <?php endwhile; ?>
    </div>
 
<?php
}

function portfolio_print_scripts() {
    global $pagenow;
 
    $pages = array('edit.php');
    if (in_array($pagenow, $pages)) {
        wp_enqueue_script('jquery-ui-sortable');
        wp_enqueue_script('portfolio-sorter', get_template_directory_uri().'/assets/js/jquery.posttype.sort.js');
    }
}
add_action( 'admin_print_scripts', 'portfolio_print_scripts' );

function portfolio_print_styles() {
    global $pagenow;
 
    $pages = array('edit.php');
    if (in_array($pagenow, $pages))
        wp_enqueue_style('style', get_template_directory_uri('template_url').'/assets/css/custom-admin.css');
}
add_action( 'admin_print_styles', 'portfolio_print_styles' ); 
 
function portfolio_save_order() {
    global $wpdb;
 
    $order = explode(',', $_POST['order']);
    $counter = 0;
 
    foreach ($order as $post_id) {
        $wpdb->update($wpdb->posts, array( 'menu_order' => $counter ), array( 'ID' => $post_id) );
        $counter++;
    }
    die(1);
}
add_action('wp_ajax_post_sort', 'portfolio_save_order');

?>