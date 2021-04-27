<?php
/*
Name:  WordPress Post Form System
Description:  A simple and efficient frontend post submit system for WordPress.
Version:      0.7.0
Author:       Hakki Cengiz
Author URI:   http://hakkicengiz.com/
License:
Copyright (C) 2020 Hakkı Cengiz
Form Types: Status, Standart, Bookmarks, Youtube Lists, Photos, Education.
This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/


// Standart Post Form


	// Shortcode #1 - Form Standart
	function form_standart_shortcode() {
		ob_start();

		$categories = get_categories(
			array( 
				'parent'     => 152,
				'hide_empty' => false, 
			)
		);

		if(isset($_POST['submitted']) && isset($_POST['post_nonce_field']) && wp_verify_nonce($_POST['post_nonce_field'], 'post_nonce')) {

			global $post;
			$post_complete = get_post($postid);
			$title = $post_complete->post_title;
			$content = $post_complete->post_content;
			
			$isimSoyisimError = '';
		
			if(trim($_POST['isimSoyisim']) === '') {
                $isimSoyisimError = __( 'Lütfen bir başlık giriniz.');
                $hasError = true;
			} else {
			    $isimSoyisim = trim($_POST['isimSoyisim']);
			}
		
			$POSTaracPlaka = esc_attr( $_POST['aracPlaka'] );
			$aracPlaka = str_replace(' ', '', $POSTaracPlaka);
			$POSTcepNo = esc_attr( $_POST['cepNo'] );
			$cepNo = str_replace(' ', '', $POSTcepNo);
		
			$istSehir  = esc_attr( $_POST['istasyonSec'] );
			list($istAdi,$istSehirID,$istSehirAdi) = explode(":", $istSehir);
		

			$redirect = site_url();

			$post_information = array(
			/* 'ID' => $post->ID, */
			'post_title' 	=> esc_attr(strip_tags($_POST['isimSoyisim'])),
			'post_content'  => esc_attr(strip_tags($_POST['postContent'])),
			'post_type' 	=> 'randevu',
			'post_status' 	=> 'publish',
			'meta_input'   => array(
				'aracPlaka'   => $aracPlaka,
				'plakaSeriNo' => esc_attr( $_POST['plakaSeriNo'] ),
				'cepNo'       => $cepNo,
				'aracCinsi'   => esc_attr( $_POST['aracCinsi'] ),
				'muayeneTipi' => esc_attr( $_POST['muayeneTipi'] ),
				'sehirID'     => $istSehirID,
				'sehirAdi'    => $istSehirAdi,
				'istasyonSec' => $istAdi,
				'randevuTarih'=> esc_attr( $_POST['randevuTarih'] ),
				'randevuSaat' => esc_attr( $_POST['randevuSaat'] ),
				'odemeDurumu' => $order_id,
				),
			);
		
			/* $post_id = wp_update_post($post_information, true); */
			$post_id = wp_insert_post($post_information);
		

			// eger wp post update islemi hatali ise
			if ( is_wp_error( $post_id ) ) {
			echo '<script language="javascript">
			Swal.fire({
				icon: "error",
				title: "Hata...",
				text: "Beklenilmeyen bir hata meydana geldi, lütfen tekrar deneyiniz.",
			}),window.location = "'.site_url().'";</script>';
			}
			else { // eger wp post update islemi hatali degil ise
				echo '<script type="text/javascript">function leave() {
					Swal.fire(
						"Başarılı!",
						"Yönlendiriliyorsunuz!",
						"success"
					),
					window.location = "'.$redirect.'"; }
					setTimeout("leave()", 500); </script>';
			}
		}

		?>
		
		<marquee>Araç Muayene Randevu Sitesi - Lütfen Bilgileri Eksiksiz Doldurunuz.</marquee>

		<form method="POST" id="postForm">
			<div class="form-group">
				<select id="response" class="form-control form-control-lg" name="istasyonSec" id="station"  required="">
					<option value="">İstasyon Seçiniz</option>
				</select>
			</div>
			<div class="form-group">
				<input type="text" class="form-control form-control-lg" name="isimSoyisim" maxlength="30" aria-describedby="isimSoyisim" placeholder="Ruhsat Sahibi Ad Soyad"  required="">
			</div>
			<div class="form-group">
				<input type="text" class="form-control form-control-lg" name="cepNo" aria-describedby="cepNo" placeholder="Cep No" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" maxlength="15" required="">
			</div>
			<div class="form-group">
				<input type="text" class="form-control form-control-lg" name="aracPlaka" maxlength="15" aria-describedby="aracPlakasi" placeholder="Araç Plakası"  required="">
			</div>
			<div class="form-group">
				<input type="text" class="form-control form-control-lg" name="plakaSeriNo" maxlength="10" aria-describedby="plakaSeriNo" placeholder="Ruhsat Seri No"  required="">
			</div>
			<div class="form-group">
				<select class="form-control form-control-lg" name="aracCinsi" required="">
					<option value="">Araç Cinsiniz</option>
					<option value="Otomobil">Otomobil</option>
					<option value="Kamyonet">Kamyonet</option>
					<option value="Minibüs">Minibüs</option>
					<option value="Kamyon">Kamyon</option>
					<option value="Otobüs">Otobüs</option>
					<option value="Motosiklet">Motosiklet</option>
					<option value="Çekici">Çekici</option>
					<option value="Römork">Römork</option>
					<option value="Yarı Römork">Yarı Römork</option>
					<option value="Traktör">Traktör</option>
					<option value="Tanker">Tanker</option>
				</select>
			</div>
			<div class="form-group">
				<select class="form-control form-control-lg" name="muayeneTipi" required="">
					<option value="">Muayene Türü Seçiniz</option>
					<option value="Genel Muayene">Genel Muayene</option>
					<option value="Muayene Tekrarı">Muayene Tekrarı</option>
					<option value="Randevu Güncellemesi">Randevu Güncellemesi</option>
				</select>
			</div>

			<div class="form-group checkbox form-check">
                <div class="checkbox__1">

                    <input type="radio" class="form-check-input" id="privacy" name="privacy" required=""> Sadece Arkadaşlar
				    <label class="form-check-label" for="privacy"> Gizlilik.</label>

                </div>
			</div>
			
			<?php wp_nonce_field('post_nonce', 'post_nonce_field'); ?>
			<input  type="hidden" name="submitted" id="submitted" value="true" />
			<button type="submit" class="btn btn__primary">Gönder</button>
		</form>		
		
		<?php
		return ob_get_clean();
	}
    add_shortcode( 'form_standart', 'form_standart_shortcode' );
    