<!DOCTYPE html>
<html <?php language_attributes( 'html' ); ?>>

<head>
<?php wp_head(); // Hook required for scripts, styles, and other <head> items. ?>
</head>

<body <?php hybrid_body_attributes(); ?>>

	<div id="container">

		<header id="header">

			<hgroup id="branding">
				<h1 id="site-title"><a href="<?php echo home_url(); ?>" title="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
				<h2 id="site-description"><?php bloginfo( 'description' ); ?></h2>
			</hgroup><!-- #branding -->

		</header><!-- #header -->

		<?php get_template_part( 'menu', 'primary' ); // Loads the menu-primary.php template. ?>

		<div id="main">