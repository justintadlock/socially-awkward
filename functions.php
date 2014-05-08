<?php
/**
 * "You know, everyone's a Democrat until they get a little money. Then they come to their senses." 
 * ~ Harold Weir (Freaks and Geeks)
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU 
 * General Public License as published by the Free Software Foundation; either version 2 of the License, 
 * or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without 
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * You should have received a copy of the GNU General Public License along with this program; if not, write 
 * to the Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 *
 * @package    SociallyAwkward
 * @subpackage Functions
 * @version    1.0.0
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2013 - 2014, Justin Tadlock
 * @link       http://themehybrid.com/themes/socially-awkward
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/* Load the core theme framework. */
require_once( trailingslashit( get_template_directory() ) . 'library/hybrid.php' );
new Hybrid();

/* Do theme setup on the 'after_setup_theme' hook. */
add_action( 'after_setup_theme', 'socially_awkward_theme_setup' );

/**
 * Theme setup function.  This function adds support for theme features and defines the default theme
 * actions and filters.
 *
 * @since  0.1.0
 * @access public
 * @return void
 */
function socially_awkward_theme_setup() {

	/* Load includes. */
	require_once( trailingslashit( get_template_directory() ) . 'inc/hybrid-core-x.php'    );
	require_once( trailingslashit( get_template_directory() ) . 'inc/socially-awkward.php' );

	/* Load scripts. */
	add_theme_support( 'hybrid-core-scripts', array( 'comment-reply', 'mobile-toggle' ) );

	/* Load styles. */
	add_theme_support( 
		'hybrid-core-styles', 
		array( 'socially-awkward-fonts', 'one-five', 'gallery', 'theme-mediaelement', 'parent', 'style' ) 
	);

	/* Load shortcodes. */
	add_theme_support( 'hybrid-core-shortcodes' );

	/* Enable custom template hierarchy. */
	add_theme_support( 'hybrid-core-template-hierarchy' );

	/* Allow per-post stylesheets. */
	add_theme_support( 'post-stylesheets' );

	/* The best thumbnail/image script ever. */
	add_theme_support( 'get-the-image' );

	/* Nicer [gallery] shortcode implementation. */
	add_theme_support( 'cleaner-gallery' );

	/* Better captions for themes to style. */
	add_theme_support( 'cleaner-caption' );

	/* Automatically add feed links to <head>. */
	add_theme_support( 'automatic-feed-links' );

	/* Post formats. */
	add_theme_support( 
		'post-formats', 
		array( 'aside', 'audio', 'chat', 'image', 'gallery', 'link', 'quote', 'status', 'video' ) 
	);

	/* Handle content width for embeds and images. */
	hybrid_set_content_width( 960 );
}
