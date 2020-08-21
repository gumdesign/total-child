<?php
/**
 * Garden Club functions
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
 

 
// Legacy functions from old Jupiter childtheme mostly for member section functionality
include_once('legacy_functions.php');

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


/*
* Load Child Theme style.css After Parent Theme style.css AND VC styles
* https://themeforest.net/item/total-responsive-multipurpose-wordpress-theme/6339019/comments?page=873&filter=all#comment_22874228
* https://wpexplorer-themes.com/total/snippets/child-css-after-parent-css/
*/

function my_load_child_theme_styles() {

    // First de-register the main stylesheet (which is now your child theme style.css)
    wp_dequeue_style( WPEX_THEME_STYLE_HANDLE );
    wp_deregister_style( WPEX_THEME_STYLE_HANDLE );

    // Add the parent style.css with the main style handle
    wp_enqueue_style( WPEX_THEME_STYLE_HANDLE, get_template_directory_uri() . '/style.css', array(), WPEX_THEME_VERSION );

    // List of dependencies for your child theme style.css
    $deps = array( WPEX_THEME_STYLE_HANDLE );

    // Add Total WPBakery styles to dependency if enabled
    if ( wpex_get_mod( 'extend_visual_composer', true )  ) {
    $deps[] = 'wpex-visual-composer';
  }
  
  //  if we are using events add to dependency
  // this breaks with the new tribe version
  /*
  if ( wpex_get_mod( 'extend_tribe_events', true )  ) {
    $deps[] = 'wpex-tribe-events'; 
    }
*/
    // Re-add child CSS with parent as dependency & add theme version
    wp_enqueue_style( 'child-theme', get_stylesheet_directory_uri() . '/style.css', $deps, wp_get_theme()->get('Version') );

}
add_action( 'wp_enqueue_scripts', 'my_load_child_theme_styles' );


/**
 * extend cookie expiration
 */
// only extending expiration if $remember and low privelege.
add_filter('auth_cookie_expiration', 'auth_cookie_expiration_filter', 10, 3);
function auth_cookie_expiration_filter($expiration, $user_id, $remember) {
    if ($remember && !user_can($user_id, 'edit_others_posts')) {
        return YEAR_IN_SECONDS;
        // return MONTH_IN_SECONDS;
        // return DAY_IN_SECONDS;
        // return HOUR_IN_SECONDS;
    }
    // default
    return $expiration;
}



/**
 * Add Google Fonts To  Child Theme
 * 
 */
/**/
function load_google_fonts() {
wp_register_style('googleWebFonts', '//fonts.googleapis.com/css2?family=Merriweather:wght@400;700&family=Raleway:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap');
wp_enqueue_style('googleWebFonts');
}
add_action('wp_print_styles', 'load_google_fonts'); 




 /**
  * remove admin bar for subscriber & gcamerica
  */
function remove_admin_bar() {
    // if (!current_user_can('administrator') && !is_admin()) {
     if (current_user_can('subscriber') || current_user_can('gcamerica') ) {
        show_admin_bar(false);
    }
}
add_action('after_setup_theme', 'remove_admin_bar');


// Redirect to home page on login
function loginRedirect( $redirect_to, $request_redirect_to, $user ) {
    if ( is_a( $user, 'WP_User' ) && $user->has_cap( 'edit_posts' ) === false ) {
        return '/members-home'; 
    }
    return $redirect_to; }
add_filter( 'login_redirect', 'loginRedirect', 10, 3 );



/**
 * Allow editor role to edit menus
 * 
 */

// Do this only once - this is saved to the DB. 
$role_object = get_role( 'editor' );
$role_object->add_cap( 'edit_theme_options' );

function editor_appearance_menu() {

    if (current_user_can('editor')) {

        remove_submenu_page( 'themes.php', 'themes.php' ); // hide the theme selection submenu
        remove_submenu_page( 'themes.php', 'theme-editor.php' ); // hide the theme editor submenu
        remove_submenu_page( 'themes.php', 'install-required-plugins.php' ); // hide the theme editor submenu
        //remove_submenu_page( 'themes.php', 'widgets.php' ); // hide the widgets submenu
        remove_submenu_page( 'themes.php', 'customize.php?return=%2Fwp-admin%2Fwidgets.php' ); // hide the customizer submenu
        //remove_submenu_page( 'themes.php', 'customize.php?return=%2Fwp-admin%2Ftools.php&#038;autofocus%5Bcontrol%5D=background_image' ); // hide the background submenu
    }
}
add_action('admin_head', 'editor_appearance_menu');





/**
 * Checks if is public page
 * home page or child pages of home
 */
function is_public_page() {
    // Get global post
    global $post;

    // home page id
    $page_id = 3377;
    
    if (  $post->post_parent == $page_id || is_page( $page_id )  ) {
        return true;
        } else {
        return false;
    }
}


/**
 * Display custom menus based on roles
 * @filter wpex_custom_menu
 */

/* */
add_filter( 'wpex_custom_menu', function( $menu_id ) {

    /*
    if role = Administrator | Editor | Fiscal | Show | Subscriber > show Member Menu
    if role =  gcamerica > show  GCAmerica Menu
    if role not set or not logged in, show Public Menu
    */

    if (is_user_logged_in( ) && !is_public_page() ) {

        $user = wp_get_current_user();
        $gcamerica = array('gcamerica');
        $admins = array('editor', 'administrator', 'fiscal', 'show', 'subscriber');

        if( array_intersect($admins, $user->roles ) ) { 
            $menu_id = '59';
        } 
        if( array_intersect($gcamerica, $user->roles ) ) { 
            $menu_id = '56';
        }

     }

	// Return menu
	return $menu_id;

} );

/**
 * Alternative logo for admin
 */
