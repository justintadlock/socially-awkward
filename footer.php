		</div><!-- #main -->

		<footer id="footer">

			<div class="wrap">

				<?php hybrid_get_menu( 'social' ); // Loads the menu/social.php template. ?>

				<p class="credit">
					<?php printf(
						/* Translators: 1 is current year, 2 is site name/link. */
						__( 'Copyright &#169; %1$s %2$s.', 'socially-awkward' ), date_i18n( 'Y' ), hybrid_get_site_link()
					); ?>
					<br />
					<?php printf(
						/* Translators: 1 is WordPress link, 2 is theme name/link. */
						__( 'Powered by %1$s and %2$s.', 'socially-awkward' ), hybrid_get_wp_link(), hybrid_get_theme_link()
					); ?>
				</p><!-- .credit -->

			</div><!-- .wrap -->

		</footer><!-- #footer -->

	</div><!-- #container -->

	<?php wp_footer(); // wp_footer ?>

</body>
</html>