                <div class="slide slide--5 last-slide" data-slideTitle="Contact">

                    <div class="slide-content">

                        <div class="row">

                            <div class="map-content">

                                <?php
                                global $data; ?>

                                <h1><?php echo $data['text_contact_us_title']; ?><span>.</span></h1>

                                <p><?php echo do_shortcode(stripslashes($data['textarea_contact_us_overview'])); ?></p>

                            </div>

                        </div><!-- end .row -->

                        <div class="row">

                            <div class="contact-content">

                                <div class="address-content">

                                    <li><i class="icon-phone"></i> <?php echo $data['text_contact_telephone']; ?></li>
                                    <li><i class="icon-map-marker"></i> <?php echo $data['text_contact_address']; ?></li>
                                    <li><i class="icon-envelope-alt"></i> <a href="mailto:<?php echo $data['text_contact_email']; ?>"><?php echo $data['text_contact_email']; ?></a></li>

                                </div><!-- end .address-content -->

                                <div class="form-content">

                                    <form id="contact-form" action="<?php echo get_template_directory_uri(); ?>/form/form.php" method="post">

                                        <input name="name" type="text" placeholder="Your Name (required)">

                                        <input name="email" type="email" placeholder="Your Email (required)">

                                        <input name="subject" type="subject" placeholder="Subject">

                                        <textarea name="message" placeholder="Please enter your Message..."></textarea>

                                        <input id="submit" name="submit" type="submit" value="Submit">

                                    </form>

                                </div><!-- end .form-content -->

                            <div><!-- end .contact-content -->

                        <div><!-- end .row -->

                        <div class="row">

                            <div class="footer-content">

                                <div class="contact-details-1">
                                    <h2><?php if (isset($data["text_twitter_profile"])) { ?>
                                        <?php echo $data['title_contact_details_1']; ?>
                                        <?php } ?>
                                    </h2>
                                    <ul>
                                        <li>
                                            <?php if (isset($data["text_twitter_profile"])) { ?>
                                            <?php echo $data['text_contact_address']; ?>
                                            <?php } ?>
                                        </li>
                                        <li><?php if (isset($data["text_twitter_profile"])) { ?>
                                            <?php echo $data['text_contact_country']; ?>
                                            <?php } ?>
                                        </li>
                                        <li><?php if (isset($data["text_twitter_profile"])) { ?>
                                            <?php echo $data['text_contact_postcode']; ?>
                                            <?php } ?>
                                        </li>
                                    </ul>
                                </div>

                                <div class="social-icons-container">
                                    <ul class="social-icons footer">
                                        <?php if (isset($data["text_twitter_profile"])) { ?>
                                            <li><a href="<?php echo $data['text_twitter_profile']; ?>" class="mk-social-twitter-alt" title="View Twitter Profile"></a></li>
                                        <?php } if (isset($data["text_facebook_profile"])){ ?>
                                            <li><a href="<?php echo $data['text_facebook_profile']; ?>" class="mk-social-facebook" title="View Facebook Profile"></a></li>
                                        <?php } if (isset($data["text_dribbble_profile"])){ ?>
                                            <li><a href="<?php echo $data['text_dribbble_profile']; ?>" class="mk-social-dribbble" title="View Dribbble Profile"></a></li>
                                        <?php } if (isset($data["text_forrst_profile"])){ ?>
                                            <li><a href="<?php echo $data['text_forrst_profile']; ?>" class="mk-social-forrst" title="View Forrst Profile"></a></li>
                                        <?php } if (isset($data["text_vimeo_profile"])){ ?>
                                            <li><a href="<?php echo $data['text_vimeo_profile']; ?>" class="mk-social-vimeo" title="View Vimeo Profile"></a></li>
                                        <?php } if (isset($data["text_youtube_profile"])){ ?>
                                            <li><a href="<?php echo $data['text_youtube_profile']; ?>" class="mk-social-youtube" title="View YouTube Profile"></a></li>
                                        <?php } if (isset($data["text_flickr_profile"])){ ?>
                                            <li><a href="<?php echo $data['text_flickr_profile']; ?>" class="mk-social-flickr" title="View Flickr Profile"></a></li>
                                        <?php } if (isset($data["text_linkedin_profile"])){ ?>
                                            <li><a href="<?php echo $data['text_linkedin_profile']; ?>" class="mk-social-linkedin" title="View Linkedin Profile"></a></li>
                                        <?php } if (isset($data["text_pinterest_profile"])){ ?>
                                            <li><a href="<?php echo $data['text_pinterest_profile']; ?>" class="mk-social-pinterest" title="View Pinterest Profile"></a></li>
                                        <?php } if (isset($data["text_googleplus_profile"])){ ?>
                                            <li><a href="<?php echo $data['text_googleplus_profile']; ?>" class="mk-social-googleplus" title="View Google + Profile"></a></li>
                                        <?php } if (isset($data["text_tumblr_profile"])){ ?>
                                            <li><a href="<?php echo $data['text_tumblr_profile']; ?>" class="mk-social-tumblr" title="View Tumblr Profile"></a></li>
                                        <?php } if (isset($data["text_soundcloud_profile"])){ ?>
                                            <li><a href="<?php echo $data['text_soundcloud_profile']; ?>" class="mk-social-soundcloud" title="View Soundcloud Profile"></a></li>
                                        <?php } if (isset($data["text_lastfm_profile"])){ ?>
                                            <li><a href="<?php echo $data['text_lastfm_profile']; ?>" class="mk-social-lastfm" title="View Last FM Profile"></a></li>
                                        <?php } ?>
                                    </ul>

                                    <div class="copyright">
                                        <p id="copyright-details">&copy; <?php echo date('Y') ?> <?php echo bloginfo('name'); ?>. <?php global $data; echo $data['textarea_footer_text']; ?></p>
                                    </div>

                                </div>

                                <div class="contact-details-2">
                                    <h2><?php if (isset($data["text_twitter_profile"])) { ?>
                                        <?php echo $data['title_contact_details_2']; ?>
                                        <?php } ?>
                                    </h2>
                                    <ul>
                                        <li><?php if (isset($data["text_twitter_profile"])) { ?>
                                            <?php echo $data['text_contact_phone']; ?>
                                            <?php } ?>
                                        </li>
                                        <li><?php if (isset($data["text_twitter_profile"])) { ?>
                                            <?php echo $data['text_contact_mail']; ?>
                                            <?php } ?>
                                        </li>
                                        <li><?php if (isset($data["text_twitter_profile"])) { ?>
                                            <?php echo $data['text_contact_website']; ?>
                                            <?php } ?>
                                        </li>
                                    </ul>
                                </div>

                            </div><!-- end .footer-content -->

                        </div><!-- end .row -->

                    </div><!-- end .slide-content -->

                </div><!-- end .last-slide -->


		<?php wp_footer(); ?>

    </body>

</html>
