<?php get_header();  ?>

<?php if ( is_user_logged_in() ) { 
    get_template_part('inc/form_add_post'); 

    echo "<div class='posts articles'>";

    if ( have_posts() ) {
        // Load posts loop.
        while ( have_posts() ) {
          the_post();
                get_template_part( 'parts/content', get_post_type() );
        }
        // Previous/next page navigation.
        if (  $wp_query->max_num_pages > 1 ) {
            echo '<div id="loadmore-home" class="hc_loadmore"><svg class="icon"><use xlink:href="#icon-loadmore" /></svg> Daha Fazla Yükle</div>';
        }
    } else {
        // If no content, include the "No posts found" template.
        get_template_part( 'parts/content', 'none' );
    }

    echo "</div>";
} 
// IF NOT Logged In
else { 
  get_template_part('inc/loginregister/login-register'); 
} ?>

<?php get_footer(); ?>