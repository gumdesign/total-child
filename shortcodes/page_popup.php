<?php

/**
 * Page Popup shortcode
 * [ce_popup page_id="" link_color="" label="" class=""]
 * 1.0
 */

 // make sure scripts are loaded
add_filter( 'wpex_load_ilightbox_globally', '__return_true' );

 function ce_popup_shortcode($atts) {
    // Parse your shortcode settings with it's defaults
    $atts = shortcode_atts( array(
        'page_id' => '',
		'label' => 'Details',
		'link_color' => '',
        'class' => '',
        'wrapper' => ''
    ), $atts, 'lightbox_modal' );
    // Extract shortcode atributes
    extract( $atts );
    if ($page_id =='') return;
    ob_start();
    // Define output var
    $out = '';
    // link
    $out.= '<a href="#mod-'.$page_id.'" class="wpex-lightbox ' .$class.' " data-width="1200" data-height="900" data-type="inline"  style="color:'.$link_color.'">'.$label.'</a>';
	
	// modal start
	$out.= '<div id="mod-'.$page_id.'" style="display:none">';
	
	// title
	$out.='<h1>'. get_the_title($page_id) . '</h1>';
     // get page content
  	$content = get_post_field('post_content', $page_id);
  	// apply filters - allow content to be formatted
  	$content =  apply_filters('the_content',$content);
   
	//$out.= do_shortcode( $content );
	$out.= $content;

	// modal end
	$out.= '</div>';
	
    /**/
    echo $out;
    return ob_get_clean();
 }
 add_shortcode( 'ce_popup', 'ce_popup_shortcode');