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
 * @version    0.1.0
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

	/* Get action/filter hook prefix. */
	$prefix = hybrid_get_prefix();

	/* Register menus. */
	add_theme_support( 'hybrid-core-menus', array( 'primary' ) );

	/* Load scripts. */
	add_theme_support( 'hybrid-core-scripts', array( 'comment-reply', 'mobile-toggle' ) );

	/* Load styles. */
	add_theme_support( 'hybrid-core-styles', array( 'one-five', 'gallery', 'parent', 'style' ) );

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

	/* Load custom scripts. */
	add_action( 'wp_enqueue_scripts', 'socially_awkward_enqueue_scripts' );

	/* Use post formats to decide prev/next post. */
	add_filter( 'get_previous_post_join', 'socially_awkward_adjacent_post_join' );
	add_filter( 'get_next_post_join',     'socially_awkward_adjacent_post_join' );

	/* Filter the prev/next posts link attributes. */
	add_filter( 'previous_posts_link_attributes', 'socially_awkward_previous_posts_link_attributes' );
	add_filter( 'next_posts_link_attributes',     'socially_awkward_next_posts_link_attributes' );

	/* Filter the prev/next comments link attributes. */
	add_filter( 'previous_comments_link_attributes', 'socially_awkward_previous_comments_link_attributes' );
	add_filter( 'next_comments_link_attributes',     'socially_awkward_next_comments_link_attributes' );

	add_filter( 'wp_audio_shortcode', 'socially_awkward_audio_shortcode', 10, 4 );
	add_filter( 'wp_video_shortcode', 'socially_awkward_video_shortcode', 10, 4 );

	/** Hybrid Core 1.7 changes **/
	add_filter( "{$prefix}_content_template", 'socially_awkward_content_template' );
	/****************************/
}

function socially_awkward_audio_shortcode( $html, $atts, $audio, $post_id ) {

	/* Don't show on single attachment pages. */
	if ( is_attachment() || is_admin() )
		return $html;

	if ( is_object( $audio ) ) {
		$attachment_id = $audio->ID;
	} else {
		preg_match( '/src=[\'"](.+?)[\'"]/i', $html, $matches );

		if ( !empty( $matches ) )
			$attachment_id = socially_awkward_get_attachment_id_from_url( $matches[1] );
	}

	if ( !empty( $attachment_id ) ) {

		$html .= '<div class="media-shortcode-extend">';
		$html .= '<div class="audio-info">';
		$html .= socially_awkward_list_audio_meta( $attachment_id );
		$html .= '</div>';
		$html .= '<a class="media-info-toggle">' . __( 'Audio Info', 'socially-awkward' ) . '</a>';
		$html .= '</div>';
	}

	return $html;
}

function socially_awkward_video_shortcode( $html, $atts, $video, $post_id ) {

	/* Don't show on single attachment pages. */
	if ( is_attachment() || is_admin() )
		return $html;

	if ( is_object( $video ) ) {
		$attachment_id = $video->ID;
	} else {
		preg_match( '/src=[\'"](.+?)[\'"]/i', $html, $matches );

		if ( !empty( $matches ) )
			$attachment_id = socially_awkward_get_attachment_id_from_url( $matches[1] );
	}

	if ( !empty( $attachment_id ) ) {

		$html .= '<div class="media-shortcode-extend">';
		$html .= '<div class="video-info">';
		$html .= socially_awkward_list_video_meta( $attachment_id );
		$html .= '</div>';
		$html .= '<a class="media-info-toggle">' . __( 'Video Info', 'socially-awkward' ) . '</a>';
		$html .= '</div>';
	}

	return $html;
}

/**
 * @link http://pippinsplugins.com/retrieve-attachment-id-from-image-url/
 */
function socially_awkward_get_attachment_id_from_url( $url ) {

global $wpdb;
	$prefix = $wpdb->prefix;
	$attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM " . $prefix . "posts" . " WHERE guid='%s';", $url )); 
        return $attachment[0]; 
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
	register_nav_menu( 'social', __( 'Social', 'socially-awkward' ) );

	if ( post_type_exists( 'portfolio_item' ) )
		register_nav_menu( 'portfolio', esc_html__( 'Portfolio', 'socially-awkward' ) );
}

function socially_awkward_get_blog_url() {
	$blog_url = '';

	$show_on_front  = get_option( 'show_on_front' );

	if ( 'posts' == $show_on_front ) {
		$blog_url = home_url();

	} else {
		$page_for_posts = get_option( 'page_for_posts' );

		if ( 0 < $page_for_posts )
			$blog_url = get_permalink( $page_for_posts );
	}

	return $blog_url;
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
		hybrid_get_prefix(), 
		hybrid_locate_theme_file( array( 'js/socially-awkward.js' ) ), 
		array( 'jquery' ),
		'20130812',
		true
	);
}