/*
add_filter( 'wpex_header_logo_img_url', function( $image ) {

    if ( !is_public_page() ) {
        $image = get_stylesheet_directory_uri() . '/images/header_clear_sackers_huntergreen_logo_ewelinakakou.png';
    }
    // Return logo image
    return $image;
} );
*/

/**
 * Set member page title
 */

add_filter( 'wpex_title', function( $title ) {
 
    // Change members title 
    if ( !is_public_page() ) {
        // $title = 'GCH Members';
        global $current_user; 
        $title = ' Aloha ' . $current_user->user_firstname ;
    }
 
    // Return the title
    return $title;
    
}, 20 );


/**
 * Add page title to content
 */

add_action( 'wpex_hook_primary_before', function() {
    if ( !is_public_page() ) {
        echo '<h1 class="page-title">'. get_the_title() .'</h1>';
    }
} );

/**
 * Add member name to page header
 */
/*
add_filter( 'wpex_post_subheading', function( $string ) {
    global $current_user; 
	if ( !is_public_page() ) {
		return '<span class="user-name wpex-inline"> Aloha ' . $current_user->user_firstname .  " </span>"; 
	}
	return $string;
} );
*/

/**
 * Add user shcortcode
 */
function user_name_shortcode() {
    global $current_user; 
    echo '<span class="user-name wpex-inline">' . $current_user->user_login . "</span>"; 
}
add_shortcode( 'user_name', 'user_name_shortcode' );



/**
 * Add shortcode for admin dropdown button - see top bar
 * [dropbutton label="" menu=""]
 * 
*/

function dropbutton_shortcode( $atts, $content = null ) {

    // show admin dropdown menu only to logged in users
    // 
    if( !is_user_logged_in(  )) {
        $out = '<span class="wpex-inline"><i class="ticon ticon-user"></i> ';
        $out .= do_shortcode('[wp_login_url text="Member Login" logout_text="Logout"]');
        $out .= '</span>';
    } else {


	extract( shortcode_atts(
		 array(
		   'label' => '',
		   'menu' => ''
		 ),
		 $atts ) );
		 
	if ($menu) {
	
		$args = array(
			'menu' => $menu,
			'echo' => 0,
			'container'       => 'div',
			'container_class' => 'dropbutton-menu-container',
			'menu_class'      => '',
			'items_wrap'      => '<ul id="dropbutton-menu" class="dropbutton-menu">%3$s</ul>',
			);
		
		$dropmenu =  wp_nav_menu($args);	 
	}	 
	// ob_start();	 
	$out = '<div id="my-account">';
	$out .= '<i class="ticon ticon-user"></i> <a href="#" id="dropbutton" class="dropbutton">' .$label .  '</a>';
	$out .= $dropmenu;
	$out .= '</div>';
   // echo ob_get_clean();	
    //$out = ob_get_clean();
   
    }
    
    echo $out;
	 	 
}
add_shortcode( 'dropbutton', 'dropbutton_shortcode' );


/**
 * Add login/out button to top menu
 * https://wpexplorer-themes.com/total/snippets/add-loginlogout-button-main-menu/
 */
add_filter( 'wp_nav_menu_items', function( $items, $args ) {

	// Only used on main menu
	if ( 'main_menu' == $args->theme_location ) {
		return $items;
	}

	// Add custom item
	$items .= '<li class="mmy-custom-login-logout-link menu-button menu-item">';

		// Log-out link
		if ( is_user_logged_in() ) {

			$text            = 'Logout';
			$logout_redirect = home_url( '/' ); // Change logout redirect URl here

			$items .= '<a href="'. wp_logout_url( $logout_redirect ) .'" title="'. esc_attr( $text ) .'" class="wpex-logout"><span class="link-inner">'. strip_tags( $text ) .'</span></a>';

		}

		// Log-in link
		else {

			$text      = 'Login';
			$login_url = wp_login_url(); // Change if you want a custom login url

			$items .= '<a href="'. esc_url( $login_url ) .'" title="'. esc_attr( $text ) .'"><span class="link-inner">'. strip_tags( $text ) .'</span></a>';

		}

	$items .= '</li>';

	// Return nav $items
	return $items;

}, 20, 2 );



/**
 * adds dropbutton script to footer
 */

add_action( 'wp_footer', function() { ?>

<script>

( function( $ ) {

    'use strict';

    $( document ).ready( function() {

        var $trigger = $('#dropbutton' );
        var $target = $( '.dropbutton-menu-container' );

        if ( $target.length ) {
            $trigger.click( function() {
               // console.log('click');
                $target.toggle();
                return false;
            } );
        }

        // change link on logo
        $('.logged-in .main-logo').attr('href', '/members-home');

    } );

} ( jQuery ) );

</script>

<?php }, 99 );
             
 


/*
add_action( 'wpex_hook_primary_before', function() {
    if ( is_user_logged_in() ) {?>
		<div class="member-nav-wrapper clr">

		<div class="container clr"> <!-- .center the navbar content -->

			<?php echo do_shortcode( '[vcex_navbar menu="4"]' );?>

		</div>

	</div>
	<?php }
} );
*/


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
 * Add Logo for mobile
 */
function myprefix_custom_mobile_logo() {
	// Get header logo data
	$logo_url   = wpex_header_logo_url();
	$logo_title = wpex_header_logo_title(); ?>
	<a href="<?php echo esc_url( $logo_url ); ?>" title="<?php echo esc_attr( $logo_title ); ?>" rel="home" class="mobile-site-logo">
		<img src="/wp-content/uploads/2020/04/logo.jpg" alt="logo" data-no-retina width="90"/>
	</a>
<?php }
add_action( 'wpex_hook_site_logo_inner', 'myprefix_custom_mobile_logo', 99 );


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