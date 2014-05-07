<div <?php hybrid_attr( 'loop-meta' ); ?>>

	<h1 <?php hybrid_attr( 'loop-title' ); ?>><?php hybrid_loop_title(); ?></h1>

	<?php if ( !is_paged() && $desc = hybrid_get_loop_description() ) : // Check if we're on page/1. ?>

		<div <?php hybrid_attr( 'loop-description' ); ?>>
			<?php echo $desc; ?>
		</div><!-- .loop-description -->

	<?php endif; // End paged check. ?>

	<?php if ( is_home() || is_tax( 'post_format' ) ) : // If viewing the blog page or post format archive. ?>

		<?php hybrid_get_menu( 'formats' ); // Loads the menu/formats.php template. ?>

	<?php endif; // End blog and post format archive check. ?>

	<?php if ( is_post_type_archive( 'portfolio_item' ) || is_tax( 'portfolio' ) ) : // If viewing the portfolio. ?>

		<?php hybrid_get_menu( 'portfolio' ); // Loads the menu/portfolio.php template. ?>

	<?php endif; // End portfolio check. ?>

</div><!-- .loop-meta -->