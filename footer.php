		</div><!-- #main -->

		<footer id="footer">

			<div class="wrap">

				<?php get_template_part( 'menu', 'social' ); // Loads the menu-social.php template. ?>

				<div class="footer-content">
					<?php echo apply_atomic_shortcode( 'footer_content', '<p class="credit">' . __( 'Copyright &copy; [the-year] [site-link].<br />Powered by [wp-link] and [theme-link].', 'socially-awkward' ) . '</p>' ); ?>
				</div><!-- .footer-content -->

			</div>

		</footer><!-- #footer -->

	</div><!-- #container -->

	<?php wp_footer(); // wp_footer ?>

</body>
</html>