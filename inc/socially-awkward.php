<?php
/**
 * Sets up custom filters and actions for the theme.  This does things like sets up sidebars, menus, scripts, 
 * and lots of other awesome stuff that WordPress themes do.
 *
 * @package    SociallyAwkward
 * @subpackage Functions
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2013 - 2014, Justin Tadlock
 * @link       http://themehybrid.com/themes/socially-awkward
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/* Register custom image sizes. */
add_action( 'init', 'socially_awkward_register_image_sizes' );

/* Register custom nav menus. */
add_action( 'init', 'socially_awkward_register_nav_menus' );

/* Add custom nav menu item classes. */
add_filter( 'nav_menu_css_class', 'socially_awkward_nav_menu_css_class', 10, 3 );

/* Register stylesheets. */
add_action( 'wp_enqueue_scripts', 'stargazer_register_styles', 0 );

/* Load custom scripts. */
add_action( 'wp_enqueue_scripts', 'socially_awkward_enqueue_scripts' );

/* Use post formats to decide prev/next post. */
add_filter( 'get_previous_post_join', 'socially_awkward_adjacent_post_join' );
add_filter( 'get_next_post_join',     'socially_awkward_adjacent_post_join' );

/* Filters the [audio] shortcode. */
add_filter( 'wp_audio_shortcode', 'socially_awkward_audio_shortcode', 10, 4 );

/* Filters the [video] shortcode. */
add_filter( 'wp_video_shortcode', 'socially_awkward_video_shortcode', 10, 3 );

/* Filter the [video] shortcode attributes. */
add_filter( 'shortcode_atts_video', 'socially_awkward_video_atts' );


/**
 * Registers custom stylesheets for the front end.
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
function stargazer_register_styles() {
	wp_deregister_style( 'mediaelement'    );
	wp_deregister_style( 'wp-mediaelement' );

	wp_register_style( 'theme-mediaelement',     trailingslashit( get_template_directory_uri() ) . 'css/mediaelement/mediaelement.min.css'                  );
	wp_register_style( 'socially-awkward-fonts', '//fonts.googleapis.com/css?family=Lora:400,700,400italic,700italic|Open+Sans:400|Open+Sans+Condensed:700' );
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

	register_nav_menu( 'primary', esc_html__( 'Primary', 'socially-awkward' ) );

	register_nav_menu( 'social', esc_html__( 'Social', 'socially-awkward' ) );

	register_nav_menu( 'formats', esc_html__( 'Formats', 'socially-awkward' ) );

	if ( post_type_exists( 'portfolio_item' ) )
		register_nav_menu( 'portfolio', esc_html__( 'Portfolio', 'socially-awkward' ) );
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
 * Adds a featured image (if one exists) next to the audio player.  Also adds a section below the player to 
 * display the audio file information (toggled by custom JS).
 *
 * @since  0.1.0
 * @access public
 * @param  string  $html
 * @param  array   $atts
 * @param  object  $audio
 * @param  object  $post_id
 * @return string
 */
function socially_awkward_audio_shortcode( $html, $atts, $audio, $post_id ) {

	/* Don't show in the admin. */
	if ( is_admin() )
		return $html;

	/* If we have an actual attachment to work with, use the ID. */
	if ( is_object( $audio ) ) {
		$attachment_id = $audio->ID;
	}

	/* Else, get the ID via the file URL. */
	else {
		$extensions = join( '|', wp_get_audio_extensions() );

		preg_match(
			'/(src|' . $extensions . ')=[\'"](.+?)[\'"]/i', 
			preg_replace( '/(\?_=[0-9])/i', '', $html ),
			$matches
		);

		if ( !empty( $matches ) )
			$attachment_id = hybrid_get_attachment_id_from_url( $matches[2] );
	}

	/* If an attachment ID was found. */
	if ( !empty( $attachment_id ) ) {

		/* Get the attachment's featured image. */
		$image = get_the_image( 
			array( 
				'post_id'      => $attachment_id,  
				'image_class'  => 'audio-image',
				'link_to_post' => is_attachment() ? false : true, 
				'echo'         => false 
			) 
		);

		/* If there's no attachment featured image, see if there's one for the post. */
		if ( empty( $image ) && !empty( $post_id ) )
			$image = get_the_image( array( 'image_class' => 'audio-image', 'link_to_post' => false, 'echo' => false ) );

		/* Add a wrapper for the audio element and image. */
		if ( !empty( $image ) ) {
			$image = preg_replace( array( '/width=[\'"].+?[\'"]/i', '/height=[\'"].+?[\'"]/i' ), '', $image );
			$html = '<div class="audio-shortcode-wrap">' . $image . $html . '</div>';
		}

		/* If not viewing an attachment page, add the media info section. */
		if ( !is_attachment() ) {
			$html .= '<div class="media-shortcode-extend">';
			$html .= '<div class="media-info audio-info">';
			$html .= hybrid_media_meta( array( 'post_id' => $attachment_id, 'echo' => false ) );
			$html .= '</div>';
			$html .= '<a class="media-info-toggle">' . __( 'Audio Info', 'stargazer' ) . '</a>';
			$html .= '</div>';
		}
	}

	return $html;
}

