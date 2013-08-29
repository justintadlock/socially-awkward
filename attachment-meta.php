<?php
/* If viewing a singular page, return. */
if ( !is_attachment() && !wp_attachment_is_image() && !hybrid_attachment_is_audio() && !hybrid_attachment_is_video() )
	return;

$_post = get_queried_object();
?>

<div class="attachment-meta">

	<?php if ( wp_attachment_is_image() ) { ?>

		<div class="image-info">

			<h3><?php _e( 'Image Info', 'socially-awkward' ); ?></h3>

			<?php echo socially_awkward_list_image_meta(); ?>

		</div><!-- .audio-info -->

		<?php $gallery = gallery_shortcode( array( 'columns' => 4, 'numberposts' => 8, 'orderby' => 'rand', 'id' => $_post->post_parent, 'exclude' => get_the_ID() ) ); ?>

		<?php if ( !empty( $gallery ) ) { ?>
			<div class="image-gallery">
				<h3><?php _e( 'Gallery', 'socially-awkward' ); ?></h3>
				<?php echo $gallery; ?>
			</div>
		<?php } ?>

	<?php } elseif ( hybrid_attachment_is_audio() ) { ?>

		<div class="audio-info">

			<h3><?php _e( 'Audio Info', 'socially-awkward' ); ?></h3>

			<?php echo socially_awkward_list_audio_meta(); ?>

		</div><!-- .audio-info -->

	<?php } elseif ( hybrid_attachment_is_video() ) { ?>

		<div class="video-info">

			<h3><?php _e( 'Video Info', 'socially-awkward' ); ?></h3>

			<?php echo socially_awkward_list_video_meta(); ?>

		</div>

	<?php } // End if check ?>

</div><!-- .attachment-meta -->