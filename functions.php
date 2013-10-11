<?php
/**
 * The functions file is used to initialize everything in the theme.  It controls how the theme is loaded and 
 * sets up the supported features, default actions, and default filters.  If making customizations, users 
 * should create a child theme and make changes to its functions.php file (not this one).  Friends don't let 
 * friends modify parent theme files. ;)
 *
 * Child themes should do their setup on the 'after_setup_theme' hook with a priority of 11 if they want to
 * override parent theme features.  Use a priority of 9 if wanting to run before the parent theme.
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
 * @version    0.1.3
 * @since      0.1.0
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2013, Justin Tadlock
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
	require_once( trailingslashit( get_template_directory() ) . 'inc/hybrid-core-x.php' );
	require_once( trailingslashit( get_template_directory() ) . 'inc/media.php' );
	require_once( trailingslashit( get_template_directory() ) . 'inc/class-get-image.php' );

	/* Get action/filter hook prefix. */
	$prefix = hybrid_get_prefix();

	/* Register menus. */
	add_theme_support( 'hybrid-core-menus', array( 'primary' ) );

	/* Load scripts. */
	add_theme_support( 'hybrid-core-scripts', array( 'comment-reply', 'mobile-toggle' ) );

	/* Load styles. */
	add_theme_support( 
		'hybrid-core-styles', 
		array( 'one-five', 'socially-awkward-mediaelement', 'gallery', 'parent', 'style' ) 
	);

	/* Load shortcodes. */
	add_theme_support( 'hybrid-core-shortcodes' );

	/* Enable custom template hierarchy. */
	add_theme_support( 'hybrid-core-template-hierarchy' );

	/* Load the media grabber script. */
	add_theme_support( 'hybrid-core-media-grabber' );

	/* Allow per-post stylesheets. */
	add_theme_support( 'post-stylesheets' );

	/* The best thumbnail/image script ever. */
	add_theme_support( 'get-the-image' );

	/* Nicer [gallery] shortcode implementation. */
	add_theme_support( 'cleaner-gallery' );

	/* Better captions for themes to style. */
	add_theme_support( 'cleaner-caption' );

	/* Add support for custom theme fonts. */
	add_theme_support( 'theme-fonts', array( 'callback' => 'socially_awkward_register_fonts' ) );

	/* Automatically add feed links to <head>. */
	add_theme_support( 'automatic-feed-links' );

	/* Post formats. */
	add_theme_support( 
		'post-formats', 
		array( 'aside', 'audio', 'chat', 'image', 'gallery', 'link', 'quote', 'status', 'video' ) 
	);

	/* Handle content width for embeds and images. */
	hybrid_set_content_width( 960 );

	/* Register custom image sizes. */
	add_action( 'init', 'socially_awkward_register_image_sizes' );

	/* Register custom nav menus. */
	add_action( 'init', 'socially_awkward_register_nav_menus', 11 );

	/* Add custom nav menu item classes. */
	add_filter( 'nav_menu_css_class', 'socially_awkward_nav_menu_css_class', 10, 3 );

	/* De-register stylesheets. */
	add_action( 'wp_enqueue_scripts', 'socially_awkward_deregister_styles' );

	/* Load custom styles. */
	add_filter( "{$prefix}_styles", 'socially_awkward_styles' );

	/* Load custom scripts. */
	add_action( 'wp_enqueue_scripts', 'socially_awkward_enqueue_scripts' );

	/* Use post formats to decide prev/next post. */
	add_filter( 'get_previous_post_join', 'socially_awkward_adjacent_post_join' );
	add_filter( 'get_next_post_join',     'socially_awkward_adjacent_post_join' );

	/* Change the comments number output. */
	add_filter( 'shortcode_atts_entry-comments-link', 'socially_awkward_entry_comments_link_atts' );
}

/**
 * Removes the WordPress mediaelement styles on the front end.  We're rolling our own.
 *
 * @since  0.1.0
 * @access public
 * @return void
 */
