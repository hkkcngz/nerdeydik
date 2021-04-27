<?php
/*
Template Name: Elements
*/
get_header(); ?>

<div class="page mt">
  <?php if ( have_posts() ) {

      // Load posts loop.
      while ( have_posts() ) {
        the_post();
          the_content();
      }
  } ?>

<?php get_template_part('inc/svg-icons'); ?>
<?php get_template_part('inc/html-elements'); ?>
</div>

<?php get_footer(); ?>