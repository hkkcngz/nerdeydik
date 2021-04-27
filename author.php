<?php

global 
    $wp,    
    $wpdb, 
    $current_user;
    $user = get_currentuserinfo();
    $userID = $current_user->ID;
    $userName = $current_user->display_name;
    $userFollowed = get_user_meta($userID,'followed',true);
    $userNotifications = get_user_meta($userID,'notifications',true);

        // Where is this User ?!
        $userLat  = get_user_meta($userID,'latitude',true);
        $userLong = get_user_meta($userID,'longitude',true);

    $currentPage = home_url( $wp->request );

    // Current Author
    $curauth = (get_query_var('author_name')) ? get_user_by('slug', get_query_var('author_name')) : get_userdata(get_query_var('author'));
    $curauthID = $curauth->ID;
    $curauthName = $curauth->nickname;
    $curauthDesc = $curauth->description;
    $curauthFollowers = get_user_meta($curauthID,'followers',true);
    $curauthFollowersCount = substr_count($curauthFollowers," ") + 0;

    $curauthFollowed = get_user_meta($curauthID,'followed',true);
    $curauthFollowedCount = substr_count($curauthFollowed," ") + 0;

    // Is Today Author's Birthday?
    $birthday = get_user_meta($curauthID, 'birthday', true);
    date_default_timezone_set('Europe/Istanbul');
    $today = date("d.m");

    // Save Places
    $curauthJob = get_user_meta($curauthID,'job',true);
    $curauthJob = $curauthJob ? $curauthJob : 'Meslek';

    $curauthJobTitle = get_user_meta($curauthID,'jobtitle',true);
    $curauthJobTitle = $curauthJobTitle ? $curauthJobTitle : 'İş Alabileceği/Verebileceği Pozisyonlar:';

    $curauthJob00 = get_user_meta($curauthID,'job00',true);
    $curauthJob00 = $curauthJob00 ? $curauthJob00 : 'Meslek';
    $curauthJob01 = get_user_meta($curauthID,'job01',true);
    $curauthJob01 = $curauthJob01 ? $curauthJob01 : 'Meslek';
    $curauthJob02 = get_user_meta($curauthID,'job02',true);
    $curauthJob02 = $curauthJob02 ? $curauthJob02 : 'Meslek';

        // Where is this Author ?!
        $curauthLat  = get_user_meta($curauthID,'latitude',true);
        $curauthLong = get_user_meta($curauthID,'longitude',true);

    // Save Places

    $authorAvatar = get_user_meta($curauthID,'userAvatar',true);
    $authorCover  = get_user_meta($curauthID,'userCover',true);
    $authorNotifications = get_user_meta($curauthID,'notifications',true);
    $authorFollowers = get_user_meta($curauthID,'followers',true);

    // Current Author Level / Rank
    $where = 'WHERE comment_approved = 1 AND user_id = ' . $curauth->ID ;
    $comment_count = $wpdb->get_var("SELECT COUNT( * ) AS total FROM {$wpdb->comments} {$where}");
    $user_post_count = count_user_posts( $curauth->ID , "post" ); 

    $exp = $user_post_count + ( $comment_count / 4 );

    if (is_user_online($curauthID)) { $is_author_online = "online";} else {$is_author_online = "offline";}

    // Profili Kim Ziyaret Etti ?
        
        if ( $curauthID !== $userID && is_user_logged_in() ) { // Yazar - Kendi Sayfasında Değilse
            if ( kelime_sor($authorNotifications, "whoviewed#$userID")  == 1 ) { // Eğer Şuanki Kullanıcı Daha Önce Ziyaret Etmişse:  
                // şu işlemleri yap
            } else { 

                // Add to Notifications
                $authorNotifications .= "whoviewed#$userID ";
                update_user_meta( $curauthID, 'notifications', $authorNotifications );

            } 
        }

    // Profili Kim Ziyaret Etti ?

    // Follow System by HC

        // Takip Et
        if(isset($_POST['follow']) && isset($_POST['post_nonce_field']) && wp_verify_nonce($_POST['post_nonce_field'], 'post_nonce')) {
            if ( kelime_sor($authorNotifications, "follow#$userID")  == 1 ) { // Eğer Şuanki Kullanıcı Daha Önce Takip Etmişse: 
                // şu işlemleri yap
            } else { 
                $userFollowed .= "#$curauthID ";
                update_user_meta( $userID, 'followed', $userFollowed );

                $authorFollowers .= "#$userID ";
                update_user_meta( $curauthID, 'followers', $authorFollowers );

                // Add to Notifications
                $authorNotifications .= "follow#$userID ";
                update_user_meta( $curauthID, 'notifications', $authorNotifications );
            }
            wp_redirect( $currentPage ); 
        }

        // Takipten Çıkar
        if(isset($_POST['unfollow']) && isset($_POST['post_nonce_field']) && wp_verify_nonce($_POST['post_nonce_field'], 'post_nonce')) {
            // str_replace($search, $replace, $subject)
            $unFollow = str_replace("#$curauthID ", "","$userFollowed");
            update_user_meta( $userID, 'followed', $unFollow );

            $unFollower = str_replace("#$userID ", "","$authorFollowers");
            update_user_meta( $curauthID, 'followers', $unFollow );

            wp_redirect( $currentPage ); 
        }
    // Follow System by HC

    // Tıkla Kaydet
    $error = array();    
    /* If profile was saved, update profile. */
    if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'update-user' ) {
    
        if ( !empty( $_POST['hiddenName'] ) ) 
            update_user_meta( $curauthID, 'nickname', esc_attr( $_POST['hiddenName'] ) );

        if ( !empty( $_POST['hiddenDesc'] ) ) 
            update_user_meta( $curauthID, 'description', trim( esc_attr( $_POST['hiddenDesc'] ) ) );
        
        if ( !empty( $_POST['hiddenJob'] ) ) 
            update_user_meta( $curauthID, 'job', esc_attr( $_POST['hiddenJob'] ) );
        if ( !empty( $_POST['hiddenJobTitle'] ) ) 
            update_user_meta( $curauthID, 'jobtitle', esc_attr( $_POST['hiddenJobTitle'] ) );            
        if ( !empty( $_POST['hiddenJob00'] ) ) 
            update_user_meta( $curauthID, 'job00', esc_attr( $_POST['hiddenJob00'] ) );
        if ( !empty( $_POST['hiddenJob01'] ) ) 
            update_user_meta( $curauthID, 'job01', esc_attr( $_POST['hiddenJob01'] ) );    
        if ( !empty( $_POST['hiddenJob02'] ) ) 
            update_user_meta( $curauthID, 'job02', esc_attr( $_POST['hiddenJob02'] ) );   

        if ( !empty( $_POST['hiddenBirthday'] ) )
            update_user_meta( $curauthID, 'birthday', esc_attr( $_POST['hiddenBirthday'] ) );
    
        // Where am I ? by HC       
        if ( !empty( $_POST['myLat'] ) )
            update_user_meta( $curauthID, 'latitude', esc_attr( $_POST['myLat'] ) );
        if ( !empty( $_POST['myLong'] ) )
            update_user_meta( $curauthID, 'longitude', esc_attr( $_POST['myLong'] ) );
        // Where am I ? by HC

        /* Redirect so the page will show updated info.*/
        if ( count($error) == 0 ) {
            //action hook for plugins and extra fields saving
            do_action('edit_user_profile_update', $userID);
            wp_redirect( $currentPage );
            exit;
        } 
    }

    // Send a Message
    if(isset($_POST['sendmessage']) && isset($_POST['message_nonce_field']) && wp_verify_nonce($_POST['message_nonce_field'], 'post_nonce')) {
    
        $autID = $_POST['authorUserID'];
        $post_title = "msg#$userID#$autID"; // Kaydederken direk böyle kaydet.

        // Daha önce bu üyeden mesaj almış mı kontrol et ve o mesaja yönlendir!
        $post_title00 = "msg#$userID#$autID";
        $post_title01 = "msg#$autID#$userID";

        $is_sended00 = get_page_by_title($post_title00, OBJECT, 'messenger');
        $is_sended01 = get_page_by_title($post_title01, OBJECT, 'messenger');

        $message_url00 = get_permalink($is_sended00->ID);
        $message_url01 = get_permalink($is_sended01->ID);

        // Yönlendirmeler güzel fakat çöpteki ortak mesajı bile buluyor. O yüzden çöpte ise yeni mesaj oluşturmalı, çöpteki mesaja yönlenmemeli. 
        if (kelime_sor($message_url00,'trashed') == 1) {
            $is_trashed = true;
        }
        if (kelime_sor($message_url01,'trashed') == 1) {
            $is_trashed = true;
        }

        if ( ( $is_sended00 && !$is_trashed ) && !$is_sended01 ) {

            wp_redirect( "$message_url00" ); 
            exit;

        } else if ( ( $is_sended01 && !$is_trashed ) && !$is_sended00 ) {

            wp_redirect( "$message_url01" );
            exit; 

        } else { // Yoksa yeni mesaj oluştur.

            $post_information = array(
                /* 'ID' => $post->ID, */
                'post_type'     => 'messenger',
                'post_title'    => "$post_title",  // ilk mesaj girişi
                // 'post_content'  =>  esc_attr( strip_tags( $_POST['postContent'] ) ), // ilk mesaj içeriği
                // 'post_category' =>  array(1604), // gelen kutusu ID'si
                'post_status'   => 'publish',
                'meta_input'    => array(
                    'notify'   => esc_attr( $_POST['bildirim'] ) ,
                    'userID'   => esc_attr( $_POST['currentUserID'] ),
                    'authID'   => esc_attr( $_POST['authorUserID'] )
                ),
            );
    
            /* $post_id = wp_update_post($post_information, true); */
            $post_id = wp_insert_post($post_information);
    
            // eger wp post update islemi hatali degil ise
            if ( !is_wp_error( $post_id ) ) {

                if ( kelime_sor($authorNotifications, "message#$userID")  == 1 ) { // Eğer Şuanki Kullanıcı Daha Önce Mesaj Atmış mı: 
                    // şu işlemleri yap
                } else { 
                    // Add to Notifications
                    $authorNotifications .= "message#$userID ";
                    update_user_meta( $curauthID, 'notifications', $authorNotifications );

                    // New Message Var. /////// BETA
                    update_user_meta( $curauthID, 'unread_message', true );
                    /////// BETA
                }

                wp_redirect( get_permalink( $post_id ) ); 
                exit;
    
            } else { // eger wp post update islemi hatali ise
                    echo $post_id->get_error_message();
            }
        }

    }
    // Send a Message


