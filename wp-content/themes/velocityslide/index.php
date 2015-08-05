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
                                    <h1>
                                        <?php echo $data['title_homepage']; ?>
                                        <!--<img src="<?php echo get_template_directory_uri(); ?>/assets/img/slide0/powercell.png" alt="Powercell" class="img-powercell"><br>
                                        <img src="<?php echo get_template_directory_uri(); ?>/assets/img/youth-grafter.png" alt="Youth Grafter">-->
                                    </h1>
                                    <h3>
                                        <?php echo $data['text_homepage']; ?>
                                    </h3>
                                    <p>
                                        <?php echo $data['text_homepage']; ?>
                                    </p>
                                    <a href="<?php echo $data['url_button_homepage']; ?>" class="js-external-link button" title="<?php echo $data['text_button_homepage']; ?>"><?php echo $data['text_button_homepage']; ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="slide slide--split slide--1 effect" data-slideTitle="Presentation" data-slideTransition="split">
                    <div class="slide--split__left">
                        <div class="s-table"><div class="s-table-cell">
                            <div class="box">
                                <div class="container">
                                    <div class="s-table"><div class="s-table-cell">
                                        <h2>
                                            <?php echo $data['left_title_presentation']; ?>
                                        </h2>
                                        <h3>
                                            <?php echo $data['left_subtitle_presentation']; ?>
                                        </h3>
                                        <p>
                                            <?php echo $data['left_description_presentation']; ?>
                                        </p>
                                    </div></div>
                                </div>
                            </div></div>
                        </div>
                    </div>
                    <div class="slide--split__right">
                        <div class="s-table"><div class="s-table-cell">
                            <div class="box">
                                <div class="container">
                                    <div class="s-table"><div class="s-table-cell">
                                        <h2>
                                            <?php echo $data['right_title_presentation']; ?>
                                        </h2>
                                        <h3>
                                            <?php echo $data['right_subtitle_presentation']; ?>
                                        </h3>
                                        <p>
                                            <?php echo $data['right_description_presentation']; ?>
                                        </p>
                                    </div></div>
                                </div>
                            </div>
                        </div></div>
                    </div>
                </div>
                <div class="slide slide--split slide--2" data-slideTitle="Services" data-slideTransition="split">
                    <?php global $data;
                    $args = array('post_type' => 'services', 'orderby' => 'menu_order', 'order' => 'ASC','posts_per_page' => -1);
                    $loop = new WP_Query($args); ?>
                        <div class="slide--split__left">
                            <div class="s-table"><div class="s-table-cell">
                               <div class="box">
                                    <div class="container">
                                        <div class="s-table">
                                            <div class="s-table-cell">
                                                <?php while ($loop->have_posts()) : $loop->the_post(); ?>
                                                    <?php $side = do_shortcode(get_post_meta($post->ID, 'gt_service_side', $single = true)); ?>
                                                    <?php if($side=='Left'){ ?>
                                                        <div class="service one-third column">
                                                            <?php echo do_shortcode(get_post_meta($post->ID, 'gt_service_icon', $single = true)); ?>
                                                            <h2>
                                                                <?php the_title(); ?>
                                                            </h2>
                                                            <p>
                                                                <?php the_content(); ?>
                                                            </p>
                                                            <?php if (do_shortcode(get_post_meta($post->ID, 'gt_service_url', $single = true))) { ?>
                                                                <a class="read-more-btn" href="<?php echo do_shortcode(get_post_meta($post->ID, 'gt_service_url', $single = true)); ?>"><?php _e('Read more', 'velocityslide'); ?> <span>&rarr;</span></a>
                                                            <?php } ?>
                                                        </div><!-- end .service -->
                                                    <?php } ?>
                                                <?php endwhile; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div></div>
                        </div>
                        <div class="slide--split__right">
                            <div class="s-table"><div class="s-table-cell">
                               <div class="box">
                                    <div class="container">
                                        <div class="s-table">
                                            <div class="s-table-cell">
                                                <?php while ($loop->have_posts()) : $loop->the_post(); ?>
                                                    <?php $side = do_shortcode(get_post_meta($post->ID, 'gt_service_side', $single = true)); ?>
                                                    <?php if($side=='Right'){ ?>
                                                        <div class="service one-third column">
                                                            <?php echo do_shortcode(get_post_meta($post->ID, 'gt_service_icon', $single = true)); ?>
                                                            <h2>
                                                                <?php the_title(); ?>
                                                            </h2>
                                                            <p>
                                                                <?php the_content(); ?>
                                                            </p>
                                                            <?php if (do_shortcode(get_post_meta($post->ID, 'gt_service_url', $single = true))) { ?>
                                                                <a class="read-more-btn" href="<?php echo do_shortcode(get_post_meta($post->ID, 'gt_service_url', $single = true)); ?>"><?php _e('Read more', 'velocityslide'); ?> <span>&rarr;</span></a>
                                                            <?php } ?>
                                                        </div><!-- end .service -->
                                                    <?php } ?>
                                                <?php endwhile; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="slide slide--split slide--3" data-slideTitle="Portfolios" data-slideTransition="split">
                    <div class="slide--split__left">
                        <div class="s-table"><div class="s-table-cell">
                            <h2>
                                <?php echo $data['left_title_portfolios']; ?>
                            </h2>
                            <p>
                                <?php echo $data['left_description_portfolios']; ?>
                            </p>
                            <?php if($data['side_left_popup_switch']): ?>
                            <a href="#popin-slide3-1" class="window-button js-window-open" title="<?php echo $data['left_text_button_portfolios']; ?>"><?php echo $data['left_text_button_portfolios']; ?></a>
                            <?php endif; ?>
                        </div></div>
                    </div>
                    <div class="slide--split__right">
                        <div class="s-table"><div class="s-table-cell">
                            <h2>
                                <?php echo $data['right_title_portfolios']; ?>
                            </h2>
                            <p>
                                <?php echo $data['right_description_portfolios']; ?>
                            </p>
                                <?php if($data['side_right_popup_switch']): ?>
                                    <a href="#popin-slide3-2" class="window-button js-window-open" title="<?php echo $data['right_text_button_portfolios']; ?>"><?php echo $data['right_text_button_portfolios']; ?></a>
                                <?php endif; ?>
                        </div></div>
                    </div>
                    <div class="window hide-window popin-slide3-1" id="popin-slide3-1">
                        <div class="window-close popin-close-overay"></div>
                        <div class="container-inner">
                            <div class="popin-content">
                                <a href="#" class="window-close popin-close">Close</a>
                                <h3>
                                    <?php echo $data['left_title_popup_portfolios']; ?><span>.</span>
                                </h3>
                                <p><?php echo do_shortcode(stripslashes($data['left_description_popup_portfolios'])); ?></p>
                                <div class="jcarousel-wrapper">
                                    <div class="jcarousel" data-options="{'itemPerPage' : [5], 'control': {'target': 5}}">
                                        <ul class="jcarousel-list">

                                            <?php
                                            query_posts(array(
                                                'post_type' => 'portfolio',
                                                'orderby' => 'menu_order',
                                                'order' => 'ASC',
                                                'posts_per_page' => -1
                                            ));
                                            ?>

                                            <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                                                    <?php $side = do_shortcode(get_post_meta($post->ID, 'gt_portfolio_side', $single = true)); ?>
                                                    <?php if($side=='Left'): ?>
                                                    <?php
                                                    $terms =  get_the_terms( $post->ID, 'project-type' );
                                                    $term_list = '';
                                                    if( is_array($terms) ) {
                                                        foreach( $terms as $term ) {
                                                            $term_list .= urldecode($term->slug);
                                                            $term_list .= ' ';
                                                        }
                                                    }
                                                    ?>

                                                    <li <?php post_class("$term_list item"); ?> id="post-<?php the_ID(); ?>"><?php the_post_thumbnail('portfolio-thumb'); ?></li>
                                                    <?php endif; ?>
                                            <?php endwhile; endif; ?>
                                        </ul>
                                    </div>
                                    <a href="#" class="jcarousel-control-prev">&lsaquo;</a>
                                    <a href="#" class="jcarousel-control-next">&rsaquo;</a>
                                    <p class="jcarousel-pagination" data-jcarouselpagination="true"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="window hide-window popin-slide3-2" id="popin-slide3-2">
                        <div class="window-close popin-close-overay"></div>
                        <div class="container-inner">
                            <div class="popin-content">
                                <a href="#" class="window-close popin-close">Close</a>
                                <h3>
                                    <?php echo $data['right_title_popup_portfolios']; ?><span>.</span>
                                </h3>
                                <p><?php echo do_shortcode(stripslashes($data['right_description_popup_portfolios'])); ?></p>
                                <div class="jcarousel-wrapper">
                                    <div class="jcarousel" data-options="{'itemPerPage' : [5], 'control': {'target': 5}}">
                                        <ul class="jcarousel-list">
                                            <?php
                                            query_posts(array(
                                                'post_type' => 'portfolio',
                                                'orderby' => 'menu_order',
                                                'order' => 'ASC',
                                                'posts_per_page' => -1
                                            ));
                                            ?>

                                            <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                                                    <?php $side = do_shortcode(get_post_meta($post->ID, 'gt_portfolio_side', $single = true)); ?>
                                                    <?php if($side=='Right'): ?>
                                                    <?php
                                                    $terms =  get_the_terms( $post->ID, 'project-type' );
                                                    $term_list = '';
                                                    if( is_array($terms) ) {
                                                        foreach( $terms as $term ) {
                                                            $term_list .= urldecode($term->slug);
                                                            $term_list .= ' ';
                                                        }
                                                    }
                                                    ?>

                                                    <li <?php post_class("$term_list item"); ?> id="post-<?php the_ID(); ?>"><?php the_post_thumbnail('portfolio-thumb'); ?></li>
                                                    <?php endif; ?>
                                                <?php endwhile; endif; ?>
                                        </ul>
                                    </div>
                                    <a href="#" class="jcarousel-control-prev">&lsaquo;</a>
                                    <a href="#" class="jcarousel-control-next">&rsaquo;</a>
                                    <p class="jcarousel-pagination" data-jcarouselpagination="true"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="slide slide--split slide--4" data-slideTitle="Blogs" data-slideTransition="split">
                    <div class="slide--split__left">
                        <div class="s-table">
                            <div class="s-table-cell">
                            <?php if (!$data['side_blog']){ ?>
                            <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div class="slide--split__right">
                        <div class="s-table">
                            <div class="s-table-cell">
                                <?php if ($data['side_blog']){ ?>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
<?php get_footer(); ?>