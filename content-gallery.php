<article<?php hybrid_post_attributes(); ?>>

	<?php if ( is_singular( get_post_type() ) ) { ?>

		<header class="entry-header">
			<h1 class="entry-title"><?php single_post_title(); ?></h1>
			<?php echo apply_atomic_shortcode( 'entry_byline', '<div class="entry-byline">' . __( '[post-format-link] [entry-published] [entry-comments-link] [entry-edit-link]', 'socially-awkward' ) . '</div>' ); ?>
		</header><!-- .entry-header -->

		<div class="entry-content">
			<?php the_content(); ?>
			<?php wp_link_pages( array( 'before' => '<p class="page-links">' . '<span class="before">' . __( 'Pages:', 'socially-awkward' ) . '</span>', 'after' => '</p>' ) ); ?>
		</div><!-- .entry-content -->

		<footer class="entry-footer">
			<?php echo apply_atomic_shortcode( 'entry_meta', '<div class="entry-meta">' . __( '[entry-terms taxonomy="category" before="Posted in "]<br />[entry-terms before="Tagged "]', 'socially-awkward' ) . '</div>' ); ?>
		</footer><!-- .entry-footer -->

	<?php } else { ?>

		<?php get_the_image( array( 'size' => 'socially-awkward-large', 'image_class' => 'aligncenter' ) ); ?>

		<header class="entry-header">
			<?php echo apply_atomic_shortcode( 'entry_title', '[entry-title]' ); ?>
			<?php echo apply_atomic_shortcode( 'entry_byline', '<div class="entry-byline">' . __( '[post-format-link] [entry-published] [entry-comments-link] [entry-edit-link]', 'socially-awkward' ) . '</div>' ); ?>
		</header><!-- .entry-header -->

		<div class="entry-summary">

			<?php the_excerpt(); ?>
			<?php $count = hybrid_get_gallery_item_count(); ?>
			<p class="gallery-count"><?php printf( _n( 'This gallery contains %s item.', 'This gallery contains %s items.', $count, 'socially-awkward' ), $count ); ?></p>

		</div><!-- .entry-summary -->

	<?php } ?>

</article><!-- .hentry -->