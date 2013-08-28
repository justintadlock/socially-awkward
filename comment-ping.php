<li <?php hybrid_comment_attributes(); ?>>

	<?php echo hybrid_avatar(); ?>

	<?php echo apply_atomic_shortcode( 'comment_meta', '<div class="comment-meta">[comment-author] [comment-published human_time="' . __( '%s ago', 'socially-awkward' ) . '"] [comment-permalink] [comment-edit-link]</div>' ); ?>

<?php /* No closing </li> is needed.  WordPress will know where to add it. */ ?>