/**
 * Adds a section below the player to  display the video file information (toggled by custom JS).
 *
 * @since  0.1.0
 * @access public
 * @param  string  $html
 * @param  array   $atts
 * @param  object  $audio
 * @return string
 */
function socially_awkward_video_shortcode( $html, $atts, $video ) {

	/* Don't show on single attachment pages or in the admin. */
	if ( is_attachment() || is_admin() )
		return $html;

	/* If we have an actual attachment to work with, use the ID. */
	if ( is_object( $video ) ) {
		$attachment_id = $video->ID;
	}

	/* Else, get the ID via the file URL. */
	else {
		$extensions = join( '|', wp_get_video_extensions() );

		preg_match(
			'/(src|' . $extensions . ')=[\'"](.+?)[\'"]/i', 
			preg_replace( '/(\?_=[0-9])/i', '', $html ),
			$matches
		);

		if ( !empty( $matches ) )
			$attachment_id = hybrid_get_attachment_id_from_url( $matches[2] );
	}

	/* If an attachment ID was found, add the media info section. */
	if ( !empty( $attachment_id ) ) {

		$html .= '<div class="media-shortcode-extend">';
		$html .= '<div class="media-info video-info">';
		$html .= hybrid_media_meta( array( 'post_id' => $attachment_id, 'echo' => false ) );
		$html .= '</div>';
		$html .= '<a class="media-info-toggle">' . __( 'Video Info', 'stargazer' ) . '</a>';
		$html .= '</div>';
	}

	return $html;
}

/**
 * Featured image for self-hosted videos.  Checks the vidoe attachment for sub-attachment images.  If 
 * none exist, checks the current post (if in The Loop) for its featured image.  If an image is found, 
 * it's used as the "poster" attribute in the [video] shortcode.
 *
 * @since  0.1.0
 * @access public
 * @param  array  $out
 * @return array
 */