// My Avatar Changer by HC
if ( 
	isset( $_POST['my_avatar_upload'] ) 
	&& wp_verify_nonce( $_POST['my_avatar_upload_nonce'], 'my_avatar_upload' )
) {
	// The nonce was valid and the user has the capabilities, it is safe to continue.

	// These files need to be included as dependencies when on the front end.
	require_once( ABSPATH . 'wp-admin/includes/image.php' );
	require_once( ABSPATH . 'wp-admin/includes/file.php' );
	require_once( ABSPATH . 'wp-admin/includes/media.php' );
	
	// Let WordPress handle the upload.
	$attachment_id = media_handle_upload( 'my_avatar_upload_file', 0 );
	
	//Uploaded Infos
	$image_attributes = wp_get_attachment_image_src( $attachment_id );
	

	if ( is_wp_error( $attachment_id ) ) {
		echo "Resim yüklenirken bir hata oluştu.";
	} else {
		// User Avatar Changer
		update_user_meta( $curauthID, 'userAvatar', $image_attributes[0] );

		$successmsg = "Profil Resmi Başarılı Bir Şekilde Değiştirildi!";
        wp_redirect( $currentPage );
        exit;
	}

} else {

	// The security check failed, maybe show the user an error.
}


// My Cover Changer by HC
if ( 
	isset( $_POST['my_cover_upload'] ) 
	&& wp_verify_nonce( $_POST['my_cover_upload_nonce'], 'my_cover_upload' )
) {
	// The nonce was valid and the user has the capabilities, it is safe to continue.

	// These files need to be included as dependencies when on the front end.
	require_once( ABSPATH . 'wp-admin/includes/image.php' );
	require_once( ABSPATH . 'wp-admin/includes/file.php' );
	require_once( ABSPATH . 'wp-admin/includes/media.php' );
	
	// Let WordPress handle the upload.
	$attachment_id = media_handle_upload( 'my_cover_upload', 0 );
	
	//Uploaded Infos
	$image_attributes = wp_get_attachment_image_src( $attachment_id );
	

	if ( is_wp_error( $attachment_id ) ) {
		echo "Resim yüklenirken bir hata oluştu.";
	} else {
		// User Cover Changer
		update_user_meta( $curauthID, 'userCover', $image_attributes[0] );

		$successmsg = "Kapak Resmi Başarılı Bir Şekilde Değiştirildi!";
        wp_redirect( $currentPage );
        exit;
	}

} else {
	// The security check failed, maybe show the user an error.
}

