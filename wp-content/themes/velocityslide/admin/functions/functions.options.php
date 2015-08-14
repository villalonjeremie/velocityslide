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
				"service"		=> "Service",
				"portfolio"		=> "Portfolio",
                "article"		=> "Article",
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
						"desc" 		=> __('Organize how you want the layout.','velocityslide'),
						"id" 		=> "blocks_homepage",
						"std" 		=> $of_options_homepage_blocks,
						"type" 		=> "sorter"
				);

$of_options[] = array( 	"name" 		=> __('Rename Layout for Menu', 'velocityslide'),
                        "desc" 		=> __('For displaying the name you wish in menu.', 'velocityslide'),
                        "id" 		=> "name_layout_switch",
                        "std" 		=> 0,
                        "folds"		=> 1,
                        "type" 		=> "switch"
);


$of_options[] = array( 	"name" 		=> __('Homepage name', 'velocityslide'),
                        "desc" 		=> __('Rename Homepage layout in menu.', 'velocityslide'),
                        "id" 		=> "name_layout_homepage",
                        "std" 		=> "",
                        "fold"		=> "name_layout_switch",
                        "type" 		=> "text"
                    );

$of_options[] = array( 	"name" 		=> __('Presentation name', 'velocityslide'),
                        "desc" 		=> __('Rename Presentation layout in menu.', 'velocityslide'),
                        "id" 		=> "name_layout_presentation",
                        "std" 		=> "",
                        "fold"		=> "name_layout_switch",
                        "type" 		=> "text"
                    );

$of_options[] = array( 	"name" 		=> __('Service name', 'velocityslide'),
                        "desc" 		=> __('Rename Service layout in menu.', 'velocityslide'),
                        "id" 		=> "name_layout_service",
                        "std" 		=> "",
                        "fold"		=> "name_layout_switch",
                        "type" 		=> "text"
                    );

$of_options[] = array( 	"name" 		=> __('Portfolio name', 'velocityslide'),
                        "desc" 		=> __('Rename Portfolio layout in menu.', 'velocityslide'),
                        "id" 		=> "name_layout_portfolio",
                        "std" 		=> "",
                        "fold"		=> "name_layout_switch",
                        "type" 		=> "text"
                    );

$of_options[] = array( 	"name" 		=> __('Article name', 'velocityslide'),
                        "desc" 		=> __('Rename Article layout in menu.', 'velocityslide'),
                        "id" 		=> "name_layout_article",
                        "std" 		=> "",
                        "fold"		=> "name_layout_switch",
                        "type" 		=> "text"
                    );

$of_options[] = array( 	"name" 		=> __('Contact name', 'velocityslide'),
                        "desc" 		=> __('Rename Contact layout in menu.', 'velocityslide'),
                        "id" 		=> "name_layout_contact",
                        "std" 		=> "",
                        "fold"		=> "name_layout_switch",
                        "type" 		=> "text"
                    );

$of_options[] = array( 	"name" 		=> __('Background upload', 'velocityslide'),
						"desc" 		=> __('Upload your background for homepage.', 'velocityslide'),
						"id" 		=> "bg_homepage",
						"std" 		=> "",
						"mod"		=> "min",
						"type" 		=> "media"
				);

$of_options[] = array( 	"name" 		=> __('Title', 'velocityslide'),
						"desc" 		=> __('Please enter a title for the homepage section.', 'velocityslide'),
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
						"desc" 		=> __('Please enter a global name for your button.', 'velocityslide'),
						"id" 		=> "text_button_homepage",
						"std" 		=> "",
						"type" 		=> "text"
				);

$of_options[] = array( 	"name" 		=> __('Url button', 'velocityslide'),
						"desc" 		=> __('Please enter an url link for your button.', 'velocityslide'),
						"id" 		=> "url_button_homepage",
						"std" 		=> "",
						"type" 		=> "text"
				);

$of_options[] = array( 	"name" 		=> __('Presentation Settings', 'velocityslide'),
						"type" 		=> "heading"
				);
				

