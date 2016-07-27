<?php
/**
 * The template for displaying the header
 *
 * Displays all of the head element and everything up until the "site-content" div.
 *
 * @package MySecondTheme
 * @subpackage My_Second_Theme
 * @since My Second Theme 1.0
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<title><?php wp_title( '|', true, 'right' ); ?></title>
	<meta name=description content="<?php bloginfo( 'description' ); ?>">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
	<header role="banner" class="site-header">
		<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><img src="<?php bloginfo( 'template_directory' ); ?>/images/logo.png" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>"></a></h1>
		<p class="site-description"><?php bloginfo( 'description' ); ?></p>

		<nav id="site-navigation" class="main-navigation" role="navigation">
			<h2 class="offscreen">Main navigation</h2>
			<?php
				// Primary navigation menu.
				wp_nav_menu( array(
					'menu_class'     => 'nav-menu',
					'theme_location' => 'primary',
					'container'      => false,
				) );
			?>
		</nav>
	</header>
	<div id="content" class="site-content">
