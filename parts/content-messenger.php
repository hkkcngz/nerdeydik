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

    $curauth = (isset($_GET['author_name'])) ? get_user_by('slug', $author_name) : get_userdata(intval($author));

?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

<?php 
    
    // Message Control
    $title = get_the_title();  
    list($msg_ID_00,$msg_ID_01,$msg_ID_02) = explode("#", $title);

    // Tüm mesajlar içinden yalnızca senin olduğun mesajları görebilesin.
    if (  is_user_logged_in() && $msg_ID_01 == $userID || $msg_ID_02 == $userID ) {  
    ?>

<div id="post-wrapper">
    <article id="post-<?php the_ID(); ?>" <?php post_class('article-item'); ?>>

        <!-- Content -->
        <section class="argue-wrapper">

            <div class="argue-message">
                <?php  
                // Degerler. 
                $post_author_id = get_post_field( 'post_author', $postID );
                $userAvatar = get_user_meta($postAuthorID,'userAvatar',true);
                ?>

 
            </div>

            <?php comments_template('/inc/tartisma-comment.php'); ?>

            <div class="message-sender">
                <img class="author_list_img"
                    src="<?php if($userAvatar) { echo $userAvatar; } else { echo $defaultThumb; } ?>" />

                <?php               
                //Array
                $comments_args = array(
                    //Define Fields
                    'fields' => array(
                        'author' => '',
                        'email' => '',
                        'url' => '',
                        'cookies' => '',
                    ),
                    'id_form' => 'message-form',
                    'class_form' => 'message-form',
                    'logged_in_as' => '',

                    'title_reply' => '',
                    'title_reply_to' => '',
                    'title_reply_before' => '<span id="reply-title" class="comment-reply-title">',
                    'title_reply_after' => '</span>',
                    'cancel_reply_link' => '',
                    'comment_field' => '<textarea id="comment" name="comment" aria-required="true" placeholder=""></textarea>',
                    'comment_notes_before' => '',
                    'comment_notes_after' => '',
                    'label_submit' => '',
                    'id_submit' => __( 'comment-submit' ),
                    'class_submit' => 'submit_msg',
                    'submit_button' => '<button type="submit" name="%1$s" id="%2$s" class="%3$s"><svg class="icon" style="height: 35px; width: 35px;"><use xlink:href="#icon-send" /></svg></button>',
                    'submit_field' => '<div class="form-submit">%1$s %2$s</div>'
                );
                comment_form( $comments_args ); ?>
            </div>


        </section>

    </article><!-- #post-<?php the_ID(); ?> -->

</div><!-- End #post-wrapper -->

<?php } else { ?>
<p>Probably, Your internet is gone.</p>
<?php } ?>


<?php endwhile; endif; ?>