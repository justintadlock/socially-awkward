<article <?php hybrid_attr( 'post' ); ?>>

	<?php if ( is_singular( get_post_type() ) ) : // If viewing a single post. ?>

		<?php if ( get_option( 'show_avatars' ) ) : // If avatars are enabled. ?>

			<header class="entry-header">
				<?php echo get_avatar( get_the_author_meta( 'email' ) ); ?>
			</header><!-- .entry-header -->

		<?php endif; // End avatars check. ?>

		<div <?php hybrid_attr( 'entry-content' ); ?>>
			<?php the_content(); ?>
			<?php wp_link_pages(); ?>
		</div><!-- .entry-content -->

		<footer class="entry-footer">
			<?php hybrid_post_format_link(); ?>
			<time <?php hybrid_attr( 'entry-published' ); ?>><?php echo get_the_date(); ?></time>
			<?php edit_post_link(); ?>
			<?php hybrid_post_terms( array( 'taxonomy' => 'category', 'text' => __( 'Posted in %s', 'socially-awkward' ) ) ); ?>
			<?php hybrid_post_terms( array( 'taxonomy' => 'post_tag', 'text' => __( 'Tagged %s', 'socially-awkward' ), 'before' => '<br />' ) ); ?>
		</footer><!-- .entry-footer -->

	<?php else : // If not viewing a single post. ?>

		<?php if ( get_option( 'show_avatars' ) ) : // If avatars are enabled. ?>

			<header class="entry-header">
				<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php echo get_avatar( get_the_author_meta( 'email' ) ); ?></a>
			</header><!-- .entry-header -->

		<?php endif; // End avatars check. ?>

		<div <?php hybrid_attr( 'entry-content' ); ?>>
			<?php the_content(); ?>
		</div><!-- .entry-content -->

		<?php if ( !get_option( 'show_avatars' ) ) : // If avatars are not enabled. ?>

			<footer class="entry-footer">
				<?php hybrid_post_format_link(); ?>
				<time <?php hybrid_attr( 'entry-published' ); ?>><?php echo get_the_date(); ?></time>
				<a class="entry-permalink" href="<?php the_permalink(); ?>" rel="bookmark" itemprop="url"><?php _e( 'Permalink', 'socially-awkward' ); ?></a>
				<?php edit_post_link(); ?>
			</footer><!-- .entry-footer -->

		<?php endif; // End avatars check. ?>

	<?php endif; // End single post check. ?>

</article><!-- .entry -->