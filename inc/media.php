<?php


add_filter( 'wp_audio_shortcode', 'socially_awkward_audio_shortcode', 10, 4 );
add_filter( 'wp_video_shortcode', 'socially_awkward_video_shortcode', 10, 4 );



/**
 * Retrieves an attachment ID based on a URL.
 *
 * @copyright Pippin Williamson
 * @license   http://www.gnu.org/licenses/gpl-2.0.html
 * @link      http://pippinsplugins.com/retrieve-attachment-id-from-image-url/
 *
 * @since  0.1.0
 * @access public
 * @param  string  $url
 * @return int
 */
function socially_awkward_get_attachment_id_from_url( $url ) {
	global $wpdb;

	$prefix = $wpdb->prefix;

	$posts = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM " . $prefix . "posts" . " WHERE guid='%s';", $url ) ); 
 
       return array_shift( $posts );
}

/**
 * Returns an array of key/value pairs representing file size units.  The key is the unit and the value is 
 * an internationalized text string representing the unit.
 *
 * @since  0.1.0
 * @access public
 * @return array
 */
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

/**
 * Formats a file size based off of what is assumed given in bytes by changing it to the most-readable file 
 * size unit.  For example, passing in '5920497' bytes will result in '5.65 MB' on output.
 *
 * @since  0.1.0
 * @access public
 * @param  int     $size
 * @return string
 */
function socially_awkward_format_file_size( $size ) {

	$size = absint( $size );

	$units = array_values( socially_awkward_get_file_size_units() );

	$base = log( $size ) / log( 1024 );

	$file_size = round( pow( 1024, $base - floor( $base ) ), 2 );

	return sprintf( __( '%1$s %2$s (%3$s bytes)', 'socially-awkward' ), $file_size, $units[ floor( $base ) ], $size );
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

function socially_awkward_get_image_meta() {

	if ( empty( $post_id ) )
		$post_id = get_the_ID();

	/* Set up some default variables and get the image metadata. */
	$meta = wp_get_attachment_metadata( $post_id );
	$items = array();

	/* Add the width/height to the $items array. */
	$items['dimensions'] = array(
		'<a href="' . esc_url( wp_get_attachment_url() ) . '">' . sprintf( __( '%1$s &#215; %2$s pixels', 'socially-awkward' ), $meta['width'], $meta['height'] ) . '</a>',
		__( 'Dimensions', 'socially-awkward' )
	);

	/* If a timestamp exists, add it to the $items array. */
	if ( !empty( $meta['image_meta']['created_timestamp'] ) )
		$items['created_timestamp'] = array( date( get_option( 'date_format' ), $meta['image_meta']['created_timestamp'] ), __( 'Date', 'socially-awkward' ) );

	/* If a camera exists, add it to the $items array. */
	if ( !empty( $meta['image_meta']['camera'] ) )
		$items['camera'] = array( $meta['image_meta']['camera'], __( 'Camera', 'socially-awkward' ) );

	/* If an aperture exists, add it to the $items array. */
	if ( !empty( $meta['image_meta']['aperture'] ) )
		$items['aperture'] = array( sprintf( __( 'f/%s', 'socially-awkward' ), $meta['image_meta']['aperture'] ), __( 'Aperture', 'socially-awkward' ) );

	/* If a focal length is set, add it to the $items array. */
	if ( !empty( $meta['image_meta']['focal_length'] ) )
		$items['focal_length'] = array( sprintf( __( '%s mm', 'socially-awkward' ), $meta['image_meta']['focal_length'] ), __( 'Focal Length', 'socially-awkward' ) );

	/* If an ISO is set, add it to the $items array. */
	if ( !empty( $meta['image_meta']['iso'] ) ) {
		$items['iso'] = array(
			$meta['image_meta']['iso'], 
			'<abbr title="' . __( 'International Organization for Standardization', 'socially-awkward' ) . '">' . __( 'ISO', 'socially-awkward' ) . '</abbr>'
		);
	}

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

		$items['shutter_speed'] = array( sprintf( __( '%s sec', 'socially-awkward' ), $shutter_speed ), __( 'Shutter Speed', 'socially-awkward' ) );
	}

	return apply_filters( hybrid_get_prefix() . '_attachment_image_meta', $items );
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
		$items['mime_type'] = array( $meta['mime_type'], __( 'Mime Type', 'socially-awkward' ) );

	return apply_filters( hybrid_get_prefix() . '_attachment_audio_meta', $items );
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
		$items['mime_type'] = array( $meta['mime_type'], __( 'Mime Type', 'socially-awkward' ) );

	return apply_filters( hybrid_get_prefix() . '_attachment_video_meta', $items );
}





/**
 * Formats media meta items into an unordered list.
 *
 * @since  0.1.0
 * @access public
 * @param  array   $items
 * @return string
 */
function socially_awkward_list_media_meta( $items ) {
	$list     = '';

	foreach ( $items as $item )
		$list .= '<li><span class="prep">' . $item[1] . '</span> <span class="data">' . $item[0] . '</span></li>';

	return '<ul class="media-meta">' . $list . '</ul>';
}

function socially_awkward_list_image_meta( $post_id = 0 ) {

	$list     = '';
	$defaults = array();
	$items    = socially_awkward_get_image_meta( $post_id );

	if ( !is_attachment() )
		$defaults['permalink'] = array( '<a href="' . get_permalink( $post_id ) . '">' . get_the_title( $post_id ) . '</a>', __( 'Permalink', 'socially-awkward' ) );

	return socially_awkward_list_media_meta( array_merge( $defaults, $items ) );
}

function socially_awkward_list_audio_meta( $post_id = 0 ) {

	$list     = '';
	$defaults = array();
	$items    = socially_awkward_get_audio_meta( $post_id );

	if ( !is_attachment() )
		$defaults['permalink'] = array( '<a href="' . get_permalink( $post_id ) . '">' . get_the_title( $post_id ) . '</a>', __( 'Permalink', 'socially-awkward' ) );

	return socially_awkward_list_media_meta( array_merge( $defaults, $items ) );
}

function socially_awkward_list_video_meta( $post_id = 0 ) {

	$defaults = array();
	$items    = socially_awkward_get_video_meta( $post_id );

	if ( !is_attachment() )
		$defaults['permalink'] = array( '<a href="' . get_permalink( $post_id ) . '">' . get_the_title( $post_id ) . '</a>', __( 'Permalink', 'socially-awkward' ) );

	return socially_awkward_list_media_meta( array_merge( $defaults, $items ) );
}


/**************/

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

?>