<?php
/**
 *
 * Description: Search Results Page Template.
 *
 */

get_header(); ?>

    <div id="main">

        <section id="content">

            <div class="container">

                <div class="full columns">

                    <section id="post-content" class="half-left columns">

                        <h1><?php _e('Search Results:', 'velocityslide'); ?></h1>

                        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>

                            <article <?php post_class("post-excerpt"); ?>>

                                <h2 class="post-title"><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h2>

                                <span class="meta-author">By <?php the_author_posts_link(); ?></span>

                                <span class="meta-category"><?php _e('Posted in', 'velocityslide'); ?> - <?php the_category(' & '); ?> <?php _e('on', 'velocityslide'); ?> <strong><?php the_time('F jS, Y'); ?></strong> <span class="comment-count"><a href="<?php the_permalink(); ?>#comments"><?php $commentscount = get_comments_number(); echo $commentscount; ?></a> <?php _e('Comments', 'velocityslide'); ?></span></span>

                                <a href="<?php the_permalink() ?>">
                                    <?php the_post_thumbnail('archive-post'); ?>
                                </a>

                                <?php the_excerpt(); ?>

                                <a class="read-more-btn" href="<?php the_permalink() ?>"><?php _e('Read More', 'velocityslide'); ?> <span>&rarr;</span></a>

                            </article><!-- end .post-excerpt -->

                        <?php endwhile; endif; ?>

                        <?php if (!have_posts()) : ?>

                            <div id="no-posts-found" class="half-right columns">

                                <p><strong><?php _e( 'Oh, that did not go so well!', 'velocityslide' ); ?></strong><br />
                                    <?php echo __( 'Sorry, but no results were found. Please try the search again.', 'velocityslide' ); ?></p>

                            </div><!-- end #no-posts-found -->

                        <?php endif; ?>

                        <?php if(function_exists('wp_pagenavi')) { ?>
                            <?php wp_pagenavi(); ?>
                        <?php } else { ?>
                            <div class="post-navigation"><p><?php posts_nav_link('&#8734;','&laquo;&laquo; Previous Posts','Older Posts &raquo;&raquo;'); ?></p></div>
                        <?php } ?>

                    </section><!-- end #post-content -->

                    <?php get_sidebar(); ?>

                </div><!-- end .full .columns -->

            </div><!-- end .container -->

        </section><!-- end #content -->

    </div><!-- end #main -->

<?php get_footer(); ?>