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
        $postAuthorName = $curauth->nickname;

        $authorLogin = get_the_author_meta( 'user_login' , $postAuthorID );
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

?>
<article id="post-<?php the_ID(); ?>" <?php post_class('article-item'); ?>>


    <div class="single-post-header">
        <div class="feature-image"
            style="background-image: url('<?php if ( has_post_thumbnail() ) { the_post_thumbnail_url(); } else { bloginfo('template_directory');echo '/images/editoryel-head.jpg';  } ?>')">
        </div>
        <div class="feature-content">
            <div class="category">
                <?php the_category(''); ?>
            </div>
            <h1><?php the_title(); ?></h1>
            <div class="author-box">
                <div class="desc"><?php the_excerpt(); ?></div>
                <div class="author-content">
                    <a href="<?=$authorProfile?>" class="single_post_author author_photo">
                        <div class="author-portrait" style="background-image: url(<?php if($userAvatar) { echo $userAvatar; } else { echo $defaultThumb; } ?>);"></div>
                    </a>
                    <div class="author-info">
                        <span class="author-name"><span class="by"></span><?=$postAuthorName?></span>
                        <ul>
                            <li><a href="<?=$authorProfile?>"
                                    title="Hakkı Cengiz Yazar sayfası">Bio</a>
                            </li>
                            <li><a href="https://twitter.com/hakkicngz" title="Hakkı Cengiz Twitter sayfası">Twitter</a>
                            </li>
                            <li><a href="https://hakkicengiz.com/author/hkkcngz"
                                    title="Hakkı Cengiz Arşiv sayfası">Archive</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="single-content">
        <?php the_content(); ?>
    </div>

    <div class="content-info">
        <?php 
            $tags = get_tags();			
            $no_of_tags = count($tags);
            if (!$no_of_tags == 0) {
                the_tags('<div class="post_tags">' , '&nbsp;' , '</div>' ); 
        } ?>
        <ul class="actions">
            <li><?php if ( function_exists( 'get_simple_likes_button' ) ) {echo get_simple_likes_button( get_the_ID() ); } ?>
            </li>
            <li><a href="<?php the_permalink(); ?>/#respond" rel="bookmark"><svg class="icon">
                        <use xlink:href="#icon-comment" /></svg> <?php comments_number( '0', '1', '%' ); ?></a></li>
            <li class="more-item">
                <svg class="icon">
                    <use xlink:href="#icon-three-dots" /></svg>
            </li>
            <li>
                <a href="whatsapp://send?text=<?php echo esc_url( get_permalink() ); ?> - Bu yazıyı okumuş muydun?"
                    target="_blank">
                    <svg class="icon">
                        <use xlink:href="#icon-whatsapp" /></svg>
                </a>
            </li>
            <li>
                <a href="https://twitter.com/intent/tweet?text=<?php echo urlencode(get_the_title()); ?>+ echo get_permalink(); ?>"
                    target="_blank">
                    <svg class="icon">
                        <use xlink:href="#icon-twitter" /></svg>
                </a>
            </li>
            <li>
                <a href="http://www.facebook.com/sharer/sharer.php?s=100&p[url]=<?php echo urlencode(get_permalink()); ?>"
                    target="_blank">
                    <svg class="icon">
                        <use xlink:href="#icon-facebook" /></svg>
                </a>
            </li>
            <li>
                <a href="mailto:?subject=Nerdeydik:<?php echo urlencode(get_the_title()); ?>&amp;body=Bu yazıyı bir oku derim  echo get_permalink(); ?>."
                    title="E-mail ile paylaş" target="_blank">
                    <svg class="icon">
                        <use xlink:href="#icon-mail" /></svg>
                </a>
            </li>
            <li>
                <?php if(function_exists('the_views')) { echo '<svg class="icon"><use xlink:href="#icon-view" /></svg>'; the_views(); } ?>
            </li>
            <?php if ( $postAuthorID == $userID ) : ?>
            <li>
                <a onclick="return confirm('Silmek istediğinize emin misiniz?');" href="<?php echo get_delete_post_link($postID) ?>" title="Sil">
                    <svg class="icon">
                        <use xlink:href="#icon-delete" /></svg>
                </a>
            </li>
            <?php endif; ?>
        </ul>
    </div>

    <?php 
        if ( comments_open() || get_comments_number() ) :
            comments_template();
        endif; 
    ?>

</article><!-- #post-<?php the_ID(); ?> -->