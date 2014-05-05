<article <?php hybrid_attr( 'post' ); ?>>

	<?php socially_awkward_get_image(); ?>

	<?php if ( is_singular( get_post_type() ) ) { ?>

		<header class="entry-header">
			<h1 class="entry-title"><?php single_post_title(); ?></h1>

			<?php echo apply_atomic_shortcode( 
				'entry_byline', 
				'<div class="entry-byline">' . 
					hybrid_entry_terms_shortcode( array( 'taxonomy' => 'portfolio', 'before' => __( 'Portfolio:', 'socially-awkward' ) . ' ' ) ) . 
				'</div>'
			); ?>
		</header><!-- .entry-header -->

		<div class="entry-content">
			<?php the_content(); ?>
			<?php wp_link_pages( array( 'before' => '<p class="page-links">' . '<span class="before">' . __( 'Pages:', 'socially-awkward' ) . '</span>', 'after' => '</p>' ) ); ?>
		</div><!-- .entry-content -->

		<footer class="entry-footer">
			<?php echo apply_atomic_shortcode( 
				'entry_meta', 
				'<div class="entry-meta">[entry-published] [entry-edit-link]' .
					socially_awkward_get_portfolio_item_link() .  
				'</div>' 
			); ?>
		</footer><!-- .entry-footer -->

	<?php } else { ?>

		<header class="entry-header">
			<?php echo apply_atomic_shortcode( 'entry_title', '[entry-title]' ); ?>
		</header><!-- .entry-header -->

		<div class="entry-summary">
			<?php if ( has_excerpt() ) {
				the_excerpt();
				wp_link_pages( array( 'before' => '<p class="page-links">' . '<span class="before">' . __( 'Pages:', 'socially-awkward' ) . '</span>', 'after' => '</p>' ) );
			} ?>
		</div><!-- .entry-summary -->

		<footer class="entry-footer">
			<?php echo apply_atomic_shortcode( 'entry_meta', '<div class="entry-meta">[entry-published] [entry-edit-link]</div>' ); ?>
		</footer><!-- .entry-footer -->

	<?php } ?>

</article><!-- .hentry -->