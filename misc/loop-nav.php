	<?php if ( is_attachment() ) : ?>

		<div class="loop-nav">
			<?php previous_post_link( '%link', '<span class="previous">' . __( '<span class="meta-nav">&larr;</span> Return to entry', 'socially-awkward' ) . '</span>' ); ?>
		</div><!-- .loop-nav -->

	<?php elseif ( is_singular( 'post' ) ) : // @todo check for non-hierarchical posts, maybe? ?>

		<div class="loop-nav">
			<?php 
				$label = get_post_format() ? get_post_format_string( get_post_format() ) : '';
				if ( empty( $label ) ) {
					$post_type = get_post_type_object( get_post_type() );
					$label = $post_type->labels->singular_name;
				}
			?>
			<?php previous_post_link( '%link', '<span class="previous">' . sprintf( __( '<span class="meta-nav">&larr;</span> Previous %s', 'socially-awkward' ), $label ). '</span>' ); ?>
			<?php next_post_link( '%link', '<span class="next">' . sprintf( __( 'Next %s <span class="meta-nav">&rarr;</span>', 'socially-awkward' ), $label ) . '</span>' ); ?>
		</div><!-- .loop-nav -->

	<?php elseif ( !is_singular() && current_theme_supports( 'loop-pagination' ) ) : loop_pagination( array( 'prev_text' => __( '<span class="meta-nav">&larr;</span> Previous', 'socially-awkward' ), 'next_text' => __( 'Next <span class="meta-nav">&rarr;</span>', 'socially-awkward' ) ) ); ?>

	<?php elseif ( !is_singular() && $nav = get_posts_nav_link( array( 'sep' => '', 'prelabel' => '<span class="previous">' . __( '<span class="meta-nav">&larr;</span> Newer', 'socially-awkward' ) . '</span>', 'nxtlabel' => '<span class="next">' . __( 'Older <span class="meta-nav">&rarr;</span>', 'socially-awkward' ) . '</span>' ) ) ) : ?>

		<div class="loop-nav">
			<?php echo $nav; ?>
		</div><!-- .loop-nav -->

	<?php endif; ?>