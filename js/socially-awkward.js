jQuery( document ).ready(

	function() {

		/* Overrides WP's <div> wrapper around videos, which mucks with flexible videos. */
		jQuery( 'div[style*="max-width: 100%"] > video' ).parent().css( 'width', '100%' );

		/* Responsive videos. */
		/* blip.tv adds a second <embed> with "display: none".  We don't want to wrap that. */
		jQuery( 'object, embed, iframe' ).not( 'embed[style*="display"], [src*="soundcloud.com"]' ).wrap( '<div class="embed-wrap" />' );

		/* Removes the 'width' attribute from embedded videos and replaces it with a max-width. */
		jQuery( '.embed-wrap object, .embed-wrap embed, .embed-wrap iframe' ).attr( 
			'width',
			function( index, value ) {
				jQuery( this ).attr( 'style', 'max-width: ' + value + 'px;' );
				jQuery( this ).removeAttr( 'width' );
			}
		);

		/* Toggles audio/video info when using the [audio] or [video] shortcode. */
		jQuery( '.media-info-toggle' ).click(
			function() {
				jQuery( this ).parent().children( '.audio-info, .video-info' ).fadeToggle( 'slow' );
				jQuery( this ).toggleClass( 'active' );
			}
		);
	}
);