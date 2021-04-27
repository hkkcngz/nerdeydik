<?php get_header();  ?>

<?php if ( is_user_logged_in() ) { 
  echo "<div class='page mt'>";
  if ( have_posts() ) {
      // Load posts loop.
      while ( have_posts() ) {
        the_post();
          the_content();
      }
  echo "</div>";
  
  } // IF NOT Logged In
  else { 
    get_template_part('inc/loginregister/login-register'); 
  } ?>

<?php get_footer(); ?>