<!doctype html>
<html <?php language_attributes(); ?> class="no-js">
    <head>

        <meta charset="utf-8">

        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        
        <title><?php bloginfo('name'); ?> | <?php is_front_page() ? bloginfo('description') : wp_title(''); ?></title>
        
        <meta name="description" content="<?php bloginfo('description'); ?>">

        <meta name="viewport" content="width=1024, user-scalable=no, minimal-ui">

        <meta name="apple-mobile-web-app-capable" content="yes">

        <meta name="apple-mobile-web-app-status-bar-style" content="black">

        <!--[if lt IE 8]>
            <script src="<?php echo get_template_directory_uri(); ?>/assets/js/css/ie.js"></script>
        <![endif]-->

    </head>
    <body>

        <header>
                <div class="header-logo eight columns">
                           <?php global $data;?> 
                        <?php if ($data['text_logo']) : ?>
                            <div id="logo-default"><a href="<?php echo home_url(); ?>" title="<?php bloginfo('name'); ?>"><?php bloginfo('name'); ?></a></div>
                        <?php elseif ($data['custom_logo']) : ?>
                            <div id="logo"><a href="<?php echo home_url(); ?>" title="<?php bloginfo('name'); ?>"><img src="<?php echo $data['custom_logo']; ?>" alt="Header Logo" /></a></div>
                        <?php endif; ?>

                </div><!-- end .header-logo -->
        </header>
    