$of_options[] = array( 	"name" 		=> __('Left background upload', 'velocityslide'),
						"desc" 		=> __('Upload your left background for presentation.', 'velocityslide'),
						"id" 		=> "left_bg_presentation",
						"std" 		=> "",
						"mod"		=> "min",
						"type" 		=> "media"
				);

$of_options[] = array( 	"name" 		=> __('Right background upload', 'velocityslide'),
						"desc" 		=> __('Upload your right background for presentation.', 'velocityslide'),
						"id" 		=> "right_bg_presentation",
						"std" 		=> "",
						"mod"		=> "min",
						"type" 		=> "media"
				);

$of_options[] = array( 	"name" 		=> __('Left content', 'velocityslide'),
                        "desc" 		=> __('For displaying content on left panel.', 'velocityslide'),
                        "id" 		=> "left_content_presentation",
                        "std" 		=> 0,
                        "folds"		=> 1,
                        "type" 		=> "switch"
                    );

$of_options[] = array( 	"name" 		=> __('Left Title', 'velocityslide'),
						"desc" 		=> __('Please enter a title for the presentation section.', 'velocityslide'),
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
                        "desc" 		=> __('For displaying content on right panel.', 'velocityslide'),
                        "id" 		=> "right_content_presentation",
                        "std" 		=> 0,
                        "folds"		=> 1,
                        "type" 		=> "switch"
                    );

$of_options[] = array( 	"name" 		=> __('Right Title', 'velocityslide'),
						"desc" 		=> __('Please enter a title for the presentation section.', 'velocityslide'),
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
						"desc" 		=> __('Upload your left background for services section.', 'velocityslide'),
						"id" 		=> "left_bg_services",
						"std" 		=> "",
						"mod"		=> "min",
						"type" 		=> "media"
				);

$of_options[] = array( 	"name" 		=> __('Right background upload', 'velocityslide'),
						"desc" 		=> __('Upload your right background for services section.', 'velocityslide'),
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
						"desc" 		=> __('Upload your left background for portfolio.', 'velocityslide'),
						"id" 		=> "left_bg_portfolio",
						"std" 		=> "",
						"mod"		=> "min",
						"type" 		=> "media"
				);

$of_options[] = array( 	"name" 		=> __('Right background upload', 'velocityslide'),
						"desc" 		=> __('Upload your right background for portfolio.', 'velocityslide'),
						"id" 		=> "right_bg_portfolio",
						"std" 		=> "",
						"mod"		=> "min",
						"type" 		=> "media"
				);

$of_options[] = array( 	"name" 		=> __('Left content', 'velocityslide'),
                        "desc" 		=> __('For displaying content on left panel.', 'velocityslide'),
                        "id" 		=> "left_content_portfolio",
                        "std" 		=> 0,
                        "folds"		=> 1,
                        "type" 		=> "switch"
                    );

$of_options[] = array( 	"name" 		=> __('Left title', 'velocityslide'),
						"desc" 		=> __('Please enter a title for the portfolio section.', 'velocityslide'),
						"id" 		=> "left_title_portfolio",
						"std" 		=> "",
						"fold" 		=> "left_content_portfolio",
						"type" 		=> "text"
				);

$of_options[] = array( 	"name" 		=> __('Left description', 'velocityslide'),
						"desc" 		=> __('You can add a short description to appear in left block.<br /><br /><em>*HTML tags are allowed.</em>', 'velocityslide'),
						"id" 		=> "left_description_portfolio",
						"std" 		=> "",
						"fold" 		=> "left_content_portfolio",
						"type" 		=> "textarea"
				);


$of_options[] = array( 	"name" 		=> __('Right content', 'velocityslide'),
                        "desc" 		=> __('For displaying content on right panel.', 'velocityslide'),
                        "id" 		=> "right_content_portfolio",
                        "std" 		=> 0,
                        "folds"		=> 1,
                        "type" 		=> "switch"
                    );

$of_options[] = array( 	"name" 		=> __('Right title', 'velocityslide'),
						"desc" 		=> __('Please enter a title for the portfolio section.', 'velocityslide'),
						"id" 		=> "right_title_portfolio",
						"std" 		=> "",
						"fold" 		=> "right_content_portfolio",
						"type" 		=> "text"
				);

