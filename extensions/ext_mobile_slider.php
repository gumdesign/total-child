<?php
/**
 * Displays alternative slider on mobile devices
 * Theme location wpex_hook_main_top
 * @dependency mobile detect plugin
 * NOTE: wp core function wp_is_mobile() is modified by mobile detect plugin to treat tablets as desktop
*/ 
function childtheme_mobile_slider_display() {
    // run only on home page
    if (!is_front_page(  )) return;
  
    if (wp_is_mobile(  ) ) {
      // small mobile slider with square images
      echo do_shortcode( '[metaslider id=715]' );
      
    } else {
      // big landscape for desktop
      echo do_shortcode( '[metaslider id=24]' );
      
    }
  }
add_action( 'wpex_hook_main_top', 'childtheme_mobile_slider_display' );
