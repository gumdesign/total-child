<?php
/**
 * Total Child Master theme functions
 *
 * always load default custom functions in this file
 * include shortcode & extensions as needed
 * 
 * 1.0
 */
 


/**
 * add shortcodes if needed
 */

include_once('shortcodes/page_popup.php');
// include_once('shortcodes/my_account_menu.php')



/**
 * add parent styles
 * this is the new recommended way to import parents styles
*/  

function enqueue_parent_styles() {
	wp_enqueue_style( 'parent-style', get_template_directory_uri().'/style.css' );
}
add_action( 'wp_enqueue_scripts', 'enqueue_parent_styles' );



/**
 * Adds custom theme scripts
 * 
 */

/*
function childtheme_script_manager() {
		// custom scripts
		wp_register_script('custom_theme_scripts', get_stylesheet_directory_uri() . '/custom_js.js', array('jquery'));
		
		wp_enqueue_script ('custom_theme_scripts');
}
add_action('wp_enqueue_scripts', 'childtheme_script_manager');
*/



/**
 * allow svg in media uploader
*/  
 
 function cc_mime_types($mimes) {
  $mimes['svg'] = 'image/svg+xml';
  return $mimes;
}
add_filter('upload_mimes', 'cc_mime_types');



/**
 * Legacy compatibility
 * Add more styles to the Formats->Total Styles dropdown
*/
 
function childtheme_format_items( $items ) {
    $items[] = array(
        'title'        => __( 'Lead Text', 'wpex' ),
        'selector'    => 'p',
        'classes'    => 'lead-text lead',
    );
    return $items;
}
add_filter( 'wpex_tiny_mce_formats_items', 'childtheme_format_items' );


/**
 * Add & remove image sizes from the "Image Sizes" panel
*/ 
function childtheme_image_sizes( $sizes ) {

    // Remove "blog_post_full" image size
    // unset( $sizes['blog_post_full'] );

    // Add new image size "my_image_sizes"
       
    $sizes['hero'] = array(
        'label'     => __( 'Hero Image', 'wpex' ), // Label
        'width'     => 'hero_width', // id for theme_mod width
        'height'    => 'hero_height', // id for theme_mod height
        'crop'      => 'hero_crop', // id for theme_mod crop
    );
	

    // Return sizes
    return $sizes;

}
 add_filter( 'wpex_image_sizes', 'childtheme_image_sizes', 9999 );



/*
* Remove news & events dashboard widget
*
*/

// Create the function to use in the action hook
function remove_news_dashboard_widget() {
    remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );      
} 
// Hook into the 'wp_dashboard_setup' action to register our function
add_action('wp_dashboard_setup', 'remove_news_dashboard_widget' );

/**
 * remove news dashboard widget
 */

function childtheme_remove_dashboard_widgets () {
# remove_meta_box('dashboard_quick_press','dashboard','side'); //Quick Press widget
#  remove_meta_box('dashboard_recent_drafts','dashboard','side'); //Recent Drafts
  remove_meta_box('dashboard_primary','dashboard','side'); //WordPress.com Blog
#  remove_meta_box('dashboard_secondary','dashboard','side'); //Other WordPress News
#  remove_meta_box('dashboard_incoming_links','dashboard','normal'); //Incoming Links
#  remove_meta_box('dashboard_plugins','dashboard','normal'); //Plugins
#  remove_meta_box('dashboard_right_now','dashboard', 'normal'); //Right Now
#  remove_meta_box('rg_forms_dashboard','dashboard','normal'); //Gravity Forms
#  remove_meta_box('dashboard_recent_comments','dashboard','normal'); //Recent Comments
#  remove_meta_box('icl_dashboard_widget','dashboard','normal'); //Multi Language Plugin
#  remove_meta_box('dashboard_activity','dashboard', 'normal'); //Activity
#  remove_action('welcome_panel','wp_welcome_panel');

}
add_action('wp_dashboard_setup', 'childtheme_remove_dashboard_widgets');




/**
 * Callout Override on page settings
 * Adds field to override default callout in page settings
 * If a page ID is set in cutomizer this field will override the default callout ID
 */

add_filter( 'wpex_metabox_array', function( $array ) {
    $array['callout']['settings']['callout_text']['title'] = 'Callout Page ID';
    $array['callout']['settings']['callout_text']['type'] = 'text';
    $array['callout']['settings']['callout_text']['description'] = 'Enter a page ID to override your default callout content set in customizer.';
    return $array;
}, 40 );


/**
 * Sets default values for button shortcode
 */
add_filter( 'wpex_shortcodes_tinymce_json', function( $data ) {

	// Alter the default insert code for the button shortcode
	$data['shortcodes']['vcex_button']['insert'] = '[vcex_button url="/" title="" ]Button Text[/vcex_button]';

	// Return data
	return $data;

}, 40 );


