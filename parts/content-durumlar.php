<?php 

/// Şu anda sayfada bulunan her kimse onun bilgilerini çeker */
global  $post, $current_user;
        $userID = $current_user->ID;
        // Post Info
		$postID 	= get_the_ID();
        $post_info 	= get_post( $postID );
        // Author Info
		// $author_ID 	= get_post_field( 'post_author', $postID );
        $postAuthorID = $post_info->post_author;	
        $authorLogin = get_the_author_meta( 'user_login' , $postAuthorID );
        $authorNickname = get_the_author_meta( 'nickname' , $postAuthorID );
/// Şu anda sayfada bulunan her kimse onun bilgilerini çeker */
    $userAvatar = get_user_meta($postAuthorID,'userAvatar',true);
    $defaultThumb = get_template_directory_uri() . "/images/default-user-image.jpg";

    // Profile URL.
    $authorProfile = "";
    $author_base = get_option( 'hc_author_base' );
    if ( empty($author_base) ) {
        $author_base = "author";
    }
    $authorProfile = get_site_url() . "/". $author_base ."/" . $authorLogin;

    $postThumbnail = get_the_post_thumbnail_url( get_the_ID() );
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('article-item '); ?>>

    <a href="<?=$authorProfile?>" class="area_post_author author_photo"> 
        <span>
            <?php
            /* don’t retrieve time as WP timestamp */
            printf(
                /* translators: time */
                esc_html__( '%s önce', 'textdomain' ),
                human_time_diff( get_the_modified_time( 'U' ), strtotime( wp_date( 'Y-m-d H:i:s' ) ) )
            );
            ?>
        </span>
        <div style=" line-height: 50px; ">
            <img class="argue-image argue-image-default" src="<?php if($userAvatar) { echo $userAvatar; } else { echo $defaultThumb; } ?>" width="36" height="36" />&nbsp;&nbsp; <?=$authorNickname?>
        </div>
    </a>

    <a class="image status" href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>">
        <?php 
        if ( $postThumbnail ) { ?>
            <img src="<?php echo get_the_post_thumbnail_url(); ?>" class="attachment-post-thumbnail size-post-thumbnail wp-post-image" alt="<?php the_title_attribute(); ?>" loading="lazy">
        <?php }
        the_excerpt(); ?>
    </a>

    <?php 
        get_template_part( 'parts/ul_actions' );
    ?>

</article><!-- #post-<?php the_ID(); ?> -->