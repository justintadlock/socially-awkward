<?php

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








?>