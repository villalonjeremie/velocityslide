<?php
/**
 *
 * Description: Main Homepage template.
 *
 */
get_header();?>
<div class="powerslide slider-wrapper">
    <div class="slider">
        <div class="slide slide--0" data-slideTitle="Homepages">
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
                <div class="slide slide--split slide--1 effect" data-slideTitle="Presentation" data-slideTransition="split">
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
                <div class="slide slide--split slide--2" data-slideTitle="Service" data-slideTransition="split">
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
                                                        <?php $side = do_shortcode(get_post_meta($post->ID, 'gt_service_side', $single = true)); ?>
                                                        <?php if ($side=='Left') : ?>
                                                            <div class="service one-third column">
                                                                <?php echo do_shortcode(get_post_meta($post->ID, 'gt_service_icon', $single = true)); ?>
                                                                <h2>
                                                                    <?php the_title(); ?>
                                                                </h2>
                                                                <p>
                                                                    <?php the_content(); ?>
                                                                </p>
                                                                <?php if (do_shortcode(get_post_meta($post->ID, 'gt_service_url', $single = true))) : ?>
                                                                    <a class="read-more-btn" href="<?php echo do_shortcode(get_post_meta($post->ID, 'gt_service_url', $single = true)); ?>"><?php _e('Read more', 'velocityslide'); ?> <span>&rarr;</span></a>
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
                                                    <?php $side = do_shortcode(get_post_meta($post->ID, 'gt_service_side', $single = true)); ?>
                                                        <?php if ($side=='Right'): ?>
                                                            <div class="service one-third column">
                                                                <?php echo do_shortcode(get_post_meta($post->ID, 'gt_service_icon', $single = true)); ?>
                                                                <h2>
                                                                    <?php the_title(); ?>
                                                                </h2>
                                                                <p>
                                                                    <?php the_content(); ?>
                                                                </p>
                                                                <?php if (do_shortcode(get_post_meta($post->ID, 'gt_service_url', $single = true))): ?>
                                                                    <a class="read-more-btn" href="<?php echo do_shortcode(get_post_meta($post->ID, 'gt_service_url', $single = true)); ?>"><?php _e('Read more', 'velocityslide'); ?> <span>&rarr;</span></a>
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
                <div class="slide slide--split slide--3" data-slideTitle="Portfolios" data-slideTransition="split">
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
                                                    <?php $side = do_shortcode(get_post_meta($loop->post->ID, 'gt_portfolio_side', $single = true)); ?>
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
                                                        <?php $side = do_shortcode(get_post_meta($loop->post->ID, 'gt_portfolio_side', $single = true)); ?>
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
                        </div>
                    </div><!-- end .popin-slide3-2 -->
                </div><!-- end .slide--3 -->
                <?php break;
                case 'article':?>
                    <div class="slide slide--split slide--4" data-slideTitle="Blogs" data-slideTransition="split">
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
                                                            <?php $side = do_shortcode(get_post_meta($loop->post->ID, 'gt_article_side', $single = true)); ?>
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
                                                            <?php $side = do_shortcode(get_post_meta($loop->post->ID, 'gt_article_side', $single = true)); ?>
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
<?php get_footer(); ?>