<?php if ( has_nav_menu( 'portfolio' ) ) { ?>

	<?php wp_nav_menu(
		array(
			'theme_location'  => 'portfolio',
			'container'       => 'nav',
			'container_id'    => 'menu-portfolio',
			'container_class' => '',
			'menu_id'         => 'menu-portfolio-items',
			'menu_class'      => 'menu-items',
			'depth'           => 1,
			'fallback_cb'     => ''
		)
	); ?>

<?php } else { ?>

	<div id="menu-portfolio">
		<ul id="menu-portfolio-items" class="menu-items">
			<?php $type = get_post_type_object( 'portfolio_item' ); ?>

			<li <?php echo is_post_type_archive( 'portfolio_item' ) ? 'class="current-cat"' : ''; ?>>
				<a href="<?php echo get_post_type_archive_link( 'portfolio_item' ); ?>">
					<?php echo ( isset( $type->labels->archive_title ) ? $type->labels->archive_title : $type->labels->name ); ?>
				</a>
			</li>

			<?php wp_list_categories( 
				array( 
					'taxonomy'         => 'portfolio', 
					'depth'            => 1, 
					'hierarchical'     => false,
					'show_option_none' => false,
					'title_li'         => false 
				) 
			); ?>
		</ul>
	</div>

<?php } ?>