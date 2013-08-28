<?php if ( has_nav_menu( 'formats' ) ) {

	wp_nav_menu(
		array(
			'theme_location'  => 'formats',
			'container'       => 'nav',
			'container_id'    => 'menu-formats',
			'container_class' => 'menu',
			'menu_id'         => 'menu-formats-items',
			'menu_class'      => 'menu-items',
			'depth'           => 1,
			'link_before'     => '<span class="screen-reader-text">',
			'link_after'      => '</span>',
			'fallback_cb'     => '',
		)
	);

} else { ?>

	<nav id="menu-formats" class="menu">

		<ul id="menu-formats-items" class="menu-items">

			<?php if ( $url = socially_awkward_get_blog_url() ) { ?>
				<li <?php echo is_home() ? 'class="current-menu-item"' : ''; ?>>
				<a class="post-format-link post-format-link-standard" href="<?php echo esc_url( $url ); ?>" title="<?php _e( 'Articles' ); ?>">
					<span class="screen-reader-text"><?php echo get_post_format_string( 'standard' ); ?></span>
				</a>
				</li>
			<?php } ?>

			<?php $formats = array( 'aside', 'link', 'quote', 'status', 'chat', 'gallery', 'image', 'audio', 'video' );

			foreach ( $formats as $format ) { ?>
				<li <?php echo is_tax( 'post_format', "post-format-{$format}" ) ? 'class="current-menu-item"' : ''; ?>>
				<a class="post-format-link post-format-link-<?php echo $format; ?>" href="<?php echo get_post_format_link( $format ); ?>" title="<?php echo esc_attr( hybrid_get_plural_post_format_string( $format ) ); ?>">
					<span class="screen-reader-text"><?php echo get_post_format_string( $format ); ?></span>
				</a>
				</li>
			<?php } ?>

		</ul><!-- #menu-formats-items -->

	</nav><!-- #menu-formats -->

<?php } ?>