<?php if ( has_nav_menu( 'primary' ) ) : // Check if there's a menu assigned to the 'primary' location. ?>

	<?php wp_nav_menu(
		array(
			'theme_location'  => 'primary',
			'container'       => 'nav',
			'container_id'    => 'menu-primary',
			'container_class' => 'menu',
			'menu_id'         => 'menu-primary-items',
			'menu_class'      => 'menu-items',
			'fallback_cb'     => '',
			'items_wrap'      => '<h3 class="menu-toggle" title="' . esc_attr__( 'Navigation', 'socially-awkward' ) . '">' . __( 'Navigation', 'socially-awkward' ) . '</h3><div class="wrap"><ul id="%1$s" class="%2$s">%3$s</ul></div>'
		)
	); ?>

<?php endif; // End check for menu. ?>