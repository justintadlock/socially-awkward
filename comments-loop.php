<?php if ( have_comments() ) { ?>

	<h2 id="comments-number"><?php comments_number( '', __( 'One Response', 'socially-awkward' ), __( '% Responses', 'socially-awkward' ) ); ?></h2>

	<ol class="comment-list">
		<?php wp_list_comments( hybrid_list_comments_args() ); ?>
	</ol><!-- .comment-list -->

	<?php get_template_part( 'comments-loop-nav' ); // Loads the comment-loop-nav.php template. ?>

<?php } // End check for comments. ?>

<?php get_template_part( 'comments-loop-error' ); // Loads the comments-loop-error.php template. ?>