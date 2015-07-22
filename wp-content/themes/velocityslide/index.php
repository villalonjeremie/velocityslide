<?php
/**
 *
 * Description: Main Homepage template.
 *
 */
get_header();?>


        <div class="powerslide slider-wrapper">
            <div class="slider">                
                <div class="slide slide--0" data-slideTitle="Home">
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


                <div class="slide slide--split slide--1 effect" data-slideTitle="Urban aggression" data-slideTransition="split">
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


                <div class="slide slide--split slide--2" data-slideTitle="Native vegetal cells" data-slideTransition="split">
                    <div class="slide--split__left">
                        <div class="s-table"><div class="s-table-cell">
                            <h2>
                                The Day
                                <span>WITH THE SERUM</span>
                            </h2>
                            <h3>
                                9x stronger<br>
                                anti-free radical power<sup>*</sup>
                            </h3>
                            <p>
                                HR Laboratories have identified<br>
                                the quintessence of self regeneration:
                            </p>
                            <p>
                                <strong>100% active vegetal cells</strong><br>
                                of Oceanic Crista &amp; Sea Holly
                            </p>
                        </div></div>
                        <p class="star">
                            <sup>*</sup> vs Powercell serum 1st generation
                        </p>
                    </div>
                    <div class="slide--split__right">
                        <div class="s-table"><div class="s-table-cell">
                            <h2>
                                THE NIGHT
                                <span>WITH skin rehab</span>
                            </h2>
                            <h3>
                                The power of 150 million native<br>
                                vegetal cells in one bottle
                            </h3>
                            <p>
                                The deep sleep phase is the most<br> 
                                conductive moment for cell regeneration:
                            </p>
                            <p>
                                The 150 million of native vegetal cells restore<br>
                                &amp; reactivate skin cell activity despite hectic life sleep.
                            </p>

                        </div></div>
                    </div>
                </div>


                <div class="slide slide--split slide--3" data-slideTitle="The Powercell duo" data-slideTransition="split">
                    <div class="slide--split__left">
                        <div class="s-table"><div class="s-table-cell">
                            <h2>
                                The Day
                                <span>WITH THE SERUM</span>
                            </h2>
                            <h3>                                
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/img/powercell.png" alt="Powercell">
                                <span class="logo-product"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/youth-grafter.png" alt="Youth Grafter"></span>
                                PROTECT &amp; REINFORCE
                            </h3>
                            <p>
                                <em>THE SUPERCHARGED SERUM</em>
                                TO BOOST SKIN’S YOUTH VISIBLY
                            </p>
                            <ul>
                                <li>
                                    Smoothed wrinkles &amp; texture
                                </li>
                                <li>
                                    Boosted tonicity
                                </li>
                                <li>
                                    Instant healthy radiance
                                </li>
                            </ul>
                        </div></div>
                    </div>
                    <div class="slide--split__right">
                        <div class="s-table"><div class="s-table-cell">
                            <h2>
                                THE NIGHT
                                <span>WITH skin rehab</span>
                            </h2>
                            <h3>                                
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/img/powercell.png" alt="Powercell">
                                <span class="logo-product logo-skinrehab"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/slide3/skinrehab.png" alt="Skin Rehab" ></span>
                                DETOX &amp; REVITALIZE
                            </h3>
                            <p>
                                <em>THE 1st NIGHT MASK-IN-ESSENCE</em>
                                TO START EVERY DAY LOOKING YOUNGER
                            </p>
                            <ul>
                                <li>
                                    Smoother rested features 
                                </li>
                                <li>
                                    Pure radiant complexion
                                </li>
                                <li>
                                    Reinvigorated younger skin
                                </li>
                            </ul>
                        </div></div>
                    </div>
                </div>


                <div class="slide slide--split slide--4" data-slideTitle="The results" data-slideTransition="split">
                    <div class="slide--split__left">
                        <div class="s-table"><div class="s-table-cell">
                            <h2>
                                The Day
                                <span>WITH THE SERUM</span>
                            </h2>
                            <h3>
                                PROVEN EFFICACY<br>
                                AFTER 10 MONTHS:<sup>*</sup>
                            </h3>
                            <p class="big">
                                <strong>+ 43%</strong> on global skin visible quality:<br>
                                radiance, tonicity, wrinkles
                            </p>
                            <p>
                                powercell serum results
                            </p>
                            <a href="#popin-slide4-1" class="popin-button window-open">
                                Discover More
                            </a>
                        </div></div>
                        <p class="star">
                            <sup>*</sup> Clinical gradings, 52 caucasian women
                        </p>
                    </div>
                    <div class="slide--split__right">
                        <div class="s-table"><div class="s-table-cell">
                            <h2>
                                THE NIGHT
                                <span>WITH skin rehab</span>
                            </h2>
                            <h3>
                                UPON WAKING,<br>
                                WITH THE NEW NIGHT D-TOXER:<sup>**</sup>
                            </h3>
                            <p class="big">
                                More tonicity: <strong>100%</strong><br>
                                Visibly younger skin: <strong>98%</strong>
                            </p>
                            <p>
                                Powercell Skin Rehab Results
                            </p>
                            <a href="#popin-slide4-2" class="popin-button window-open">
                                Discover More
                            </a>
                        </div></div>
                        <p class="star">
                            <sup>**</sup> self assessment, 53 asian women, after 5 days of use.
                        </p>
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