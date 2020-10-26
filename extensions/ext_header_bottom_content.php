<?php
/**
 * add content below header
 */

function add_header_bottom() {
    // TODO: get template ID from customizer
    $template = 1021;
    ?>
    <div class="header-bottom">
        <div class="container clr">
            <?php echo  do_shortcode( '[templatera id="' . $template . '"]' ); ?>
        </div>
    </div>
  
<?php 
}
add_action( 'wpex_hook_header_after', 'add_header_bottom');
