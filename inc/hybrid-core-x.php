<?php
/**
 * Hybrid Core Experimental - Experimental features for future Hybrid Core versions.
 *
 * Group of functions and filters likely to be include in a future version of the Hybrid Core framework.  
 * This file is a test run of these new features.  This means that the contents of this file will 
 * probably be removed in a future version of this theme.
 *
 * @package    SociallyAwkward
 * @subpackage Functions
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2013 - 2014, Justin Tadlock
 * @link       http://themehybrid.com/themes/socially-awkward
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/* Filter the prev/next posts link attributes. */
add_filter( 'previous_posts_link_attributes', 'socially_awkward_previous_posts_link_attributes' );
add_filter( 'next_posts_link_attributes',     'socially_awkward_next_posts_link_attributes' );

/* Filter the prev/next comments link attributes. */
add_filter( 'previous_comments_link_attributes', 'socially_awkward_previous_comments_link_attributes' );
add_filter( 'next_comments_link_attributes',     'socially_awkward_next_comments_link_attributes' );

/**
 * @since      0.1.0
 * @deprecated 1.0.0
 */
function socially_awkward_content_template( $template ) {
	return $template;
}

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
