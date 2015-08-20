<?php

add_filter('widget_text', 'do_shortcode');
add_filter('get_the_excerpt', 'do_shortcode');

/*-----------------------------------------------------------------------------------*/
/*	Blockquotes
/*-----------------------------------------------------------------------------------*/

function blockquotes($atts, $content = null) {
    extract(shortcode_atts(array(
        'cite' => ''
    ), $atts));
    $blockquotes = '<blockquote';
    $blockquotes .= '><p>' . $content . '</p>';
    if ($cite) {
        $blockquotes .= '<cite>' . $cite . '</cite>';
    }
    $blockquotes .= '</blockquote>';
    return $blockquotes;
}

add_shortcode('blockquote', 'blockquotes');


/*-----------------------------------------------------------------------------------*/
/*	Accordion
/*-----------------------------------------------------------------------------------*/

function accordion_shortcode($atts, $content = null ) {
    return '<div id="accordion-container">' . do_shortcode($content) . '</div>';
}

add_shortcode( 'accordion', 'accordion_shortcode' );

function accordion_section_shortcode( $atts, $content = null  ) {

    extract( shortcode_atts( array(
        'title' => 'Title',
    ), $atts ) );

    return '<h2 class="accordion-header">'. $title .'</a></h2><div class="accordion-content">' . do_shortcode($content) . '</div>';
}

add_shortcode( 'accordion_section', 'accordion_section_shortcode' );

/*-----------------------------------------------------------------------------------*/
/*	Tabs
/*-----------------------------------------------------------------------------------*/

add_shortcode( 'tabgroup', 'st_tabgroup' );

function st_tabgroup( $atts, $content ){

    $GLOBALS['tab_count'] = 0;
    do_shortcode( $content );

    if( is_array( $GLOBALS['tabs'] ) ){

        foreach( $GLOBALS['tabs'] as $tab ){
            $tabs[] = '<li><a href="#'.$tab['id'].'">'.$tab['title'].'</a></li>';
            $panes[] = '<li id="'.$tab['id'].'Tab">'.$tab['content'].'</li>';
        }
        $return = "\n".'<!-- the tabs --><ul class="tabs">'.implode( "\n", $tabs ).'</ul>'."\n".'<!-- tab "panes" --><ul class="tabs-content">'.implode( "\n", $panes ).'</ul>'."\n";
    }
    return $return;

}

add_shortcode( 'tab', 'st_tab' );
function st_tab( $atts, $content ){
    extract(shortcode_atts(array(
        'title' => '%d',
        'id' => '%d'
    ), $atts));

    $x = $GLOBALS['tab_count'];
    $GLOBALS['tabs'][$x] = array(
        'title' => sprintf( $title, $GLOBALS['tab_count'] ),
        'content' =>  do_shortcode($content),
        'id' =>  $id );

    $GLOBALS['tab_count']++;
}

/*-----------------------------------------------------------------------------------*/
/*	Toggle
/*-----------------------------------------------------------------------------------*/

function toggle( $atts, $content = null ) {
    extract(shortcode_atts(array(
        'title' => '',
        'style' => 'list'
    ), $atts));
    $toggle = '<div class="'.$style.'"><p class="trigger"><a href="#">' .$title. '</a></p><div class="toggle_container"><div class="block">'.$content.'</div></div></div>';
    return $toggle;
}
add_shortcode('toggle', 'toggle');

/*-----------------------------------------------------------------------------------*/
/*	YouTube Video
/*-----------------------------------------------------------------------------------*/

function youtube_video($atts, $content = null)
{
    extract(shortcode_atts(array(
        'id' => ''
    ), $atts));
    $return = $content;
    if ($content)
        $return .= "<br /><br />";
    $youtube_video = '<div class="video-frame"><iframe src="http://www.youtube.com/embed/' . $id . '" frameborder="0" allowfullscreen></iframe></div>';
    return $youtube_video;
}
add_shortcode('youtube', 'youtube_video');

/*-----------------------------------------------------------------------------------*/
/*	Buttons
/*-----------------------------------------------------------------------------------*/

function button( $atts, $content = null ) {
    extract(shortcode_atts(array(
        'link' => '',
        'size' => 'medium',
        'color' => '',
        'target' => '_self',
        'align' => 'right'
    ), $atts));
    $button = '<div class="custom-button '.$size.' '.$align.'"><a target="'.$target.'" class="button '.$color.'" href="'.$link.'">'.$content.'</a></div>';
    return $button;
}
add_shortcode('button', 'button');

/*-----------------------------------------------------------------------------------*/
/*	Clear Floats
/*-----------------------------------------------------------------------------------*/

function float_clear( $atts, $content = null ) {
    return '<div class="clear"></div>';
}
add_shortcode('clear', 'float_clear');


?>