/**
 * Registers custom fonts for the theme.
 *
 * @since  0.1.0
 * @access public
 * @param  object  $fonts
 * @return void
 */
function socially_awkward_register_fonts( $fonts ) {

	$fonts->add_setting(
		array(
			'id' => 'headers',
			'default' => 'roboto-condensed',
			'selectors' => 'h1,h2,h3,h4,h5,h6,.comment-author cite, dt, .format-chat cite'
		)
	);

	$fonts->add_font(
		array(
			'handle' => 'roboto-condensed',
			'family' => 'Roboto Condensed',
			'weight' => 700,
			'type'   => 'google'
		)
	);

	$fonts->add_setting(
		array(
			'id' => 'secondary',
			'default' => 'muli',
			'selectors' => '#site-description, .entry-byline, 
				.entry-meta, .breadcrumb-trail, #footer, #menu-primary li a, .loop-nav, .page-links, 
				.loop-pagination, .gallery-caption, .wp-caption-text, .comment-meta, .comment-reply-link, 
				label, th, .image-info .prep, .video-info .prep, .audio-info .prep, #menu-portfolio li a, .comments-nav a,
				.media-info-toggle'
		)
	);

	$fonts->add_font(
		array(
			'handle' => 'muli',
			'family' => 'Muli',
			'type'   => 'google'
		)
	);
}

/**
 * Wrapper function for the Socially_Awkward_Get_Image class.
 *
 * @since  0.1.0
 * @access public
 * @return void
 */
function socially_awkward_get_image() {
	new Socially_Awkward_Get_Image();
}

/**
 * Class created to handle extracting an image from the post and splitting it. Right now, the Get the Image 
 * script cannot handle this, but it should be worked into a future version.  Most likely, this class will 
 * be removed and replaced by that script.  Please don't use this class directly.  Use the wrapper function 
 * socially_awkward_get_image().
 *
 * @since  0.1.0
 * @access public
 * @return void
 */
final class Socially_Awkward_Get_Image {

	/**
	 * Image HTML output.
	 *
	 * @since  0.1.0
	 * @access public
	 * @var    string
	 */
	public $image = '';

	/**
	 * Original image HTML pulled from the post content.
	 *
	 * @since  0.1.0
	 * @access public
	 * @var    string
	 */
	public $original_image = '';

	/**
	 * Sets up and executes the script.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return void
	 */
	public function __construct() {
		echo $this->get_image();

		remove_filter( 'the_content', 'hybrid_image_content' );

		if ( !empty( $this->original_image ) )
			add_filter( 'the_content', array( $this, 'split_content' ), 1 );
	}

	/**
	 * Gets an image from the post content (including the caption if it exists).
	 *
	 * @since  0.1.0
	 * @access public
	 * @return string
	 */
	public function get_image() {

		$image = array();

		$post_id = get_the_ID();

		$post_content = get_post_field( 'post_content', $post_id );

		/* Finds matches for shortcodes in the content. */
		preg_match_all( '/' . get_shortcode_regex() . '/s', $post_content, $matches, PREG_SET_ORDER );

		if ( !empty( $matches ) ) {

			foreach ( $matches as $shortcode ) {

				if ( in_array( $shortcode[2], array( 'caption', 'wp_caption' ) ) ) {

					preg_match( '#id=[\'"]attachment_([\d]*)[\'"]|class=[\'"].*?wp-image-([\d]*).*?[\'"]#i', $shortcode[0], $matches );

					if ( !empty( $matches ) && isset( $matches[1] ) || isset( $matches[2] ) ) {

						$attachment_id = !empty( $matches[1] ) ? absint( $matches[1] ) : absint( $matches[2] );

						$image_src = wp_get_attachment_image_src( $attachment_id, 'socially-awkward-large' );

						if ( !empty( $image_src ) ) {

							/* old-style captions. */
							if ( preg_match( '#.*?[\s]caption=[\'"](.+?)[\'"]#i', $shortcode[0], $caption_matches ) )
								$image_caption = trim( $caption_matches[1] );

							$args = array(
								'width'   => $image_src[1],
								'align'   => 'center'
							);

							if ( !empty( $image_caption ) )
								$args['caption'] = $image_caption;


							/* Set up the patterns for the 'src', 'width', and 'height' attributes. */
							$patterns = array(
								'/(src=[\'"]).+?([\'"])/i',
								'/(width=[\'"]).+?([\'"])/i',
								'/(height=[\'"]).+?([\'"])/i',
							);

							/* Set up the replacements for the 'src', 'width', and 'height' attributes. */
							$replacements = array(
								'${1}' . $image_src[0] . '${2}',
								'${1}' . $image_src[1] . '${2}',
								'${1}' . $image_src[2] . '${2}',
							);

							/* Filter the image attributes. */
							$shortcode_content = preg_replace( $patterns, $replacements, $shortcode[5] );

							$image = img_caption_shortcode( $args, $shortcode_content );

							$this->original_image = $shortcode[0];


							return $image;
						}
						else {
							$this->original_image = $shortcode[0];
							return do_shortcode( $shortcode[0] );
						}
					}
				}
			}
		}

		if ( preg_match( '#((?:<a [^>]+>\s*)?<img [^>]+>(?:\s*</a>)?)(.*)#is', $post_content, $matches ) )
			return $this->original_image = $matches[0];


		return get_the_image( array( 'size' => 'full', 'image_class' => 'aligncenter', 'echo' => false ) );
	}

