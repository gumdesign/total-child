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



/*
* Load Child Theme style.css After Parent Theme style.css AND VC styles
* https://themeforest.net/item/total-responsive-multipurpose-wordpress-theme/6339019/comments?page=873&filter=all#comment_22874228
* https://wpexplorer-themes.com/total/snippets/child-css-after-parent-css/

* updated 10-2020 https://gist.github.com/wpexplorer/96c330a0bd58233672bc06988d3fa166
*/

function childtheme_styles() {

    // First de-register the main stylesheet (which is now your child theme style.css)
    wp_dequeue_style( WPEX_THEME_STYLE_HANDLE );
    wp_deregister_style( WPEX_THEME_STYLE_HANDLE );

    // Add the parent style.css with the main style handle
    wp_enqueue_style( WPEX_THEME_STYLE_HANDLE, get_template_directory_uri() . '/style.css', array(), WPEX_THEME_VERSION );

    
    // Add Total WPBakery styles to dependency if loaded
   if ( defined( 'WPEX_VC_ACTIVE' ) && WPEX_VC_ACTIVE && function_exists( 'wpex_has_vc_mods' ) && wpex_has_vc_mods() ) {
        $deps[] = 'wpex-wpbakery';
    }

    // Re-add child CSS with parent as dependency & add theme version
    wp_enqueue_style(
        'child-theme',
        get_stylesheet_directory_uri() . '/style.css',
        $deps,
        wp_get_theme()->get( 'Version' )
    );

}
add_action( 'wp_enqueue_scripts', 'childtheme_styles' );



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
 * Add Google Fonts To  Child Theme
 * 
 */
/**/
function load_google_fonts() {
    wp_register_style('googleWebFonts', '//fonts.googleapis.com/css?family=Open+Sans:400,400i,700');
    wp_enqueue_style('googleWebFonts');
}
add_action('wp_print_styles', 'load_google_fonts'); 




/**
 * Add more styles to the Formats->Total Styles dropdown
*/
 
function childtheme_format_items( $items ) {
    $items[] = array(
        'title'        => __( 'Lead Text', 'wpex' ),
        'selector'    => 'p',
        'classes'    => 'lead-text lead',
    );
    $items[] = array(
        'title'        => __( 'Subheader', 'wpex' ),
        'selector'    => 'h1,h2,h3,h4,h5,p,span',
        'classes'    => 'subheader',
    );
    $items[] = array(
        'title'        => __( 'Definition Title', 'wpex' ),
        'selector'    => 'p',
        'classes'    => 'dt',
    );
    $items[] = array(
        'title'        => __( 'Definition Description', 'wpex' ),
        'selector'    => 'p',
        'classes'    => 'dd',
    );
    $items[] = array(
        'title'        => __( 'Large Text', 'wpex' ),
        'selector'    => 'p',
        'classes'    => 'text-large',
    );
    $items[] = array(
        'title'        => __( 'Heading Size 2', 'wpex' ),
        'selector'    => 'p, div, span',
        'classes'    => 'theme-heading size-h2',
    );
    $items[] = array(
        'title'        => __( 'Heading Size 3', 'wpex' ),
        'selector'    => 'p, div, span',
        'classes'    => 'theme-heading size-h3',
    );
    $items[] = array(
        'title'        => __( 'Heading Size 4', 'wpex' ),
        'selector'    => 'p, div, span',
        'classes'    => 'theme-heading size-h4',
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


