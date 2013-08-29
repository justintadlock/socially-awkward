<?php 
/**
 * Template Name: Formats
 */

get_header(); // Loads the header.php template. ?>

	<div id="content" class="hfeed">

		<?php if ( have_posts() ) { ?>

			<?php while ( have_posts() ) { ?>

				<?php the_post(); ?>

				<?php if ( hybrid_post_has_content() ) { ?>

					<article<?php hybrid_post_attributes(); ?>>

						<header class="entry-header">
							<h1 class="entry-title"><?php single_post_title(); ?></h1>
						</header><!-- .entry-header -->

						<div class="entry-content">
							<?php the_content(); ?>
							<?php wp_link_pages( array( 'before' => '<p class="page-links">' . '<span class="before">' . __( 'Pages:', 'socially-awkward' ) . '</span>', 'after' => '</p>' ) ); ?>
						</div><!-- .entry-content -->

						<?php get_template_part( 'menu', 'formats' ); // Loads the menu-formats.php template ?>

					</article><!-- .hentry -->

				<?php } else { ?>

					<?php get_template_part( 'menu', 'formats' ); // Loads the menu-formats.php template ?>

				<?php } // endif ?>

			<?php } // endwhile ?>

		<?php } // endif ?>

	</div><!-- #content -->

<?php get_footer(); // Loads the footer.php template. ?>