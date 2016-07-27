<?php
/**
 * The template for displaying archive pages
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package MySecondTheme
 * @subpackage My_Second_Theme
 * @since My Second Theme 1.0
 */

?>
<?php get_header(); ?>

  <?php if ( have_posts() ) : the_post(); ?>

    <?php if ( is_category() ) : ?>
      <h2>Archive for category: <?php single_cat_title(); ?></h2>
    <?php elseif ( is_tag() ) : ?>
      <h2>Posts Tagged: <?php single_tag_title(); ?></h2>
    <?php elseif ( is_day() ) : ?>
        <h2>Archive for <?php the_time( 'F jS, Y' ); ?></h2>
    <?php elseif ( is_month() ) : ?>
      <h2>Archive for <?php the_time( 'F, Y' ); ?></h2>
    <?php elseif ( is_year() ) : ?>
      <h2>Archive for <?php the_time( 'Y' ); ?></h2>
    <?php elseif ( is_author() ) : ?>
      <h2>Author archive</h2>
    <?php elseif ( isset( $_GET['paged'] ) && ! empty( $_GET['paged'] ) ) : ?>
      <h3>Archives</h3>
    <?php endif; ?>

    <?php rewind_posts(); ?>

		<?php while (have_posts()) : the_post(); ?>
				<?php get_template_part( 'content', get_post_format() ); ?>
		<?php endwhile; ?>

    <?php else : ?>

      <?php if ( is_category() ) : ?>
        <h2>Sorry, but there aren't any posts in the <?php single_cat_title(); ?> category yet.</h2>
      <?php elseif ( is_date() ) : ?>
        <h2>Sorry, but there aren't any posts with this date.</h2>
      <?php elseif ( is_author() ) : ?>
        <?php get_userdatabylogin( get_query_var( 'author_name' ) ); ?>
        <h2>Sorry, but there aren't any posts by <?php echo $userdata->display_name; ?> yet.</h2>
      <?php else : ?>
        <h2>No posts found.</h2>
      <?php endif; ?>

  <?php endif; ?>

<?php get_footer(); ?>
