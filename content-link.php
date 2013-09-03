<article<?php hybrid_post_attributes(); ?>>

	<?php if ( is_singular( get_post_type() ) ) { ?>

		<header class="entry-header">
			<h1 class="entry-title"><?php single_post_title(); ?></h1>
			<?php echo apply_atomic_shortcode( 'entry_byline', '<div class="entry-byline">[post-format-link] [entry-published] [entry-comments-link] [entry-edit-link]</div>' ); ?>
		</header><!-- .entry-header -->

		<div class="entry-content">
			<?php the_content(); ?>
			<?php wp_link_pages( array( 'before' => '<p class="page-links">' . '<span class="before">' . __( 'Pages:', 'socially-awkward' ) . '</span>', 'after' => '</p>' ) ); ?>
		</div><!-- .entry-content -->

		<footer class="entry-footer">
			<?php echo apply_atomic_shortcode( 'entry_meta', '<div class="entry-meta">[entry-terms taxonomy="category" before="' . __( 'Posted in', 'socially-awkward' ) . ' "]<br />[entry-terms before="' . __( 'Tagged', 'socially-awkward' ) .' "]</div>' ); ?>
		</footer><!-- .entry-footer -->

	<?php } else { ?>

		<?php if ( get_the_title() ) { ?>

			<header class="entry-header">
				<?php the_title( '<h2 class="entry-title"><a href="' . hybrid_get_the_post_format_url() . '" title="' . the_title_attribute( array( 'echo' => false ) ) . '">', ' <span class="meta-nav">&rarr;</span></a></h2>' ); ?>
			</header><!-- .entry-header -->

		<?php } else { ?>

			<div class="entry-content">
				<?php the_content( __( 'Read more &rarr;', 'socially-awkward' ) ); ?>
				<?php wp_link_pages( array( 'before' => '<p class="page-links">' . '<span class="before">' . __( 'Pages:', 'socially-awkward' ) . '</span>', 'after' => '</p>' ) ); ?>
			</div><!-- .entry-content -->

		<?php } ?>

		<footer class="entry-footer">
			<?php echo apply_atomic_shortcode( 'entry_meta', '<div class="entry-meta">[post-format-link] [entry-published] [entry-permalink ] [entry-comments-link] [entry-edit-link]</div>' ); ?>
		</footer><!-- .entry-footer -->

	<?php } ?>

</article><!-- .hentry -->