$of_options[] = array( 	"name" 		=> __('Right description', 'velocityslide'),
						"desc" 		=> __('You can add a short description to appear in right block.<br /><br /><em>*HTML tags are allowed.</em>', 'velocityslide'),
						"id" 		=> "right_description_portfolio",
						"std" 		=> "",
						"fold" 		=> "right_content_portfolio",
						"type" 		=> "textarea"
				);


$of_options[] = array( 	"name" 		=> __('Left popup display', 'velocityslide'),
                        "desc" 		=> __('For displaying button and popup.', 'velocityslide'),
                        "id" 		=> "side_left_popup_switch",
                        "std" 		=> 0,
                        "folds"		=> 1,
                        "type" 		=> "switch"
                    );


$of_options[] = array( 	"name" 		=> __('Left title popup', 'velocityslide'),
                        "desc" 		=> __('Please enter a global name for your popup.', 'velocityslide'),
                        "id" 		=> "left_title_popup_portfolio",
                        "std" 		=> "",
                        "fold"		=> "side_left_popup_switch",
                        "type" 		=> "text"
                    );

$of_options[] = array( 	"name" 		=> __('Left description popup', 'velocityslide'),
                        "desc" 		=> __('Please enter a description for your popup.', 'velocityslide'),
                        "id" 		=> "left_description_popup_portfolio",
                        "std" 		=> "",
                        "fold"		=> "side_left_popup_switch",
                        "type" 		=> "textarea"
                    );

$of_options[] = array( 	"name" 		=> __('Left title button', 'velocityslide'),
						"desc" 		=> __('Please enter a global name for your button.', 'velocityslide'),
						"id" 		=> "left_text_button_portfolio",
						"std" 		=> "",
						"fold" 		=> "side_left_popup_switch",
						"type" 		=> "text"
				);

$of_options[] = array( 	"name" 		=> __('Right popup display', 'velocityslide'),
                        "desc" 		=> __('For displaying button and popup.', 'velocityslide'),
                        "id" 		=> "side_right_popup_switch",
                        "std" 		=> 0,
                        "folds"		=> 1,
                        "type" 		=> "switch"
                    );

$of_options[] = array( 	"name" 		=> __('Right title popup', 'velocityslide'),
                        "desc" 		=> __('Please enter a global name for your popup.', 'velocityslide'),
                        "id" 		=> "right_title_popup_portfolio",
                        "std" 		=> "",
						"fold" 		=> "side_right_popup_switch",
                        "type" 		=> "text"
                );

$of_options[] = array( 	"name" 		=> __('Right description popup', 'velocityslide'),
                        "desc" 		=> __('Please enter a description for your popup.', 'velocityslide'),
                        "id" 		=> "right_description_popup_portfolio",
                        "std" 		=> "",
						"fold" 		=> "side_right_popup_switch",
                        "type" 		=> "textarea"
                    );
$of_options[] = array( 	"name" 		=> __('Right title button', 'velocityslide'),
						"desc" 		=> __('Please enter a global name for your button.', 'velocityslide'),
						"id" 		=> "right_text_button_portfolio",
						"std" 		=> "",
						"fold" 		=> "side_right_popup_switch",
						"type" 		=> "text"
				);

$of_options[] = array( 	"name" 		=> __('Blog Settings', 'velocityslide'),
						"type" 		=> "heading"
				);

$of_options[] = array( 	"name" 		=> __('Left post display', 'velocityslide'),
                        "desc" 		=> __('For displaying post on left panel.', 'velocityslide'),
                        "id" 		=> "left_post_switch",
                        "std" 		=> 0,
                        "type" 		=> "switch"
                    );

$of_options[] = array( 	"name" 		=> __('Right post display', 'velocityslide'),
                        "desc" 		=> __('For displaying post on right panel.', 'velocityslide'),
                        "id" 		=> "right_post_switch",
                        "std" 		=> 0,
                        "type" 		=> "switch"
                    );

