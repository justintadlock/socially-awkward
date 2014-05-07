<article <?php hybrid_attr( 'post' ); ?>>

	<?php if ( is_attachment() ) : // If viewing a single attachment. ?>

		<?php if ( has_excerpt() ) : // If the image has an excerpt/caption. ?>

			<?php $src = wp_get_attachment_image_src( get_the_ID(), 'full' ); ?>

			<?php echo img_caption_shortcode( array( 'align' => 'aligncenter', 'width' => esc_attr( $src[1] ), 'caption' => get_the_excerpt() ), wp_get_attachment_image( get_the_ID(), 'full', false ) ); ?>

		<?php else : // If the image doesn't have a caption. ?>

			<?php echo wp_get_attachment_image( get_the_ID(), 'full', false, array( 'class' => 'aligncenter' ) ); ?>

		<?php endif; // End check for image caption. ?>

		<header class="entry-header">

			<h1 <?php hybrid_attr( 'entry-title' ); ?>><?php single_post_title(); ?></h1>

			<div class="entry-byline">
				<span class="image-sizes"><?php printf( __( 'Sizes: %s', 'socially-awkward' ), hybrid_get_image_size_links() ); ?></span>
			</div><!-- .entry-byline -->

		</header><!-- .entry-header -->

		<div <?php hybrid_attr( 'entry-content' ); ?>>
			<?php the_content(); ?>
			<?php wp_link_pages(); ?>
		</div><!-- .entry-content -->

		<footer class="entry-footer">
			<time <?php hybrid_attr( 'entry-published' ); ?>><?php echo get_the_date(); ?></time>
			<?php edit_post_link(); ?>
		</footer><!-- .entry-footer -->

	<?php else : // If not viewing a single post. ?>

		<header class="entry-header">
			<?php the_title( '<h2 ' . hybrid_get_attr( 'entry-title' ) . '><a href="' . get_permalink() . '" rel="bookmark" itemprop="url">', '</a></h2>' ); ?>
		</header><!-- .entry-header -->

		<div <?php hybrid_attr( 'entry-summary' ); ?>>
			<?php get_the_image(); ?>
			<?php the_excerpt(); ?>
			<?php wp_link_pages(); ?>
		</div><!-- .entry-summary -->

	<?php endif; // End single attachment check. ?>

</article><!-- .entry -->

<div class="attachment-meta">

	<div class="image-info">

		<h3><?php _e( 'Image Info', 'socially-awkward' ); ?></h3>

		<?php hybrid_media_meta(); ?>

	</div><!-- .audio-info -->

	<?php $gallery = gallery_shortcode( array( 'columns' => 4, 'numberposts' => 8, 'orderby' => 'rand', 'id' => get_queried_object()->post_parent, 'exclude' => get_the_ID() ) ); ?>

	<?php if ( !empty( $gallery ) ) : // Check if the gallery is not empty. ?>

		<div class="image-gallery">
			<h3><?php _e( 'Gallery', 'socially-awkward' ); ?></h3>
			<?php echo $gallery; ?>
		</div><!-- .image-gallery -->

	<?php endif; // End gallery check ?>

</div><!-- .attachment-meta -->