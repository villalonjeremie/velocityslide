<?php
/**
 *
 * Description: 404 Page Template.
 *
 */

get_header(); ?>

    <div id="main">

        <section id="content">

            <div class="container">

                <div id="page-not-found" class="full columns">

                    <h1><?php _e('Woops! It seems a page is missing.', 'velocityslide'); ?></h1>
                    <p><?php _e('It seems we can&rsquo;t find what you&rsquo;re looking for. Please try using one of the navigation links above.', 'velocityslide'); ?></p>
                    <a class="read-more-btn" href="<?php echo home_url(); ?>"><span>&larr;</span> <?php _e('Go to the Homepage', 'velocityslide'); ?></a>

                </div><!-- end .sixteen columns -->

            </div><!-- end .container -->

        </section><!-- end #content -->

    </div><!-- end #main -->

<?php get_footer(); ?>