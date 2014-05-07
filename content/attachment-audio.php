<article <?php hybrid_attr( 'post' ); ?>>

	<?php if ( is_attachment() ) : // If viewing a single attachment. ?>

		<?php hybrid_attachment(); // Function for handling non-image attachments. ?>

		<header class="entry-header">
			<h1 <?php hybrid_attr( 'entry-title' ); ?>><?php single_post_title(); ?></h1>
		</header><!-- .entry-header -->

		<div class="entry-content">
			<?php the_content(); ?>
			<?php wp_link_pages(); ?>
		</div><!-- .entry-content -->

		<footer class="entry-footer">
			<time <?php hybrid_attr( 'entry-published' ); ?>><?php echo get_the_date(); ?></time>
			<?php edit_post_link(); ?>
		</footer><!-- .entry-footer -->

	<?php else : // If not viewing a single attachment. ?>

		<header class="entry-header">
			<?php the_title( '<h2 ' . hybrid_get_attr( 'entry-title' ) . '><a href="' . get_permalink() . '" rel="bookmark" itemprop="url">', '</a></h2>' ); ?>
		</header><!-- .entry-header -->

		<div <?php hybrid_attr( 'entry-summary' ); ?>>
			<?php the_excerpt(); ?>
		</div><!-- .entry-summary -->

	<?php endif; // End single attachment check. ?>

</article><!-- .entry -->

<?php if ( is_attachment() ) : // If viewing a single attachment ?>

	<div class="attachment-meta">

		<div class="video-info">

			<h3><?php _e( 'Audio Info', 'socially-awkward' ); ?></h3>

			<?php hybrid_media_meta(); ?>

		</div><!-- .video-info -->

	</div><!-- .attachment-meta -->

	<?php if ( $lyrics = hybrid_get_audio_transcript() ) : ?>

		<div class="attachment-meta">

			<div class="attachment-transcript">
				<h3><?php _e( 'Lyrics', 'socially-awkward' ); ?></h3>
				<?php echo $lyrics; ?>
			</div><!-- .attachment-transcript -->

		</div><!-- .attachment-meta -->

	<?php endif; // End check for audio transcript. ?>

<?php endif; // End single attachment check. ?>