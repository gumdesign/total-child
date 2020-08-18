<?php
/**
 * Child theme functions
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development
 * and http://codex.wordpress.org/Child_Themes), you can override certain
 * functions (those wrapped in a function_exists() call) by defining them first
 * in your child theme's functions.php file. The child theme's functions.php
 * file is included before the parent theme's file, so the child theme
 * functions would be used.
 *
 * Text Domain: wpex
 * @link http://codex.wordpress.org/Plugin_API
 *
 */
 
 


/**
 * allow shortcodes in widgets
*/ 
add_filter('widget_text', 'do_shortcode'); 


/**
 * allow svg in media uploader
*/  
 
 function cc_mime_types($mimes) {
  $mimes['svg'] = 'image/svg+xml';
  return $mimes;
}
add_filter('upload_mimes', 'cc_mime_types');


/**
 * add parent styles
 * this is the new recommended way to import parents styles
*/  

function enqueue_parent_styles() {
	wp_enqueue_style( 'parent-style', get_template_directory_uri().'/style.css' );
}
add_action( 'wp_enqueue_scripts', 'enqueue_parent_styles' );




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
 * Add more styles to the Formats->Total Styles dropdown
*/
 
 function my_new_formats_items( $items ) {
    $items[] = array(
        'title'        => __( 'Lead Text', 'wpex' ),
        'selector'    => 'p',
        'classes'    => 'lead',
    );
    $items[] = array(
        'title'        => __( 'Subheader', 'wpex' ),
        'selector'    => 'h1,h2,h3,h4,h5',
        'classes'    => 'subheader',
    );
    return $items;
}
add_filter( 'wpex_tiny_mce_formats_items', 'my_new_formats_items' );


/**
 * Add & remove image sizes from the "Image Sizes" panel
*/ 
function my_image_sizes( $sizes ) {

    // Remove "blog_post_full" image size
    // unset( $sizes['blog_post_full'] );

    // Add new image size "my_image_sizes"
       
    $sizes['slides'] = array(
        'label'     => __( 'Slide Images', 'wpex' ), // Label
        'width'     => 'slide_width', // id for theme_mod width
        'height'    => 'slide_height', // id for theme_mod height
        'crop'      => 'slide_crop', // id for theme_mod crop
    );
	

    // Return sizes
    return $sizes;

}
 add_filter( 'wpex_image_sizes', 'my_image_sizes', 9999 );

function remove_dashboard_widgets () {
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
add_action('wp_dashboard_setup', 'remove_dashboard_widgets');


/**
 * optional below
 */
 
 
/**
 * add custom shortcode to editor
 * http://wpexplorer-themes.com/total/snippets/tinymce-shortcodes-filter/
 */
  
 
 /*
 add_filter( 'wpex_shortcodes_tinymce_json', function( $data ) {

	// Add your custom shortcode
	$data['shortcodes']['my_custom_shortcode'] = array(
		'text' => __( 'Custom Shortcode', 'total' ),
		'insert' => '[custom_shortcode parameter1="value"]',
	);

	// Return data
	return $data;

} );
 
*/ 
 
 
 /**
 * load only theme fonts in VC modules
 */
 
/* 
 function myprefix_google_fonts( $array ) {
    return array( 'Open Sans', 'Roboto', 'Source Sans Pro' );
}
add_filter( 'wpex_google_fonts_array', 'myprefix_google_fonts' );
*/ 


/**
  * @plugin wp-scss
 * for development only, remove when live
*/ 
// define('WP_SCSS_ALWAYS_RECOMPILE', true);
 



/**
 * script manager template for registering and enqueuing files
 * @link http://wpcandy.com/teaches/how-to-load-scripts-in-wordpress-themes
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
 * Add TypeKit Fonts To  Child Theme
 * 
 */
 
 /*
function theme_typekit() {
    wp_enqueue_script( 'theme_typekit', '//use.typekit.net/bjg1wkq.js');
}
add_action( 'wp_enqueue_scripts', 'theme_typekit' );
    function theme_typekit_inline() {
    if ( wp_script_is( 'theme_typekit', 'done' ) ) { ?>
  	<script type="text/javascript">try{Typekit.load();}catch(e){}</script>
<?php }
}
add_action( 'wp_head', 'theme_typekit_inline' );
*/


 

/*-----------------------------------------------------------------------------------*/
/*	- SAMPLE SNIPPETS
/*	- Below are some useful snippets you can use to tweak the theme.
/*	- See the Total FAQ for more useful snippets or ask me :)
/*-----------------------------------------------------------------------------------*/


/**
 * Load translations from your child theme
 * Add your .po/.mo files into a "languages" folder in your child theme then uncomment this function
 *
 * @link http://codex.wordpress.org/Function_Reference/load_child_theme_textdomain
 * @return url
 */
/*
function my_child_theme_setup() {
    load_child_theme_textdomain( 'my_child_theme', get_stylesheet_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'my_child_theme_setup' );
*/



/**
 * Add page settings meta options to other post types
 *
 * @link Total/framework/redux/meta-config.php
 */
/*
function my_add_meta_to_post_types( $array ) {
    $array[] = 'gallery';
    return $array;
}
add_filter( 'wpex_main_metaboxes_post_types', 'my_add_meta_to_post_types' );
*/



/**
 * Alter the layout on any page, post, archive
 *
 * @link Total/framework/post-layout.php
 */
/*
function my_fullwidth_homepage( $class ) {
    // Make the front-page have a full-width layout
    if ( is_front_page() ) {
        return 'full-width';
    } else { 
        return $class;
    }
}
add_filter( 'wpex_post_layout_class', 'my_fullwidth_homepage' );
*/



/**
 * Disable the main header anywhere
 *
 * @link Total/framework/header/page-header.php
 */
/*
function my_disable_header( $return ) {
    // Disable header on the homepage
    if ( is_front_page() ) {
        return false;
    } else { 
        return $return;
    }
}
add_filter( 'wpex_display_header', 'my_disable_header' );
*/



/**
 * Customize the default logo URL
 *
 * @link Total/framework/header/header-logo.php
 * @return string
 */
/*
function my_custom_logo_url() {
    return 'wpexplorer.com';
}
add_filter( 'wpex_logo_url', 'my_custom_logo_url' );
*/