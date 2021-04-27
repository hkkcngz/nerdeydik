<?php
/*
Template Name: Notifications
*/

/// Şu anda sayfada bulunan her kimse onun bilgilerini çeker */
global  $current_user, $wp;
		$user = get_currentuserinfo();
        $userID = $current_user->ID;
		$userName = $current_user->display_name;
		$userLogin = $current_user->user_login;
		$userProfile = get_site_url() . "/". get_option( 'hc_author_base' ) ."/" . $userLogin;

        $userAvatar = get_user_meta($userID,'userAvatar',true);
        $userFollowers = get_user_meta($userID,'followers',true);
        $userNotifications = get_user_meta($userID,'notifications',true);
/// Şu anda sayfada bulunan her kimse onun bilgilerini çeker */

// Notifications 
$userNotifications  = trim( $userNotifications );
$eachNotification   = explode(" ", $userNotifications);
$countNotifications = count( $eachNotification );

get_header();  ?>
<div class="page">
    <?php if ( have_posts() ) {

      // Load posts loop.
      while ( have_posts() ) {
        the_post();
          the_content();
      }
  } ?>
</div>

<!-- Section -->
<section>

    <!-- Bildirimler -->
    <div class="tabs in_sidebar">
        <input type="radio" name="tabs" id="tabone" checked="checked">
        <label for="tabone" class="tab_title"><svg class="icon"><use xlink:href="#icon-notification" /></svg><span>Bildirimler</span></label>
        <div class="tab">

            <!-- Bildirimler -->
            <h3>Bildirimler </h3>
            <ul class="notifications">
                <?php 
                    // Delete Notification
                    if(isset($_POST['delNotification']) && isset($_POST['post_nonce_field']) && wp_verify_nonce($_POST['post_nonce_field'], 'post_nonce')) {

                        $delFromNotification = str_replace($_POST["delNotification"],"",$userNotifications);
                        update_user_meta( $userID, 'notifications', $delFromNotification );

                        wp_redirect( home_url( $wp->request ) ); 
                        
                    }

                    foreach( array_reverse($eachNotification) as $notification) {

                        if ( !$notification ) {
                            // echo "Henüz bildirim yok";
                        } else {

                            list($notificationType,$notificationUserID, $notificationPostID) = explode("#", $notification);

                            // Find Who Made This
                            $whodidthis 	= get_user_by('id', (int)$notificationUserID);
                            $whodidthisID 	= $whodidthis->ID;
                            $whodidthisName = $whodidthis->display_name;
                            $whodidthisAvatar = get_user_meta($whodidthisID,'userAvatar',true);

                            // What Happened
                            $whathappened = "<span>$whodidthisName bir şey yaptı.</span>";
                            if ( $notificationType == "newpost" ) 
                                $whathappened = "<span>$whodidthisName yeni bir yazı paylaştı.</span>";
                            if ( $notificationType == "whoviewed" ) 
                                $whathappened = "<span>$whodidthisName senin profiline baktı.</span>";
                            if ( $notificationType == "follow" ) 
                                $whathappened = "<span> $whodidthisName seni takip etti.</span>";
                            if ( $notificationType == "comment" ) 
                                $whathappened = "<span> $whodidthisName senin yazına yorum yaptı.</span>";	
                            if ( $notificationType == "liked" ) 
                                $whathappened = "<span> $whodidthisName senin yazını beğendi.</span>";	
                            if ( $notificationType == "message" ) 
                                $whathappened = "<span> $whodidthisName sana bir mesaj gönderdi.</span>";

                            // Mini Delete Form
                            $deleteForm = "<input type='hidden' name='delNotification' value='".$notification."'><input type='submit' class='del-btn' value='X'>";
                            
                            // Find Who Made This Avatar
                            if($whodidthisAvatar) {
                                $avatarURL = "<img src='". esc_url( $whodidthisAvatar ). "' alt='' />";
                            } else {
                                $avatarURL = "<img src='". get_template_directory_uri() . "/images/default-user-image.jpg' alt='varsayılan avatar' />";
                            }
                            $notificationAvatar = "<div class='author_photo'> $avatarURL </div>";

                            echo "<li>$notificationAvatar $whathappened <form method='POST'>$deleteForm"; wp_nonce_field('post_nonce', 'post_nonce_field'); echo "</form></li>";
                        }
                    }
                ?>
            </ul>
            <!-- Bildirimler Son --> 

        </div>

        <input type="radio" name="tabs" id="tabtwo">
        <label for="tabtwo" class="tab_title"><svg class="icon"><use xlink:href="#icon-megaphone" /></svg><span>Duyurular</span></label>
        <div class="tab">


        <h3>Tüyolar & Duyurular</h3> <br>
            <ul class="notifications">
                <li>Profilinizde düzenlemek istediğiniz bir alan mı var yalnızca dokunmanız yeterli.</li>
                <li>
                    Doğum günü olan kişilerin yanında 'pasta' ikonu görebilirsin. 
                </li>
                <li>
                    İş durumunu sergilemek istersen ekleyebilirsin. 
                </li>
            </ul>
        </div>

        <input type="radio" name="tabs" id="tabthree">
        <label for="tabthree" class="tab_title"><svg class="icon"><use xlink:href="#icon-bookmark" /></svg><span>Kaydedilenler</span></label>
        <div class="tab padding">
            <h3>Çayladığın Yazılar</h3> <br>
            <ul>
            <?php
                $types = get_post_types( array( 'public' => true ) );
                $args = array(
                    'numberposts' => -1,
                    'post_type' => $types,
                    'meta_query' => array (
                        array (
                        'key' => '_user_liked',
                        'value' => $userID,
                        'compare' => 'LIKE'
                        )
                    ) 
                );		
                $like_query = new WP_Query( $args );
                if ( $like_query->have_posts() ) : while ( $like_query->have_posts() ) : $like_query->the_post(); ?>
                    <li>
                        <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                            <?php the_title(); ?>
                        </a>
                    </li>
            <?php endwhile;  else : ?>
                <p><?php _e( 'Henüz bişey çaylamadınız.', 'editorsel' ); ?></p>
            <?php endif; wp_reset_postdata(); ?>
            </ul>

        </div>
    </div>
    <!-- Bildirimler Son -->

</section>


<?php get_footer(); ?>