get_header(); ?>

<!-- Content -->
<section class="mb">


<div class="profile-wrapper_del">

    <div id="profile-card__cover" class="profile-card__cover">
        <?php if($authorCover) { ?>
            <img id="author_cover" src="<?php echo esc_url( $authorCover ); ?>" alt="profile cover" />
        <?php } else { ?>
            <img id="author_cover" src="<?php echo get_template_directory_uri(); ?>/images/editoryel-head.jpg" alt="profile card" />
        <?php } ?>
        <label for="my_cover_upload">
            <svg class="icon" id="checkLocation" style="cursor: pointer;width: 30px;height: 30px;margin-top: 30px;">
                <use xlink:href="#icon-image-edit"></use>
            </svg>
        </label>
    </div>
  <div class="profile-card js-profile-card">
    <div class="profile-card__img">
        <?php if($authorAvatar) { ?>
            <img id="author_avatar" src="<?php echo esc_url( $authorAvatar ); ?>" alt="profile card" />
        <?php } else { ?>
            <img id="author_avatar" src="<?php echo get_template_directory_uri(); ?>/images/default-user-image.jpg" alt="profile card" />
        <?php } ?>

        <!-- Online ? -->
        <span data-tooltip="<?=$is_author_online?>" class="is_online <?=$is_author_online?>"> ● </span>

        <!-- Birthday ? -->
        <?php if ($birthday == $today) { echo "<div class='birthday'><svg class='icon'><use xlink:href='#icon-birthday-cake' /></svg></div>"; } ?>

        <?php if ( is_user_logged_in() && ( $curauthID == $userID ) ) { ?>
            <!-- Avatar Change ? -->
            <label for="my_avatar_upload_file">
                <svg class="icon" id="checkLocation" style="cursor: pointer;width: 30px;height: 30px;margin-top: 10px;">
                    <use xlink:href="#icon-image-edit" />
                </svg>
            </label>
        <?php } ?>
    </div>

    <div class="profile-card__cnt js-profile-cnt">
      <div class="profile-card__name" <?php if ( is_user_logged_in() && $curauthID == $userID ) { echo "contenteditable id='editableName'"; } ?>><?=$curauthName?></div>
      <div class="profile-card__txt" title="Kalın Yazmak için <b></b>" <?php if ( is_user_logged_in() && $curauthID == $userID ) { echo "contenteditable id='editableDesc'"; } ?>><?=$curauthDesc?></div>

      <div class="profile-card-loc">
        <span class="profile-card-loc__icon">
          <svg class="icon" style=" width: 1em; height: 1em; "><use xlink:href="#icon-location2"></use></svg>
        </span>

        <span class="profile-card-loc__txt">
            <span <?php if ( is_user_logged_in() && $curauthID == $userID ) { echo "contenteditable id='editableJob'"; } ?>><?=$curauthJob?></span> (<small id="kacKM"></small>)
        </span>
      </div>

      <div class="profile-card-inf">
        <div class="profile-card-inf__item">
          <div class="profile-card-inf__title"><?=$curauthFollowersCount?></div>
          <div class="profile-card-inf__txt">Takipçiler</div>
        </div>

        <div class="profile-card-inf__item">
          <div class="profile-card-inf__title"><?=$curauthFollowedCount?></div>
          <div class="profile-card-inf__txt">Takipler</div>
        </div>

        <div class="profile-card-inf__item">
          <div class="profile-card-inf__title"><?php echo count_user_posts( $curauthID ); ?></div>
          <div class="profile-card-inf__txt">Yazılar</div>
        </div>

      </div>

        <?php if ( is_user_logged_in() && ( $curauthID == $userID ) ) { // Yazar - Kendi Sayfasında Ise ?>

            <small id="infoLocation" class="mt-3 mb-3 alert alert-success" role="alert"></small>

            <div class="profile-card-ctr">
                <button id="checkLocation" class="profile-card__button button--blue js-message-btn">Konumu Güncelle</button>

                    <form method="POST" id="authorSaveForm" style="display: inline-flex;">
                    
                    <!-- Edit Name -->
                    <input type="hidden" id="hiddenName" name="hiddenName" value="">
                    <!-- Edit Description -->
                    <input type="hidden" id="hiddenDesc" name="hiddenDesc" value="">
                    <!-- Edit Job -->
                    <input type="hidden" id="hiddenJob" name="hiddenJob" value="">
                    <!-- Edit Job Title -->
                    <input type="hidden" id="hiddenJobTitle" name="hiddenJobTitle" value="">  
                    <!-- Edit Job #00 -->
                    <input type="hidden" id="hiddenJob00" name="hiddenJob00" value="">
                    <!-- Edit Job #01 -->
                    <input type="hidden" id="hiddenJob01" name="hiddenJob01" value="">
                    <!-- Edit Job #02 -->
                    <input type="hidden" id="hiddenJob02" name="hiddenJob02" value="">
                    <!-- Edit Birthday -->
                    <input type="hidden" id="hiddenBirthday" name="hiddenBirthday" value="">

                    <!-- Edit UserAvatar -->
                    <input type="hidden" name="avatar_url" value="<?=$authorAvatar?>">

                    <!-- Geolocation Infos -->
                    <input id="inputMyLat"  type="hidden" name="myLat"  value="<?=$curauthLat?>">
                    <input id="inputMyLong" type="hidden" name="myLong" value="<?=$curauthLong?>">

                        <button type="submit" class="profile-card__button button--orange" name="updateuser">Kaydet</button>

                        <?php wp_nonce_field( 'update-user' ) ?>
                    <input name="action" type="hidden" id="action" value="update-user" />
                    </form><!-- #updateuser -->

            </div>

        <?php } ?>

        <?php if ( is_user_logged_in() && $curauthID !== $userID ) { // Yazar - Kendi Sayfasında Değilse ?>

            <div class="profile-card-ctr">
                <form class="sohbet-form" method="POST">
                <?php wp_nonce_field('post_nonce', 'message_nonce_field'); ?>
                <input type="hidden" name="authorUserID"    value="<?=$curauthID?>" />

                    <button name="sendmessage" class="profile-card__button button--blue js-message-btn">Mesaj Gönder</button>
                </form>
                
                <?php if ( kelime_sor($userFollowed, "#$curauthID")  == 1 ) { // Eğer Zaten Takip Ediyorsa  ?>
                <form method="POST">
                    <?php wp_nonce_field('post_nonce', 'post_nonce_field'); ?>
                    <input type="hidden" name="unfollow" id="unfollow" value="true" />
                    <button type="submit" class="profile-card__button button--orange followed"> <span>Takip Ediliyor</span> </button>
                </form>
                <?php } else { ?>
                <form method="POST">
                    <?php wp_nonce_field('post_nonce', 'post_nonce_field'); ?>
                    <input type="hidden" name="follow" id="follow" value="true" />
                    <button type="submit" class="profile-card__button button--orange follow"> Takip Et </button>
                </form>
                <?php } ?>
            </div>

        <?php } ?>

    </div>

    <!-- Author Tabs -->
    <div class="tabs in_sidebar">
        <input type="radio" name="tabs" id="tabone" checked="checked">
        <label for="tabone" class="tab_title"><svg class="icon"><use xlink:href="#icon-text" /></svg><span>Yazılar</span></label>
        <div class="tab text-center">

            <!-- Posts -->
            <h3>Yazılar </h3>
            <ol reversed>
                <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                <li>
                    <a href="<?php the_permalink() ?>" rel="bookmark"
                        title="<?php esc_attr_e( 'Tıkla & Oku:', 'editorsel' ); ?> <?php the_title(); ?>">
                        <?php the_title(); ?></a> •
                    <?php the_time('d M Y'); ?> • <?php the_category('&');?> • okunma: <?php if(function_exists('the_views')) { the_views(); } 
                        $topokunma += get_post_meta( get_the_ID(), 'views', true );
                        ?>
                </li>
                <?php endwhile; else: ?>
                <li><?php _e( 'Henüz içerik yok.', 'editorsel' ); ?></li>
                <?php endif; ?>

        </div>

        <input type="radio" name="tabs" id="tabtwo">
        <label for="tabtwo" class="tab_title"><svg class="icon"><use xlink:href="#icon-user_contact" /></svg><span>İletişim</span></label>
        <div class="tab">
            <h3>İletişim</h3>
            <p>
                E-posta Adresi: <span>-</span> <br>
            </p>

        </div>

        <input type="radio" name="tabs" id="tabthree">
        <label for="tabthree" class="tab_title"><svg class="icon">
                <use xlink:href="#icon-setting" /></svg><span>Ayarlar</span></label>
        <div class="tab padding border-dashed">

        <!--
            <div class="work_situation">
                <span style="display: inline-flex;float: right;font-size: 1em;height: 1em;"> CV <svg class="icon" style=" width: 1em; height: 1em;"><use xlink:href="#icon-file" /></svg></span>
                <h3 <?php if ( is_user_logged_in() && $curauthID == $userID ) { echo "contenteditable id='editableJobTitle'"; } ?> ><?=$curauthJobTitle?></h3>
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>Beceri</th>
                                <th>İş Alımı</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td <?php if ( is_user_logged_in() && $curauthID == $userID ) { echo "contenteditable id='editableJob00'"; } ?>><?=$curauthJob00?></td>
                                <td><span style="color: grey">●</span></td>
                            </tr>
                            <tr>
                                <td <?php if ( is_user_logged_in() && $curauthID == $userID ) { echo "contenteditable id='editableJob01'"; } ?>><?=$curauthJob01?></td>
                                <td><span style="color: limegreen">●</span></td>
                            </tr>
                            <tr>
                                <td <?php if ( is_user_logged_in() && $curauthID == $userID ) { echo "contenteditable id='editableJob02'"; } ?>><?=$curauthJob02?></td>
                                <td><span style="color: limegreen">●</span></td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td><span style="color: limegreen">●</span> <span>Müsait</span> <span
                                        style="color: grey">●</span> <span>Müsait Değil</span></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

        -->

        </div>
    </div>
    <!-- Tabs Son -->


  </div>