	/**
	 * Splits the original image HTML from the post content.
	 *
	 * @since  0.1.0
	 * @access public
	 * @param  string  $content
	 * @return string
	 */
	public function split_content( $content ) {

		remove_filter( 'the_content', array( $this, 'split_content' ), 1 );

		return str_replace( $this->original_image, '', $content );
	}
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

/**
 * Returns a set of image attachment links based on size.
 *
 * @since  0.1.0
 * @access public
 * @return string
 */
function socially_awkward_get_image_size_links() {

	/* If not viewing an image attachment page, return. */
	if ( !wp_attachment_is_image( get_the_ID() ) )
		return;

	/* Set up an empty array for the links. */
	$links = array();

	/* Get the intermediate image sizes and add the full size to the array. */
	$sizes = get_intermediate_image_sizes();
	$sizes[] = 'full';

	/* Loop through each of the image sizes. */
	foreach ( $sizes as $size ) {

		/* Get the image source, width, height, and whether it's intermediate. */
		$image = wp_get_attachment_image_src( get_the_ID(), $size );

		/* Add the link to the array if there's an image and if $is_intermediate (4th array value) is true or full size. */
		if ( !empty( $image ) && ( true === $image[3] || 'full' == $size ) )
			$links[] = "<a class='image-size-link' href='" . esc_url( $image[0] ) . "'>{$image[1]} &times; {$image[2]}</a>";
	}

	/* Join the links in a string and return. */
	return join( ' <span class="sep">/</span> ', $links );
}

/**
 * Displays an attachment image's metadata and exif data while viewing a singular attachment page.
 *
 * Note: This function will most likely be restructured completely in the future.  The eventual plan is to 
 * separate each of the elements into an attachment API that can be used across multiple themes.  Keep 
 * this in mind if you plan on using the current filter hooks in this function.
 *
 * @since  0.1.0
 * @access public
 * @return void
 */
function socially_awkward_image_info() {

	/* Set up some default variables and get the image metadata. */
	$meta = wp_get_attachment_metadata( get_the_ID() );
	$items = array();
	$list = '';

	/* Add the width/height to the $items array. */
	$items['dimensions'] = sprintf( __( '<span class="prep">Dimensions:</span> %s', 'socially-awkward' ), '<span class="data"><a href="' . esc_url( wp_get_attachment_url() ) . '">' . sprintf( __( '%1$s &#215; %2$s pixels', 'socially-awkward' ), $meta['width'], $meta['height'] ) . '</a></span>' );

	/* If a timestamp exists, add it to the $items array. */
	if ( !empty( $meta['image_meta']['created_timestamp'] ) )
		$items['created_timestamp'] = sprintf( __( '<span class="prep">Date:</span> %s', 'socially-awkward' ), '<span class="data">' . date( get_option( 'date_format' ), $meta['image_meta']['created_timestamp'] ) . '</span>' );

	/* If a camera exists, add it to the $items array. */
	if ( !empty( $meta['image_meta']['camera'] ) )
		$items['camera'] = sprintf( __( '<span class="prep">Camera:</span> %s', 'socially-awkward' ), '<span class="data">' . $meta['image_meta']['camera'] . '</span>' );

	/* If an aperture exists, add it to the $items array. */
	if ( !empty( $meta['image_meta']['aperture'] ) )
		$items['aperture'] = sprintf( __( '<span class="prep">Aperture:</span> %s', 'socially-awkward' ), '<span class="data">' . sprintf( __( 'f/%s', 'socially-awkward' ), $meta['image_meta']['aperture'] ) . '</span>' );

	/* If a focal length is set, add it to the $items array. */
	if ( !empty( $meta['image_meta']['focal_length'] ) )
		$items['focal_length'] = sprintf( __( '<span class="prep">Focal Length:</span> %s', 'socially-awkward' ), '<span class="data">' . sprintf( __( '%s mm', 'socially-awkward' ), $meta['image_meta']['focal_length'] ) . '</span>' );

	/* If an ISO is set, add it to the $items array. */
	if ( !empty( $meta['image_meta']['iso'] ) )
		$items['iso'] = sprintf( __( '<span class="prep">ISO:</span> %s', 'socially-awkward' ), '<span class="data">' . $meta['image_meta']['iso'] . '</span>' );

	/* If a shutter speed is given, format the float into a fraction and add it to the $items array. */
	if ( !empty( $meta['image_meta']['shutter_speed'] ) ) {

		if ( ( 1 / $meta['image_meta']['shutter_speed'] ) > 1 ) {
			$shutter_speed = '1/';

			if ( number_format( ( 1 / $meta['image_meta']['shutter_speed'] ), 1 ) ==  number_format( ( 1 / $meta['image_meta']['shutter_speed'] ), 0 ) )
				$shutter_speed .= number_format( ( 1 / $meta['image_meta']['shutter_speed'] ), 0, '.', '' );
			else
				$shutter_speed .= number_format( ( 1 / $meta['image_meta']['shutter_speed'] ), 1, '.', '' );
		} else {
			$shutter_speed = $meta['image_meta']['shutter_speed'];
		}

		$items['shutter_speed'] = sprintf( __( '<span class="prep">Shutter Speed:</span> %s', 'socially-awkward' ), '<span class="data">' . sprintf( __( '%s sec', 'socially-awkward' ), $shutter_speed ) . '</span>' );
	}

	/* Allow devs to overwrite the array of items. */
	$items = apply_atomic( 'image_info_items', $items );

	/* Loop through the items, wrapping each in an <li> element. */
	foreach ( $items as $item )
		$list .= "<li>{$item}</li>";

	/* Format the HTML output of the function. */
	$output = '<div class="image-info"><h3>' . __( 'Image Info', 'socially-awkward' ) . '</h3><ul>' . $list . '</ul></div>';

	/* Display the image info and allow devs to overwrite the final output. */
	echo apply_atomic( 'image_info', $output );
}

function socially_awkward_get_audio_meta( $post_id = 0 ) {

	if ( empty( $post_id ) )
		$post_id = get_the_ID();

	/* Set up some default variables and get the image metadata. */
	$meta = wp_get_attachment_metadata( $post_id );
	$items = array();

	if ( !empty( $meta['length_formatted'] ) )
		$items['length_formatted'] = array( $meta['length_formatted'], __( 'Run Time', 'socially-awkward' ) );

	if ( !empty( $meta['artist'] ) )
		$items['artist'] = array( $meta['artist'], __( 'Artist', 'socially-awkward' ) );

	if ( !empty( $meta['composer'] ) )
		$items['composer'] = array( $meta['composer'], __( 'Composer', 'socially-awkward' ) );

	if ( !empty( $meta['album'] ) )
		$items['album'] = array( $meta['album'], __( 'Album', 'socially-awkward' ) );

	if ( !empty( $meta['track_number'] ) )
		$items['track_number'] = array( $meta['track_number'], __( 'Track', 'socially-awkward' ) );

	if ( !empty( $meta['year'] ) )
		$items['year'] = array( $meta['year'], __( 'Year', 'socially-awkward' ) );

	if ( !empty( $meta['genre'] ) )
		$items['genre'] = array( $meta['genre'], __( 'Genre', 'socially-awkward' ) );

	$items['file_name'] = array( '<a href="' . esc_url( wp_get_attachment_url( $post_id ) ) . '">' . basename( get_attached_file( $post_id ) ) . '</a>', __( 'File Name', 'socially-awkward' ) );

	if ( !empty( $meta['filesize'] ) )
		$items['filesize'] = array( socially_awkward_format_file_size( $meta['filesize'] ), __( 'File Size', 'socially-awkward' ) );

	if ( preg_match( '/^.*?\.(\w+)$/', get_attached_file( $post_id ), $matches ) )
		$items['file_type'] = array( esc_html( strtoupper( $matches[1] ) ), __( 'File Type', 'socially-awkward' ) );

	if ( !empty( $meta['mime_type'] ) )
		$items['mime_type'] = array( $meta['mime_type'], __( 'Mime Type' ) );


	return apply_filters( hybrid_get_prefix() . '_audio_meta', $items );
}

function socially_awkward_list_audio_meta( $post_id = 0 ) {

	$list     = '';
	$defaults = array();
	$items    = socially_awkward_get_audio_meta( $post_id );

	if ( !is_attachment() )
		$defaults['permalink'] = array( '<a href="' . get_permalink( $post_id ) . '">' . get_the_title( $post_id ) . '</a>', __( 'Permalink', 'socially-awkward' ) );

	return socially_awkward_list_media_meta( array_merge( $defaults, $items ) );
}

function socially_awkward_list_media_meta( $items ) {
	$list     = '';

	foreach ( $items as $item )
		$list .= '<li><span class="prep">' . $item[1] . '</span> <span class="data">' . $item[0] . '</span></li>';

	return '<ul class="media-meta">' . $list . '</ul>';
}


function socially_awkward_format_file_size( $size ) {

	$units = array_values( socially_awkward_get_file_size_units() );

	$base = log( $size ) / log( 1024 );

	$file_size = round( pow( 1024, $base - floor( $base ) ), 2 );

	return sprintf( __( '%1$s %2$s (%3$s bytes)', 'socially-awkward' ), $file_size, $units[ floor( $base ) ], $size );
}

function socially_awkward_get_video_meta( $post_id = 0 ) {

	if ( empty( $post_id ) )
		$post_id = get_the_ID();

	/* Set up some default variables and get the image metadata. */
	$meta = wp_get_attachment_metadata( $post_id );
	$items = array();

	if ( !empty( $meta['length_formatted'] ) )
		$items['length_formatted'] = array( $meta['length_formatted'], __( 'Run Time', 'socially-awkward' ) );

	if ( !empty( $meta['width'] ) && !empty( $meta['height'] ) )
		$items['dimensions'] = array( sprintf( __( '%1$s &#215; %2$s pixels', 'socially-awkward' ), $meta['width'], $meta['height'] ), __( 'Dimensions', 'socially-awkward' ) );

	$items['file_name'] = array( '<a href="' . esc_url( wp_get_attachment_url( $post_id ) ) . '">' . basename( get_attached_file( $post_id ) ) . '</a>', __( 'File Name', 'socially-awkward' ) );

	if ( !empty( $meta['filesize'] ) )
		$items['filesize'] = array( socially_awkward_format_file_size( $meta['filesize'] ), __( 'File Size', 'socially-awkward' ) );

	if ( preg_match( '/^.*?\.(\w+)$/', get_attached_file( $post_id ), $matches ) )
		$items['file_type'] = array( esc_html( strtoupper( $matches[1] ) ), __( 'File Type', 'socially-awkward' ) );

	if ( !empty( $meta['mime_type'] ) )
		$items['mime_type'] = array( $meta['mime_type'], __( 'Mime Type' ) );

	return apply_filters( hybrid_get_prefix() . '_video_meta', $items );
}

function socially_awkward_list_video_meta( $post_id = 0 ) {

	$defaults = array();
	$items    = socially_awkward_get_video_meta( $post_id );

	if ( !is_attachment() )
		$defaults['permalink'] = array( '<a href="' . get_permalink( $post_id ) . '">' . get_the_title( $post_id ) . '</a>', __( 'Permalink', 'socially-awkward' ) );

	return socially_awkward_list_media_meta( array_merge( $defaults, $items ) );
}

function socially_awkward_get_file_size_units() {

	$units = array(
		'b'  => '<abbr title="' . __( 'Bytes', 'socially-awkward' ) . '">' . __( 'B', 'socially-awkward' ) . '</abbr>',
		'kb' => '<abbr title="' . __( 'Kilobytes', 'socially-awkward' ) . '">' . __( 'KB', 'socially-awkward' ) . '</abbr>',
		'mb' => '<abbr title="' . __( 'Megabytes', 'socially-awkward' ) . '">' . __( 'MB', 'socially-awkward' ) . '</abbr>',
		'gb' => '<abbr title="' . __( 'Gigabytes', 'socially-awkward' ) . '">' . __( 'GB', 'socially-awkward' ) . '</abbr>',
		'tb' => '<abbr title="' . __( 'Terabytes', 'socially-awkward' ) . '">' . __( 'TB', 'socially-awkward' ) . '</abbr>',
	);

	return apply_filters( hybrid_get_prefix() . '_file_size_units', $units );
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






?>