<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 *
 * @package MySecondTheme
 * @subpackage My_Second_Theme
 * @since My Second Theme 1.0
 */

get_header(); ?>

	<?php  get_sidebar(); ?>

	<?php
	if ( have_posts() ) :
		while ( have_posts() ) :
			the_post(); ?>

			<article <?php post_class(); ?>>
				<h2><?php the_title(); ?></h2>

				<?php the_content(); ?>
			</article>

		<?php
		endwhile;
	endif;
	?>

<?php get_footer(); ?>
