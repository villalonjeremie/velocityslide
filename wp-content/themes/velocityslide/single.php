<?php
/**
 *
 * Description: Standard Blog (Single Article) template.
 *
 */

get_header(); ?>

<div id="main main-article">

    <section id="content">

        <div class="container">

            <div class="full columns">

                <section id="article-content" class="half-left columns">

                    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>

                        <article <?php post_class("single-article"); ?>>

                            <h1><?php the_title(); ?><span>.</span></h1>

                            <span class="meta-author">By <?php the_author_posts_link(); ?></span>

                            <span class="meta-category"><?php _e('Posted in', 'velocityslide'); ?> - <?php the_category(' & '); ?> <?php _e('on', 'velocityslide'); ?> <strong><?php the_time('F jS, Y'); ?></strong></span>

                            <?php the_post_thumbnail('single-post'); ?>

                            <?php the_content(); ?>

                            <span class="tags"><i class="icon-tags"></i> <?php the_tags(' ',' '); ?></span>

                        </article><!-- end #single-article -->

                    <?php endwhile; endif; ?>

                    <?php comments_template(); ?>

                </section><!-- end #post-content -->

                <?php get_sidebar(); ?>

            </div><!-- end .full columns -->

        </div><!-- end .container -->

    </section><!-- end #content -->

</div><!-- end #main -->

<?php get_footer(); ?>
