<?php if ( have_posts() ) { ?>

	<?php while ( have_posts() ) { ?>

		<?php the_post(); // Loads the post data. ?>

		<?php hybrid_get_content_template(); // Loads the content template. ?>

		<?php if ( is_singular() ) { ?>

			<?php if ( is_attachment() ) get_template_part( 'attachment-meta' ); // Loads the attachment-meta.php template. ?>

			<?php comments_template(); // Loads the comments.php template. ?>

		<?php } // End if check. ?>

	<?php } // End while loop. ?>

<?php } else { ?>

	<?php get_template_part( 'loop-error' ); // Loads the loop-error.php template. ?>

<?php } // End if check. ?>