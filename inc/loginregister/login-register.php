<?php if( !get_option('users_can_register') ) {
  $regMsj = "Üye kayıtları durdurulmuştur. Lütfen daha sonra deneyiniz.";
  $disabled = "disabled";
}
?>
  <div class="container">
    <div class="forms-container">
      <div class="signin-signup">
        <form action="<?php bloginfo('url') ?>/wp-login.php"  method="POST" class="sign-in-form">
          <h2 class="title">Giriş yap</h2>
          <div class="input-field">
            <i class="fas fa-user"></i>
            <input type="text" name="log" placeholder="Kullanıcı adı" />
          </div>
          <div class="input-field">
            <i class="fas fa-lock"></i>
            <input type="password" name="pwd" placeholder="Şifre" />
          </div>
            <?php do_action('login_form'); ?>
            <input type="hidden" name="redirect_to" value="<?php echo esc_attr($_SERVER['REQUEST_URI']); ?>" />
					  <input type="hidden" name="user-cookie" value="1" />

            <!-- Errors & Success Messages -->    
            <?php 
            // Kayıtta bi hata varsa:
            if(defined('REGISTRATION_ERROR')) {
              foreach(unserialize(REGISTRATION_ERROR) as $error)
              $msg = "Hata! {$error}";
            } 
            // Kayıt Başarılı ise
            elseif (defined('REGISTERED_A_USER')) 
              $msg = "Başarılı! = Şifreniz <b>".REGISTERED_A_USER."</b> olarak oluşturulmuştur.";
 
              $register = $_GET['register']; 
              $reset = $_GET['reset']; 
              
            if ($register == true) { 
              $msg = "Başarılı! Şifre için e-postanızı kontrol edin ve tekrar giriş yapın.";

            } elseif ($reset == true) {
              $msg = "Başarılı! Şifreyi sıfırlamak için epostanızı kontrol edin.";
            }
            
            if( isset( $_GET['login_error'] ) && $_GET['login_error'] == '1' ) {
              $msg = "Böyle bir kullanıcı adı yok, Lütfen tekrar deneyiniz.";
            } else if( isset( $_GET['login_error'] ) && $_GET['login_error'] == '2' ) {
              $msg = "Kullanıcı adı veya şifre hatalı. Lütfen kontrol ediniz.";
            } ?>
            <p style=" font-size: 15px; color: #ff8490; font-weight: 600; "><?=$msg?></p>
            <!-- Errors & Success Messages -->  

          <input type="submit" name="user-submit" value="Login" class="btn solid" />
          <p class="social-text">Ya da sosyal platformlar ile bağlan</p>
          <div class="social-media">
            <a href="#" class="social-icon">
              <i class="fab fa-facebook-f"></i>
            </a>
            <a href="#" class="social-icon">
              <i class="fab fa-twitter"></i>
            </a>
            <a href="#" class="social-icon">
              <i class="fab fa-google"></i>
            </a>
            <a href="#" class="social-icon">
              <i class="fab fa-linkedin-in"></i>
            </a>
          </div>
        </form>
        
        <form action="<?php bloginfo('url'); ?>/?reg=new-user" method="POST" class="sign-up-form">
          <h2 class="title">Kayıt ol</h2>
          <div class="input-field">
            <i class="fas fa-user"></i>
            <input type="text" name="user" placeholder="Kullanıcı adı" <?=$disabled?> />
          </div>
          <div class="input-field">
            <i class="fas fa-envelope"></i>
            <input type="email" name="email" placeholder="Email" <?=$disabled?> />
          </div>
          <div class="input-field">
            <i class="fas fa-lock"></i>
            <input type="password" name="pass" placeholder="Şifre" <?=$disabled?> />
          </div>
          <input type="submit" class="btn" value="Kaydol" />

          <p style=" font-size: 15px; color: #ff8490; font-weight: 600; "><?=$regMsj?></p>
          <p class="social-text">Ya da sosyal platformlar ile kaydol</p>
          <div class="social-media">
            <a href="#" class="social-icon">
              <i class="fab fa-facebook-f"></i>
            </a>
            <a href="#" class="social-icon">
              <i class="fab fa-twitter"></i>
            </a>
            <a href="#" class="social-icon">
              <i class="fab fa-google"></i>
            </a>
            <a href="#" class="social-icon">
              <i class="fab fa-linkedin-in"></i>
            </a>
          </div>
        </form>
      </div>
    </div>

    <div class="panels-container">
      <div class="panel left-panel">
        <div class="content">
          <h3>Buralarda yeni misin ?</h3>
          <p>
            O zaman sana etrafı göstermeden önce kayıt olmaya ne dersin?
          </p>
          <button class="btn transparent" id="sign-up-btn">
            Kayıt Ol
          </button>
        </div>
        <img src="<?php echo get_template_directory_uri(); ?>/inc/loginregister/img/reg.png" class="image" alt="" />
      </div>
      <div class="panel right-panel">
        <div class="content">
          <h3>Zaten bizden biri misin ?</h3>
          <p>
            O halde daha da dışarda kalmadan hemen kapıdan içeri girelim.
          </p>
          <button class="btn transparent" id="sign-in-btn">
            Giriş yap
          </button>
        </div>
        <img src="<?php echo get_template_directory_uri(); ?>/inc/loginregister/img/log.png" class="image" alt="" />
      </div>
    </div>
  </div>