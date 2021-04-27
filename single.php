<?php get_header();  

// If User is user :P
if ( is_user_logged_in() ) { 
    
    while (have_posts()) : the_post(); 
        echo "<div id='post-wrapper'>";
            echo do_shortcode('[ajax_posts]');
        echo "</div><!-- End #post-wrapper -->";
    endwhile; 
} else { // If User is offline
    get_template_part('inc/loginregister/login-register'); 
}

get_footer(); ?>