jQuery(document).ready(function($) {
    // $() will work as an alias for jQuery() inside of this function
    //  $('.slides').append('<div class="banner"></div>');

    // add external icon to all outgoing links
    $("#primary a").filter(function() {
    return this.hostname && this.hostname !== location.hostname;
    }).attr('target', '_blank').append(' <span class="ticon ticon-external-link"></span>');
});


/**
 * show/hide VC row
 */
( function( $ ) {

    'use strict';

    $( document ).ready( function() {

        // 
        var $trigger = $( '.vc-row-toggle-button' );

        // 
        var $hiddenRow = $( '.vc-row-toggle' );

        if ( $hiddenRow.length ) {
            $trigger.click( function() {
                console.log('click vc-row-toggle-button');
                $hiddenRow.slideToggle();
                return false;
            } );
        }

    } );

} ( jQuery ) );