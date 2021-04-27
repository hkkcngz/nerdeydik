<?php
/* Template Name: Inbox Page */

/// Şu anda sayfada bulunan her kimse onun bilgilerini çeker */
global  $post, $current_user;
        $userID = $current_user->ID;

        // Author Info
		// $author_ID 	= get_post_field( 'post_author', $postID );
        $postAuthorID = $post_info->post_author;	
        $authorLogin = get_the_author_meta( 'user_login' , $postAuthorID );
/// Şu anda sayfada bulunan her kimse onun bilgilerini çeker */
    $defaultThumb = get_template_directory_uri() . "/images/default-user-image.jpg";

get_header();  ?>

    <ul class="inbox-messages">
        <?php 
            if ( is_user_logged_in() ) {
            /**
             * Setup query to show the 'messenger' post type.
             * Output is linked title with user avatar image and excerpt.
             */
            
            $msgCount = 0;

            $args = array(  
                'post_type' => 'messenger',
                'posts_per_page' => -1, 
            );

            $messages = new WP_Query( $args ); 
                
            while ( $messages->have_posts() ) : $messages->the_post(); 

                // Message Control
                $title = get_the_title();  
                list($msg_ID_00,$msg_ID_01,$msg_ID_02) = explode("#", $title);

                // Tüm mesajlar içinden yalnızca senin olduğun mesajları görebilesin.
                if ( $msg_ID_01 == $userID || $msg_ID_02 == $userID ) {  

                    // Finding Current User
                    if ( $msg_ID_01 == $userID ) {
                        $currentUserID = $msg_ID_01;
                    } else if ( $msg_ID_02 == $userID ) {
                        $currentUserID = $msg_ID_02;
                    } else {
                        $currentUserID = "0";
                    }

                    // Finding WhoSentMsg User
                    if ( $msg_ID_01 != $userID ) {
                        $WhoSentMsg = $msg_ID_01;
                    } else if ( $msg_ID_02 != $userID ) {
                        $WhoSentMsg = $msg_ID_02;
                    } else {
                        $WhoSentMsg = "0";
                    }
                    
                    // Current User
                    $currentUser = get_user_by('id', $currentUserID);
                    $userAvatar = get_user_meta($currentUserID,'userAvatar',true);

                    // The Other User Who Sent Message
                    $otherUser = get_user_by('id', $WhoSentMsg);
                    $whoSentMsgAvatar = get_user_meta($WhoSentMsg,'userAvatar',true);
                    // Gelen Kutusu Görünümü Kat

                    $msgCount++;
                    ?>

                    <li class="msg-<?php the_ID(); ?>">
                        <a href="<?php the_permalink(); ?>" title="<?php echo get_comment_author(); ?>" class="message-item">
                            <img class="author_list_img" src="<?php if ($whoSentMsgAvatar) {echo esc_url($whoSentMsgAvatar);} else {echo $defaultThumb; }?>" alt="<?php echo $otherUser->display_name; ?>"  />
                            <div class="message-info">
                                <h3><?php echo $otherUser->display_name; ?></h3>
                                <?php 
                                    $args = array(
                                            'number' => '1',
                                            'post_id' => $post->ID
                                    );
                                    $comments = get_comments($args);
                                    foreach($comments as $comment) :
                                        echo $comment->comment_content . " ∙ ";
                                        printf( _x( '%s önce', '%s = human-readable time difference', 'editorsel'), human_time_diff( get_comment_time( 'U' ), current_time( 'timestamp' ) ) ); 
                                    endforeach;
                                ?>
                            </div>
                        </a>
                    </li>

                <?php }

            endwhile;

            wp_reset_postdata(); 

        } else {
           // Home Redirect. 
        }

        
        ?>


    </ul>

<?php get_footer(); ?>