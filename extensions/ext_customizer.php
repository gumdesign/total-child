<?php
/*
 * Add phone field to site idendity section
 *
 */


function childtheme_customizer_options( $wp_customize ) {

  $wp_customize->add_setting(
        'company_phone', //ID
        array(
            'default' => '', // Give it a default
        )
    );


    $wp_customize->add_setting(
        'admin_body_class', //ID
        array(
            'default' => '', // Give it a default
        )
    );

    $wp_customize->add_setting(
        'header_image', //ID
        array(
            'default' => '', // Give it a default
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Control(
            $wp_customize,
            'company_phone', //give it an ID
            array(
                'type' =>	'text',
                'label'      => __( 'Company Phone', 'GD' ), //set the label to appear in the Customizer
                'section'    => 'title_tagline', //select the section for it to appear under  
                'settings'   => 'company_phone' //pick the setting it applies to
            )
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Control(
            $wp_customize,
            'admin_body_class', //give it an ID
            array(
                'type' =>	'checkbox', // text, checkbox, textarea, dropdown, number, wpex-heading, wpex-fa-icon-select, wpex-sortable
                'label'      => __( 'Admin Mode', 'GD' ), //set the label to appear in the Customizer
                'section'    => 'title_tagline', //select the section for it to appear under  
                'settings'   => 'admin_body_class' //pick the setting it applies to
            )
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Control(
            $wp_customize,
            'header_image', //give it an ID
            array(
                'type' =>	'number', // text, checkbox, textarea, dropdown, number, wpex-heading, wpex-fa-icon-select, wpex-sortable
                'label'      => __( 'Header Image', 'GD' ), //set the label to appear in the Customizer
                'section'    => 'title_tagline', //select the section for it to appear under  
                'settings'   => 'header_image' //pick the setting it applies to
            )
        )
    );


}
add_action('customize_register','childtheme_customizer_options');

/*
* output functions
*/



/**
 * adds admin class to body
 */
function add_admin_bodyclass( $classes ) {
	if( get_theme_mod( 'admin_body_class' ) ){
		$classes[] = 'is_admin';
	}
	return $classes;
}
add_action( 'body_class', 'add_admin_bodyclass', 9999 );



function gd_get_phone() {
    if( get_theme_mod( 'company_phone' ) ){
        return esc_attr( get_theme_mod( 'company_phone') );
    }
}
add_shortcode( 'phone', 'gd_get_phone' );






/*
* shortcut output 
*/


?>