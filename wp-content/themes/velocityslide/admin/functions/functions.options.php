<?php

add_action('init','of_options');

if (!function_exists('of_options'))
{
	function of_options()
	{
		//Access the WordPress Categories via an Array
		$of_categories 		= array();  
		$of_categories_obj 	= get_categories('hide_empty=0');
		foreach ($of_categories_obj as $of_cat) {
		    $of_categories[$of_cat->cat_ID] = $of_cat->cat_name;}
		$categories_tmp 	= array_unshift($of_categories, "Select a category:");    
	       
		//Access the WordPress Pages via an Array
		$of_pages 			= array();
		$of_pages_obj 		= get_pages('sort_column=post_parent,menu_order');    
		foreach ($of_pages_obj as $of_page) {
		    $of_pages[$of_page->ID] = $of_page->post_name; }
		$of_pages_tmp 		= array_unshift($of_pages, "Select a page:");       
	
		$of_options_radio 	= array("two" => "Two","four" => "Four","six" => "Six");
		
		//Sample Homepage blocks for the layout manager (sorter)
		$of_options_homepage_blocks = array
		( 
			"disabled" => array (
				"placebo" 		=> "placebo", //REQUIRED!
				"presentation"	=> "Presentation",
				"services"		=> "Services",
				"portfolio"		=> "Portfolio",
				"blog"			=> "Blog",
			), 
			"enabled" => array (
				"placebo" 		=> "placebo", //REQUIRED!
			),
		);


		//Stylesheets Reader
		$alt_stylesheet_path = LAYOUT_PATH;
		$alt_stylesheets = array();
		
		if ( is_dir($alt_stylesheet_path) ) 
		{
		    if ($alt_stylesheet_dir = opendir($alt_stylesheet_path) ) 
		    { 
		        while ( ($alt_stylesheet_file = readdir($alt_stylesheet_dir)) !== false ) 
		        {
		            if(stristr($alt_stylesheet_file, ".css") !== false)
		            {
		                $alt_stylesheets[] = $alt_stylesheet_file;
		            }
		        }    
		    }
		}


		//Background Images Reader
		$bg_images_path = get_stylesheet_directory(). '/images/bg/'; // change this to where you store your bg images
		$bg_images_url = get_template_directory_uri().'/images/bg/'; // change this to where you store your bg images
		$bg_images = array();
		
		if ( is_dir($bg_images_path) ) {
		    if ($bg_images_dir = opendir($bg_images_path) ) { 
		        while ( ($bg_images_file = readdir($bg_images_dir)) !== false ) {
		            if(stristr($bg_images_file, ".png") !== false || stristr($bg_images_file, ".jpg") !== false) {
		            	natsort($bg_images); //Sorts the array into a natural order
		                $bg_images[] = $bg_images_url . $bg_images_file;
		            }
		        }    
		    }
		}
		

		/*-----------------------------------------------------------------------------------*/
		/* TO DO: Add options/functions that use these */
		/*-----------------------------------------------------------------------------------*/
		
		//More Options
		$uploads_arr 		= wp_upload_dir();
		$all_uploads_path 	= $uploads_arr['path'];
		$all_uploads 		= get_option('of_uploads');
		$other_entries 		= array("Select a number:","1","2","3","4","5","6","7","8","9","10","11","12","13","14","15","16","17","18","19");
		$body_repeat 		= array("no-repeat","repeat-x","repeat-y","repeat");
		$body_pos 			= array("top left","top center","top right","center left","center center","center right","bottom left","bottom center","bottom right");
		
		// Image Alignment radio box
		$of_options_thumb_align = array("alignleft" => "Left","alignright" => "Right","aligncenter" => "Center"); 
		
		// Image Links to Options
		$of_options_image_link_to = array("image" => "The Image","post" => "The Post"); 


/*-----------------------------------------------------------------------------------*/
/* The Options Array */
/*-----------------------------------------------------------------------------------*/

// Set the Options Array
global $of_options;
$of_options = array();

$of_options[] = array( 	"name" 		=> __('Homepage Settings', 'velocityslide'),
						"type" 		=> "heading"
				);
				
$of_options[] = array( 	"name" 		=> __('Hello There!', 'velocityslide'),
						"desc" 		=> "",
						"id" 		=> "introduction",
						"std" 		=> __('<h3 style=\"margin: 0 0 10px;\">Welcome to the Options velocity slide</h3><br>Organize how you want the layout to appear on the homepage.<br /><br />You can choose to enable/disable sections via drag & drop, or re-order their stacking order on the homepage.<br /><br />NB; Let the fields empty for not display', 'velocityslide'),
						"icon" 		=> true,
						"type" 		=> "info"
				);

$of_options[] = array( 	"name" 		=> __('Homepage Layout Manager', 'velocityslide'),
						"desc" 		=> __('Organize how you want the layout','velocityslide'),
						"id" 		=> "blocks_homepage",
						"std" 		=> $of_options_homepage_blocks,
						"type" 		=> "sorter"
				);

$of_options[] = array( 	"name" 		=> __('Background upload', 'velocityslide'),
						"desc" 		=> __('Upload your background for homepage', 'velocityslide'),
						"id" 		=> "bg_homepage",
						"std" 		=> "",
						"mod"		=> "min",
						"type" 		=> "media"
				);

$of_options[] = array( 	"name" 		=> __('Title', 'velocityslide'),
						"desc" 		=> __('Please enter a title for the homepage section. (eg; Hear what we have to say. It is all good.)', 'velocityslide'),
						"id" 		=> "title_homepage",
						"std" 		=> "",
						"type" 		=> "text"
				);

$of_options[] = array( 	"name" 		=> __('Description', 'velocityslide'),
						"desc" 		=> __('You can add a short description to appear in this block.<br /><br /><em>*HTML tags are allowed.</em>', 'velocityslide'),
						"id" 		=> "text_homepage",
						"std" 		=> "",
						"type" 		=> "text"
				);

$of_options[] = array( 	"name" 		=> __('Title button', 'velocityslide'),
						"desc" 		=> __('Please enter a global name for your button (eg; Launch Project or Visit Website).', 'velocityslide'),
						"id" 		=> "text_button_homepage",
						"std" 		=> "",
						"type" 		=> "text"
				);

$of_options[] = array( 	"name" 		=> __('Url button', 'velocityslide'),
						"desc" 		=> __('Please enter an url link for your button (eg; http://www.webaffinity.com).', 'velocityslide'),
						"id" 		=> "url_button_homepage",
						"std" 		=> "",
						"type" 		=> "text"
				);

$of_options[] = array( 	"name" 		=> __('Presentation Settings', 'velocityslide'),
						"type" 		=> "heading"
				);
				

$of_options[] = array( 	"name" 		=> __('Left background upload', 'velocityslide'),
						"desc" 		=> __('Upload your left background for presentation', 'velocityslide'),
						"id" 		=> "left_bg_presentation",
						"std" 		=> "",
						"mod"		=> "min",
						"type" 		=> "media"
				);

$of_options[] = array( 	"name" 		=> __('Right background upload', 'velocityslide'),
						"desc" 		=> __('Upload your right background for presentation', 'velocityslide'),
						"id" 		=> "right_bg_presentation",
						"std" 		=> "",
						"mod"		=> "min",
						"type" 		=> "media"
				);

$of_options[] = array( 	"name" 		=> __('Left content', 'velocityslide'),
                        "desc" 		=> __('For displaying content on left panel', 'velocityslide'),
                        "id" 		=> "left_content_presentation",
                        "std" 		=> 0,
                        "folds"		=> 1,
                        "type" 		=> "switch"
                    );

$of_options[] = array( 	"name" 		=> __('Left Title', 'velocityslide'),
						"desc" 		=> __('Please enter a title for the presentation section. (eg; Hear what we have to say. It is all good.)', 'velocityslide'),
						"id" 		=> "left_title_presentation",
						"std" 		=> "",
                        "fold"		=> "left_content_presentation",
						"type" 		=> "text"
				);

$of_options[] = array( 	"name" 		=> __('Left Subtitle', 'velocityslide'),
						"desc" 		=> __('You can add a subtitle to appear in left block.<br /><br /><em>*HTML tags are allowed.</em>', 'velocityslide'),
						"id" 		=> "left_subtitle_presentation",
						"std" 		=> "",
                        "fold"		=> "left_content_presentation",
						"type" 		=> "text"
				);

$of_options[] = array( 	"name" 		=> __('Left Description', 'velocityslide'),
						"desc" 		=> __('You can add a short description to appear in left block.<br /><br /><em>*HTML tags are allowed.</em>', 'velocityslide'),
						"id" 		=> "left_description_presentation",
						"std" 		=> "",
                        "fold"		=> "left_content_presentation",
						"type" 		=> "textarea"
				);

$of_options[] = array( 	"name" 		=> __('Right content', 'velocityslide'),
                        "desc" 		=> __('For displaying content on right panel', 'velocityslide'),
                        "id" 		=> "right_content_presentation",
                        "std" 		=> 0,
                        "folds"		=> 1,
                        "type" 		=> "switch"
                    );

$of_options[] = array( 	"name" 		=> __('Right Title', 'velocityslide'),
						"desc" 		=> __('Please enter a title for the presentation section. (eg; Hear what we have to say. It is all good.)', 'velocityslide'),
						"id" 		=> "right_title_presentation",
						"std" 		=> "",
                        "fold"		=> "right_content_presentation",
						"type" 		=> "text"
				);

$of_options[] = array( 	"name" 		=> __('Right Subtitle', 'velocityslide'),
						"desc" 		=> __('You can add a subtitle to appear in right block.<br /><br /><em>*HTML tags are allowed.</em>', 'velocityslide'),
						"id" 		=> "right_subtitle_presentation",
						"std" 		=> "",
                        "fold"		=> "right_content_presentation",
						"type" 		=> "text"
				);

$of_options[] = array( 	"name" 		=> __('Right Description', 'velocityslide'),
						"desc" 		=> __('You can add a short description to appear in right block.<br /><br /><em>*HTML tags are allowed.</em>', 'velocityslide'),
						"id" 		=> "right_description_presentation",
						"std" 		=> "",
                        "fold"		=> "right_content_presentation",
						"type" 		=> "textarea"
				);

$of_options[] = array( 	"name" 		=> __('Services Settings', 'velocityslide'),
						"type" 		=> "heading"
				);

$of_options[] = array( 	"name" 		=> __('Left background upload', 'velocityslide'),
						"desc" 		=> __('Upload your left background for services section', 'velocityslide'),
						"id" 		=> "left_bg_services",
						"std" 		=> "",
						"mod"		=> "min",
						"type" 		=> "media"
				);

$of_options[] = array( 	"name" 		=> __('Right background upload', 'velocityslide'),
						"desc" 		=> __('Upload your right background for services section', 'velocityslide'),
						"id" 		=> "right_bg_services",
						"std" 		=> "",
						"mod"		=> "min",
						"type" 		=> "media"
				);

$of_options[] = array( 	"name" 		=> __('Select services', 'velocityslide'),
						"desc" 		=> __('Select your count services that you want to display.', 'velocityslide'),
						"id" 		=> "select_services",
						"std" 		=> "two",
						"type" 		=> "select",
						"mod" 		=> "mini",
						"options" 	=> $of_options_radio
				); 


$of_options[] = array( 	"name" 		=> __('Portfolios Settings', 'velocityslide'),
						"type" 		=> "heading"
				);
				

$of_options[] = array( 	"name" 		=> __('Left background upload', 'velocityslide'),
						"desc" 		=> __('Upload your left background for portfolios', 'velocityslide'),
						"id" 		=> "left_bg_portfolios",
						"std" 		=> "",
						"mod"		=> "min",
						"type" 		=> "media"
				);

$of_options[] = array( 	"name" 		=> __('Right background upload', 'velocityslide'),
						"desc" 		=> __('Upload your right background for portfolios', 'velocityslide'),
						"id" 		=> "right_bg_portfolios",
						"std" 		=> "",
						"mod"		=> "min",
						"type" 		=> "media"
				);



$of_options[] = array( 	"name" 		=> __('Left content', 'velocityslide'),
                        "desc" 		=> __('For displaying content on left panel', 'velocityslide'),
                        "id" 		=> "left_content_portfolio",
                        "std" 		=> 0,
                        "folds"		=> 1,
                        "type" 		=> "switch"
                    );

$of_options[] = array( 	"name" 		=> __('Left title', 'velocityslide'),
						"desc" 		=> __('Please enter a title for the portfolios section. (eg; Hear what we have to say. It is all good.)', 'velocityslide'),
						"id" 		=> "left_title_portfolios",
						"std" 		=> "",
						"fold" 		=> "left_content_portfolio",
						"type" 		=> "text"
				);

$of_options[] = array( 	"name" 		=> __('Left description', 'velocityslide'),
						"desc" 		=> __('You can add a short description to appear in left block.<br /><br /><em>*HTML tags are allowed.</em>', 'velocityslide'),
						"id" 		=> "left_description_portfolios",
						"std" 		=> "",
						"fold" 		=> "left_content_portfolio",
						"type" 		=> "textarea"
				);


$of_options[] = array( 	"name" 		=> __('Right content', 'velocityslide'),
                        "desc" 		=> __('For displaying content on right panel', 'velocityslide'),
                        "id" 		=> "right_content_portfolio",
                        "std" 		=> 0,
                        "folds"		=> 1,
                        "type" 		=> "switch"
                    );

$of_options[] = array( 	"name" 		=> __('Right title', 'velocityslide'),
						"desc" 		=> __('Please enter a title for the portfolios section. (eg; Hear what we have to say. It is all good.)', 'velocityslide'),
						"id" 		=> "right_title_portfolios",
						"std" 		=> "",
						"fold" 		=> "right_content_portfolio",
						"type" 		=> "text"
				);

$of_options[] = array( 	"name" 		=> __('Right description', 'velocityslide'),
						"desc" 		=> __('You can add a short description to appear in right block.<br /><br /><em>*HTML tags are allowed.</em>', 'velocityslide'),
						"id" 		=> "right_description_portfolios",
						"std" 		=> "",
						"fold" 		=> "right_content_portfolio",
						"type" 		=> "textarea"
				);


$of_options[] = array( 	"name" 		=> __('Left popup display', 'velocityslide'),
                        "desc" 		=> __('For displaying button and popup', 'velocityslide'),
                        "id" 		=> "side_left_popup_switch",
                        "std" 		=> 0,
                        "folds"		=> 1,
                        "type" 		=> "switch"
                    );


$of_options[] = array( 	"name" 		=> __('Left title popup', 'velocityslide'),
                        "desc" 		=> __('Please enter a global name for your popup (eg; Launch Project or Visit Website).', 'velocityslide'),
                        "id" 		=> "left_title_popup_portfolios",
                        "std" 		=> "",
                        "fold"		=> "side_left_popup_switch",
                        "type" 		=> "text"
                    );

$of_options[] = array( 	"name" 		=> __('Left description popup', 'velocityslide'),
                        "desc" 		=> __('Please enter a description for your popup (eg; Launch Project or Visit Website).', 'velocityslide'),
                        "id" 		=> "left_description_popup_portfolios",
                        "std" 		=> "",
                        "fold"		=> "side_left_popup_switch",
                        "type" 		=> "textarea"
                    );

$of_options[] = array( 	"name" 		=> __('Left title button', 'velocityslide'),
						"desc" 		=> __('Please enter a global name for your button (eg; Launch Project or Visit Website).', 'velocityslide'),
						"id" 		=> "left_text_button_portfolios",
						"std" 		=> "",
						"fold" 		=> "side_left_popup_switch",
						"type" 		=> "text"
				);

$of_options[] = array( 	"name" 		=> __('Right popup display', 'velocityslide'),
                        "desc" 		=> __('For displaying button and popup', 'velocityslide'),
                        "id" 		=> "side_right_popup_switch",
                        "std" 		=> 0,
                        "folds"		=> 1,
                        "type" 		=> "switch"
                    );

$of_options[] = array( 	"name" 		=> __('Right title popup', 'velocityslide'),
                        "desc" 		=> __('Please enter a global name for your popup (eg; Launch Project or Visit Website).', 'velocityslide'),
                        "id" 		=> "right_title_popup_portfolios",
                        "std" 		=> "",
						"fold" 		=> "side_right_popup_switch",
                        "type" 		=> "text"
                );

$of_options[] = array( 	"name" 		=> __('Right description popup', 'velocityslide'),
                        "desc" 		=> __('Please enter a description for your popup (eg; Launch Project or Visit Website).', 'velocityslide'),
                        "id" 		=> "right_description_popup_portfolios",
                        "std" 		=> "",
						"fold" 		=> "side_right_popup_switch",
                        "type" 		=> "textarea"
                    );
$of_options[] = array( 	"name" 		=> __('Right title button', 'velocityslide'),
						"desc" 		=> __('Please enter a global name for your button (eg; Launch Project or Visit Website).', 'velocityslide'),
						"id" 		=> "right_text_button_portfolios",
						"std" 		=> "",
						"fold" 		=> "side_right_popup_switch",
						"type" 		=> "text"
				);


$of_options[] = array( 	"name" 		=> __('Blog Settings', 'velocityslide'),
						"type" 		=> "heading"
				);

$of_options[] = array( 	"name" 		=> __('Blog side', 'velocityslide'),
						"desc" 		=> __('Blog in right (on),Blog in left (off)', 'velocityslide'),
						"id" 		=> "side_blog",
						"std" 		=> 0,
						"type" 		=> "switch"
				);

$of_options[] = array( 	"name" 		=> __('Left background upload', 'velocityslide'),
						"desc" 		=> __('Upload your left background for blog section', 'velocityslide'),
						"id" 		=> "left_bg_blog",
						"std" 		=> "",
						"mod"		=> "min",
						"type" 		=> "media"
				);

$of_options[] = array( 	"name" 		=> __('Right background upload', 'velocityslide'),
						"desc" 		=> __('Upload your right background for blog section', 'velocityslide'),
						"id" 		=> "right_bg_blog",
						"std" 		=> "",
						"mod"		=> "min",
						"type" 		=> "media"
				);

$of_options[] = array( 	"name" 		=> __('Contact Settings', 'velocityslide'),
						"type" 		=> "heading"
				);		
					
$of_options[] = array(  "name"		=> __('Contact Address', 'velocityslide'),
					    "desc"		=> __('Please enter your company address (eg; 10 Columbus Circle, New York, NY 10019, United States.)', 'velocityslide'),
					    "id"		=> "text_address_contact",
						"std"       => "",
						"type"		=> "text"
				);
					
$of_options[] = array(  "name"		=> __('Contact Telephone Number', 'velocityslide'),
					    "desc"		=> __('Please enter your company telephone number (eg; (212) 823-6000.)', 'velocityslide'),
					    "id"		=> "text_telephone_contact",
					    "std"		=> "",
					    "type"		=> "text");
					
$of_options[] = array(  "name"		=> __('Fax Telephone Number', 'velocityslide'),
					    "desc"		=> __('Please enter your company fax number (eg; (212) 823-6005.)', 'velocityslide'),
						"id"		=> "text_fax_contact",
						"std"		=> "",
						"type"		=> "text");	
					
$of_options[] = array(  "name"		=> __('Contact Email', 'velocityslide'),
						"desc"		=> __('Please enter your company email address (eg; marc@guuthemes.com) This will also populate into your Contact Form.', 'velocityslide'),
						"id"		=> "text_email_contact",
						"std"		=> "",
						"type"		=> "text");			

$of_options[] = array( 	"name" 		=> __('General Settings', 'velocityslide'),
						"type" 		=> "heading"
				);

$of_options[] = array( 	"name" 		=> __('Background upload', 'velocityslide'),
						"desc" 		=> __('Upload your background for contact', 'velocityslide'),
						"id" 		=> "bg_contact",
						"std" 		=> "",
						"mod"		=> "min",
						"type" 		=> "media"
				);
					
				
$of_options[] = array( 	"name" 		=> __('Tracking Code', 'velocityslide'),
						"desc" 		=> __('Paste your Google Analytics (or other) tracking code here. This will be added into the footer template of your theme.', 'velocityslide'),
						"id" 		=> "google_analytics",
						"std" 		=> "",
						"type" 		=> "textarea"
				);
				
$of_options[] = array( 	"name" 		=> __('Footer Text', 'velocityslide'),
						"desc" 		=> __('You can use the following shortcodes in your footer text: [wp-link] [theme-link] [loginout-link] [blog-title] [blog-link] [the-year]', 'velocityslide'),
						"id" 		=> "footer_text",
						"std" 		=> "Powered by [wp-link]. Built on the [theme-link].",
						"type" 		=> "textarea"
				);
				
$of_options[] = array( 	"name" 		=> __('Styling Options', 'velocityslide'),
						"type" 		=> "heading"
				);
				
$of_options[] = array( 	"name" 		=> __('Body Background Color', 'velocityslide'),
						"desc" 		=> __('Pick a background color for the theme (default: #fff)', 'velocityslide'),
						"id" 		=> "body_background",
						"std" 		=> "",
						"type" 		=> "color"
				);
				
$of_options[] = array( 	"name" 		=> __('Body Font', 'velocityslide'),
						"desc" 		=> __('Specify the body font properties', 'velocityslide'),
						"id" 		=> "body_font",
						"std" 		=> array('size' => '12px','face' => 'arial','style' => 'normal','color' => '#000000'),
						"type" 		=> "typography"
				);  
				
$of_options[] = array( 	"name" 		=> __('Custom CSS', 'velocityslide'),
						"desc" 		=> __('Quickly add some CSS to your theme by adding it to this block.', 'velocityslide'),
						"id" 		=> "custom_css",
						"std" 		=> "",
						"type" 		=> "textarea"
				); 

				
$of_options[] = array( 	"name" 		=> __('Google Font Select', 'velocityslide'),
						"desc" 		=> __('Some description. Note that this is a custom text added from options file.', 'velocityslide'),
						"id" 		=> "g_select",
						"std" 		=> "Select a font",
						"type" 		=> "select_google_font",
						"preview" 	=> array(
										"text" => "This is my preview text!", //this is the text from preview box
										"size" => "30px" //this is the text size from preview box
						),
						"options" 	=> array(
										"none" => "Select a font",//please, always use this key: "none"
										"Lato" => "Lato",
										"Loved by the King" => "Loved By the King",
										"Tangerine" => "Tangerine",
										"Terminal Dosis" => "Terminal Dosis"
						)
				);
				
// Backup Options
$of_options[] = array( 	"name" 		=> __('Backup Options', 'velocityslide'),
						"type" 		=> "heading",
						"icon"		=> ADMIN_IMAGES . "icon-slider.png"
				);
				
$of_options[] = array( 	"name" 		=> __('Backup and Restore Options', 'velocityslide'),
						"id" 		=> "of_backup",
						"std" 		=> "",
						"type" 		=> "backup",
						"desc" 		=> __('You can use the two buttons below to backup your current options, and then restore it back at a later time. This is useful if you want to experiment on the options but would like to keep the old settings in case you need it back.', 'velocityslide'),
				);
				
$of_options[] = array( 	"name" 		=> __('Transfer Theme Options Data', 'velocityslide'),
						"id" 		=> "of_transfer",
						"std" 		=> "",
						"type" 		=> "transfer",
						"desc" 		=> __('You can tranfer the saved options data between different installs by copying the text inside the text box. To import data from another install, replace the data in the text box with the one from another install and click "Import Options".', 'velocityslide'),
				);


/*				
$of_options[] = array( 	"name" 		=> "JQuery UI Slider example 1 with steps(5)",
						"desc" 		=> "JQuery UI slider description.<br /> Min: 0, max: 300, step: 5, default value: 75",
						"id" 		=> "slider_example_2",
						"std" 		=> "75",
						"min" 		=> "0",
						"step"		=> "5",
						"max" 		=> "300",
						"type" 		=> "sliderui" 
				);

$of_options[] = array( 	"name" 		=> "JQuery UI Spinner",
						"desc" 		=> "JQuery UI spinner description.<br /> Min: 0, max: 300, step: 5, default value: 75",
						"id" 		=> "spinner_example_2",
						"std" 		=> "75",
						"min" 		=> "0",
						"step"		=> "5",
						"max" 		=> "300",
						"type" 		=> "spinner" 
				);
				

				
				
$of_options[] = array( 	"name" 		=> "Slider Options",
						"desc" 		=> "Unlimited slider with drag and drop sortings.",
						"id" 		=> "pingu_slider",
						"std" 		=> "",
						"type" 		=> "slider"
				);
					
$of_options[] = array( 	"name" 		=> "Background Images",
						"desc" 		=> "Select a background pattern.",
						"id" 		=> "custom_bg",
						"std" 		=> $bg_images_url."bg0.png",
						"type" 		=> "tiles",
						"options" 	=> $bg_images,
				);

$of_options[] = array( 	"name" 		=> __('Theme Stylesheet', 'velocityslide'),
						"desc" 		=> __('Select your themes alternative color scheme.', 'velocityslide'),
						"id" 		=> "alt_stylesheet",
						"std" 		=> "default.css",
						"type" 		=> "select",
						"options" 	=> $alt_stylesheets
				);

$of_options[] = array( 	"name" 		=> "Border",
						"desc" 		=> "This is a border specific option.",
						"id" 		=> "border",
						"std" 		=> array(
											'width' => '2',
											'style' => 'dotted',
											'color' => '#444444'
										),
						"type" 		=> "border"
				);

				
$of_options[] = array( 	"name" 		=> "Input Checkbox (false)",
						"desc" 		=> "Example checkbox with false selected.",
						"id" 		=> "example_checkbox_false",
						"std" 		=> 0,
						"type" 		=> "checkbox"
				);

$of_options[] = array( 	"name" 		=> "Input Radio (one)",
						"desc" 		=> "Radio select with default of 'one'.",
						"id" 		=> "example_radio",
						"std" 		=> "one",
						"type" 		=> "radio",
						"options" 	=> $of_options_radio
				);

$of_options[] = array( 	"name" 		=> "Multicheck",
						"desc" 		=> "Multicheck description.",
						"id" 		=> "example_multicheck",
						"std" 		=> array("three","two"),
						"type" 		=> "multicheck",
						"options" 	=> $of_options_radio
				);

$of_options[] = array( 	"name" 		=> "Hidden option 1",
						"desc" 		=> "This is a sample hidden option 1",
						"id" 		=> "hidden_option_1",
						"std" 		=> "Hi, I\'m just a text input",
						"fold" 		=> "offline", 
						"type" 		=> "text"
				);

 
				
$of_options[] = array( 	"name" 		=> "Select a Category",
						"desc" 		=> "A list of all the categories being used on the site.",
						"id" 		=> "example_category",
						"std" 		=> "Select a category:",
						"type" 		=> "select",
						"options" 	=> $of_categories
				);

				

				
$of_options[] = array( 	"name" 		=> "Hidden option 2",
						"desc" 		=> "This is a sample hidden option 2",
						"id" 		=> "hidden_option_2",
						"std" 		=> "Hi, I\'m just a text input",
						"fold" 		=> "offline", 
						"type" 		=> "text"
				);

				*/

				
	}//End function: of_options()
}//End check if function exists: of_options()
?>
