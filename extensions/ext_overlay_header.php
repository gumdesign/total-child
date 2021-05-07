
<?php 

/**
 * Will use the Featured image as the background when selecting the
 * Background Image page header style.
 *
 * @link http://wpexplorer-themes.com/total/snippets/alter-the-page-header-style/
 * @link http://wpexplorer-themes.com/total/snippets/post-featured-image-header-bg/
 *
 */
function my_page_header_background_image( $image ) {
	if ( has_post_thumbnail() ) {
		$image = get_post_thumbnail_id();
	}
	return $image;
}
add_filter( 'wpex_page_header_background_image', 'my_page_header_background_image' );


/**
 * set default height on title - override page meta setting
 */
add_filter('wpex_post_title_height', function($title_height){
    $title_height ='550px';
  return $title_height;
});


if (! wp_is_mobile(  ) ) {
// Enable overlay header site-wide
    add_filter( 'wpex_has_overlay_header', '__return_true' );
}

// Set Overlay header style - light, dark
add_filter( 'wpex_header_overlay_style', function( $style ) {
	$meta = get_post_meta( get_the_ID(), 'wpex_overlay_header_style', true );
	return $meta ? $meta : 'light';
} );

// Change the page header style to background image
// Home may have a slide show
add_filter( 'wpex_page_header_style', function( $style ) {
    if ( !is_front_page(  ) ) {
       // return 'background-image';
    }
    return 'background-image';
} );


/**
 * Set default page header background image
 * Move setting to customizer
 */

add_filter( 'wpex_page_header_background_image', function( $image ) {
   
    // look for featured image
    if ( $thumbnail = get_post_thumbnail_id() ) {
        $image = $thumbnail;
     }
     // look for image in customizer
     if( !$thumbnail && get_theme_mod( 'header_image' ) ){
        $image = esc_attr( get_theme_mod( 'header_image') ); // get ID
    }



     // default for blog landing, category & single if not set
     if ( is_home() ||  is_singular( 'post' )) {
       // $image = '21';
     }

    // Set a custom page header background image for all pages
    if ( is_singular( 'post' ) ) {
        // Define your image url or ID => An attachment ID is always best

    } 

  // Return image
  return $image;
} );