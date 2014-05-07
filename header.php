<!DOCTYPE html>
<html <?php language_attributes( 'html' ); ?>>

<head>
<?php wp_head(); // Hook required for scripts, styles, and other <head> items. ?>
</head>

<body <?php hybrid_attr( 'body' ); ?>>

	<div id="container">

		<header id="header">

			<div id="branding">
				<?php hybrid_site_title(); ?>
				<?php hybrid_site_description(); ?>
			</div><!-- #branding -->

		</header><!-- #header -->

		<?php hybrid_get_menu( 'primary' ); // Loads the menu/primary.php template. ?>

		<div id="main">