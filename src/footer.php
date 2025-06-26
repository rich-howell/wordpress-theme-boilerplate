		<footer>
			<div class="container">
				<div class="row">
					<p>{{theme name}} Copyright <?php echo esc_attr( gmdate( 'Y' ) ); ?> 
					<a class="footer-link" href="<?php echo esc_url( home_url() ); ?>">Copyright {{your theme }}</a></p>
				</div>
			</div>
		</footer>

		<?php wp_footer(); ?>
	</body>
</html>
