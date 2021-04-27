<?php 

/// Şu anda sayfada bulunan her kimse onun bilgilerini çeker */
global  $current_user;
        $user = get_currentuserinfo();
        $userID = $current_user->ID;
        $userName = $current_user->display_name;
        $userNickname = $current_user->nickname;
        $userLogin = $current_user->user_login;

        $userAvatar = get_user_meta($userID,'userAvatar',true);
        $userFollowers = get_user_meta($userID,'followers',true);
        $userNotifications = get_user_meta($userID,'bildirimler',true);
/// Şu anda sayfada bulunan her kimse onun bilgilerini çeker */

get_header();  ?>

<?php if ( is_user_logged_in() ) { 
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