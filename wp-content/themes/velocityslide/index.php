<?php
/**
 *
 * Description: Main Homepage template.
 *
 */
get_header();?>
<div class="powerslide slider-wrapper">

    <div class="slider">

        <div class="slide slide--0" data-slideTitle="<?php echo (($data["name_layout_homepage"]) ? $data["name_layout_homepage"] : 'Homepage'); ?>">

            <div class="slide-content">

                <h2 class="logo">
                    <a href="#" class="js-external-link">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/img/logo-hr.png" alt="Helena Rubinstein">
                    </a>
                </h2>

                <div class="slide__content__right">

                    <div class="s-table">

                        <div class="s-table-cell">

                            <?php if (isset($data["title_homepage"])) : ?>
                                <h1>
                                    <?php echo $data['title_homepage']; ?>
                                </h1>
                            <?php endif; ?>

                            <?php if (isset($data["subtitle_homepage"])) : ?>
                                <h3>
                                    <?php echo $data['subtitle_homepage']; ?>
                                </h3>
                            <?php endif; ?>

                            <?php if (isset($data["text_homepage"])) : ?>
                                <p>
                                    <?php echo $data['text_homepage']; ?>
                                </p>
                            <?php endif; ?>

                            <?php if (isset($data["text_button_homepage"]) || isset($data["url_button_homepage"])) : ?>
                                <a href="<?php echo $data['url_button_homepage']; ?>" class="js-external-link button" title="<?php echo $data['text_button_homepage']; ?>"><?php echo $data['text_button_homepage']; ?></a>
                            <?php endif; ?>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <?php
        $layout = $data['blocks_homepage']['enabled'];?>
        <?php if ($layout):
            foreach ($layout as $key=>$value) {
            switch($key) {
                case 'presentation': ?>

                <div class="slide slide--split slide--1 effect" data-slideTitle="<?php echo (($data["name_layout_presentation"]) ? $data["name_layout_presentation"] : 'Presentation'); ?>" data-slideTransition="split">

                    <div class="slide--split__left">

                        <div class="s-table">

                            <div class="s-table-cell">

                                <div class="box">

                                    <div class="container">

                                        <div class="s-table">

                                            <div class="s-table-cell">

                                                <?php if (isset($data["left_content_presentation"])) : ?>
                                                    <?php if (isset($data["left_title_presentation"])) : ?>
                                                        <h2>
                                                            <?php echo $data['left_title_presentation']; ?>
                                                        </h2>
                                                    <?php endif; ?>

                                                    <?php if (isset($data["left_subtitle_presentation"])) : ?>

                                                        <h3>
                                                            <?php echo $data['left_subtitle_presentation']; ?>
                                                        </h3>
                                                    <?php endif; ?>

                                                    <?php if (isset($data["left_description_presentation"])) : ?>
                                                        <p>
                                                            <?php echo $data['left_description_presentation']; ?>
                                                        </p>
                                                    <?php endif; ?>
                                                <?php endif; ?>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div><!-- end .slide--split__left -->

                    <div class="slide--split__right">

                        <div class="s-table">

                            <div class="s-table-cell">

                                <div class="box">

                                    <div class="container">

                                        <div class="s-table">

                                            <div class="s-table-cell">

                                                <?php if (isset($data["right_content_presentation"])) : ?>
                                                    <?php if (isset($data["right_title_presentation"])) : ?>
                                                        <h2>
                                                            <?php echo $data['right_title_presentation']; ?>
                                                        </h2>
                                                    <?php endif; ?>

                                                    <?php if (isset($data["right_subtitle_presentation"])) : ?>
                                                        <h3>
                                                            <?php echo $data['right_subtitle_presentation']; ?>
                                                        </h3>
                                                    <?php endif; ?>

                                                    <?php if (isset($data["right_description_presentation"])) : ?>
                                                        <p>
                                                            <?php echo $data['right_description_presentation']; ?>
                                                        </p>
                                                    <?php endif; ?>
                                                <?php endif; ?>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div><!-- end .slide--split__right -->

                </div><!-- end .slide--1 -->

                <?php break;
                case 'service': ?>

                <div class="slide slide--split slide--2" data-slideTitle="<?php echo (($data["name_layout_service"]) ? $data["name_layout_service"] : 'Services'); ?>" data-slideTransition="split">
                    <?php global $data;
                    $args = array('post_type' => 'service', 'orderby' => 'menu_order', 'order' => 'ASC','posts_per_page' => -1);
                    $loop = new WP_Query($args); ?>

                    <div class="slide--split__left">

                        <div class="s-table">

                            <div class="s-table-cell">

                                <div class="box">

                                    <div class="container">

                                        <div class="s-table">

                                            <div class="s-table-cell">

                                                <?php if ($loop->have_posts()) : ?>
                                                    <?php while ($loop->have_posts()) : $loop->the_post(); ?>
                                                        <?php $side = do_shortcode(get_post_meta($post->ID, 'vs_service_side', $single = true)); ?>
                                                        <?php if ($side=='Left') : ?>

                                                            <div class="service one-third column">
                                                                <?php echo do_shortcode(get_post_meta($post->ID, 'vs_service_icon', $single = true)); ?>

                                                                <h2>
                                                                    <?php the_title(); ?>
                                                                </h2>

                                                                <p>
                                                                    <?php the_content(); ?>
                                                                </p>

                                                                <?php if (do_shortcode(get_post_meta($post->ID, 'vs_service_url', $single = true))) : ?>
                                                                    <a class="read-more-btn" href="<?php echo do_shortcode(get_post_meta($post->ID, 'vs_service_url', $single = true)); ?>"><?php _e('Read more', 'velocityslide'); ?> <span>&rarr;</span></a>
                                                                <?php endif; ?>
                                                            </div><!-- end .service -->

                                                        <?php endif; ?>
                                                    <?php endwhile; ?>
                                                <?php endif; ?>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div><!-- end .slide--split__left-->
                    <div class="slide--split__right">

                        <div class="s-table">

                            <div class="s-table-cell">

                               <div class="box">

                                    <div class="container">

                                        <div class="s-table">

                                            <div class="s-table-cell">

                                                <?php if ($loop->have_posts()) : ?>
                                                    <?php while ($loop->have_posts()) : $loop->the_post(); ?>
                                                    <?php $side = do_shortcode(get_post_meta($post->ID, 'vs_service_side', $single = true)); ?>
                                                        <?php if ($side=='Right'): ?>

                                                            <div class="service one-third column">
                                                                <?php echo do_shortcode(get_post_meta($post->ID, 'vs_service_icon', $single = true)); ?>

                                                                <h2>
                                                                    <?php the_title(); ?>
                                                                </h2>

                                                                <p>
                                                                    <?php the_content(); ?>
                                                                </p>

                                                                <?php if (do_shortcode(get_post_meta($post->ID, 'vs_service_url', $single = true))): ?>
                                                                    <a class="read-more-btn" href="<?php echo do_shortcode(get_post_meta($post->ID, 'vs_service_url', $single = true)); ?>"><?php _e('Read more', 'velocityslide'); ?> <span>&rarr;</span></a>
                                                                <?php endif; ?>

                                                            </div><!-- end .service -->

                                                        <?php endif; ?>
                                                    <?php endwhile; ?>
                                                <?php endif; ?>

                                            </div>

                                        </div>

                                    </div>

                               </div>

                            </div>

                        </div>

                    </div><!-- end .slide--split__right-->

                </div><!-- end .slide--2 -->

                <?php break;
                case 'portfolio':?>

                <div class="slide slide--split slide--3" data-slideTitle="<?php echo (($data["name_layout_portfolio"]) ? $data["name_layout_portfolio"] : 'Portfolios'); ?>" data-slideTransition="split">

                    <div class="slide--split__left">

                        <div class="s-table">

                            <div class="s-table-cell">

                                <?php if ($data['left_content_portfolio']) : ?>

                                    <?php if ($data['left_title_portfolio']) : ?>
                                        <h2>
                                            <?php echo $data['left_title_portfolio']; ?>
                                        </h2>
                                    <?php endif; ?>
                                    <?php if ($data['left_description_portfolio']) : ?>
                                        <p>
                                            <?php echo $data['left_description_portfolio']; ?>
                                        </p>
                                    <?php endif; ?>

                                <?php endif; ?>

                                <?php if ($data['side_left_popup_switch']): ?>
                                    <a href="#popin-slide3-1" class="window-button js-window-open" title="<?php echo $data['left_text_button_portfolio']; ?>">
                                        <?php echo $data['left_text_button_portfolio']; ?>
                                    </a>
                                <?php endif; ?>

                            </div>

                        </div>

                    </div><!-- end .slide--split__left -->

                    <div class="slide--split__right">

                        <div class="s-table">

                            <div class="s-table-cell">

                                <?php if ($data['right_content_portfolio']) : ?>

                                    <?php if ($data['right_title_portfolio']) : ?>
                                    <h2>
                                        <?php echo $data['right_title_portfolio']; ?>
                                    </h2>
                                    <?php endif; ?>

                                    <?php if ($data['right_description_portfolio']) : ?>
                                    <p>
                                        <?php echo $data['right_description_portfolio']; ?>
                                    </p>
                                    <?php endif; ?>
                                <?php endif; ?>

                                <?php if ($data['side_right_popup_switch']) : ?>
                                    <a href="#popin-slide3-2" class="window-button js-window-open" title="<?php echo $data['right_text_button_portfolio']; ?>">
                                        <?php echo $data['right_text_button_portfolio']; ?>
                                    </a>
                                <?php endif; ?>

                            </div>

                        </div>

                    </div><!-- end .slide--split__right -->

                    <div class="window hide-window popin-slide3-1" id="popin-slide3-1">
                        <?php global $data;
                        $args = array('post_type' => 'portfolio', 'orderby' => 'menu_order', 'order' => 'ASC','posts_per_page' => -1);
                        $loop = new WP_Query($args); ?>

                        <div class="window-close popin-close-overay"></div>

                        <div class="container-inner">

                            <div class="popin-content">

                                <a href="#" class="window-close popin-close">Close</a>

                                <?php if ($data['left_title_popup_portfolio']) : ?>
                                    <h3>
                                        <?php echo $data['left_title_popup_portfolio']; ?><span>.</span>
                                    </h3>
                                <?php endif; ?>

                                <?php if ($data['left_description_popup_portfolio']) : ?>
                                    <p>
                                        <?php echo do_shortcode(stripslashes($data['left_description_popup_portfolio'])); ?>
                                    </p>
                                <?php endif; ?>

                                <div class="jcarousel-wrapper">

                                    <div class="jcarousel" data-options="{'itemPerPage' : [5], 'control': {'target': 5}}">

                                        <ul class="jcarousel-list">

                                            <?php if($loop->have_posts()) : ?>
                                                <?php while ($loop->have_posts()) : ?>
                                                    <?php $loop->the_post(); ?>
                                                    <?php $side = do_shortcode(get_post_meta($loop->post->ID, 'vs_portfolio_side', $single = true)); ?>
                                                    <?php if($side=='Left'): ?>
                                                    <?php
                                                    $terms =  get_the_terms( $loop->post->ID, 'project-type' );
                                                    $term_list = '';
                                                    if( is_array($terms) ) {
                                                        foreach( $terms as $term ) {
                                                            $term_list .= urldecode($term->slug);
                                                            $term_list .= ' ';
                                                        }
                                                    }
                                                    ?>
                                                    <li <?php post_class("$term_list item"); ?> id="post-<?php the_ID(); ?>">
                                                        <?php the_post_thumbnail('portfolio-thumb'); ?>
                                                    </li>

                                                    <?php endif; ?>
                                                <?php endwhile; ?>
                                            <?php endif; ?>

                                        </ul>

                                    </div>

                                    <a href="#" class="jcarousel-control-prev">&lsaquo;</a>

                                    <a href="#" class="jcarousel-control-next">&rsaquo;</a>

                                    <p class="jcarousel-pagination" data-jcarouselpagination="true"></p>

                                </div>

                            </div>

                        </div>

                    </div><!-- end .popin-slide3-1 -->

                    <div class="window hide-window popin-slide3-2" id="popin-slide3-2">

                        <div class="window-close popin-close-overay"></div>

                        <div class="container-inner">

                            <div class="popin-content">

                                <a href="#" class="window-close popin-close">Close</a>

                                <?php if ($data['right_title_popup_portfolio']) : ?>
                                <h3>
                                    <?php echo $data['right_title_popup_portfolio']; ?><span>.</span>
                                </h3>
                                <?php endif; ?>

                                <?php if ($data['right_description_popup_portfolio']) : ?>
                                    <p>
                                        <?php echo do_shortcode(stripslashes($data['right_description_popup_portfolio'])); ?>
                                    </p>
                                <?php endif; ?>

                                <div class="jcarousel-wrapper">

                                    <div class="jcarousel" data-options="{'itemPerPage' : [5], 'control': {'target': 5}}">

                                        <ul class="jcarousel-list">

                                            <?php if($loop->have_posts()) : ?>
                                                <?php while ($loop->have_posts()) : $loop->the_post(); ?>
                                                    <?php $side = do_shortcode(get_post_meta($loop->post->ID, 'vs_portfolio_side', $single = true)); ?>
                                                    <?php if($side=='Right'): ?>
                                                    <?php
                                                    $terms =  get_the_terms( $loop->post->ID, 'project-type' );
                                                    $term_list = '';
                                                        if( is_array($terms) ) {
                                                        foreach( $terms as $term ) {
                                                            $term_list .= urldecode($term->slug);
                                                            $term_list .= ' ';
                                                        }
                                                    }
                                                    ?>

                                                    <li <?php post_class("$term_list item"); ?> id="post-<?php the_ID(); ?>">
                                                        <?php the_post_thumbnail(); ?>
                                                    </li>

                                                    <?php endif; ?>
                                                <?php endwhile; ?>
                                            <?php endif; ?>
                                        </ul>

                                    </div>

                                    <a href="#" class="jcarousel-control-prev">&lsaquo;</a>

                                    <a href="#" class="jcarousel-control-next">&rsaquo;</a>

                                    <p class="jcarousel-pagination" data-jcarouselpagination="true"></p>

                                </div>

                            </div>

                        </div>

                    </div><!-- end .popin-slide3-2 -->

                </div><!-- end .slide3-->

                <?php break;
                case 'article':?>

                <div class="slide slide--split slide--4" data-slideTitle="<?php echo (($data["name_layout_article"]) ? $data["name_layout_article"] : 'Articles'); ?>" data-slideTransition="split">
                    <?php
                    global $data; $args = array('post_type' => 'post', 'posts_per_page' => -1);
                    $loop = new WP_Query($args); ?>

                    <div class="slide--split__left">

                        <div class="s-table">

                            <div class="s-table-cell">

                                <?php if ($data['left_post_switch']) : ?>

                                    <div class="jcarousel-wrapper jcarousel-article">

                                        <div class="jcarousel" data-options="{'itemPerPage' : [1], 'control': {'target': 1}}">

                                            <ul class="jcarousel-list">

                                                <?php if($loop->have_posts()) : ?>
                                                    <?php while ($loop->have_posts()) : ?>
                                                        <?php $loop->the_post(); ?>
                                                        <?php $side = do_shortcode(get_post_meta($loop->post->ID, 'vs_article_side', $single = true)); ?>
                                                        <?php if($side=='Left'): ?>

                                                            <li class="item-article">

                                                                <article class="article one-third column">

                                                                    <div class="thumbnail">
                                                                        <?php the_post_thumbnail('large'); ?>
                                                                    </div>

                                                                    <h4>
                                                                        <a href="<?php the_permalink() ?>">
                                                                            <?php the_title(); ?><span>.</span>
                                                                        </a>
                                                                    </h4>

                                                                    <div class="meta">
                                                                        <span><?php _e('Posted in -', 'velocityslide'); ?> <?php the_category(' & '); ?><br />on <strong><?php the_time('F jS, Y'); ?></strong></span>
                                                                        <span><i class="icon-comment"></i> <a href="<?php the_permalink(); ?>#comments"><?php $commentscount = get_comments_number(); echo $commentscount; ?> <?php _e('Comments', 'velocityslide'); ?></a></span>
                                                                    </div>

                                                                    <?php the_excerpt(); ?>

                                                                    <a class="read-more-btn" href="<?php the_permalink() ?>"><?php _e('Read more', 'velocityslide'); ?> <span>&rarr;</span></a>

                                                                </article><!-- end article -->

                                                            </li>

                                                        <?php endif; ?>
                                                    <?php endwhile; ?>
                                                <?php endif; ?>
                                            </ul>

                                        </div>

                                        <a href="#" class="jcarousel-control-prev">&lsaquo;</a>

                                        <a href="#" class="jcarousel-control-next">&rsaquo;</a>

                                        <p class="jcarousel-pagination" data-jcarouselpagination="true"></p>

                                    </div>
                                <?php endif; ?>

                            </div>

                        </div>

                    </div><!-- end .slide--split__left -->

                    <div class="slide--split__right">

                        <div class="s-table">

                            <div class="s-table-cell">

                                <?php if ($data['right_post_switch']) : ?>

                                    <div class="jcarousel-wrapper jcarousel-article">

                                        <div class="jcarousel" data-options="{'itemPerPage' : [1], 'control': {'target': 1}}">

                                            <ul class="jcarousel-list">

                                                <?php if($loop->have_posts()) : ?>
                                                    <?php while ($loop->have_posts()) : ?>
                                                        <?php $loop->the_post(); ?>
                                                        <?php $side = do_shortcode(get_post_meta($loop->post->ID, 'vs_article_side', $single = true)); ?>
                                                        <?php if($side=='Right'): ?>

                                                            <li class="item-article">

                                                                <article class="article one-third column">

                                                                    <div class="thumbnail">
                                                                        <?php the_post_thumbnail('large'); ?>
                                                                    </div>

                                                                    <h4>
                                                                        <a href="<?php the_permalink() ?>"><?php the_title(); ?><span>.</span>
                                                                        </a>
                                                                    </h4>

                                                                    <div class="meta">
                                                                        <span><?php _e('Posted in -', 'velocityslide'); ?> <?php the_category(' & '); ?><br />on <strong><?php the_time('F jS, Y'); ?></strong></span>
                                                                        <span><i class="icon-comment"></i> <a href="<?php the_permalink(); ?>#comments"><?php $commentscount = get_comments_number(); echo $commentscount; ?> <?php _e('Comments', 'velocityslide'); ?></a></span>
                                                                    </div>

                                                                    <?php the_excerpt(); ?>

                                                                    <a class="read-more-btn" href="<?php the_permalink() ?>"><?php _e('Read more', 'velocityslide'); ?> <span>&rarr;</span></a>

                                                                </article><!-- end article -->

                                                            </li>

                                                        <?php endif; ?>
                                                    <?php endwhile; ?>
                                                <?php endif; ?>
                                            </ul>

                                        </div>

                                        <a href="#" class="jcarousel-control-prev">&lsaquo;</a>

                                        <a href="#" class="jcarousel-control-next">&rsaquo;</a>

                                        <p class="jcarousel-pagination" data-jcarouselpagination="true"></p>

                                    </div>

                                <?php endif; ?>

                            </div>

                        </div>

                    </div><!-- end .slide--split__right -->

                </div><!-- end .slide--4 -->

            <?php break; }
            }?>

        <?php endif; ?>

        <div class="slide slide--5 last-slide" data-slideTitle="<?php echo (($data["name_layout_contact"]) ? $data["name_layout_contact"] : 'Contact'); ?>">

            <div class="slide-content">

                <div class="row">

                    <div class="map-content">
                        <?php global $data; ?>

                        <?php if (isset($data["title_details_contact"])) : ?>
                            <h1>
                                <?php echo $data['title_contact']; ?><span>.</span>
                            </h1>
                        <?php endif; ?>

                        <?php if (isset($data["title_details_contact"])) : ?>
                            <p>
                                <?php echo do_shortcode(stripslashes($data['subtitle_contact'])); ?>
                            </p>
                        <?php endif; ?>

                    </div>

                </div><!-- end .row -->

                <div class="row">

                    <div class="contact-content">

                        <div class="address-content-left">

                            <?php if (isset($data["title_details_contact"])) : ?>
                                <h2>
                                    <?php echo $data['title_details_contact']; ?>

                                </h2>
                            <?php endif; ?>

                            <ul>

                                <?php if (isset($data["text_address_contact"])) : ?>
                                    <li>
                                        <i class="icon-map-marker"></i> <?php echo $data['text_address_contact']; ?>
                                    </li>
                                <?php endif; ?>

                                <?php if (isset($data["text_telephone_contact"])) : ?>
                                    <li>
                                        <i class="icon-phone"></i> <?php echo $data['text_telephone_contact']; ?>
                                    </li>
                                <?php endif; ?>

                                <?php if (isset($data["text_fax_contact"])) : ?>
                                    <li>
                                        <i class="icon-phone"></i> <?php echo $data['text_fax_contact']; ?>
                                    </li>
                                <?php endif; ?>

                            </ul>

                        </div><!-- end .address-content-left -->

                        <div class="form-content">

                            <form id="contact-form" action="<?php echo get_template_directory_uri(); ?>/form/form.php" method="post">

                                <input name="name" type="text" placeholder="Your Name (required)">

                                <input name="email" type="email" placeholder="Your Email (required)">

                                <input name="subject" type="subject" placeholder="Subject">

                                <textarea name="message" placeholder="Please enter your Message..."></textarea>

                                <input id="submit" name="submit" type="submit" value="Submit">

                            </form>

                        </div><!-- end .form-content -->

                        <div class="address-content-right">

                            <?php if (isset($data["title_details_contact"])) : ?>
                                <h2>
                                    <?php echo $data['title_details_contact']; ?>
                                </h2>
                            <?php endif; ?>

                            <ul>

                                <?php if (isset($data["text_email_contact"])) : ?>
                                    <li>
                                        <i class="icon-envelope-alt"></i>
                                        <a href="mailto:<?php echo $data['text_email_contact']; ?>">
                                            <?php echo $data['text_email_contact']; ?>
                                        </a>
                                    </li>
                                <?php endif; ?>

                                <?php if (isset($data["text_postcode_contact"])) : ?>
                                    <li>
                                        <i class="icon-map-marker"></i><?php echo $data['text_postcode_contact']; ?>
                                    </li>
                                <?php endif; ?>

                                <?php if (isset($data["text_country_contact"])) : ?>
                                    <li>
                                        <i class="icon-map-marker"></i><?php echo $data['text_country_contact']; ?>
                                    </li>
                                <?php endif; ?>
                            </ul>

                        </div>

                    </div><!-- end .contact-content -->

                </div><!-- end .row -->

                <div class="row">

                    <div class="footer-content">

                        <div class="social-icons-container">

                            <ul class="social-icons footer">

                                <?php if (isset($data["text_twitter_profile"])) : ?>
                                    <li>
                                        <a href="<?php echo $data['text_twitter_profile']; ?>" class="mk-social-twitter-alt" title="View Twitter Profile">
                                        </a>
                                    </li>
                                <?php endif; ?>

                                <?php if (isset($data["text_facebook_profile"])) : ?>
                                    <li>
                                        <a href="<?php echo $data['text_facebook_profile']; ?>" class="mk-social-facebook" title="View Facebook Profile">
                                        </a>
                                    </li>
                                <?php endif; ?>

                                <?php if (isset($data["text_dribbble_profile"])) : ?>
                                    <li>
                                        <a href="<?php echo $data['text_dribbble_profile']; ?>" class="mk-social-dribbble" title="View Dribbble Profile"></a>
                                    </li>
                                <?php endif; ?>

                                <?php if (isset($data["text_forrst_profile"])) : ?>
                                    <li>
                                        <a href="<?php echo $data['text_forrst_profile']; ?>" class="mk-social-forrst" title="View Forrst Profile"></a>
                                    </li>
                                <?php endif; ?>

                                <?php if (isset($data["text_vimeo_profile"])) : ?>
                                    <li>
                                        <a href="<?php echo $data['text_vimeo_profile']; ?>" class="mk-social-vimeo" title="View Vimeo Profile"></a>
                                    </li>
                                <?php endif; ?>

                                <?php if (isset($data["text_youtube_profile"])) : ?>
                                    <li>
                                        <a href="<?php echo $data['text_youtube_profile']; ?>" class="mk-social-youtube" title="View YouTube Profile"></a>
                                    </li>
                                <?php endif; ?>

                                <?php if (isset($data["text_flickr_profile"])) : ?>
                                    <li>
                                        <a href="<?php echo $data['text_flickr_profile']; ?>" class="mk-social-flickr" title="View Flickr Profile"></a>
                                    </li>
                                <?php endif; ?>

                                <?php if (isset($data["text_linkedin_profile"])) : ?>
                                    <li>
                                        <a href="<?php echo $data['text_linkedin_profile']; ?>" class="mk-social-linkedin" title="View Linkedin Profile"></a>
                                    </li>
                                <?php endif; ?>

                                <?php if (isset($data["text_pinterest_profile"])) : ?>
                                    <li>
                                        <a href="<?php echo $data['text_pinterest_profile']; ?>" class="mk-social-pinterest" title="View Pinterest Profile"></a>
                                    </li>
                                <?php endif; ?>

                                <?php if (isset($data["text_googleplus_profile"])) : ?>
                                    <li>
                                        <a href="<?php echo $data['text_googleplus_profile']; ?>" class="mk-social-googleplus" title="View Google + Profile"></a>
                                    </li>
                                <?php endif; ?>

                                <?php if (isset($data["text_tumblr_profile"])) : ?>
                                    <li>
                                        <a href="<?php echo $data['text_tumblr_profile']; ?>" class="mk-social-tumblr" title="View Tumblr Profile"></a>
                                    </li>
                                <?php endif; ?>

                                <?php if (isset($data["text_soundcloud_profile"])) : ?>
                                    <li>
                                        <a href="<?php echo $data['text_soundcloud_profile']; ?>" class="mk-social-soundcloud" title="View Soundcloud Profile"></a>
                                    </li>
                                <?php endif; ?>

                                <?php if (isset($data["text_lastfm_profile"])) : ?>
                                    <li>
                                        <a href="<?php echo $data['text_lastfm_profile']; ?>" class="mk-social-lastfm" title="View Last FM Profile"></a>
                                    </li>
                                <?php endif; ?>

                            </ul>

                            <p id="copyright-details">
                                &copy; <?php echo date('Y') ?> <?php echo bloginfo('name'); ?>. <?php global $data; echo $data['footer_text']; ?>
                            </p>

                        </div>

                    </div><!-- end .footer-content -->

                    <div class="contact-details-2">

                        <?php if (isset($data["title_details_contact"])) : ?>
                            <h2>
                                <?php echo $data['title_details_contact']; ?>
                            </h2>
                        <?php endif; ?>

                        <ul>

                            <?php if (isset($data["text_phone_contact"])) : ?>
                                <li>
                                    <?php echo $data['text_phone_contact']; ?>
                                </li>
                            <?php endif; ?>

                            <?php if (isset($data["text_mail_contact"])) : ?>
                                <li>
                                    <?php echo $data['text_mail_contact']; ?>
                                </li>
                            <?php endif; ?>

                            <?php if (isset($data["text_website_contact"])) : ?>
                                <li>
                                    <?php echo $data['text_website_contact']; ?>
                                </li>
                            <?php endif; ?>

                        </ul>

                    </div>

                </div><!-- end .row -->

            </div><!-- end .slide-content -->

        </div><!-- end .last-slide -->

    </div><!-- end .slider-->

</div><!-- end .powerslide -->
<?php get_footer(); ?>