$of_options[] = array( 	"name" 		=> __('Left background upload', 'velocityslide'),
						"desc" 		=> __('Upload your left background for blog section.', 'velocityslide'),
						"id" 		=> "left_bg_blog",
						"std" 		=> "",
						"mod"		=> "min",
						"type" 		=> "media"
				);

$of_options[] = array( 	"name" 		=> __('Right background upload', 'velocityslide'),
						"desc" 		=> __('Upload your right background for blog section.', 'velocityslide'),
						"id" 		=> "right_bg_blog",
						"std" 		=> "",
						"mod"		=> "min",
						"type" 		=> "media"
				);

$of_options[] = array( 	"name" 		=> __('Contact Settings', 'velocityslide'),
						"type" 		=> "heading"
				);

$of_options[] = array(  "name"		=> __('Title contact', 'velocityslide'),
                        "id"		=> "title_contact",
                        "std"       => "",
                        "type"		=> "text"
                    );

$of_options[] = array(  "name"		=> __('Subtitle contact', 'velocityslide'),
                        "id"		=> "subtitle_contact",
                        "std"       => "",
                        "type"		=> "text"
                    );

$of_options[] = array(  "name"		=> __('Title details contact', 'velocityslide'),
                        "id"		=> "title_details_contact",
                        "std"       => "",
                        "type"		=> "text"
                    );

$of_options[] = array(  "name"		=> __('Contact Address', 'velocityslide'),
					    "desc"		=> __('Please enter your company address.', 'velocityslide'),
					    "id"		=> "text_address_contact",
						"std"       => "",
						"type"		=> "text"
				    );

$of_options[] = array(  "name"		=> __('Country contact', 'velocityslide'),
                        "id"		=> "text_country_contact",
                        "std"       => "",
                        "type"		=> "text"
                     );

$of_options[] = array(  "name"		=> __('Postcode contact', 'velocityslide'),
                        "id"		=> "text_postcode_contact",
                        "std"       => "",
                        "type"		=> "text"
                    );
					
$of_options[] = array(  "name"		=> __('Contact Telephone Number', 'velocityslide'),
					    "desc"		=> __('Please enter your company telephone number.', 'velocityslide'),
					    "id"		=> "text_telephone_contact",
					    "std"		=> "",
					    "type"		=> "text"
                    );
					
$of_options[] = array(  "name"		=> __('Fax Telephone Number', 'velocityslide'),
					    "desc"		=> __('Please enter your company fax number.', 'velocityslide'),
						"id"		=> "text_fax_contact",
						"std"		=> "",
						"type"		=> "text"
                    );
					
$of_options[] = array(  "name"		=> __('Contact Email', 'velocityslide'),
						"desc"		=> __('Please enter your company email address (eg; marc@guuthemes.com) This will also populate into your Contact Form.', 'velocityslide'),
						"id"		=> "text_email_contact",
						"std"		=> "",
						"type"		=> "text"
                    );

$of_options[] = array( 	"name" 		=> __('General Settings', 'velocityslide'),
						"type" 		=> "heading"
				);

$of_options[] = array( 	"name" 		=> __('Background upload', 'velocityslide'),
						"desc" 		=> __('Upload your background for contact.', 'velocityslide'),
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
						"desc" 		=> __('You can use the following shortcodes in your footer text: [wp-link] [theme-link] [loginout-link] [blog-title] [blog-link] [the-year].', 'velocityslide'),
						"id" 		=> "footer_text",
						"std" 		=> "Powered by [wp-link]. Built on the [theme-link].",
						"type" 		=> "textarea"
				);
				
$of_options[] = array( 	"name" 		=> __('Styling Options', 'velocityslide'),
						"type" 		=> "heading"
				);
				
$of_options[] = array( 	"name" 		=> __('Body Background Color', 'velocityslide'),
						"desc" 		=> __('Pick a background color for the theme (default: #fff).', 'velocityslide'),
						"id" 		=> "body_background",
						"std" 		=> "",
						"type" 		=> "color"
				);
				
