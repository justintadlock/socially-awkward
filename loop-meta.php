<?php
/* If viewing a singular page, return. */
if ( is_singular() || is_front_page() || is_404() )
	return;
?>

<div class="loop-meta">

	<?php if ( is_home() ) { ?>

		<h1 class="loop-title"><?php echo get_post_field( 'post_title', get_queried_object_id() ); ?></h1>

		<?php get_template_part( 'menu', 'formats' ); ?>

	<?php } elseif ( is_category() ) { ?>

		<h1 class="loop-title"><?php single_cat_title(); ?></h1>

		<div class="loop-description">
			<?php echo category_description(); ?>
		</div><!-- .loop-description -->

	<?php } elseif ( is_tag() ) { ?>

		<h1 class="loop-title"><?php single_tag_title(); ?></h1>

		<div class="loop-description">
			<?php echo tag_description(); ?>
		</div><!-- .loop-description -->

	<?php } elseif ( is_tax() ) { ?>

		<h1 class="loop-title"><?php single_term_title(); ?></h1>

		<div class="loop-description">
			<?php echo term_description( '', get_query_var( 'taxonomy' ) ); ?>

			<?php if ( is_tax( 'portfolio' ) ) get_template_part( 'menu', 'portfolio' ); ?>

			<?php if ( is_tax( 'post_format' ) ) get_template_part( 'menu', 'formats' ); ?>
		</div><!-- .loop-description -->

	<?php } elseif ( is_author() ) { ?>

		<h1 class="loop-title fn n"><?php the_author_meta( 'display_name', get_query_var( 'author' ) ); ?></h1>

		<div class="loop-description">
			<?php echo wpautop( get_the_author_meta( 'description', get_query_var( 'author' ) ) ); ?>
		</div><!-- .loop-description -->

	<?php } elseif ( is_search() ) { ?>

		<h1 class="loop-title"><?php echo esc_attr( get_search_query() ); ?></h1>

		<div class="loop-description">
			<?php echo wpautop( sprintf( __( 'You are browsing the search results for "%s"', 'socially-awkward' ), esc_attr( get_search_query() ) ) ); ?>
		</div><!-- .loop-description -->

	<?php } elseif ( is_post_type_archive() ) { ?>

		<?php $post_type = get_post_type_object( get_query_var( 'post_type' ) ); ?>

		<h1 class="loop-title"><?php post_type_archive_title(); ?></h1>

		<div class="loop-description">
			<?php if ( !empty( $post_type->description ) ) echo wpautop( $post_type->description ); ?>

			<?php if ( is_post_type_archive( 'portfolio_item' ) ) get_template_part( 'menu', 'portfolio' ); ?>
		</div><!-- .loop-description -->

	<?php } elseif ( is_day() || is_month() || is_year() ) { ?>

		<?php
			if ( is_day() )
				$date = get_the_time( __( 'F d, Y', 'socially-awkward' ) );
			elseif ( is_month() )
				$date = get_the_time( __( 'F Y', 'socially-awkward' ) );
			elseif ( is_year() )
				$date = get_the_time( __( 'Y', 'socially-awkward' ) );
		?>

		<h1 class="loop-title"><?php echo $date; ?></h1>

		<div class="loop-description">
			<?php echo wpautop( sprintf( __( 'You are browsing the site archives for %s.', 'socially-awkward' ), $date ) ); ?>
		</div><!-- .loop-description -->

	<?php } elseif ( is_archive() ) { ?>

		<h1 class="loop-title"><?php _e( 'Archives', 'socially-awkward' ); ?></h1>

		<div class="loop-description">
			<?php echo wpautop( __( 'You are browsing the site archives.', 'socially-awkward' ) ); ?>
		</div><!-- .loop-description -->

	<?php } // End if check ?>

</div><!-- .loop-meta -->