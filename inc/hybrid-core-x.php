<?php
/**
 * Hybrid Core Experimental - Experimental features for future Hybrid Core versions.
 *
 * Functionality likely to be included in Hybrid Core version 1.7.0.
 */

$prefix = hybrid_get_prefix();

add_filter( "{$prefix}_content_template", 'socially_awkward_content_template' );

	/* Filter the prev/next posts link attributes. */
	add_filter( 'previous_posts_link_attributes', 'socially_awkward_previous_posts_link_attributes' );
	add_filter( 'next_posts_link_attributes',     'socially_awkward_next_posts_link_attributes' );

	/* Filter the prev/next comments link attributes. */
	add_filter( 'previous_comments_link_attributes', 'socially_awkward_previous_comments_link_attributes' );
	add_filter( 'next_comments_link_attributes',     'socially_awkward_next_comments_link_attributes' );



/* === HYBRID CORE 1.7 CHANGES. === 
 *
 * The following changes are slated for Hybrid Core version 1.7 and will likely be removed 
 * from the next version of this theme.
 */

	function socially_awkward_content_template( $template ) {

		if ( 'attachment' === get_post_type() ) {

			$mime_type = get_post_mime_type();

			if ( false !== strpos( $mime_type, '/' ) )
				list( $type, $subtype ) = explode( '/', $mime_type );
			else
				list( $type, $subtype ) = array( $mime_type, '' );

			$has_template = locate_template( "content-attachment-{$type}.php", false, false );

			return $has_template ? $has_template : $template;
		}

		return $template;
	}

/* End Hybrid Core 1.7 section. */


/**
 * Adds 'rel="prev"' to the previous posts link.
 *
 * @since  0.1.0
 * @access public
 * @param  string  $attributes
 * @return string
 */
function socially_awkward_previous_posts_link_attributes( $attributes ) {
	return $attributes . ' rel="prev"';
}

/**
 * Adds 'rel="next"' to the next posts link.
 *
 * @since  0.1.0
 * @access public
 * @param  string  $attributes
 * @return string
 */
function socially_awkward_next_posts_link_attributes( $attributes ) {
	return $attributes . ' rel="next"';
}

/**
 * Adds 'rel="prev" to the previous comments link.
 *
 * @since  0.1.0
 * @access public
 * @param  string $attributes The previous comments link attributes.
 * @return string
 */
function socially_awkward_previous_comments_link_attributes( $attributes ) {
	return $attributes . ' rel="prev"';
}

/**
 * Adds 'rel="next" to the next comments link.
 *
 * @since  0.1.0
 * @access public
 * @param  string $attributes The next comments link attributes.
 * @return string
 */
function socially_awkward_next_comments_link_attributes( $attributes ) {
	return $attributes . ' rel="next"';
}





?>