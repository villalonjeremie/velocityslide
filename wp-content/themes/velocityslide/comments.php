<?php

// Do not delete these lines
if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
    die (__('Please do not load this page directly. Thanks!', 'velocityslide' ));

if ( post_password_required() ) { ?>
    <p class="nocomments"><?php echo __( 'This post is password protected. Enter the password to view comments.', 'velocityslide' ); ?></p>
    <?php
    return;
}
?>

<?php if ( have_comments() ) : ?>

    <div id="comments">

    <h4>(<?php $commentscount = get_comments_number(); echo $commentscount; ?>) <?php echo __( 'awesome folk have had something to say...', 'velocityslide' ); ?></h4>

    <div class="navigation">
        <div class="alignleft"><?php previous_comments_link() ?></div>
        <div class="alignright"><?php next_comments_link() ?></div>
    </div>

    <ul class="comments-list">
        <?php wp_list_comments('callback=vs_comments'); ?>
    </ul>

<?php else : // this is displayed if there are no comments so far ?>

    <?php if ('open' == $post->comment_status) : ?>
        <!-- If comments are open, but there are no comments. -->

    <?php else : // comments are closed ?>
        <!-- If comments are closed. -->
        <?php
        global $data; ?>

        <?php if ($data['text_comments_closed']) { ?>

        <?php } else { ?>
            <strong class="nocomments"><?php echo __( 'Comments are currently closed.', 'velocityslide' ); ?></p></strong>
        <?php } ?>

    <?php endif; ?>
<?php endif; ?>

<?php if ('open' == $post->comment_status) : ?>

    <?php comment_form(); ?>

<?php endif;?>