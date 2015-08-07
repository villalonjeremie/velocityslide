                <div class="slide slide--5 last-slide" data-slideTitle="Contact">
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
                            <div><!-- end .contact-content -->
                        <div><!-- end .row -->
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
                                </div>
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
                            </div><!-- end .footer-content -->
                        </div><!-- end .row -->
                    </div><!-- end .slide-content -->
                </div><!-- end .last-slide -->
		<?php wp_footer(); ?>
    </body>
</html>