$of_options[] = array( 	"name" 		=> __('Body Font', 'velocityslide'),
						"desc" 		=> __('Specify the body font properties.', 'velocityslide'),
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

$of_options[] = array(  "name" => __('Twitter', 'velocityslide'),
                        "desc" => __('Enter your Twitter Profile URL <br />(ie; http://twitter.com/affinitythemes)', 'velocityslide'),
                        "id" => "text_twitter_profile",
                        "std" => "",
                        "type" => "text");

$of_options[] = array(   "name" => __('Facebook', 'velocityslide'),
                        "desc" => __('Enter your Facebook Profile URL <br />(ie; http://facebook.com/affinitythemes)', 'velocityslide'),
                        "id" => "text_facebook_profile",
                        "std" => "",
                        "type" => "text");

$of_options[] = array(  "name" => __('Dribbble', 'velocityslide'),
                        "desc" => __('Enter your Dribbble Profile URL <br />(ie; http://dribbble.com/affinitythemes)', 'velocityslide'),
                        "id" => "text_dribbble_profile",
                        "std" => "",
                        "type" => "text");

$of_options[] = array(  "name" => __('Forrst', 'velocityslide'),
                        "desc" => __('Enter your Forrst Profile URL <br />(ie; http://forrst.com/people/affinitythemes)', 'velocityslide'),
                        "id" => "text_forrst_profile",
                        "std" => "",
                        "type" => "text");

$of_options[] = array(  "name" => __('Vimeo', 'velocityslide'),
                        "desc" => __('Enter your Vimeo Profile URL <br />(ie; http://vimeo.com/affinitythemes)', 'velocityslide'),
                        "id" => "text_vimeo_profile",
                        "std" => "",
                        "type" => "text");

$of_options[] = array(  "name" => __('YouTube', 'velocityslide'),
                        "desc" => __('Enter your YouTube Profile URL <br />(ie; http://youtube.com/user/affinitythemes)', 'velocityslide'),
                        "id" => "text_youtube_profile",
                        "std" => "",
                        "type" => "text");

$of_options[] = array(  "name" => __('Flickr', 'velocityslide'),
                        "desc" => __('Enter your Flickr Profile URL <br />(ie; http://flickr.com/people/affinitythemes)', 'velocityslide'),
                        "id" => "text_flickr_profile",
                        "std" => "",
                        "type" => "text");

$of_options[] = array(  "name" => __('Linkedin', 'velocityslide'),
                        "desc" => __('Enter your Linkedin Profile URL <br />(ie; http://linkedin.com/in/affinitythemes)', 'velocityslide'),
                        "id" => "text_linkedin_profile",
                        "std" => "",
                        "type" => "text");

$of_options[] = array(  "name" => __('Pinterest', 'velocityslide'),
                        "desc" => __('Enter your Pinterest Profile URL <br />(ie; http://pinterest.com/affinitythemes)', 'velocityslide'),
                        "id" => "text_pinterest_profile",
                        "std" => "",
                        "type" => "text");

$of_options[] = array(  "name" => __('Google +', 'velocityslide'),
                        "desc" => __('Enter your Google + Profile URL <br />(ie; http://plus.google.com/1030594445)', 'velocityslide'),
                        "id" => "text_googleplus_profile",
                        "std" => "",
                        "type" => "text");

$of_options[] = array(  "name" => __('Tumblr', 'velocityslide'),
                        "desc" => __('Enter your Tumblr Profile URL <br />(ie; http://affinitythemes.tumblr.com)', 'velocityslide'),
                        "id" => "text_tumblr_profile",
                        "std" => "",
                        "type" => "text");

$of_options[] = array(  "name" => __('Soundcloud', 'velocityslide'),
                        "desc" => __('Enter your Soundcloud Profile URL <br />(ie; https://soundcloud.com/affinitythemes)', 'velocityslide'),
                        "id" => "text_soundcloud_profile",
                        "std" => "",
                        "type" => "text");

$of_options[] = array(  "name" => __('Last FM', 'velocityslide'),
                        "desc" => __('Enter your Last FM Profile URL <br />(ie; http://last.fm/user/affinitythemes)', 'velocityslide'),
                        "id" => "text_lastfm_profile",
                        "std" => "",
                        "type" => "text");


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
	}//End function: of_options()
}//End check if function exists: of_options()
?>