function socially_awkward_video_atts( $out ) {

	/* Don't show in the admin. */
	if ( is_admin() )
		return $out;

	/* Only run if the user didn't set a 'poster' image. */
	if ( empty( $out['poster'] ) ) {

		/* Check the 'src' attribute for an attachment file. */
		if ( !empty( $out['src'] ) )
			$attachment_id = hybrid_get_attachment_id_from_url( $out['src'] );

		/* If we couldn't get an attachment from the 'src' attribute, check other supported file extensions. */
		if ( empty( $attachment_id ) ) {

			$default_types = wp_get_video_extensions();

			foreach ( $default_types as $type ) {

				if ( !empty( $out[ $type ] ) ) {
					$attachment_id = hybrid_get_attachment_id_from_url( $out[ $type ] );

					if ( !empty( $attachment_id ) )
						break;
				}
			}
		}

		/* If there's an attachment ID at this point. */
		if ( !empty( $attachment_id ) ) {

			/* Get the attachment's featured image. */
			$image = get_the_image( 
				array( 
					'post_id'      => $attachment_id, 
					'size'         => 'full',
					'format'       => 'array',
					'echo'         => false
				) 
			);
		}

		/* If no image has been found and we're in the post loop, see if the current post has a featured image. */
		if ( empty( $image ) && get_post() )
			$image = get_the_image( array( 'size' => 'full', 'format' => 'array', 'echo' => false ) );

		/* Set the 'poster' attribute if we have an image at this point. */
		if ( !empty( $image ) )
			$out['poster'] = $image['src'];
	}

	return $out;
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

/**
 * @since      0.1.0
 * @deprecated 1.0.0
 */
function socially_awkward_deregister_styles() {
	_deprecated_function( __FUNCTION__, '1.0.0', '' );
}

/**
 * @since      0.1.0
 * @deprecated 1.0.0
 */
function socially_awkward_styles() {
	_deprecated_function( __FUNCTION__, '1.0.0', '' );
}

/**
 * @since      0.1.0
 * @deprecated 1.0.0
 */
function socially_awkward_entry_comments_link_atts( $out ) {
	_deprecated_function( __FUNCTION__, '1.0.0', '' );
	return $out;
}

/**
 * @since      0.1.0
 * @deprecated 1.0.0
 */
function socially_awkward_get_blog_url() {
	_deprecated_function( __FUNCTION__, '1.0.0', 'hybrid_get_blog_url()' );
	return hybrid_get_blog_url();
}

/**
 * @since      0.1.0
 * @deprecated 1.0.0
 */
function socially_awkward_get_attachment_id_from_url( $url ) {
	_deprecated_function( __FUNCTION__, '1.0.0', 'hybrid_get_attachment_id_from_url()' );
	return hybrid_get_attachment_id_from_url( $url );
}

/**
 * @since      0.1.0
 * @deprecated 1.0.0
 */
function socially_awkward_get_file_size_units() {
	_deprecated_function( __FUNCTION__, '1.0.0', '' );
}

/**
 * @since      0.1.0
 * @deprecated 1.0.0
 */
function socially_awkward_format_file_size() {
	_deprecated_function( __FUNCTION__, '1.0.0', '' );
}

/**
 * @since      0.1.0
 * @deprecated 1.0.0
 */
function socially_awkward_get_image_size_links() {
	_deprecated_function( __FUNCTION__, '1.0.0', 'hybrid_get_image_size_links()' );
	return hybrid_get_image_size_links();
}

/**
 * @since      0.1.0
 * @deprecated 1.0.0
 */
function socially_awkward_get_image_meta() {
	_deprecated_function( __FUNCTION__, '1.0.0', 'hybrid_media_meta()' );
	return hybrid_media_meta( array( 'echo' => false ) );
}

/**
 * @since      0.1.0
 * @deprecated 1.0.0
 */
function socially_awkward_get_audio_meta() {
	_deprecated_function( __FUNCTION__, '1.0.0', 'hybrid_media_meta()' );
	return hybrid_media_meta( array( 'echo' => false ) );
}

/**
 * @since      0.1.0
 * @deprecated 1.0.0
 */
function socially_awkward_get_video_meta() {
	_deprecated_function( __FUNCTION__, '1.0.0', 'hybrid_media_meta()' );
	return hybrid_media_meta( array( 'echo' => false ) );
}

/**
 * @since      0.1.0
 * @deprecated 1.0.0
 */
function socially_awkward_list_media_meta() {
	_deprecated_function( __FUNCTION__, '1.0.0', '' );
}

/**
 * @since      0.1.0
 * @deprecated 1.0.0
 */
function socially_awkward_list_image_meta() {
	_deprecated_function( __FUNCTION__, '1.0.0', '' );
}

/**
 * @since      0.1.0
 * @deprecated 1.0.0
 */
function socially_awkward_list_audio_meta() {
	_deprecated_function( __FUNCTION__, '1.0.0', '' );
}

/**
 * @since      0.1.0
 * @deprecated 1.0.0
 */
function socially_awkward_list_video_meta() {
	_deprecated_function( __FUNCTION__, '1.0.0', '' );
}

/**
 * @since      0.1.0
 * @deprecated 1.0.0
 */
function socially_awkward_get_audio_transcript() {
	_deprecated_function( __FUNCTION__, '1.0.0', 'hybrid_get_audio_transcript()' );
	return hybrid_get_audio_transcript();
}

/**
 * @since      0.1.0
 * @deprecated 1.0.0
 */
function socially_awkward_sub_attachment_image() {
	_deprecated_function( __FUNCTION__, '1.0.0', '' );
}

/**
 * @since      0.1.0
 * @deprecated 1.0.0
 */
function socially_awkward_get_image() {
	_deprecated_function( __FUNCTION__, '1.0.0', 'get_the_image()' );
	get_the_image();
}

/**
 * @since      0.1.0
 * @deprecated 1.0.0
 */
function socially_awkward_register_fonts( $fonts ) {
	_deprecated_function( __FUNCTION__, '1.0.0', '' );
}
