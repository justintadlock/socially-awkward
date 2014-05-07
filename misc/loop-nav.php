<?php if ( is_singular( 'post' ) ) : // If viewing a single post page. ?>

	<div class="loop-nav">
		<?php $label = get_post_format() ? get_post_format_string( get_post_format() ) : get_post_type_object( get_post_type() )->labels->singular_name; ?>

		<?php previous_post_link( '%link', '<span class="previous">' . sprintf( __( '<span class="meta-nav">&larr;</span> Previous %s', 'socially-awkward' ), $label ) . '</span>' ); ?>
		<?php next_post_link(     '%link', '<span class="next">'     . sprintf( __( 'Next %s <span class="meta-nav">&rarr;</span>',     'socially-awkward' ), $label ) . '</span>' ); ?>
	</div><!-- .loop-nav -->

<?php elseif ( is_attachment() ) : // If viewing a single attachment. ?>

	<div class="loop-nav">
		<?php previous_post_link( '%link', '<span class="previous">' . __( '<span class="meta-nav">&larr;</span> Return to entry', 'socially-awkward' ) . '</span>' ); ?>
	</div><!-- .loop-nav -->

<?php elseif ( !is_singular() && $nav = get_posts_nav_link( array( 'sep' => '', 'prelabel' => '<span class="previous">' . __( '<span class="meta-nav">&larr;</span> Newer', 'socially-awkward' ) . '</span>', 'nxtlabel' => '<span class="next">' . __( 'Older <span class="meta-nav">&rarr;</span>', 'socially-awkward' ) . '</span>' ) ) ) : ?>

	<div class="loop-nav">
		<?php echo $nav; ?>
	</div><!-- .loop-nav -->

<?php endif; // End check for type of page. ?>