function socially_awkward_deregister_styles() {
	wp_deregister_style( 'mediaelement' );
	wp_deregister_style( 'wp-mediaelement' );
}

/**
 * Adds a custom stylesheet to the Hybrid styles loader.  We need to do this so that it's loaded in the correct 
 * order (before the theme style).
 *
 * @since  0.1.0
 * @access public
 * @param  array  $styles
 * @return array
 */
function socially_awkward_styles( $styles ) {

	$styles['socially-awkward-mediaelement'] = array(
		'version' => '20130830.1',
		'src'     => trailingslashit( get_template_directory_uri() ) . 'css/mediaelement/mediaelement.min.css'
	);

	return $styles;
}

/**
 * Loads scripts needed by the theme.
 *
 * @since  0.1.0
 * @access public
 * @return void
 */
function socially_awkward_enqueue_scripts() {

	wp_enqueue_script( 
		'socially-awkward', 
		hybrid_locate_theme_file( array( 'js/socially-awkward.js' ) ), 
		array( 'jquery' ),
		'20130812',
		true
	);
}

/**
 * Changes the post comments link number to "(%s)".
 *
 * @since  0.1.0
 * @access public
 * @param  array  $out
 * @return array
 */
function socially_awkward_entry_comments_link_atts( $out ) {

	$out['zero'] = _x( '(0)',  'comments number', 'socially-awkward' );
	$out['one']  = _x( '(%s)', 'comments number', 'socially-awkward' );
	$out['more'] = _x( '(%s)', 'comments number', 'socially-awkward' );

	return $out;
}

/**
 * Adds custom nav menu item classes.
 *
 * @since  0.1.0
 * @access public
 * @param  array   $classes
 * @param  object  $item
 * @param  object  $args
 */
function socially_awkward_nav_menu_css_class( $classes, $item, $args ) {

	if ( 'formats' === $args->theme_location && 'taxonomy' === $item->type && 'post_format' === $item->object )
		$classes[] = 'menu-item-' . hybrid_clean_post_format_slug( get_term_field( 'slug', $item->object_id, $item->object, 'attribute' ) );

	if ( 'post_type' === $item->type && 'page' === $item->object && $item->object_id == get_option( 'page_for_posts' ) )
		$classes[] = 'menu-item-blog';

	return $classes;
}

/**
 * Registers custom image sizes for the theme.
 *
 * @since  0.1.0
 * @access public
 * @return void
 */
function socially_awkward_register_image_sizes() {
	set_post_thumbnail_size( 175, 131, true );
	add_image_size( 'socially-awkward-large', 960, 720, true );
}

/**
 * Registers custom nav menus for the theme.
 *
 * @since  0.1.0
 * @access public
 * @return void
 */
function socially_awkward_register_nav_menus() {

	register_nav_menu( 'social', esc_html__( 'Social', 'socially-awkward' ) );

	register_nav_menu( 'formats', esc_html__( 'Formats', 'socially-awkward' ) );

	if ( post_type_exists( 'portfolio_item' ) )
		register_nav_menu( 'portfolio', esc_html__( 'Portfolio', 'socially-awkward' ) );
}

/**
 * Gets the "blog" (posts page) page URL.  WordPress could really use this function.
 *
 * @since  0.1.0
 * @access public
 * @return string
 */
function socially_awkward_get_blog_url() {
	$blog_url = '';

	if ( 'posts' === get_option( 'show_on_front' ) )
		$blog_url = home_url();

	elseif ( 0 < ( $page_for_posts = get_option( 'page_for_posts' ) ) )
		$blog_url = get_permalink( $page_for_posts );

	return $blog_url;
}

/**
 * Registers custom fonts for the theme.
 *
 * Note that I'm passing an empty array() as the 'selectors' argument. This is so that the Theme Fonts script 
 * won't auto-output the CSS, which I'm simply handling in 'style.css'.  Most likely, this code will change 
 * drastically in future versions as the Theme Fonts class allows more flexibility.
 *
 * @since  0.1.0
 * @access public
 * @param  object  $fonts
 * @return void
 */
