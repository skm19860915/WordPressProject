<?php
	global $header_menu_class;

	$header_menu_class = " ";
?>
<!--header-->
<header class="header header10">
	<!--wrap-->
	<div class="wrap">
		<?php get_template_part('includes/parts/header/header', 'contact'); ?>		
		<?php get_template_part('includes/parts/header/header', 'logo'); ?>
		<?php get_template_part('includes/parts/header/header', 'ribbon'); ?>
		<div class="minicart">
		<?php
			global $cart_page_url;
			if (!empty($cart_page_url) && BookYourTravel_Theme_Utils::is_woocommerce_active()) {
				$cart_count = WC()->cart->cart_contents_count;
			?>
			<a href="<?php echo esc_url($cart_page_url) ?>"><?php echo sprintf('<i class="material-icons">shopping_cart</i> <span>%d</span>', $cart_count); ?></a>
			<?php
			}
		?>
		</div>
	</div>
	<!--//wrap-->
	<?php get_template_part('includes/parts/header/header', 'nav'); ?>	
</header>
<!--//header-->