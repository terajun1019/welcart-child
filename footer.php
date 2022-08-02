
	</div><!-- #main -->
	
	
	<?php if(! wp_is_mobile()): ?>
	
		<div id="toTop" class="wrap fixed"><a href="#masthead"><i class="fa fa-chevron-circle-up"></i></a></div>
	
	<?php endif; ?>
	
	<footer id="colophon" role="contentinfo" class="l-footer">
<!-- **************top of footer************** -->
	
		<nav id="site-info" class="footer-navigation">
			<div>
				ブラウニーズ　Online Store
			</div>
			<hr>
			<?php
				$page_c	=	get_page_by_path('usces-cart');
				$page_m	=	get_page_by_path('usces-member');
				$pages	=	"{$page_c->ID},{$page_m->ID}";
				wp_nav_menu(array( 'theme_location' => 'footer', 'exclude' => $pages , 'menu_class' => 'footer-menu cf' )); 
			?>
		</nav>

		<div class="l-footer__address">
			<div class="l-content__flex-child50 w-60">
				<img src="<?php echo get_theme_file_uri() ?>/images/logo.png" alt="ロゴ" class="w-80 mb-3">
				<br>
				〒039-0071<br>
				青森県八戸市沼館1-21-2<br>
				TEL 0178-70-5409<br>
				support@little-brownies.com<br>
			</div>
			<div class="l-content__flex-child50 w-40">
			<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3032.698296464757!2d141.48909631744388!3d40.526159199999995!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x5f9b52637d0bf13b%3A0x15ce96e14fe107b0!2z44OW44Op44Km44OL44O844K6!5e0!3m2!1sja!2sjp!4v1651910965268!5m2!1sja!2sjp" style="width:100%; height:auto; border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
			</div>
		</div>
		
		<div class="copyright">
			<div class="shine"></div>
			<?php usces_copyright(); ?>
		</div>
<!-- **************bottom of footer************** -->
	
	</footer><!-- #colophon -->
	
	<?php wp_footer(); ?>
	</body>
</html>
