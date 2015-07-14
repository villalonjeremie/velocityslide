	<footer id="footer-global" role="contentinfo" class="clearfix">
		
		<section id="contact">
		
			<div class="container">
			
				<div class="row">
		
					<div class="sixteen columns">
						
						<?php
						global $data; ?>
						
						<h1><?php echo $data['text_contact_us_title']; ?><span>.</span></h1>
						
						<p><?php echo do_shortcode(stripslashes($data['textarea_contact_us_overview'])); ?></p>
						
					</div>		
					
				</div><!-- end .row -->
				
				<div class="row">
				
					<div class="sixteen columns">
				
						<div class="eight columns alpha">
						
							<form id="contact-form" action="<?php echo get_template_directory_uri(); ?>/form/form.php" method="post">
							
								<input name="name" type="text" placeholder="Your Name (required)">
		
							    <input name="email" type="email" placeholder="Your Email (required)">
		
							    <textarea name="message" placeholder="Please enter your Message..."></textarea>
							    
							    <p id="user">
							    <input name="username" type="text" placeholder="Your Username">
							    </p>
							            
							    <input id="submit" name="submit" type="submit" value="Submit">
							        
							</form>
							
							<div id="response"></div>
							
						</div><!-- end .eight columns alpha -->
					
						<div class="eight columns omega">
						
							<address id="contact-details">
								
								<ul>
							    	<li><i class="icon-map-marker"></i> <?php echo $data['text_contact_address']; ?></li>
							        <li><i class="icon-phone"></i> <?php echo $data['text_contact_telephone']; ?></li>
							        <li><i class="icon-print"></i> <?php echo $data['text_contact_fax']; ?></li>
							        <li><i class="icon-envelope-alt"></i> <a href="mailto:<?php echo $data['text_contact_email']; ?>"><?php echo $data['text_contact_email']; ?></a></li>
							    </ul>
							
							</address>
							
							<ul class="social-icons footer">
							<?php if ($data["text_twitter_profile"]) { ?>
								<li><a href="<?php echo $data['text_twitter_profile']; ?>" class="mk-social-twitter-alt" title="View Twitter Profile"></a></li>
							<?php } if ($data["text_facebook_profile"]){ ?>
								<li><a href="<?php echo $data['text_facebook_profile']; ?>" class="mk-social-facebook" title="View Facebook Profile"></a></li>
							<?php } if ($data["text_dribbble_profile"]){ ?>
								<li><a href="<?php echo $data['text_dribbble_profile']; ?>" class="mk-social-dribbble" title="View Dribbble Profile"></a></li>
							<?php } if ($data["text_forrst_profile"]){ ?>
								<li><a href="<?php echo $data['text_forrst_profile']; ?>" class="mk-social-forrst" title="View Forrst Profile"></a></li>
							<?php } if ($data["text_vimeo_profile"]){ ?>
								<li><a href="<?php echo $data['text_vimeo_profile']; ?>" class="mk-social-vimeo" title="View Vimeo Profile"></a></li>
							<?php } if ($data["text_youtube_profile"]){ ?>
								<li><a href="<?php echo $data['text_youtube_profile']; ?>" class="mk-social-youtube" title="View YouTube Profile"></a></li>
							<?php } if ($data["text_flickr_profile"]){ ?>
								<li><a href="<?php echo $data['text_flickr_profile']; ?>" class="mk-social-flickr" title="View Flickr Profile"></a></li>
							<?php } if ($data["text_linkedin_profile"]){ ?>
								<li><a href="<?php echo $data['text_linkedin_profile']; ?>" class="mk-social-linkedin" title="View Linkedin Profile"></a></li>
							<?php } if ($data["text_pinterest_profile"]){ ?>
								<li><a href="<?php echo $data['text_pinterest_profile']; ?>" class="mk-social-pinterest" title="View Pinterest Profile"></a></li>
							<?php } if ($data["text_googleplus_profile"]){ ?>
								<li><a href="<?php echo $data['text_googleplus_profile']; ?>" class="mk-social-googleplus" title="View Google + Profile"></a></li>
							<?php } if ($data["text_tumblr_profile"]){ ?>
								<li><a href="<?php echo $data['text_tumblr_profile']; ?>" class="mk-social-tumblr" title="View Tumblr Profile"></a></li>
							<?php } if ($data["text_soundcloud_profile"]){ ?>
								<li><a href="<?php echo $data['text_soundcloud_profile']; ?>" class="mk-social-soundcloud" title="View Soundcloud Profile"></a></li>
							<?php } if ($data["text_lastfm_profile"]){ ?>
								<li><a href="<?php echo $data['text_lastfm_profile']; ?>" class="mk-social-lastfm" title="View Last FM Profile"></a></li>
							<?php } ?>
							</ul>
							
						</div><!-- end .eight columns omega -->
						
					</div><!-- end .sixteen columns -->

				</div><!-- end .row -->
				
				<div class="row">
				
					<div class="sixteen columns">
				  	  
			  			<p id="copyright-details">&copy; <?php echo date('Y') ?> <?php echo bloginfo('name'); ?>. <?php global $data; echo $data['textarea_footer_text']; ?></p>
			  		
			  		</div>
			  		
			  	</div><!-- end .row -->  
		  	
			</div><!-- end .container -->
		
		</section><!-- end #contact -->
		
	</footer><!-- end #footer-global -->
	
<script type="text/javascript">
function scrollTo(target) {
    var targetPosition = $(target).offset().top;
    $('html,body').animate({
        scrollTop: targetPosition
    }, 'slow');
}
jQuery(document).ready(function () {
    jQuery('nav ul').mobileMenu({
        defaultText: '<?php _e("Navigation", "kula");?>',
        className: 'mobile-menu',
        subMenuDash: '&ndash;'
    });
});
</script>

<?php echo $data['google_analytics']; ?>
	
<?php wp_footer(); ?>
	
</body>

</html>