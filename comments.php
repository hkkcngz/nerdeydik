<?php


global $current_user;
    $user = get_currentuserinfo();
    $userID = $current_user->ID;

    $userAvatar = get_user_meta($userID,'userAvatar',true);
    $defaultThumb = get_template_directory_uri() . "/images/default-user-image.jpg";

if ( post_password_required() )
    return;
?>
<ul class="comments">
    <?php if ( have_comments() ) : ?>
		
			<?php
				wp_list_comments( array(
					'callback'  => 'better_comments',
					'style'     => 'ul'
				) );
            ?>
            
    <?php else : // or, if we don't have comments: ?>
		
    <?php endif; // have_comments() ?>

        <?php if ( ! comments_open() ) { ?>
            <li><?php _e( 'Yorumlar kapalı.', 'editorsel' ); ?></li>
        <?php } // end ! comments_open()
        else { ?>
            <li class="comment-block">
                <img class="author_list_img" src="<?php if($userAvatar) { echo $userAvatar; } else { echo $defaultThumb; } ?>" />
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
                        'id_form' => 'post-form',
                        'class_form' => 'post-form',
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
                        'class_submit' => 'submit_comment',
                        'submit_button' => '<button type="submit" name="%1$s" id="%2$s" class="%3$s"><svg class="icon" style="height: 35px; width: 35px;"><use xlink:href="#icon-send" /></svg></button>',
                        'submit_field' => '<div class="form-submit">%1$s %2$s</div>'
                    );
                    comment_form( $comments_args );
                ?>
            </li>
        <?php } ?>	
    
</ul><!-- #comments -->