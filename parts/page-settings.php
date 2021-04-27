<?php
/*
Template Name: Settings Page
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

    <a href="#" id="wheelmenu" class="do-dark-mode">
        <svg viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z" />
        </svg>
        <span>Dark Mode</span>
    </a>
    <ul class="modes hidden">
        <li class="do-dark-mode">Dark Mode</li>
        <li class="do-spring-mode">Spring Mode</li>
        <li class="do-rainy-mode">Rainy Mode</li>
    </ul>

    <a href="<?php echo wp_logout_url('index.php'); ?>"><svg class="icon"><use xlink:href="#icon-exit" /></svg> <small>Çıkış</small> </a>

</div>

    <script src='<?php echo get_template_directory_uri(); ?>/assets/js/modes.js'></script>

<?php get_footer(); ?>