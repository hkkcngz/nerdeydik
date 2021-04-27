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

// Notifications 
$userNotifications  = trim( $userNotifications );

    // if notification > 0, then change svg icon (:
    if ($userNotifications) {$notificationIcon = "-has";}

    // menu names
    $page00 = "inbox";
    $page01 = "welcome";
    // $page02 = "add-all";
    $page04 = "notifications";
    $page05 = "settings";

    $body_class = ""; 
    if (kelime_sor( cookie('mode'),'dark-mode') == 1)
        $body_class = 'dark-mode';
    // if ( !is_user_logged_in() ) { $body_class .= " gradient-bg"; }
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php wp_title(''); ?><?php if(wp_title('', false)) { echo ' -'; } ?> <?php bloginfo('name'); ?></title>
    <meta name="description" content="<?php bloginfo('description'); ?>">
    <link rel="icon" href="<?php echo get_template_directory_uri(); ?>/images/favicon.png"> 

    <?php if ( is_user_logged_in() ) { ?>
        <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/css/main.css">
        <link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>?v=1.0.1" />
    <?php } else { // Offline User // ?> 
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet">
        <script src="https://kit.fontawesome.com/64d58efce2.js" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/inc/loginregister/offline.css">
    <?php } ?>

    <?php wp_head(); ?>
</head>
<body <?php body_class($body_class); ?>>

<div class="screen">

    <?php get_template_part('inc/svg'); ?>

    <?php if ( is_user_logged_in() ) { ?>

    <ul class="bottom-menu top-menu-fixed shadow">
        <li>
            <a href="<?php bloginfo('url'); ?>">
                <svg class="icon"> <use xlink:href="#icon-home" /> </svg>
                <span>Anasayfa</span>
            </a>
        </li>
        <li></li>
        <li></li>
        <li></li>
        <li>
            <a href="<?php bloginfo('url'); ?>/<?=$page05?>" class="author_photo">
                <svg class="icon"> <use xlink:href="#icon-setting" /> </svg> 
                <span> Ayarlar</span>
            </a>
        </li>
    </ul>

    
        <ul class="bottom-menu bottom-menu-fixed shadow">
            <li class="logo-place">
                <a href="<?=profile_url($userID);?>" class="author_photo">
                        <?php if($userAvatar) { ?>
                            <img src="<?php echo esc_url( $userAvatar ); ?>" alt="" />
                        <?php } else { ?>
                            <svg class="icon"> <use xlink:href="#icon-user" /> </svg> 
                        <?php } ?>
                    <span><?=$userNickname?></span>
                </a>
            </li>
            <li>
                <a href="<?php bloginfo('url'); ?>/?s=">
                    <svg class="icon"> <use xlink:href="#icon-search" /> </svg>
                    <span>Keşfet</span>
                </a>
            </li>
            <li>
                <a id="btn-add-content" href="#">
                    <svg class="icon"> <use xlink:href="#icon-plus" /> </svg>
                    <span>İçerik Ekle</span>
                </a>
            </li>
            <li>
                <a href="<?php bloginfo('url'); ?>/<?=$page04?>">
                    <svg class="icon"><use xlink:href="#icon-notification<?=$notificationIcon?>" /></svg>
                    <span>Bildirimler </span>
                </a>
            </li>
            <li>
                <a href="<?php bloginfo('url'); ?>/<?=$page00?>">
                    <svg class="icon"> <use xlink:href="#icon-messages" /> </svg>
                    <span>Mesajlar</span>
                </a>
            </li>
        </ul>
    <?php } ?>

    <main>