</div>



</section>

<?php if ( is_user_logged_in() && ( $curauthID == $userID ) ) { ?>

    <form action="" id="cover_upload"  method="post" enctype="multipart/form-data">
        <input type="file" name="my_cover_upload" id="my_cover_upload" accept="image/*" onchange="loadFile(event, 'author_cover')" multiple="false" style="display:none">

        <input type="submit" value="Go" style="display: none;">
        <?php wp_nonce_field( 'my_cover_upload', 'my_cover_upload_nonce' ); ?>
    </form>

    <form class="author_page_pp" id="featured_upload" method="post" enctype="multipart/form-data" action="?upload=user-avatar">

        <input type="file" name="my_avatar_upload_file" id="my_avatar_upload_file" accept="image/*" onchange="loadFile(event, 'author_avatar')" multiple="false" style="display:none">

        <input type="submit" value="Go" style="display: none;">
        <?php wp_nonce_field( 'my_avatar_upload', 'my_avatar_upload_nonce' ); ?>
    </form>

    <script>

    /// Author - Save Event 
    document.getElementById('authorSaveForm').addEventListener('submit', function(evt){

        // Edit Name
        document.getElementById("hiddenName").value = document.getElementById("editableName").innerHTML;

        // Edit Description
        document.getElementById("hiddenDesc").value = document.getElementById("editableDesc").innerHTML;

        // Edit Job
        document.getElementById("hiddenJob").value = document.getElementById("editableJob").innerHTML;

        // Edit Job Title
        document.getElementById("hiddenJobTitle").value = document.getElementById("editableJobTitle").innerHTML;

        // Edit Job #00
        document.getElementById("hiddenJob00").value = document.getElementById("editableJob00").innerHTML;
        
        // Edit Job #01
        document.getElementById("hiddenJob01").value = document.getElementById("editableJob01").innerHTML;

        // Edit Job #02
        document.getElementById("hiddenJob02").value = document.getElementById("editableJob02").innerHTML;

        // Edit Birthday
        document.getElementById("hiddenBirthday").value = document.getElementById("editableBirthday").value;

    });
    /// Author - Save Event 
    </script>
<?php } ?>

<script> 
        
    jQuery(function ($) {
        
        var kacKM = getDistanceFromLatLongInKm(<?=$curauthLat?>, <?=$curauthLong?>, <?=$userLat?>, <?=$userLong?> );
        var kacKM = Math.ceil(kacKM); 
        $('#kacKM').text(kacKM + ' km uzakta!');
        
    }); 
</script>

<?php get_footer(); ?>