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
                                            <?php echo $data['left_text_presentation']; ?>
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
                                            <?php echo $data['right_text_presentation']; ?>
                                        </p>
                                    </div></div>
                                </div>
                            </div>
                        </div></div>
                    </div>
                </div>


                <div class="slide slide--split slide--2" data-slideTitle="Services" data-slideTransition="split">
                    <div class="slide--split__left">
                        <div class="s-table"><div class="s-table-cell">
                           <div class="box">
                                <div class="container">
                                    <div class="s-table"><div class="s-table-cell">

                                        <?php global $data;
                                        
                                        $args = array('post_type' => 'services', 'orderby' => 'menu_order', 'order' => 'ASC', 'posts_per_page' => $data['select_services']);
                                        $loop = new WP_Query($args);
                                        while ($loop->have_posts()) : $loop->the_post(); ?>
                                    
                                        <div class="service one-third column">
                        
                                            <?php echo do_shortcode(get_post_meta($post->ID, 'gt_service_icon', $single = true)) ?>

                                            <h2><?php the_title(); ?></h2>

                                            <p><?php the_content(); ?></p>

                                            <?php if (get_post_meta($post->ID, 'gt_service_url', true)) { ?>
                                            <a class="read-more-btn" href="<?php echo get_post_meta($post->ID, 'gt_service_url', true) ?>"><?php _e('Read more', 'velocityslide'); ?> <span>&rarr;</span></a>
                                            <?php } ?>
                                        
                                        </div><!-- end .service -->
                    
                                        <?php endwhile; ?>
                                    </div></div>
                                </div>
                            </div>
                        </div></div>
                    </div>
                    <div class="slide--split__right">
                        <div class="s-table"><div class="s-table-cell">
                           <div class="box">
                                <div class="container">
                                    <div class="s-table"><div class="s-table-cell">
                                        <?php global $data;
                                        
                                        $args = array('post_type' => 'services', 'orderby' => 'menu_order', 'order' => 'ASC', 'posts_per_page' => $data['select_services']);
                                        $loop = new WP_Query($args);
                                        while ($loop->have_posts()) : $loop->the_post(); ?>
                                    
                                        <div class="service one-third column">
                        
                                            <?php echo do_shortcode(get_post_meta($post->ID, 'gt_service_icon', $single = true)) ?>
                        
                                            <h2><?php the_title(); ?></h2>
                                            
                                            <p><?php the_content(); ?></p>
                                            
                                            <?php if (get_post_meta($post->ID, 'gt_service_url', true)) { ?>
                                            <a class="read-more-btn" href="<?php echo get_post_meta($post->ID, 'gt_service_url', true) ?>"><?php _e('Read more', 'velocityslide'); ?> <span>&rarr;</span></a>
                                            <?php } ?>
                                        
                                        </div><!-- end .service -->
                    
                                        <?php endwhile; ?>
                                    </div></div>
                                </div>
                            </div>
                        </div></div>
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
                            <a href="<?php echo $data['left_url_button_portfolios']; ?>" class="js-external-link button" title="<?php echo $data['left_text_button_portfolios']; ?>"><?php echo $data['left_text_button_portfolios']; ?></a>
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
                            <a href="<?php echo $data['right_url_button_portfolios']; ?>" class="js-external-link button" title="<?php echo $data['right_text_button_portfolios']; ?>"><?php echo $data['right_text_button_portfolios']; ?></a>
                        </div></div>
                    </div>
                </div>


                <div class="slide slide--split slide--4" data-slideTitle="The results" data-slideTransition="split">
                    <div class="slide--split__left">
                        <div class="s-table"><div class="s-table-cell">
                            <?php if (!$data['side_blog']){ ?>
                            <?php } ?>
                        </div></div>
                    </div>
                    <div class="slide--split__right">
                        <div class="s-table"><div class="s-table-cell">
                            <?php if ($data['side_blog']){ ?>
                            <?php } ?>
                    </div>

                    <div class="window hide-window popin-slide4-1" id="popin-slide4-1">
                        <div class="window-close popin-close-overay"></div>
                        <div class="container-inner">
                            <div class="popin-content">
                                <a href="#" class="window-close popin-close">Close</a>
                                <h3>
                                    POWERCELL SERUM : The youth grafter efficacy
                                    <span>
                                        In 5 days, the visible signs of a younger skin.<br>
                                        In 1 month, all youth signs improved at 100%<sup>*</sup>.
                                    </span>
                                </h3>
                                <ul>
                                    <li class="graph1">
                                        <div class="graph__content">
                                            <h4>
                                                <span class="number">1</span>
                                                Smoother
                                            </h4>
                                            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/slide4/popin2-graph1.jpg" alt="">
                                        </div>
                                    </li>
                                    <li class="graph2">
                                        <div class="graph__content">
                                            <h4>
                                                <span class="number">3</span>
                                                Firmer
                                            </h4>
                                            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/slide4/popin2-graph1.jpg" alt="">
                                        </div>
                                    </li>
                                    <li class="graph3">
                                        <div class="graph__content">
                                            <h4>
                                                <span class="number">2</span>
                                                More Refined
                                            </h4>
                                            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/slide4/popin2-graph1.jpg" alt="">
                                        </div>
                                    </li>
                                    <li class="graph3">
                                        <div class="graph__content">
                                            <h4>
                                                <span class="number">4</span>
                                                More Radiant
                                            </h4>
                                            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/slide4/popin2-graph1.jpg" alt="">
                                        </div>
                                    </li>
                                </ul>
                                <p class="note-popin">
                                    Self evaluation on 60 women, 1 month.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="window hide-window popin-slide4-2" id="popin-slide4-2">
                        <div class="window-close popin-close-overay"></div>
                        <div class="container-inner">
                            
                            <div class="popin-content">
                                <a href="#" class="window-close popin-close">Close</a>
                                <h3>
                                    THE SKIN REHAB EFFICACY
                                </h3>
                                <ul>
                                    <li class="graph1">
                                        <h4>
                                            <span class="number">1</span>
                                            PURE RADIANT<br>
                                            COMPLEXION
                                        </h4>
                                        <img src="<?php echo get_template_directory_uri(); ?>/assets/img/slide4/popin1-graph1.jpg" alt="">
                                        <p class="note">
                                            After 1 month
                                        </p>
                                    </li>
                                    <li class="graph2">
                                        <h4>
                                            <span class="number">2</span>
                                            SMOOTHER RESTED<br>
                                            FEATURES
                                        </h4>
                                        <img src="<?php echo get_template_directory_uri(); ?>/assets/img/slide4/popin1-graph2.jpg" alt="">
                                    </li>
                                    <li class="graph3">
                                        <h4>
                                            <span class="number">3</span>
                                            SKIN LOOKS<br>
                                            YOUNGER
                                        </h4>
                                        <img src="<?php echo get_template_directory_uri(); ?>/assets/img/slide4/popin1-graph3.jpg" alt="">
                                    </li>
                                </ul>
                                <p class="note-popin">
                                    Self evaluation, 53 asian women.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

<?php get_footer(); ?>