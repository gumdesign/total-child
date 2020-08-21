<?php

/**
 * Shortcode for admin dropdown button
 * Usefull in topbar
 * [my_account_menu label="" menu=""]
 * 
 * TODO: Need to add CSS styles
*/

function my_account_menu_shortcode( $atts, $content = null ) {

  // show admin dropdown menu only to logged in users
 
  if( !is_user_logged_in(  )) {
      $out = '<span class="wpex-inline"><i class="ticon ticon-user"></i> ';
      $out .= do_shortcode('[wp_login_url text="Member Login" logout_text="Logout"]');
      $out .= '</span>';
  } else {


      extract( shortcode_atts(
        array(
          'label' => 'My Account',
          'menu' => ''
        ),
        $atts ) );
        
        if ($menu) {

          $args = array(
            'menu' => $menu,
            'echo' => 0,
            'container'       => 'div',
            'container_class' => 'my-account-menu-container',
            'menu_class'      => '',
            'items_wrap'      => '<ul id="my-account-menu" class="my-account-menu">%3$s</ul>',
            );
          
          $dropmenu =  wp_nav_menu($args);	 
        }	 
        // ob_start();	 
        $out = '<div id="my-account">';
        $out .= '<i class="ticon ticon-user"></i> <a href="#" id="my-account" class="my-account">' .$label .  '</a>';
        $out .= $dropmenu;
        $out .= '</div>';
        // echo ob_get_clean();	
        //$out = ob_get_clean();
 
    }
  
  echo $out;
    
}
add_shortcode( 'my_account_menu', 'my_account_menu_shortcode' );


/**
 * Adds my-account-menu script to footer
 */

add_action( 'wp_footer', function() { ?>

  <script>
  
  ( function( $ ) {
  
      'use strict';
  
      $( document ).ready( function() {
  
          var $trigger = $('#my-account-menu' );
          var $target = $( '.my-account-menu-container' );
  
          if ( $target.length ) {
              $trigger.click( function() {
                 // console.log('click');
                  $target.toggle();
                  return false;
              } );
          }
  
  
      } );
  
  } ( jQuery ) );
  
  </script>
  
  <?php }, 99 );

