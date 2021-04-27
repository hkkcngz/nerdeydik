<?php
/* Template Name: Panel Sayfası */

/// Şu anda sayfada bulunan her kimse onun bilgilerini çeker */
global $current_user, $wp_roles, $user_ID, $user_identity;
$user = get_currentuserinfo();
$userID = $current_user->ID;
$userName = $current_user->display_name;
$userLogin = $current_user->user_login;
$userProfile = get_site_url() . "/author/" . $userLogin;

$userAvatar = get_user_meta($userID, 'userAvatar', true);
$defaultThumb = get_template_directory_uri() . "/images/default-user-image.jpg";

$userFollowers = get_user_meta($userID, 'followers', true);
$userBildirimler = get_user_meta($userID, 'bildirimler', true);
/// Şu anda sayfada bulunan her kimse onun bilgilerini çeker */

// Register Codes
$register   = $_GET['register'];
$reset      = $_GET['reset'];

get_header(); ?>
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
<section class="text-center">

    <?php if (!$userID) { ?>
    <div id="login-register-password">

        <?php if ($register == true) { ?>

        <h3><?php _e('Başarılı!', 'editorsel');?></h3>

        <?php } elseif ($reset == true) { ?>

        <h3><?php _e('Başarılı!', 'editorsel');?></h3>
        <p><?php _e('Şifreyi sıfırlamak için epostanızı kontrol edin.', 'editorsel');?></p>

        <?php } ?>

        <div class="wp_login_error">
            <?php if (isset($_GET['login_error']) && $_GET['login_error'] == '1') { ?>
            <p><?php _e('Böyle bir kullanıcı adı yok, Lütfen tekrar deneyiniz.', 'editorsel');?></p>
            <?php } else if (isset($_GET['login_error']) && $_GET['login_error'] == '2') {?>
            <p><?php _e('Kullanıcı adı veya şifre hatalı. Lütfen kontrol ediniz.', 'editorsel');?></p>
            <?php } ?>
        </div>
        <!-- Author Tabs -->
        <div class="tabs in_sidebar">
            <input type="radio" name="tabs" id="tabone" checked="checked">
            <label for="tabone" class="tab_title">
                <svg class="icon">
                    <use xlink:href="#icon-idcard" /></svg>
                <span>Giriş</span>
            </label>
            <div class="tab text-center">

                <?php 
                echo $errorMessage;
                if (defined('REGISTRATION_ERROR')) {
                    foreach (unserialize(REGISTRATION_ERROR) as $error) {
                        echo "<script>$(\"#UserKayit\").show();</script><div class=\"HataLi\"><div class=\"alert alert-error\"><button data-dismiss=\"alert\" class=\"btn close\">×</button><strong>Hata!</strong>{$error}</div></div>";
                    }
                } elseif (defined('REGISTERED_A_USER')) {
                    echo "<div class=\"alert alert-success\"><button data-dismiss=\"alert\" class=\"close\">×</button><strong>Başarılı! = </strong><script>$(\"#UserKayit\").show();</script>Şifreniz <b>" . REGISTERED_A_USER . "</b> olarak oluşturulmuştur. </div>";
                }
            ?>

                <h3>Giriş</h3>
                <form method="post" action="<?php bloginfo('url')?>/wp-login.php" class="form-standart">

                    <div class="form-group">
                        <label for="user_login"
                            class="control-label col-lg-2"><?php _e('Kullanıcı adı: ', 'editorsel');?></label>
                        <input class="form__input" type="text" name="log"
                            value="<?php echo esc_attr(stripslashes($user_login)); ?>" size="20" id="user_login"
                            tabindex="11" />
                    </div>

                    <div class="form-group">
                        <label for="user_pass"
                            class="control-label col-lg-2"><?php _e('Şifre: ', 'editorsel');?></label>
                        <input class="form__input" type="password" name="pwd" value="" size="20" id="user_pass"
                            tabindex="12" />
                    </div>

                    <div class="form-group">
                        <?php do_action('login_form');?>
                        <button class="btn btn__primary" type="submit"
                            name="user-submit"><?php esc_attr_e('Giriş Yap', 'editorsel');?></button>
                        <input type="hidden" name="redirect_to"
                            value="<?php echo esc_attr($_SERVER['REQUEST_URI']); ?>" />
                        <input type="hidden" name="user-cookie" value="1" />
                    </div>

                </form>
            </div>
            <input type="radio" name="tabs" id="tabtwo">
            <label for="tabtwo" class="tab_title"><svg class="icon">
                    <use xlink:href="#icon-posts" /></svg><span>Kaydol</span></label>
            <div class="tab">

                <h3><?php _e('Çay Arasına Kaydol!', 'editorsel');?></h3>
                <p><?php _e('Sende çay masasındaki muhabbete katıl (:', 'editorsel');?></p><br />
                <?php if (get_option('users_can_register')) {?>
                <form class="form-standart" id="commentForm" method="post" action="?kayit=user-reg">
                    <div class="form-group">
                        <label for="cname"
                            class="control-label col-lg-2"><?php _e('Kullanıcı Adı', 'editorsel');?></label>
                        <input class="form__input" id="cname" name="user" type="text" required />
                    </div>

                    <div class="form-group">
                        <label for="cesifre" class="control-label col-lg-2"><?php _e('Şifre', 'editorsel');?></label>
                        <input class="form__input" id="cesifre" name="pass" type="password" required />
                    </div>

                    <div class="form-group">
                        <label for="cemail" class="control-label col-lg-2"><?php _e('Email', 'editorsel');?></label>
                        <input class="form__input" id="cemail" name="email" type="text" required />
                    </div>

                    <div class="form-group">
                        <label for="cespam"
                            class="control-label col-lg-2"><?php _e('Spam Kontrol', 'editorsel');?></label>
                        <input class="form__input" id="cespam" name="spam" type="text"
                            value="<?php esc_attr_e('Üye olurken siliniz', 'editorsel');?>" />
                    </div>

                    <div class="form-group">
                        <button class="btn btn__primary"
                            type="submit"><?php esc_attr_e('Kayıt Ol', 'editorsel');?></button>
                    </div>
                </form>

                <?php } else {
                                    echo "<div class=\"alert alert-error\"><button data-dismiss=\"alert\" class=\"close\">×</button><strong>Hata!</strong>Üye kayıtları durdurulmuştur. Lütfen daha sonra deneyiniz.</div>";
                                }
                                ?>

            </div>
            <input type="radio" name="tabs" id="tabthree">
            <label for="tabthree" class="tab_title"><svg class="icon">
                    <use xlink:href="#icon-settings" /></svg><span>Şifremi Unuttum</span></label>
            <div class="tab padding">
                <h3><?php _e('Bir şey mi kaybettiniz?', 'editorsel');?></h3>
                <p><?php _e('Giriş yapmada problem yaşıyorsanız e-postanız ile sıfırlayabilirsiniz.', 'editorsel');?>
                </p><br />
                <form method="post" class="form-standart"
                    action="<?php echo site_url('wp-login.php?action=lostpassword', 'login_post') ?>">

                    <div class="form-group">
                        <label for="sueposta"
                            class="control-label col-lg-2"><?php _e('E-posta Adresi:', 'editorsel');?></label>
                        <input class="form__input" type="text" name="user_login" value="" size="20" id="sueposta"
                            tabindex="1001" />
                    </div>
                    <div class="form-group">
                        <?php do_action('login_form', 'resetpass');?>
                        <button class="btn btn__primary" type="submit"
                            name="user-submit"><?php esc_attr_e('Şifremi Sıfırla', 'editorsel');?></button>
                        <?php $reset = $_GET['reset'];if ($reset == true) {echo '<p>Eposta adresinize bir posta gönderilecektir.</p>';}?>
                        <input type="hidden" name="redirect_to"
                            value="<?php echo esc_attr($_SERVER['REQUEST_URI']); ?>?reset=true" />
                        <input type="hidden" name="user-cookie" value="1" />
                    </div>
                </form>
            </div>

        </div>
    </div>

    <?php } else { // is logged in ?>

    <div class="author_header">
        <h3><small><?php _e('Profil Ayarları', 'editorsel');?></small><br /><?php _e('Hoşgeldin', 'editorsel');?>
            <?php echo $user_identity;
                    $userAvatar = get_user_meta($user_id, 'userAvatar', true); ?>,</h3>

        <div class="hc-author_photo text-center">
            <?php if ($userAvatar) {?>
            <img src="<?php echo esc_url($userAvatar); ?>" style="width: 200px; height: 200px; object-fit: cover;" />
            <?php } else {?>
            <img src="<?php bloginfo('template_directory');?>/images/default-user-image.jpg" alt="varsayılan avatar"
                class="img-thumbnail mx-auto d-block" style="width: 150px;height: 150px;object-fit: cover;" />
            <?php }?>
        </div>

        <ul class="actions text-center justify-center">
            <li><a href="<?php bloginfo('url');?>/hizli-yazi-ekle/"
                    title="<?php esc_attr_e('Hızlı Yazı Ekle', 'editorsel');?>"
                    class="btn btn__secondary"><?php _e('Hızlı Yazı Ekle', 'editorsel');?></a></li>
            <li><a href="<?php bloginfo('url'); echo '/yazar/' . $current_user->user_login . "\n";?>"
                    title="<?php esc_attr_e('Profili gör', 'editorsel');?>"
                    class="btn btn__secondary"><?php _e('Profili gör', 'editorsel');?></a></li>
            <li><a href="<?php bloginfo('url');?>/profil-edit/"
                    title="<?php esc_attr_e('Profili düzenle', 'editorsel');?>"
                    class="btn btn__secondary"><?php _e('Profili düzenle', 'editorsel');?></a></li>
            <li><a href="<?php echo wp_logout_url('index.php'); ?>" title="<?php esc_attr_e('Çıkış', 'editorsel');?>"
                    class="btn btn__secondary"><?php _e('Çıkış', 'editorsel');?></a></li>
        </ul>
    </div>

    <?php } //end ?>

    </div>

</section>

<?php get_footer(); ?>