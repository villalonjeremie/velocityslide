<?php

/*-----------------------------------------------------------------------------------*/
/*	Define Metabox Fields
/*-----------------------------------------------------------------------------------*/

$prefix = 'gt_';
 
$meta_box_service = array(
	'id' => 'service_details',
    'title' => __('Service Details', 'velocityslide'),
    'page' => 'services',
    'context' => 'normal',
    'priority' => 'high',
    'fields' => array(
        array(
        	'name' => __('Service Icon', 'velocityslide'),
            'desc' => __('Add an Icon for your service via Shortcode <br />Example: [icon name=icon-file]<br /><br />A list of all available icons can be found <a href="http://fortawesome.github.com/Font-Awesome" target="_blank">here</a>', 'velocityslide'),
            'id' => $prefix . 'service_icon',
            'type' => 'text',
            'std' => ''
        ),
        array(
           'name' => __('Service URL', 'velocityslide'),
           'desc' => __('Please add a page URL for this Service to link to', 'velocityslide'),
           'id' => $prefix . 'service_url',
           'type' => 'text',
           'std' => ''
        ),
        array(
            'name' => __('Service Side', 'velocityslide'),
            'desc' => __('Please select the side where you want to put your service box', 'velocityslide'),
            'id' => $prefix . 'service_select',
            'type' => 'select',
            'value' => '',
            'std' => 'left'
        )
    )
);

add_action('admin_menu', 'gt_add_box_service');


/*-----------------------------------------------------------------------------------*/
/*	Callback function to show fields in meta box
/*-----------------------------------------------------------------------------------*/

function gt_show_box_service() {
    global $meta_box_service, $post;

    // Use nonce for verification
	echo '<input type="hidden" name="vs_add_box_service_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';

	echo '<table class="form-table">';
		
	foreach ($meta_box_service['fields'] as $field) {
		// get current post meta data
            if($field['type']=='text') {
                $meta = get_post_meta($post->ID, $field['id'], true);
                error_log(print_r($meta));

                echo '<tr style="border-bottom:1px solid #eeeeee;">',
                '<th style="width:25%"><label for="', $field['id'], '"><strong>', $field['name'], '</strong><span style=" display:block; color:#999; margin:5px 0 0 0; line-height: 18px;">' . $field['desc'] . '</span></label></th>',
                '<td>';
                echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : stripslashes(htmlspecialchars(($field['std']), ENT_QUOTES)), '" size="30" style="width:75%; margin-right: 20px; float:left;" />';
                echo '</td></tr>';
            }else{
                $meta = get_post_meta($post->ID, $field['value'], true);

                echo '<tr style="border-bottom:1px solid #eeeeee;">',
                '<th style="width:25%"><label for="', $field['id'], '"><strong>', $field['name'], '</strong><span style=" display:block; color:#999; margin:5px 0 0 0; line-height: 18px;">' . $field['desc'] . '</span></label></th>',
                '<td>';
                echo '<select name="', $field['name'], '">';
                echo '<option ' . selected('Left', $meta ? $meta : stripslashes(htmlspecialchars(($field['std']), ENT_QUOTES), false)) . ' value="left">' . __('Left side', 'velocityslide') . '</option>';
                echo '<option ' . selected('Right', $meta ? $meta : stripslashes(htmlspecialchars(($field['std']), ENT_QUOTES), false)) . ' value="right"> ' . __('Right side', 'velocityslide') . '</option>';
                echo '</select>';
                echo '</td></tr>';
            }
		}
    echo '</table>';
}

add_action('save_post', 'gt_save_data_service');

/*-----------------------------------------------------------------------------------*/
/*	Add metabox to edit page
/*-----------------------------------------------------------------------------------*/
 
function gt_add_box_service() {
	global $meta_box_service;
	add_meta_box($meta_box_service['id'], $meta_box_service['title'], 'gt_show_box_service', $meta_box_service['page'], $meta_box_service['context'], $meta_box_service['priority']);
}

// Save data from meta box
function gt_save_data_service($post_id) {
    global $meta_box_service;
error_log('coucou');
    // verify nonce
    if ( !isset($_POST['gt_add_box_service_nonce']) || !wp_verify_nonce($_POST['gt_add_box_service_nonce'], basename(__FILE__))) {
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

    foreach ($meta_box_service['fields'] as $field) { // save each option

        $old = get_post_meta($post_id, $field['id'], true);
        $new = $_POST[$field['id']];

        if ($new && $new != $old) { // compare changes to existing values
            update_post_meta($post_id, $field['id'], $new);
        } elseif ('' == $new && $old) {
            delete_post_meta($post_id, $field['id'], $old);
        }
    }
}