<?php

/**
 * @package Welcart
 * @subpackage Welcart_Basic
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>" />
	<meta name="viewport" content="width=device-width, user-scalable=no">
	<meta name="format-detection" content="telephone=no" />

	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=M+PLUS+2:wght@100;200;300&family=Sawarabi+Gothic&display=swap" rel="stylesheet">
	<!-- clay.css -->
	<link rel="stylesheet" href="https://unpkg.com/claymorphism-css/dist/clay.css" />
	<!-- swiper -->
	<link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css" />
	<script src="https://unpkg.com/swiper@7/swiper-bundle.min.js"></script>

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>

	<header id="masthead" class="site-header" role="banner">
		<?php
		// トップページ判定
		$isHome = (is_front_page() || is_home()) && get_header_image();
		?>
		<!-- ヘッダータイトル -->
		<div class="l-header__title">
			<!-- トップ　ヘッダー -->
			<div class="<? echo $isHome ? 'l-header__logoleft-home' : 'l-header__logoleft'; ?>">
				<?php if ( $isHome ) : ?>
				<img src="<?php echo get_stylesheet_directory_uri() ?>/images/logo-side.png">
				<? else: ?>
				<img src="<?php echo get_stylesheet_directory_uri() ?>/images/logo.png">
				<? endif; ?>
			</div>
			
			<?php if ( $isHome ) : ?>
			<div class="l-header__logocenter">
				<img src="<?php echo get_stylesheet_directory_uri() ?>/images/logo.png">
			</div>
			<?php endif; ?>

			<div class="l-header__logoright">
				<?php if ( $isHome ) : ?>
				<!-- cart button -->
				<div class="incart-btn">
					<a href="<?php echo USCES_CART_URL; ?>"><i class="fa fa-shopping-cart"><span><?php _e('In the cart', 'usces') ?></span></i><?php if (!defined('WCEX_WIDGET_CART')) : ?><span class="total-quant"><?php usces_totalquantity_in_cart(); ?></span><?php endif; ?></a>
				</div>
				<?php endif; ?>
				<!-- hamburger menu -->
				<nav id="site-navigation" class="c-hamburger" role="navigation">
					<input type="checkbox" id="panel" class="on-off" />
					<label for="panel" class="c-hamburger__button">
						<span></span>
						<div class="c-hamburger__caption">MENU</div>
					</label>
					<?php
					$page_c	=	get_page_by_path('usces-cart');
					$page_m	=	get_page_by_path('usces-member');
					$pages	=	"{$page_c->ID},{$page_m->ID}";
					wp_nav_menu(array('theme_location' => 'header', 'container_class' => 'nav-menu-open', 'exclude' => $pages,  'menu_class' => 'header-nav-container cf'));
					?>
				</nav>
				<?php //else: ?>
				<?php //endif; ?>
			</div>
		</div>
		<!-- ヘッダー　下線 -->
		<div class="c-hr__fullwidth c-hr__brown"> </div>
		
		<?php if ( $isHome ) : ?>
		<!-- トップ　キャッチフレーズ -->
		<div class="l-header__logobottom">
			<img src="<?php echo get_stylesheet_directory_uri() ?>/images/logo-bottom.png" alt="since 2011">
		</div>

		<!-- トップ メニューボタン -->
		<div class="inner cf l-topnav__wrapper">
			<?php 
				wp_nav_menu( array( 
					'menu' => 'header fixed menu',
					'container' => 'div',
					'container_class' => 'c-buttons card',
					'link_before' => '<div class="clay azuki">
						<img src="$key$">
						<p>',
					'link_after' => '</p></div>',
				) );
			?>
			<?php $heading_tag = (is_home() || is_front_page()) ? 'h1' : 'div'; ?>
		</div>
		<?php else: ?>
		<?php endif; ?>

		<?php if (!welcart_basic_is_cart_page()) : ?>

			<!-- <nav id="site-navigation" class="main-navigation" role="navigation">
				<label for="panel"><span></span></label>
				<input type="checkbox" id="panel" class="on-off" />
				<?php
				// $page_c	=	get_page_by_path('usces-cart');
				// $page_m	=	get_page_by_path('usces-member');
				// $pages	=	"{$page_c->ID},{$page_m->ID}";
				// wp_nav_menu(array('theme_location' => 'header', 'container_class' => 'nav-menu-open', 'exclude' => $pages,  'menu_class' => 'header-nav-container cf'));
				?>
			</nav> -->
			<!-- #site-navigation -->

		<?php endif; ?>
		<!-- **************bottom of header************** -->

	</header><!-- #masthead -->

	<!-- **************top of mainimg************** -->
	<?php if ((is_front_page() || is_home()) && get_header_image()) : ?>
		<!-- トップ　スライダー -->
		<div class="swiper-tophead">
			<!-- 必要に応じたwrapper -->
			<div class="swiper-wrapper">
				<?php
				$args_slider = array (
					'post_type' => 'post',
					'post_status' => 'publish',
					'posts_per_page' => 3,	// 表示最大3件
					'tag' => 'topslider',
					'orderby' => array('date' => 'ASC'),
				);
				$sliderposts = new WP_Query($args_slider);
				while ($sliderposts->have_posts()):
					$sliderposts->the_post();
				?>
					<div class="swiper-slide">
						<a href="<?php the_permalink(); ?>">
							<img src="<?php the_post_thumbnail_url(); ?>">
							<div class="swiper-title">
								<?php the_title(); ?>
							</div>
						</a>
					</div>
					
				<?php
				endwhile;
				?>
			</div>
			<div class="swiper-button-prev swiper-button-white c-swiper-button"></div>
			<div class="swiper-button-next swiper-button-white c-swiper-button"></div>
		</div>


		<!-- <div class="main-image">
			<img src="<?php header_image(); ?>" width="<?php echo get_custom_header()->width; ?>" height="<?php echo get_custom_header()->height; ?>" alt="<?php bloginfo('name'); ?>">
		</div> -->
		<!-- main-image -->
	<?php endif; ?>
	
	<?php if (is_front_page() || is_home()) : ?>
	<div class="l-top__bottomtext">
		<!-- キャッチフレーズ：全角空白は改行に置換 -->
		<?php echo str_replace("　","<br>",get_bloginfo('description')); ?>
	</div>
	<?php endif; ?>

	<?php
	if (is_front_page() || is_home() || welcart_basic_is_cart_page() || welcart_basic_is_member_page()) {
		$class = 'one-column';
	} else {
		$class = 'two-column right-set';
	};
	?>
	<!-- **************bottom of mainimg************** -->

	<div id="main" class="wrapper <?php echo $class; ?>">