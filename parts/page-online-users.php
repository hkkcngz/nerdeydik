<?php 
/* Template Name: Online Users */

// User Info
global $current_user, $wp;
    $user = get_currentuserinfo();
    $userID = $current_user->ID;
    $userName = $current_user->display_name;
    $userLogin = $current_user->user_login;

    $userAvatar = get_user_meta($userID,'userAvatar',true);
    $defaultThumb = get_template_directory_uri() . "/images/default-user-image.jpg";

    $userFollowers = get_user_meta($userID,'followers',true);
    $userFollowed = get_user_meta($userID,'followed',true);
    $userNotifications = get_user_meta($userID,'notifications',true);

    $currentPage = home_url( $wp->request );

    // Profile URL.
    $author_base = get_option( 'hc_author_base' );
    if ( empty($author_base) ) {
        $author_base = "author";
    }
    $userProfile = get_site_url() . "/". $author_base ."/" . $userLogin;
// User Info




get_header();  ?>

<div class="page">
  <?php if ( have_posts() ) {

      // Load posts loop.
      while ( have_posts() ) {
        the_post();
          the_content();
      }
  } ?>

<div class="author-list">
        <h3>Online Üyeler</h3>
        <?php 

            $uyeler = get_users( array( 'fields' => array( 'ID' ) ) );
            // Array of stdClass objects.
            foreach ( $uyeler as $uye ) { 

                if( is_user_online( $uye->ID ) ) {
                    $userName = get_the_author_meta( 'user_login', $uye->ID);
                    $avatar   = ( $userAvatar ) ? ( $userAvatar ) : $defaultThumb; ?>

                        <a href="<?php echo profile_url($uye->ID); ?>" title="<?php echo $uye->display_name . ' - ' . wp_trim_words($uye->description); ?> ">
                            <img src="<?=$avatar?>" alt="<?=$userName?> Profil Fotoğrafı" class="author_list_img">
                        </a>
                        
                <?php } // end user online

            } // end foreach ?>
    </div>

    <br><br>

    <h3><?php _e('Tüm Üyeler', 'editorsel');?></h3> <br>
        <div class="author-list">
        <?php
            $args = array(
                'fields' => array( 'ID' )
                // 'has_published_posts' => array('post'),
            );

            // The Query
            $user_query = new WP_User_Query($args);

            // User Loop
            if (!empty($user_query->get_results())) {
                foreach ($user_query->get_results() as $yazar) {
                    $userAvatar = get_user_meta($yazar->ID, 'userAvatar', true);
                    $birthday = get_user_meta($yazar->ID, 'birthday', true);
                    date_default_timezone_set('Europe/Istanbul');
                    $today = date("d.m"); ?>
            <a href="<?php echo profile_url($yazar->ID); ?>" title="<?php echo $yazar->display_name . ' - ' . wp_trim_words($yazar->description); ?> ">
                <img class="author_list_img" src="<?php if ($userAvatar) {echo esc_url($userAvatar);} else {echo $defaultThumb; }?>" alt="<?php echo $yazar->display_name; ?>"  />
                <?php if ($birthday == $today) { echo "<div class='birthday'><svg class='icon'><use xlink:href='#icon-birthday-cake' /></svg></div>"; } ?>
            </a>
        <?php
            }
            } else {
                _e('<p>Sitenizde listelenecek üye bulunamadı</p>', 'editorsel');
            }

            ?>
        </div>



</div>

<?php get_footer(); ?>