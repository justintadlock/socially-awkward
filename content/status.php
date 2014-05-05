<article <?php hybrid_attr( 'post' ); ?>>

	<?php if ( is_singular( get_post_type() ) ) { ?>

		<?php if ( get_option( 'show_avatars' ) ) { ?>
			<header class="entry-header">
				<?php echo get_avatar( get_the_author_meta( 'email' ) ); ?>
			</header><!-- .entry-header -->
		<?php } ?>

		<div class="entry-content">
			<?php the_content(); ?>
			<?php wp_link_pages( array( 'before' => '<p class="page-links">' . '<span class="before">' . __( 'Pages:', 'socially-awkward' ) . '</span>', 'after' => '</p>' ) ); ?>
		</div><!-- .entry-content -->

		<footer class="entry-footer">
			<?php echo apply_atomic_shortcode( 'entry_meta', '<div class="entry-meta">[post-format-link] [entry-published] [entry-edit-link]<br />[entry-terms taxonomy="category" before="' . __( 'Posted in', 'socially-awkward' ) . ' "]<br />[entry-terms before="' . __( 'Tagged', 'socially-awkward' ) . ' "]</div>' ); ?>
		</footer><!-- .entry-footer -->

	<?php } else { ?>

		<?php if ( get_option( 'show_avatars' ) ) { ?>
			<header class="entry-header">
				<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php echo get_avatar( get_the_author_meta( 'email' ) ); ?></a>
			</header><!-- .entry-header -->
		<?php } ?>

		<div class="entry-content">
			<?php the_content( __( 'Read more <span class="meta-nav">&rarr;</span>', 'socially-awkward' ) ); ?>
			<?php wp_link_pages( array( 'before' => '<p class="page-links">' . '<span class="before">' . __( 'Pages:', 'socially-awkward' ) . '</span>', 'after' => '</p>' ) ); ?>
		</div><!-- .entry-content -->

		<?php if ( !get_option( 'show_avatars' ) ) { ?>

			<footer class="entry-footer">
				<?php echo apply_atomic_shortcode( 'entry_meta', '<div class="entry-meta">[post-format-link] [entry-published] [entry-permalink] [entry-edit-link]</div>' ); ?>
			</footer><!-- .entry-footer -->

		<?php } ?>

	<?php } ?>

</article><!-- .hentry -->