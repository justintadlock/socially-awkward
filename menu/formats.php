<?php if ( has_nav_menu( 'formats' ) ) : // Check if there's a menu assigned to the 'formats' location. ?>

	<?php wp_nav_menu(
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
	); ?>

<?php else : // If there's not a 'formats' menu. ?>

	<nav id="menu-formats" class="menu">

		<ul id="menu-formats-items" class="menu-items">

			<?php if ( $url = hybrid_get_blog_url() ) : // Check for blog URL. ?>

				<li class="menu-item-blog <?php echo is_home() ? 'current-menu-item' : ''; ?>">
					<a href="<?php echo esc_url( $url ); ?>" title="<?php _e( 'Articles', 'socially-awkward' ); ?>">
						<span class="screen-reader-text"><?php echo get_post_format_string( 'standard' ); ?></span>
					</a>
				</li>

			<?php endif; // End blog URL check. ?>

			<?php foreach ( array( 'aside', 'link', 'quote', 'status', 'chat', 'gallery', 'image', 'audio', 'video' ) as $format ) : ?>

				<li class="menu-item-<?php echo esc_attr( $format ); ?> <?php echo is_tax( 'post_format', "post-format-{$format}" ) ? 'current-menu-item' : ''; ?>">
					<a href="<?php echo get_post_format_link( $format ); ?>" title="<?php echo esc_attr( get_post_format_string( $format ) ); ?>">
						<span class="screen-reader-text"><?php echo get_post_format_string( $format ); ?></span>
					</a>
				</li>

			<?php endforeach; // End loop through post formats. ?>

		</ul><!-- #menu-formats-items -->

	</nav><!-- #menu-formats -->

<?php endif; // End menu check. ?>