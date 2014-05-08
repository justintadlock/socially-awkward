<?php if ( get_option( 'page_comments' ) && 1 < get_comment_pages_count() ) : // Check for paged comments. ?>

	<div class="comments-nav">
		<?php previous_comments_link( __( '<span class="meta-nav">&larr;</span> Previous', 'socially-awkward' ) ); ?>

		<span class="page-numbers"><?php 
			/* Translators: Comments page numbers. 1 is current page and 2 is total pages. */
			printf( __( 'Page %1$s of %2$s', 'stargazer' ), get_query_var( 'cpage' ) ? absint( get_query_var( 'cpage' ) ) : 1, get_comment_pages_count() ); 
		?></span>

		<?php next_comments_link( __( 'Next <span class="meta-nav">&rarr;</span>', 'socially-awkward' ) ); ?>

	</div><!-- .comments-nav -->

<?php endif; // End check for paged comments. ?>