function socially_awkward_register_fonts( $fonts ) {

	/* Body copy. */
	$fonts->add_setting( array( 'id' => 'body', 'default' => 'lora', 'selectors' => array() ) );
	$fonts->add_setting( array( 'id' => 'body-italic', 'default' => 'lora-italic', 'selectors' => array() ) );
	$fonts->add_setting( array( 'id' => 'body-bold', 'default' => 'lora-bold', 'selectors' => array() ) );
	$fonts->add_setting( array( 'id' => 'body-bold-italic', 'default' => 'lora-bold-italic', 'selectors' => array() ) );

	/* Headers and other bold fonts. */
	$fonts->add_setting( array( 'id' => 'headers', 'default' => 'open-sans-condensed', 'selectors' => array() ) );

	/* Misc. secondary font. */
	$fonts->add_setting( array( 'id' => 'accent', 'default' => 'open-sans', 'selectors' => array() ) );

	/* Lora font family (normal, italic, bold, bold italic). */
	$fonts->add_font(
		array( 'handle' => 'lora', 'family' => 'Lora', 'type' => 'google' )
	);
	$fonts->add_font(
		array( 'handle' => 'lora-italic', 'family' => 'Lora', 'style' => 'italic', 'type' => 'google' )
	);
	$fonts->add_font(
		array( 'handle' => 'lora-bold', 'family' => 'Lora', 'weight' => 700, 'type' => 'google' )
	);
	$fonts->add_font(
		array( 'handle' => 'lora-bold-italic', 'family' => 'Lora', 'weight' => 700, 'style' => 'italic', 'type' => 'google' )
	);

	/* Open Sans Condensed font family. */
	$fonts->add_font(
		array( 'handle' => 'open-sans-condensed', 'family' => 'Open Sans Condensed', 'weight' => 700, 'type'   => 'google' ) 
	);

	/* Open Sans font family. */
	$fonts->add_font(
		array( 'handle' => 'open-sans', 'family' => 'Open Sans', 'type' => 'google' )
	);
}

/**
 * Changes the next/previous single post links based on the post format.
 *
 * @since  0.1.0
 * @access public
 * @param  string  $join
 * @return string
 */
function socially_awkward_adjacent_post_join( $join ) {
	global $wpdb;

	$post_id   = get_the_ID();
	$post_type = get_post_type();

	/* Only run if the post type supports 'post-formats'. */
	if ( post_type_supports( $post_type, 'post-formats' ) ) {

		/* Gets an array of post format IDs for the post. */
		$term_ids = wp_get_object_terms( $post_id, 'post_format', array( 'fields' => 'ids' ) );

		/* If no post format IDs or if an error was returned, return the original $join. */
		if ( empty( $term_ids ) || is_wp_error( $term_ids ) )
			return $join;

		/* Set up the join. */
		$join = $wpdb->prepare( 
			" INNER JOIN $wpdb->term_relationships 
			  AS tr 
			  ON p.ID = tr.object_id 
			  INNER JOIN $wpdb->term_taxonomy tt 
			  ON tr.term_taxonomy_id = tt.term_taxonomy_id 
			  AND tt.taxonomy = 'post_format' 
			  AND tt.term_id = %d", 
			array_shift( $term_ids ) 
		);
	}

	return $join;
}

/* === CPT: PORTFOLIO PLUGIN. === */

	/**
	 * Returns a link to the porfolio item URL if it has been set.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return void
	 */
	function socially_awkward_get_portfolio_item_link() {

		$url = get_post_meta( get_the_ID(), 'portfolio_item_url', true );

		if ( !empty( $url ) )
			return '<a class="portfolio-item-link" href="' . esc_url( $url ) . '">' . $url . '</a>';
	}

/* End CPT: Portfolio section. */

?>