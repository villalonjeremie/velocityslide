<?php

/*-----------------------------------------------------------------------------------*/
/*	Define Metabox Fields
/*-----------------------------------------------------------------------------------*/

$prefix = 'vs_';
 
$meta_box_portfolio = array(
	'id' => 'gt-meta-box-portfolio',
	'title' =>  __('Portfolio Detail Settings', 'velocityslide'),
	'page' => 'portfolio',
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
		array(
    	   'name' => __('Client Name', 'velocityslide'),
    	   'desc' => __('Client who this project was completed for.', 'velocityslide'),
    	   'id' => $prefix . 'client_name',
    	   'type' => 'text',
    	   'std' => ''
    	),
    	array(
    	   'name' => __('Project Date', 'velocityslide'),
    	   'desc' => __('What was the date of the completed project.', 'velocityslide'),
    	   'id' => $prefix . 'project_date',
    	   'type' => 'text',
    	   'std' => ''
    	),
    	array(
    	   'name' => __('Project URL', 'velocityslide'),
    	   'desc' => __('What is the URL for this project.', 'velocityslide'),
    	   'id' => $prefix . 'project_url',
    	   'type' => 'text',
    	   'std' => ''
    	),
        array(
            'name' =>  __('Portfolio Side', 'velocityslide'),
            'desc' => __('Choose the side popup portfolio you wish to display.', 'velocityslide'),
            'id' => $prefix . 'portfolio_side',
            "type" => "select",
            'std' => 'Left',
            'options' => array('Left', 'Right')
        )
	)
);


add_action('admin_menu', 'vs_add_box_portfolio');


/*-----------------------------------------------------------------------------------*/
/*	Add metabox to edit page
/*-----------------------------------------------------------------------------------*/
 
function vs_add_box_portfolio() {
	global $meta_box_portfolio;
	
	add_meta_box($meta_box_portfolio['id'], $meta_box_portfolio['title'], 'vs_show_box_portfolio', $meta_box_portfolio['page'], $meta_box_portfolio['context'], $meta_box_portfolio['priority']);
}


/*-----------------------------------------------------------------------------------*/
/*	Callback function to show fields in meta box
/*-----------------------------------------------------------------------------------*/

function vs_show_box_portfolio() {
	global $meta_box_portfolio, $post;
	
	$wp_version = get_bloginfo('version');

	// Use nonce for verification
	echo '<input type="hidden" name="vs_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
 
	echo '<table class="form-table">';
 
	foreach ($meta_box_portfolio['fields'] as $field) {
		// get current post meta data
		$meta = get_post_meta($post->ID, $field['id'], true);
		switch ($field['type']) {
 
			
			//If Text		
			case 'text':
			
			echo '<tr style="border-bottom:1px solid #eeeeee;">',
				'<th style="width:25%"><label for="', $field['id'], '"><strong>', $field['name'], '</strong><span style=" display:block; color:#999; margin:5px 0 0 0; line-height: 18px;">'. $field['desc'].'</span></label></th>',
				'<td>';
			echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : stripslashes(htmlspecialchars(( $field['std']), ENT_QUOTES)), '" size="30" style="width:75%; margin-right: 20px; float:left;" />';
			
			break;
			
			//If textarea		
			case 'textarea':
			
			echo '<tr>',
				'<th style="width:25%"><label for="', $field['id'], '"><strong>', $field['name'], '</strong><span style="line-height:18px; display:block; color:#999; margin:5px 0 0 0;">'. $field['desc'].'</span></label></th>',
				'<td>';
			echo '<textarea name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" rows="4" cols="5" style="width:75%; margin-right: 20px; float:left;">', $meta ? $meta : $field['std'], '</textarea>';
			
			break;
 
			//If Button	
			case 'button':
				echo '<input style="float: left;" type="button" class="button" name="', $field['id'], '" id="', $field['id'], '"value="', $meta ? $meta : $field['std'], '" />';
				echo 	'</td>',
			'</tr>';
			
			break;
			
			//If Select	
			case 'select':
			
				echo '<tr>',
				'<th style="width:25%"><label for="', $field['id'], '"><strong>', $field['name'], '</strong><span style=" display:block; color:#999; margin:5px 0 0 0; line-height: 18px;">'. $field['desc'].'</span></label></th>',
				'<td>';
			
				echo'<select id="' . $field['id'] . '" name="'.$field['id'].'">';
			
				foreach ($field['options'] as $option) {
					
					echo'<option';
					if ($meta == $option ) { 
						echo ' selected="selected"'; 
					}
					echo'>'. $option .'</option>';
				
				} 
				
				echo'</select>';
			
			break;
		}

	}
 
	echo '</table>';
}
 
add_action('save_post', 'vs_save_data_portfolio');


/*-----------------------------------------------------------------------------------*/
/*	Save data when post is edited
/*-----------------------------------------------------------------------------------*/
 
function vs_save_data_portfolio($post_id) {
	global $meta_box_portfolio;
 
	// verify nonce
	if ( !isset($_POST['vs_meta_box_nonce']) || !wp_verify_nonce($_POST['vs_meta_box_nonce'], basename(__FILE__))) {
		return $post_id;
	}
 
	// check autosave
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return $post_id;
	}
 
	// check permissions
	if ('page' == $_POST['post_type']) {
		if (!current_user_can('edit_page', $post_id)) {
			return $post_id;
		}
	} elseif (!current_user_can('edit_post', $post_id)) {
		return $post_id;
	}
 
	foreach ($meta_box_portfolio['fields'] as $field) {
		$old = get_post_meta($post_id, $field['id'], true);
		$new = $_POST[$field['id']];
 
		if ($new && $new != $old) {
			update_post_meta($post_id, $field['id'], stripslashes(htmlspecialchars($new)));
		} elseif ('' == $new && $old) {
			delete_post_meta($post_id, $field['id'], $old);
		}
	}
}

/*-----------------------------------------------------------------------------------*/
/*	Queue Scripts
/*-----------------------------------------------------------------------------------*/

function vs_admin_scripts_portfolio() {
	global $post;
	$wp_version = get_bloginfo('version');

	// enqueue scripts
	wp_enqueue_script('media-upload');
	if( version_compare( $wp_version, '3.4.2', '<=' ) ) {

		wp_enqueue_script('thickbox');
		wp_register_script('gt-upload', get_template_directory_uri() . '/functions/js/upload-button.js', array('jquery','media-upload','thickbox'));
		wp_enqueue_script('gt-upload');

		wp_enqueue_style('thickbox');
	}

	if( isset($post) ) {
		wp_localize_script( 'jquery', 'vs_ajax', array(
		    'post_id' => $post->ID,
		    'nonce' => wp_create_nonce( 'vs-ajax' )
		) );
	}

}
add_action('admin_enqueue_scripts', 'vs_admin_scripts_portfolio');