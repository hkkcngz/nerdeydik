<?php 
/* Template Name: Add Post Ready Page */

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

<div class="page mt">
  <?php if ( have_posts() ) {

      // Load posts loop.
      while ( have_posts() ) {
        the_post();
          the_content();
      }
  } ?>


  <div class="post-add-contents">
  
    <a href="#" class="post-add-item">
        <svg class="icon">
            <use xlink:href="#icon-megaphone"></use>
        </svg>
        <h3>Durum Paylaşımı</h3>
        <p>İçini Dökmek İstersen...</p>
    </a>

    <a href="#" class="post-add-item">
        <svg class="icon">
            <use xlink:href="#icon-text"></use>
        </svg>
        <h3>Standart Post Paylaşımı</h3>
        <p>Normal Yazı Paylaşımı, Hikaye, Deneme, Makale, Şiir...</p>
    </a>
    <a href="#" class="post-add-item">
        <svg class="icon">
            <use xlink:href="#icon-bookmark"></use>
        </svg>
        <h3>Yer İmleri Paylaşımı</h3>
        <p>Hoşunuza giden website bağlantılarını veya notlar..</p>
    </a>
    <a href="#" class="post-add-item">
        <svg class="icon">
            <use xlink:href="#icon-list"></use>
        </svg>
        <h3>Youtube Liste Paylaşımı</h3>
        <p>Youtube Linkleri ile kendi playlistinizi oluşturun.</p>
    </a>

    <a href="#" class="post-add-item">
        <svg class="icon">
                <use xlink:href="#icon-post-standard"></use>
        </svg>
        <h3>Foto İçerik Paylaşımı</h3>
        <p>Öğretici ve Eğitici içerikler..</p>
    </a>

    <a href="#" class="post-add-item">
        <svg class="icon">
                <use xlink:href="#icon-posts"></use>
        </svg>
        <h3>Eğitici İçerik Paylaşımı</h3>
        <p>Öğretici ve Eğitici içerikler..</p>
    </a>

  </div>


</div>

<?php get_footer(); ?>