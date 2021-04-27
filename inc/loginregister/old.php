	<!-- Banner -->
    <section class="row white">
		<div class="col pad">
				<header>
					<h1><?php _e( "Selam, Çay Arası'na <br />hoşgeldiniz", 'editorsel' ); ?></h1>
					<p><svg class="icon"><use xlink:href="#icon-quote-left" /></svg> <?php _e( 'Bİ DOLU MUHABBET ORTAMI!', 'editorsel' ); ?> <svg class="icon"><use xlink:href="#icon-quote-right" /></svg></p>
				</header>
				<p><?php _e( 'Esasında bi çay masasında ettiğimiz muhabbeti taşıdığımız mekan. Notlarımıza kaydettiğimiz makaleleri, bazen gerçekleri (tarih, gündem) bazen de denemeleri, şiirleri, kurgu yazılarımızı paylaşıp tartıştığımız platform. Yazılarımız telif hakkına tabidir. İzinsiz kopyalanamaz.', 'editorsel' ); ?> </p><br />

		</div>
		<div class="col image object">
    <form method="post" action="<?php bloginfo('url')?>/wp-login.php" class="form-standart">

      <div class="form-group">
          <label for="user_login"
              class="control-label col-lg-2"><?php _e('Kullanıcı adı: ', 'editorsel');?></label>
          <input class="form__input" type="text" name="log" value="<?php echo esc_attr(stripslashes($user_login)); ?>"
              size="20" id="user_login" tabindex="11" />
      </div>

      <div class="form-group">
          <label for="user_pass"
              class="control-label col-lg-2"><?php _e('Şifre: ', 'editorsel');?></label>
          <input class="form__input" type="password" name="pwd" value="" size="20" id="user_pass" tabindex="12" />
      </div>

      <div class="form-group">
          <?php do_action('login_form');?>
          <button class="btn btn__primary" type="submit" name="user-submit" style=" width: 100%; "><?php esc_attr_e('Giriş Yap', 'editorsel');?></button>
          <input type="hidden" name="redirect_to"
              value="<?php echo esc_attr($_SERVER['REQUEST_URI']); ?>" />
          <input type="hidden" name="user-cookie" value="1" />
      </div>

      <hr>

      <a href="" class="btn btn__primary" style=" width: fit-content; display: block; margin: 0 auto; "> Kayıt Ol </a>
    </form>

    

		</div>
	</section>