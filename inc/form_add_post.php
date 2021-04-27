<?php 
/// Şu anda sayfada bulunan her kimse onun bilgilerini çeker */
global  $current_user;
        $userID = $current_user->ID;
        $userName = $current_user->display_name;
        $userLogin = $current_user->user_login;

        $userAvatar = get_user_meta($userID,'userAvatar',true);
        $userFollowers = get_user_meta($userID,'followers',true);
        $userNotifications = get_user_meta($userID,'notifications',true);
/// Şu anda sayfada bulunan her kimse onun bilgilerini çeker */

if(isset($_POST['submit_durum']) && isset($_POST['post_nonce_field']) && wp_verify_nonce($_POST['post_nonce_field'], 'post_nonce')) {
    $durum = esc_attr(strip_tags($_POST['postContent']));
    $durumTitle = substr($durum, 0, 8);

    $post_title = "#" . $userID . "-" . $userLogin . "-" . $durumTitle;


	$post_information = array(
		'post_title'    =>  "$post_title",
		'post_content'  =>  $durum,
		'post_type'     => 'durumlar',
		'post_status'   => 'publish'
	);

	/* $post_id = wp_update_post($post_information, true); */
	$post_id = wp_insert_post($post_information);
    
    /* Upload File */
    if ( $_FILES["upload_img"] ) {
        $uploaddir = wp_upload_dir();
        $file = $_FILES["upload_img"];
        $uploadfile = $uploaddir['path'] . '/' . basename( $file['name'] );
        
        move_uploaded_file( $file['tmp_name'] , $uploadfile );
        $filename = basename( $uploadfile );
        
        $wp_filetype = wp_check_filetype(basename($filename), null );
        
        $attachment = array(
            'post_mime_type' => $wp_filetype['type'],
            'post_title' => preg_replace('/\.[^.]+$/', '', $filename),
            'post_content' => '',
            'post_status' => 'inherit',
            'menu_order' => $_i + 1000
        );
        $attach_id = wp_insert_attachment( $attachment, $uploadfile );

        if ($attach_id > 0){
            //and if you want to set that image as Post  then use:
            update_post_meta($post_id,'_thumbnail_id',$attach_id);
        }
    }
    /* Upload File */

    // eger wp post update islemi hatali ise
    if ( is_wp_error( $post_id ) ) {
		echo '<script language="javascript">alert("Beklenilmeyen bir hata meydana geldi, lütfen tekrar deneyiniz."),window.location = "'.site_url().'";</script>';
    }
    else { // eger wp post update islemi hatali degil ise

        // Bildirim Gönder
        $allUserFollowers = str_replace( "#", "", $userFollowers);
        $userFollower = explode(" ", $allUserFollowers);
        foreach($userFollower as $followerID) {

            // usulünce takipçiyi bulalım.
            $follower = get_user_by('id', (int)$followerID);
            $followerID = $follower->ID;

            // Add to Notifications
            $followersNotifications  = get_user_meta($followerID,'notifications',true);
            $followersNotifications .= "newpost#$userID ";
            update_user_meta( $followerID, 'notifications', $followersNotifications );
            
        }
        echo '<script type="text/javascript">function leave() {window.location = "'.get_home_url().'";}setTimeout("leave()", 0); </script>';
       
    }
}

?>
<!-- Post Status -->
<form method="POST" class="add-content mt" enctype="multipart/form-data">
    <img id="post_img" alt="">
    <div class="add-content-top">
        <div class="author_photo">

        <?php if($userAvatar) { ?>
            <img src="<?php echo esc_url( $userAvatar ); ?>" alt="" />
        <?php } else { ?>
            <img src="<?php bloginfo('template_directory'); ?>/images/default-user-image.jpg" alt="varsayılan avatar"/>
        <?php } ?>

        </div>
        <div class="add-content-input">
            <textarea class="test-emoji" name="postContent" placeholder="Ne düşünüyorsun?"></textarea>
        </div>

    </div>
    <ul class="add-content-bottom">
            <li style="position: relative;">
                <span id="emoji-picker" class="durum-link" data-tooltip="Emoji">
                    <svg class="icon"><use xlink:href="#icon-smile" /></svg>
                </span>
                <span id="nereye" class="intercom-composer-popover intercom-composer-emoji-popover">
                	<!-- Emoji Picker Settings -->
                    <?php get_template_part('inc/emojipicker'); ?>
	                <!-- Emoji Picker Settings End -->
                </span>
                
                <label for="upload_img" data-tooltip="Resim / Video Ekle" class="durum-link custom-file-input">  <svg class="icon"><use xlink:href="#icon-picture" /></svg> </label>
                <input type="file" class="hidden" name="upload_img" id="upload_img" onchange="loadFile(event, 'post_img')" accept="video/*,image/*">
            </li>

            <li>
                <a class="durum-link privacy" href="#" data-tooltip="Gizlilik"> <svg class="icon"><use xlink:href="#icon-privacy" /></svg></a>

                <?php wp_nonce_field('post_nonce', 'post_nonce_field'); ?>
                <input type="hidden" name="submit_durum" id="submit_durum" value="true" />
                <button type="submit" class="btn btn__secondary">Paylaş</button>
            </li>
        </ul>
</form>

<hr class="empty">