<?php get_header(); ?>
<section>
  <h2>Latest news</h2>

  <?php if( have_posts() ): ?>

    <?php while( have_posts()): the_post(); ?>

      <?php get_template_part( 'content', get_post_format()); ?>

    <?php endwhile; ?>

  <?php else: ?>

    <article class="error">
      <h3>Sorry there were no news articles found.</h3>
    </article>

  <?php endif; ?>

  <p class="post-page-navigation">
    <?php previous_posts_link("&laquo; More recent news"); ?>
    <?php next_posts_limk( "Past news &raquo;"); ?>
  </p>

</section>

  <?php get_sidebar( 'news' ); ?>

<?php get_footer(); ?>
