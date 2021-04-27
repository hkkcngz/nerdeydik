<?php
/*
 * Çay Arası Extended
 * Hakkı Cengiz
 */

global
    $current_user,
    $wp_roles, $wp_query;
    $current_user = wp_get_current_user();
    $user = wp_get_current_user();
    $user_id = $current_user->ID;


///// Basics

    // Register Theme Features
    function tema_destekleri()
    {
        add_theme_support('automatic-feed-links');
        add_theme_support('post-formats', array('status', 'quote', 'gallery', 'image', 'video', 'audio', 'link', 'aside', 'chat', 'not'));
        add_theme_support('post-thumbnails');
        add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption'));
        remove_filter('the_content', 'wptexturize');
    }
    add_action('after_setup_theme', 'tema_destekleri');


    // JQMIGRATE: Migrate is installed, version 1.4.0 HATASI
    add_action('wp_default_scripts', function ($scripts) {
        if (!empty($scripts->registered['jquery'])) {
            $scripts->registered['jquery']->deps = array_diff($scripts->registered['jquery']->deps,
                array('jquery-migrate'));
        }
    });

    // remove img thumbnails
    function add_image_insert_override($sizes)
    {
        unset($sizes['thumbnail']);
        unset($sizes['medium']);
        unset($sizes['large']);
        return $sizes;
    }
    add_filter('intermediate_image_sizes_advanced', 'add_image_insert_override');

    // Excerpt kısmından [] parantezleri kaldırmak
    function new_excerpt_more($more)
    {
        return '...';
    }
    add_filter('excerpt_more', 'new_excerpt_more');

    function new_excerpt_length($length)
    {
        return 22;
    }
    add_filter('excerpt_length', 'new_excerpt_length', 999);

    // add custom class to tag
    function add_class_the_tags($html)
    {
        $postid = get_the_ID();
        $html = str_replace('<a', '<a class="tag"', $html);
        return $html;
    }
    add_filter('the_tags', 'add_class_the_tags');

    // Disable Comments URL field
    function disable_comment_url($fields)
    {
        unset($fields['url']);
        return $fields;
    }
    add_filter('comment_form_default_fields', 'disable_comment_url');

    function cookie($name) {
        if ( isset($_COOKIE[$name]) && !empty($_COOKIE[$name]))
            return $_COOKIE[$name];
    }

///// Security

    // Post Silince şuraya git 
    add_action( 'trashed_post', 'nr_redirect_after_trashing', 10 );
    function nr_redirect_after_trashing() {
        wp_redirect( home_url() );
        exit;
    }

    // Admin barını komple kapa
    show_admin_bar(false);

	$page_showing = basename($_SERVER['REQUEST_URI']);

    if (strpos($page_showing, 'failed') !== false) {
        $errorMessage = '<p class="error-msg"><strong>HATA:</strong> Geçersiz kullanıcı adı veya şifre.</p>';
    }
    elseif (strpos($page_showing, 'blank') !== false ) {
        $errorMessage = '<p class="error-msg"><strong>ERROR:</strong> Kullanıcı adı veya şifre alanı boş bırakılmış.</p>';
    }

    // Ortam Kütüphanesindeki Dosyaları Gizle
    add_filter('pre_get_posts', 'hide_posts_media_by_other');

    function hide_posts_media_by_other($query)
    {
        global $pagenow;

        if ('upload.php' != $pagenow || !$query->is_admin) {
            return $query;
        }

        if (!current_user_can('manage_options')) {
            global $user_ID;
            $query->set('author', $user_ID);
        }
        return $query;
    }

    /*
    *    To let the current users only view his/her uploaded attachments, add the following code to your themes actions:
    */
    add_filter('posts_where', 'devplus_wpquery_where');
    function devplus_wpquery_where($where)
    {
        global $current_user;

        if (is_user_logged_in() && !current_user_can('manage_options')) {
            // logged in user, but are we viewing the library?
            if (isset($_POST['action']) && ($_POST['action'] == 'query-attachments')) {
                // here you can add some extra logic if you'd want to.
                $where .= ' AND post_author=' . $current_user->data->ID;
            }
        }

        return $where;
    }

    // remove wp version number from scripts and styles
    function remove_css_js_version($src) {
        if (strpos($src, '?ver=')) {
            $src = remove_query_arg('ver', $src);
        }
        return $src;
    }
    add_filter('style_loader_src', 'remove_css_js_version', 9999);
    add_filter('script_loader_src', 'remove_css_js_version', 9999);

    // remove wp version number from head and rss
    function hc_remove_version() {
        return '';
    }
    add_filter('the_generator', 'hc_remove_version');

    // Wp-Login.Php Redirect //
    function goto_login_page() {
        $login_page = home_url();
        $page = basename($_SERVER['REQUEST_URI']);

        if ($page == "wp-login.php" && $_SERVER['REQUEST_METHOD'] == 'GET') {
            wp_redirect($login_page);
            exit;
        }
    }
    add_action('init', 'goto_login_page');

    function login_failed() {
        $login_page = home_url();
        wp_redirect($login_page . '?login=failed');
        exit;
    }
    add_action('wp_login_failed', 'login_failed');

    function blank_username_password($user, $username, $password) {
        $login_page = home_url();
        if ($username == "" || $password == "") {
            wp_redirect($login_page . "?login=blank");
            exit;
        }
    }
    add_filter('authenticate', 'blank_username_password', 1, 3);

    //echo $login_page = $page_path ;

    function logout_page() {
        $login_page = home_url();
        wp_redirect($login_page . "?login=false");
        exit;
    }
    add_action('wp_logout', 'logout_page');

    $page_showing = basename($_SERVER['REQUEST_URI']);
    $msj = '';
    if (strpos($page_showing, 'failed') !== false) {
        $msj = 'Hata! Geçersiz kullanıcı adı veya şifre.';
    } elseif (strpos($page_showing, 'blank') !== false) {
        $msj = 'Hata! Kullanıcı adı veya şifre alanı boş bırakılmış.';
    }

    // Wp-Login.php Redirect End //

    /**
     * Function Name: front_end_login_fail.
     * Description: This redirects the failed login to the custom login page instead of default login page with a modified url
     **/
    function _login_failed_redirect($username) {

        //get your page by slug and then its permalink
        $post = get_page_by_path('slug');

        //Or you can get your page ID, if you are assigning a custom template to a page.
        $redirect_page = !empty($post) ? get_permalink($post->ID) : site_url() . "/panel";

        $user = get_user_by('login', $username);

        if (!$user) {
            //Username incorrect
            wp_redirect($redirect_page . '?login_error=1');

        } else {
            //Username Password combination incoorect
            wp_redirect($redirect_page . '?login_error=2');
        }

    }
    add_action('wp_login_failed', '_login_failed_redirect');


///// Load More

    add_action( 'wp_enqueue_scripts', 'hc_script_and_styles');
    
    function hc_script_and_styles() {
        // absolutely need it, because we will get $wp_query->query_vars and $wp_query->max_num_pages from it.
        global $wp_query;
    
        // when you use wp_localize_script(), do not enqueue the target script immediately
        wp_register_script( 'hc_scripts', get_stylesheet_directory_uri() . '/assets/js/ajax.js', array('jquery') );
    
        // passing parameters here
        // actually the <script> tag will be created and the object "hc_loadmore_params" will be inside it 
        wp_localize_script( 'hc_scripts', 'hc_loadmore_params', array(
            'ajaxurl' => admin_url('admin-ajax.php'), // WordPress AJAX
            'posts' => json_encode( $wp_query->query_vars ), // everything about your loop is here
            'current_page' => $wp_query->query_vars['paged'] ? $wp_query->query_vars['paged'] : 1,
            'max_page' => $wp_query->max_num_pages
        ) );
    
        wp_enqueue_script( 'hc_scripts' );
    }

    function hc_loadmore_ajax_handler(){
        
        // prepare our arguments for the query
        $params = json_decode( stripslashes( $_POST['query'] ), true ); // query_posts() takes care of the necessary sanitization 
        $params['paged'] = $_POST['page'] + 1; // we need next page to be loaded
        $params['post_status'] = 'publish';

        // it is always better to use WP_Query but not here
        query_posts( $params );

        if( have_posts() ) :

            // run the loop
            while( have_posts() ): the_post();

                get_template_part( 'parts/content', get_post_format() );

            endwhile;

        endif;
        die; // here we exit the script and even no wp_reset_query() required!
    }

    add_action('wp_ajax_loadmore', 'hc_loadmore_ajax_handler'); // wp_ajax_{action}
    add_action('wp_ajax_nopriv_loadmore', 'hc_loadmore_ajax_handler'); // wp_ajax_nopriv_{action}

    // Filter

    add_action('wp_ajax_hcfilter', 'hc_filter_function'); 
    add_action('wp_ajax_nopriv_hcfilter', 'hc_filter_function');
        
    function hc_filter_function(){
        
        // example: date-ASC 
        $order = explode( '-', $_POST['hc_order_by'] );

        $params = array(
            'posts_per_page' => $_POST['hc_number_of_results'], // when set to -1, it shows all posts
            'orderby' => $order[0], // example: date
            'order'	=> $order[1] // example: ASC
        );

        query_posts( $params );

        global $wp_query;

        if( have_posts() ) :

            ob_start(); // start buffering because we do not need to print the posts now

            while( have_posts() ): the_post();

                get_template_part( 'parts/content', get_post_format() );

            endwhile;

            $posts_html = ob_get_contents(); // we pass the posts to variable
            ob_end_clean(); // clear the buffer
        else:
            $posts_html = '<p>Aradığınız kriterde içerik bulunmamaktadır.</p>';
        endif;

        // no wp_reset_query() required

        echo json_encode( array(
            'posts' => json_encode( $wp_query->query_vars ),
            'max_page' => $wp_query->max_num_pages,
            'found_posts' => $wp_query->found_posts,
            'content' => $posts_html
        ) );

        die();
    }

    // LOAD MORE

///// Registration
    function register_a_user() {
        
        if (( isset( $_GET['reg'] ) && $_GET['reg'] == 'new-user' )) {
            $errors = array();

            if (( empty( $_POST['user'] ) || empty( $_POST['email'] ) )) {
                $errors[] = __( 'Bir kullanıcı ve e-posta adresi seçiniz' );
            }
            // if (!empty( $_POST['spam'] )) { $errors[] = __('Spam Koruması'); }

            $user_login = esc_attr( $_POST['user'] );
            $user_email = esc_attr( $_POST['email'] );
            $user_pass  = esc_attr( $_POST['pass'] );
            require_once( ABSPATH . WPINC . '/registration.php' );
            $sanitized_user_login = sanitize_user( $user_login );
            $user_email = apply_filters( 'user_registration_email', $user_email );

            if (!is_email( $user_email )) {
                $errors[] = __('Geçersiz e-posta adresi.');
            } else {
                if (email_exists( $user_email )) {
                    $errors[] = __('Bu e-posta zaten kayıtlı.');
                }
            }

            if (( empty( $sanitized_user_login ) || !validate_username( $user_login ) )) {
                $errors[] = __('Geçersiz kullanıcı adı.');
            } else {
                if (username_exists( $sanitized_user_login )) {
                    $errors[] = __('Kullanıcı adı zaten kayıtlı.');
                }
            }
            if (empty( $errors )) {
                $user_id = wp_create_user( $sanitized_user_login, $user_pass, $user_email );

                if (!$user_id) {
                    $errors[] = __('Kayıt başarısız...');
                } else {
                    update_user_option( $user_id, 'default_password_nag', true, true );
                    wp_new_user_notification( $user_id, $user_pass );
                }
            }
            if (!empty( $errors )) {
                define( 'REGISTRATION_ERROR', serialize( $errors ) );
                return null;
            }

            define( 'REGISTERED_A_USER', $user_pass );
        }

	}
	add_action( 'template_redirect', 'register_a_user' );



///// Post Like System

    $postlikeurl = get_stylesheet_directory() . '/inc/likes/init.php';
    if (file_exists($postlikeurl)) {
        include_once $postlikeurl;
    }

///// Post Form System

    $postformurl = get_stylesheet_directory() . '/inc/postform/init.php';
    if (file_exists($postformurl)) {
        include_once $postformurl;
    }


///// Follow System - Notifications - See Posts From Only Followed Index Loop 
    /*
    * Kelime Sor
    kullanım şekli:
    $veriler="ahmet mehmet ayşe fatma 1,23,456";
    if (kelime_sor($veriler,'ayşe') == 1):
    echo 'aradığınız bulundu.';
    endif;
    note: don't change, It's better.
    */
    function kelime_sor($e, $f)
    {
        $gelen = strpos("x$e", $f);
        if ($gelen == true):
            $donen = "1";
            return ($donen);
        else:
            $donen = "0";
            return ($donen);
        endif;
    }

    /** PRE_GET_POSTS
     * This function modifies the main WordPress query to include an array of
     * post types instead of the default 'post' post type.
     *
     * @param object $query  The original query.
     * @return object $query The amended query.
     */
    function add_post_type_to_search($query)
    {
        global $current_user;
        $user = get_currentuserinfo();
        $userID = $current_user->ID;
        $userName = $current_user->display_name;

        // Takip Ettiğin Kullanıcılar
        $userFollowed = get_user_meta($userID, 'followed', true);

        // Hashtag (#) ile kaydedildiği için tekrar parçalayalım.
        $parcala = explode("#", $userFollowed);
        foreach ($parcala as $p) {
            $takipEttikleri .= trim($p) . ",";
        }
        // Parçaladık ve virgül ile ayırdık ama sağda solda boşluklar ve en sonra virgül oluştu.
        // Takip ettiklerimizin başına kendimizi de dahil edelim.
        $takipEttikleri = "$userID" . "$takipEttikleri";
        // Oluşan boşlukları ve sondaki virgülü silelim. Çıktı: 1,5,7 gibi olacak.
        $takipEttikleri = trim($takipEttikleri, " \t\n\r\0,");

        // Main Loop
        if (is_home()) {
            $query->set('post_type', array('post', 'durumlar'));
            $query->set('author', "$takipEttikleri");
        }

        if ( $query->is_search ) {
            $query->set( 'post_type', array( 'post', 'durumlar' ) );
        }

        /* Eger arama sayfasındaysak, esas query ile çalışıyorsak, ve birşey arattıysak...
        if (is_search() && $query->is_main_query() && $query->get('s')) {

        // Include our product CPT.
        $query->set('post_type', array(
        'post',
        'page',
        'not', // Ders Notları CPT.
        ));
        } */
        /* Exclude Some Categories
        if ( $query->is_home ) {
        $query->set('cat', "-111, -112");
        }
        // normal loopta 10 post çıkar ve bu author sayfasını da etkiler.
        if ( !is_admin() && $query->is_main_query() && is_author() ) {
        $query->set('posts_per_page', 150); // 30 is the number of posts
        }

        */
        return $query;
    }
    add_filter('pre_get_posts', 'add_post_type_to_search');


    
///// Author and User Panel

    /**
     * WP Custom Avatar
     * by HC
     */

    add_filter('get_avatar', 'my_custom_avatar', 2, 5);

    function my_custom_avatar($avatar, $id_or_email, $size, $default, $alt)
    {

        if ((isset($_GET['upload']) && $_GET['upload'] == 'user-avatar')) {

            $user_avatar = esc_attr($_POST['avatar_url']);
            $user = true;

            if (is_numeric($id_or_email)) {
                $id = (int) $id_or_email;
                $user = get_user_by('id', $id);
            } elseif (is_object($id_or_email)) {
                if (!empty($id_or_email->user_id)) {
                    $id = (int) $id_or_email->user_id;
                    $user = get_user_by('id', $id);
                }
            } else {
                $user = get_user_by('email', $id_or_email);
            }

            if ($user && is_object($user)) {
                if ($user->data->ID == '2') {
                    $avatar = $user_avatar;
                    $avatar = "<img alt='{$alt}' src='{$avatar}' class='avatar avatar-{$size} photo' height='{$size}' width='{$size}' />";
                }
            }
            return $avatar;
        }
    }

    // Add Unique ID to Users on Registration
    add_action('user_register', 'hc_unique_number', 10, 1);
    function hc_unique_number($user_id)
    {
        $unique_id = date("Ymd") . (int) microtime(true);
        add_user_meta($user_id, 'unique_number', $unique_id);
    }


    /**
     * WP authorField Info
     * by HC
     */

    add_action('show_user_profile', 'extra_user_profile_fields');
    add_action('edit_user_profile', 'extra_user_profile_fields');

    function extra_user_profile_fields($user)
    {?>
            <hr>
            <?php if (current_user_can('administrator')) {?>

                <?php
        $uyeRutbe = get_the_author_meta('uyeRutbe', $user->ID);
        $ucretsiz = ($uyeRutbe == 'ucretsiz' ? 'checked' : '');
        $standart = ($uyeRutbe == 'standart' ? 'checked' : '');
        $pro = ($uyeRutbe == 'pro' ? 'checked' : '');
        $seo = ($uyeRutbe == 'seo' ? 'checked' : '');

        // Get User Role //
        // Use: foreach ($user_roles as $val) { echo $val; }
        $user_meta = get_userdata($user->ID);
        $user_roles = $user_meta->roles;
        // Get User Role //
        ?>

                <h3>Özel Üye Ayarları</h3>

                <table class="form-table">
                    <tr>
                        <th>Üye Yetkisi</th>
                        <td><?php foreach ($user_roles as $val) {
            echo $val;
        }?> | Unique ID: <?php echo esc_attr(get_the_author_meta('unique_number', $user->ID)); ?>
                        </td>
                    </tr>
                    <tr>
                    <th><label for="author">Üyenin Premium Bilgisi</label></th>
                    <td>
                        <input type="radio" name="uyeRutbe" value="ucretsiz" <?=$ucretsiz?>> Ücretsiz
                        <input type="radio" name="uyeRutbe" value="standart" <?=$standart?>> Standart
                        <input type="radio" name="uyeRutbe" value="pro" <?=$pro?>> Pro
                        <input type="radio" name="uyeRutbe" value="seo" <?=$seo?>> SEO
                        <br><br>
                        <span class="description"><?php _e("Lütfen Yazar için bir Rütbe belirleyiniz..");?></span>
                    </td>
                    </tr>
                </table>

                <h3>Bakiye Bilgisi ₺</h3>
                <table class="form-table">
                    <tr>
                    <th><label for="author">Yazarın Total Bakiyesi</label></th>
                    <td>
                        <input name="authorBakiye" type="number" min="0" max="100" value="<?php echo esc_attr(get_the_author_meta('authorBakiye', $user->ID)); ?>">
                        <span class="description"><?php _e("Lütfen Yazar için Bakiye Bilgisi Giriniz..");?> </span>
                    </td>
                    </tr>
                    <tr>
                    <th><label for="author">Yazarın Satın Aldığı Notlar</label></th>
                    <td>
                        <input name="authorPurchased" type="text" value="<?php echo esc_attr(get_the_author_meta('authorPurchased', $user->ID)); ?>">
                        <span class="description"><?php _e("Satın aldıkları burada listelenir..");?> </span>
                    </td>
                    </tr>
                </table>
                <hr>
                <h3>Takip</h3>
                <table class="form-table">
                    <tr>
                        <th><label for="authorFollowed">Takip Ettikleri</label></th>
                        <td>
                            <textarea id="authorFollowed" name="authorFollowed"><?php echo esc_attr(get_the_author_meta('followed', $user->ID)); ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="authorFollowers">Takipçiler</label></th>
                        <td>
                            <textarea id="authorFollowers" name="authorFollowers"><?php echo esc_attr(get_the_author_meta('followers', $user->ID)); ?></textarea>
                        </td>
                    </tr>
                </table>

                <table class="form-table">
                    <tr>
                        <th>Bildirimler</th>
                        <td> <textarea name="authorNotifications"><?php echo esc_attr(get_the_author_meta('notifications', $user->ID)); ?></textarea> </td>
                    </tr>
                </table>
                <hr>
            <?php }?>

                <h3>Avatar</h3>
                <table class="form-table">
                    <tr>
                        <th><label for="author">Üye Avatar Önizleme</label></th>
                        <td>
                            <img src="<?php echo esc_attr(get_the_author_meta('userAvatar', $user->ID)); ?>" style="border-radius: 20px;height: 150px;width: 150px;object-fit:cover;" alt="avatar" />
                        </td>
                    </tr>

                    <tr>
                        <th><label for="author">Üye Avatar URL</label></th>
                        <td>
                            <input name="userAvatar" type="text" value="<?php echo esc_attr(get_the_author_meta('userAvatar', $user->ID)); ?>">
                            <span class="description"><?php _e("Avatar için Dışardan Linkte Kullanılabilir..");?></span>
                        </td>
                    </tr>
                </table>
                <hr>
                <h3>Okuduğu Bölüm</h3>

                <table class="form-table">
                    <tr>
                    <th><label for="author">Üyenin İlgili Bölümü</label></th>
                    <td>
                        <input name="bolum" type="text" value="<?php echo esc_attr(get_the_author_meta('bolum', $user->ID)); ?>">
                        <span class="description"><?php _e("Lütfen Yazar için Bölüm Bilgisi Giriniz..");?></span>
                    </td>
                    </tr>
                </table>

                <h2>Doğum Tarihi</h2>
                <table class="form-table">
                    <tr>
                        <th><label for="user_birthday">Birthday</label></th>
                        <td>
                            <input
                                type="date"
                                value="<?php echo esc_attr(get_user_meta($user->ID, 'birthday', true)); ?>"
                                name="user_birthday"
                                id="user_birthday"
                            >
                            <span class="description">Yalnızca gün ve ay giriniz.</span>
                        </td>
                    </tr>
                </table>
                <hr>
            <?php }

    add_action('personal_options_update', 'save_extra_user_profile_fields');
    add_action('edit_user_profile_update', 'save_extra_user_profile_fields');

    function save_extra_user_profile_fields($user_id)
    {
        if (!current_user_can('edit_user', $user_id)) {
            return false;
            function check_fields($errors, $update, $user)
            {
                $errors->add('demo_error', __('Yetkiniz, değişikliğe yetmiyor.'));
            }
        }

        if (current_user_can('administrator')) {
            // Stuff here for administrators
            update_user_meta($user_id, 'uyeRutbe', $_POST['uyeRutbe']);
            update_user_meta($user_id, 'authorBakiye', $_POST['authorBakiye']);
            update_user_meta($user_id, 'authorPurchased', $_POST['authorPurchased']);
            update_user_meta($user_id, 'followed', $_POST['authorFollowed']);
            update_user_meta($user_id, 'followers', $_POST['authorFollowers']);
            update_user_meta($user_id, 'bildirimler', $_POST['authorNotifications']);
        }

        update_user_meta($user_id, 'bolum', $_POST['bolum']);
        update_user_meta($user_id, 'userAvatar', $_POST['userAvatar']);
        update_user_meta($user_id, 'birthday', $_REQUEST['user_birthday']);

    }

    add_action('user_profile_update_errors', 'check_fields', 10, 5);



///// Infinity Scroll on Single.php

    /*
    * enqueue js script
    */
    add_action( 'wp_enqueue_scripts', 'ajax_single_script' );
    /*
    * enqueue js script call back
    */
    function ajax_single_script() {
        wp_enqueue_script( 'script_ajax', get_theme_file_uri( '/assets/js/ajax-single.js' ), array( 'jquery' ), '1.0', true );
    }

    /*
    * initial posts dispaly
    */
    function script_load_more($args = array()) {
        //initial posts load
        echo '<div id="ajax-primary" class="content-area">';
            echo '<div id="ajax-content" class="content-area">';
                ajax_script_load_more($args);
            echo '</div>';
            echo '<div id="loadMore-single" class="hc_loadmore" data-page="1" data-url="'.admin_url("admin-ajax.php").'" ><svg class="icon"><use xlink:href="#icon-loadmore" /></svg> Daha Fazla Yükle</div>';
        echo '</div>';
    }

    /*
    * create short code.
    */
    add_shortcode('ajax_posts', 'script_load_more');
    
    /*
    * load more script call back
    */
    function ajax_script_load_more($args) {
        //init ajax
        $ajax = false;
        //check ajax call or not
        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $ajax = true;
        }
        //number of posts per page default
        $num = 1;
        //page number
        $paged = $_POST['page'] + 1;

        // show first post by ID. And don't repeat firstpost on loop (but still it does.)
        $firstPost = ""; // decleration...
        if ( $paged == 1 ) { $firstPostID = get_the_ID(); }

        //args
        $args = array(
            'p' => $firstPostID,
            'post_type' => array('post', 'durumlar'),
            'post__not_in' => array($firstPostID),
            'post_status' => 'publish',
            'posts_per_page' =>$num,
            'paged'=>$paged
        );
        //query
        $query = new WP_Query($args);
        //check
        if ($query->have_posts()):
            //loop articales
            while ($query->have_posts()): $query->the_post();
                //include articles template

                get_template_part( 'parts/content-single' );
            endwhile;
        else:
            echo 0;
        endif;
        //reset post data
        wp_reset_postdata();
        //check ajax call
        if($ajax) die();
    }


    /*
    * load more script ajax hooks
    */
    add_action('wp_ajax_nopriv_ajax_script_load_more', 'ajax_script_load_more');
    add_action('wp_ajax_ajax_script_load_more', 'ajax_script_load_more');



///// User Last Login Info

    // Store Last Login Timestamp in User Meta
    add_action( 'wp_login', 'hc_collect_login_timestamp', 20, 2 );
    
    function hc_collect_login_timestamp( $user_login, $user ) {
        update_user_meta( $user->ID, 'last_login', time() );
    }



///// Better Comments
	// Include better comments file
	function better_comments( $comment, $args, $depth ) {
		global $post;
		$author_id = $post->post_author;
		$GLOBALS['comment'] = $comment;
		switch ( $comment->comment_type ) :
			case 'pingback' :
			case 'trackback' :
			// Display trackbacks differently than normal comments. ?>

			<li id="pingback-<?php comment_ID(); ?>" <?php comment_class(' comment-wrap pingback-entry'); ?>>
				<span class="pingback-heading">Bahsedilen</span> <?php comment_author_link(); ?>
			</li>
		<?php break; default :
			// Degerler. 
			$comment_id = $commentID; //$commentID your var
			$comment = get_comment( $comment_id );
			$comment_author_id = $comment->user_id;
			$user_info = get_userdata($comment_author_id);
            $commentUserLogin = $user_info->user_login;
            
            // Profile URL.
            $author_base = get_option( 'hc_author_base' );
            if ( empty($author_base) ) {
                $author_base = "author";
            }
            $commentUserProfile = get_site_url() . "/". $author_base ."/" . $commentUserLogin;
		?>
			<li id="comment-<?php comment_ID(); ?>" <?php comment_class(' comment-wrap'); ?>>
				<div class="comment-photo">
					<div class="hc-author_photo">
						<?php if (!$comment_author_id) {  ?> 
								<img src="<?php bloginfo('template_directory'); ?>/images/default-user-image.jpg" alt="varsayılan avatar" class="img-thumbnail thumbnail-avatar" />
						<?php } else { ?>
							<a href="<?=$commentUserProfile?>" title="Profili Görüntüle">
                                <?php $userAvatar = get_user_meta($comment_author_id,'userAvatar',true);
                                if($userAvatar) { ?>
                                    <img src="<?php echo esc_url( $userAvatar ); ?>" class="thumbnail-avatar" />
                                <?php } else { ?>
                                    <img src="<?php bloginfo('template_directory'); ?>/images/default-user-image.jpg" alt="varsayılan avatar" class="img-thumbnail thumbnail-avatar" />
                                <?php } ?>
							</a>
						<?php } ?>
					</div>
				</div>
				<div class="comment-block">
						<span class="comment-text"><?php comment_text(); ?></span>
						<?php if ( '0' == $comment->comment_approved ) : ?>
							<p class="comment-text onay-bekleyen-yorum">Yorumunuz onay bekliyor.</p>
						<?php endif; ?><!-- .comment-content -->
					<div class="comment-footer">
							<div class="comment-date"><?php printf( _x( '%s önce', '%s = human-readable time difference', 'editorsel' ), human_time_diff( get_comment_time( 'U' ), current_time( 'timestamp' ) ) ); ?></div>
							<ul class="comment-actions">
								<li class="complain"><a href="<?=$commentUserProfile?>" title="Profili Görüntüle" > <?php comment_author(); ?></a></li>
								<li class="reply"><?php comment_reply_link( array_merge( $args, array(
								        'reply_text' => esc_html__( 'Cevapla'),
								        'depth'      => $depth,
								        'max_depth'	 => $args['max_depth'] )
                                    ) ); ?>
                                </li>
                                <li class="comment-like"><?php if ( function_exists( 'get_simple_likes_button' ) ) {echo get_simple_likes_button( get_comment_ID() ); } ?></li>
							</ul>
					</div><!-- .comment-metadata -->
				</div><!-- #comment-## -->
			</li>	
	<?php
			break;
		endswitch; // End comment_type check.
    }
    

    //// On Submit Comment 
    add_action( 'comment_post', 'send_comment_notification', 10, 2 );
    function send_comment_notification( $comment_ID, $comment_approved) {

        if( 1 === $comment_approved ){
            // function logic goes here

            $user = wp_get_current_user();
            $user_id = $user->ID;

            $comment = get_comment( $comment_ID ); 
            $comment_post_ID = $comment->comment_post_ID; // Yorum Yapılan Yazı ID

            $comment_post = get_post( $comment_post_ID );    // Yorum Yapılan Yazı
            $comment_author_ID = $comment_post->post_author; // Yorum Yapılan Yazının Yazar ID
        
            $authorNotifications = get_user_meta($comment_author_ID,'notifications',true);
            // $authorFollowers = get_user_meta($comment_author_ID,'followers',true);


            // Yorum Yapınca Bildirim Gönder Yazı Sahibine
            if ( $comment_author_ID !== $user_id && is_user_logged_in() ) { // Yazar - Kendi Yazısına Yorum Atmıyorsa
                if ( kelime_sor($authorNotifications, "comment#$user_id#$comment_post_ID")  == 1 ) { // Eğer Şuanki Kullanıcı Daha Önce Yorum Yapmışsa:  
                    // şu işlemleri yap
                } else { 
    
                    // Add to Notifications
                    $authorNotifications .= "comment#$user_id#$comment_post_ID ";
                    update_user_meta( $comment_author_ID, 'notifications', $authorNotifications );
    
                } 
            }
            // Yorum Yapınca Bildirim Gönder Yazı Sahibine

            
        }
    }

///// Custom Post Types


    /*
    * Adding a menu to contain the custom post types for frontpage
    */
    function frontpage_admin_menu()
    {

        add_menu_page(
            'Özel Alanlar',
            'Özel Alanlar',
            'read',
            'front-sections',
            '',
            'dashicons-grid-view',
            40
        );

    }

    add_action('admin_menu', 'frontpage_admin_menu');

    // #1 Post Type: Youtube List

    // Youtube Link Eraser
    function extractUTubeVidId($url)
    {
        /*
        * type1: http://www.youtube.com/watch?v=9Jr6OtgiOIw
        * type2: http://www.youtube.com/watch?v=9Jr6OtgiOIw&feature=related
        * type3: http://youtu.be/9Jr6OtgiOIw
        */
        $vid_id = "";
        $flag = false;
        if (isset($url) && !empty($url)) {
            /*case1 and 2*/
            $parts = explode("?", $url);
            if (isset($parts) && !empty($parts) && is_array($parts) && count($parts) > 1) {
                $params = explode("&", $parts[1]);
                if (isset($params) && !empty($params) && is_array($params)) {
                    foreach ($params as $param) {
                        $kv = explode("=", $param);
                        if (isset($kv) && !empty($kv) && is_array($kv) && count($kv) > 1) {
                            if ($kv[0] == 'v') {
                                $vid_id = $kv[1];
                                $flag = true;
                                break;
                            }
                        }
                    }
                }
            }

            /*case 3*/
            if (!$flag) {
                $needle = "youtu.be/";
                $pos = null;
                $pos = strpos($url, $needle);
                if ($pos !== false) {
                    $start = $pos + strlen($needle);
                    $vid_id = substr($url, $start, 11);
                    $flag = true;
                }
            }
        }
        return $vid_id;
    }

    function func_youtubelist()
    {

        $labels = array(
            'name' => 'YoutubeList İşlemleri',
            'singular_name' => 'YoutubeList İşlemi',
            'menu_name' => 'YoutubeList İşlemleri',
            'name_admin_bar' => 'YoutubeList İşlemleri',
            'archives' => 'Arşivler',
            'attributes' => 'YoutubeList Attributes',
            'parent_item_colon' => 'Alt YoutubeList',
            'all_items' => 'Tüm YoutubeListler',
            'add_new_item' => 'Yeni YoutubeList Ekle',
            'add_new' => 'Yeni Ekle',
            'new_item' => 'Yeni YoutubeList Ekle',
            'edit_item' => 'YoutubeList Düzenle',
            'update_item' => 'YoutubeList Güncelle',
            'view_item' => 'YoutubeList Göster',
            'view_items' => 'YoutubeListları Göster',
            'search_items' => 'YoutubeList Ara',
            'not_found' => 'Bulunamadı',
            'not_found_in_trash' => 'YoutubeList Çöpte de Yok',
            'featured_image' => 'Öne Çıkan Görsel',
            'set_featured_image' => 'Öne Çıkan Görsel Ayarla',
            'remove_featured_image' => 'Öne Çıkan Görseli Sil',
            'use_featured_image' => 'Öne Çıkan Görsel Belirle',
            'insert_into_item' => 'YoutubeList\'e Ekle',
            'uploaded_to_this_item' => 'Bu işlem güncellendi',
            'items_list' => 'YoutubeListlar listesi',
            'items_list_navigation' => 'YoutubeListlar listesi menu',
            'filter_items_list' => 'YoutubeListlar listesi filtre',
        );

        $args = array(
            'label' => 'YoutubeList İşlemi',
            'description' => 'YoutubeList İşlemleri, Raporlar',
            'labels' => $labels,
            'supports' => array('title', 'editor', 'thumbnail', 'comments', 'trackbacks', 'revisions', 'custom-fields', 'page-attributes', 'post-formats', 'author'),
            'taxonomies' => array('category', 'post_tag'),
            'hierarchical' => false,
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => 'front-sections',
            'menu_icon' => 'dashicons-groups',
            'show_in_admin_bar' => false,
            'show_in_nav_menus' => true,
            'can_export' => true,
            'has_archive' => true,
            'exclude_from_search' => false,
            'publicly_queryable' => true,
            'capability_type' => 'post',
            'show_in_rest' => true,
        );
        register_post_type('youtubelist', $args);

    }
    add_action('init', 'func_youtubelist', 0);

    // #2 Post Type: Durum Paylaşma
    function func_durumpaylas()
    {

        $labels = array(
            'name' => 'Durumlar',
            'singular_name' => 'Durum',
            'menu_name' => 'Durumlar',
            'name_admin_bar' => 'Durumlar',
            'archives' => 'Arşivler',
            'attributes' => 'Durumlar Attributes',
            'parent_item_colon' => 'Alt Durumlar',
            'all_items' => 'Tüm Durumlar',
            'add_new_item' => 'Yeni Durum Ekle',
            'add_new' => 'Yeni Ekle',
            'new_item' => 'Yeni Durum Ekle',
            'edit_item' => 'Durumu Düzenle',
            'update_item' => 'Durumu Güncelle',
            'view_item' => 'Durumu Göster',
            'view_items' => 'Durumu Göster',
            'search_items' => 'Durumu Ara',
            'not_found' => 'Bulunamadı',
            'not_found_in_trash' => 'Durum Çöpte de Yok',
            'featured_image' => 'Öne Çıkan Görsel',
            'set_featured_image' => 'Öne Çıkan Görsel Ayarla',
            'remove_featured_image' => 'Öne Çıkan Görseli Sil',
            'use_featured_image' => 'Öne Çıkan Görsel Belirle',
            'insert_into_item' => 'Durum\'a Ekle',
            'uploaded_to_this_item' => 'Bu işlem güncellendi',
            'items_list' => 'Durumlar listesi',
            'items_list_navigation' => 'Durum listesi menu',
            'filter_items_list' => 'Durum listesi filtre',
        );

        $args = array(
            'label' => 'Durumlar',
            'description' => 'Durum Paylaşma İşlemleri Burada Listelenecek.',
            'labels' => $labels,
            'supports' => array('title', 'editor', 'thumbnail', 'comments', 'trackbacks', 'revisions', 'custom-fields', 'page-attributes', 'post-formats', 'author'),
            'taxonomies' => array('category', 'post_tag'),
            'hierarchical' => false,
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'menu_icon' => 'dashicons-editor-quote',
            'show_in_admin_bar' => false,
            'show_in_nav_menus' => true,
            'can_export' => true,
            'has_archive' => true,
            'exclude_from_search' => false,
            'publicly_queryable' => true,
            'capability_type' => 'post',
            'show_in_rest' => true,
        );
        register_post_type('durumlar', $args);

    }
    add_action('init', 'func_durumpaylas', 0);

    // #3 Post Type: Duyurular
    function func_duyurular()
    {
        $labels = array(
            'name' => 'Duyurular',
            'singular_name' => 'Duyurular',
            'menu_name' => 'Duyurular',
            'name_admin_bar' => 'Duyurular',
            'archives' => 'Arşivler',
            'attributes' => 'Duyurular Attributes',
            'parent_item_colon' => 'Alt İşlem',
            'all_items' => 'Duyurular',
            'add_new_item' => 'Yeni Duyuru Ekle',
            'add_new' => 'Yeni Ekle',
            'new_item' => 'Yeni Duyuru Ekle',
            'edit_item' => 'Duyuruyu Düzenle',
            'update_item' => 'Duyuruyu Güncelle',
            'view_item' => 'Duyuruyu Göster',
            'view_items' => 'Duyuruları Göster',
            'search_items' => 'Duyuru Ara',
            'not_found' => 'Bulunamadı',
            'not_found_in_trash' => 'Duyuru Çöpte de Yok',
            'featured_image' => 'Öne Çıkan Görsel',
            'set_featured_image' => 'Öne Çıkan Görsel Ayarla',
            'remove_featured_image' => 'Öne Çıkan Görseli Sil',
            'use_featured_image' => 'Öne Çıkan Görsel Belirle',
            'insert_into_item' => 'Duyurulara Ekle',
            'uploaded_to_this_item' => 'Bu duyuru güncellendi',
            'items_list' => 'Duyurular listesi',
            'items_list_navigation' => 'Duyurular listesi menu',
            'filter_items_list' => 'Duyurular listesi filtre',
        );

        $args = array(
            'label' => 'Satın Alma İşlemi',
            'description' => 'Satın Alma İşlemleri, Raporlar',
            'labels' => $labels,
            'supports' => array('title', 'editor', 'thumbnail','page-attributes'),
            'taxonomies' => array('category', 'post_tag'),
            'hierarchical' => false,
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => 'front-sections',
            'menu_position' => 5,
            'menu_icon' => 'dashicons-list-view',
            'show_in_admin_bar' => false,
            'show_in_nav_menus' => true,
            'can_export' => true,
            'has_archive' => false,
            'exclude_from_search' => true,
            'publicly_queryable' => true,
            'capability_type' => 'post',
        );
        register_post_type('duyurular', $args);

    }
    add_action('init', 'func_duyurular', 0);

    // #4 Post Type: Messenger
    function func_messenger()
    {
        $labels = array(
            'name' => 'Gelen Kutusu',
            'singular_name' => 'Gelen Kutusu',
            'menu_name' => 'Gelen Kutusu',
            'name_admin_bar' => 'Gelen Kutusu',
            'archives' => 'Arşivler',
            'attributes' => 'Gelen Kutusu Attributes',
            'parent_item_colon' => 'Alt İşlem',
            'all_items' => 'Gelen Kutusu',
            'add_new_item' => 'Yeni Mesaj Ekle',
            'add_new' => 'Yeni Ekle',
            'new_item' => 'Yeni Mesaj Ekle',
            'edit_item' => 'Mesajı Düzenle',
            'update_item' => 'Mesajı Güncelle',
            'view_item' => 'Mesajı Göster',
            'view_items' => 'Gelen Kutusuı Göster',
            'search_items' => 'Mesaj Ara',
            'not_found' => 'Bulunamadı',
            'not_found_in_trash' => 'Mesaj Çöpte de Yok',
            'featured_image' => 'Öne Çıkan Görsel',
            'set_featured_image' => 'Öne Çıkan Görsel Ayarla',
            'remove_featured_image' => 'Öne Çıkan Görseli Sil',
            'use_featured_image' => 'Öne Çıkan Görsel Belirle',
            'insert_into_item' => 'Gelen Kutusuna Ekle',
            'uploaded_to_this_item' => 'Bu Mesaj güncellendi',
            'items_list' => 'Gelen Kutusu',
            'items_list_navigation' => 'Gelen Kutusu menu',
            'filter_items_list' => 'Gelen Kutusu filtre',
        );

        $args = array(
            'label' => 'Gelen Kutusu',
            'description' => 'Gelen Kutusu İşlemleri, Messenger',
            'labels' => $labels,
            'supports' => array('title', 'editor', 'thumbnail', 'comments', 'trackbacks', 'revisions', 'custom-fields', 'page-attributes', 'post-formats', 'author'),
            'taxonomies' => array('category', 'post_tag'),
            'hierarchical' => false,
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'menu_position' => 55,
            'menu_icon' => 'dashicons-list-view',
            'show_in_admin_bar' => false,
            'show_in_nav_menus' => true,
            'can_export' => true,
            'has_archive' => false,
            'exclude_from_search' => true,
            'publicly_queryable' => true,
            'capability_type' => 'post',
            'rewrite' => array( 'slug' => 'messenger' ),
        );
        register_post_type('messenger', $args);

    }
    add_action('init', 'func_messenger', 0);



    add_action( 'template_redirect', 'prefix_redirect_single_cpt' );
    // Redirect Testimonials CPT Singles
    function prefix_redirect_single_cpt() {
        if ( is_singular( 'testimonial' )) {
            wp_redirect( '/testimonials', 301 );
            exit;
        }
    }


///////////// Online Users
    // User IP
    function user_ip() {
        foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                if (filter_var($ip, FILTER_VALIDATE_IP) !== false) {
                return $ip;
                }
            }
            }
        }
    }

    add_action('wp', 'update_online_users_status');
    function update_online_users_status(){

        if( is_user_logged_in() ){

            // get the online users list
            if(($logged_in_users = get_transient('users_online')) === false) $logged_in_users = array();

            $current_user = wp_get_current_user();
            $current_user = $current_user->ID;  
            $current_time = current_time('timestamp');

            if(!isset($logged_in_users[$current_user]) || ($logged_in_users[$current_user] < ($current_time - (15 * 60)))){
            $logged_in_users[$current_user] = $current_time;
            set_transient('users_online', $logged_in_users, 30 * 60);
            }

        }
    }


    function is_user_online($user_id) {

        // get the online users list
        $logged_in_users = get_transient('users_online');

        // online, if (s)he is in the list and last activity was less than 15 minutes ago
        return isset($logged_in_users[$user_id]) && ($logged_in_users[$user_id] > (current_time('timestamp') - (15 * 60)));
    }

    ### Function: Add Author Custom Fields || I Need I Function to Add Users to Search. 
    add_action('publish_post', 'add_author_fields');
    function add_author_fields($post_ID) {
        global $wpdb;
        $user_id = $wpdb->get_var("SELECT post_author FROM $wpdb->posts WHERE ID = $post_ID");
        $first_name = $wpdb->get_var("SELECT meta_value FROM $wpdb->usermeta WHERE meta_key = 'first_name' AND user_id = $user_id");
        $last_name = $wpdb->get_var("SELECT meta_value FROM $wpdb->usermeta WHERE meta_key = 'last_name' AND user_id = $user_id");
        $user_name = $wpdb->get_var("SELECT user_login FROM $wpdb->users WHERE ID = $user_id");
        add_post_meta($post_ID, 'author_realname', $first_name.' '.$last_name, true);
        add_post_meta($post_ID, 'author_username', $user_name, true);
    }


///////////// Online Users

///// Profile URL by ID - HC
    // Profile URL.
    function profile_url($userID) {
        
        $userLogin = get_the_author_meta( 'user_login' , $userID );
        // Profile URL.
        $authorProfile = "";
        $author_base = get_option( 'hc_author_base' );
        if ( empty($author_base) ) {
            $author_base = "author";
        }
        $authorProfile = get_site_url() . "/". $author_base ."/" . $userLogin;

        return $authorProfile;
    }

///// Author Base URL Changer
    /*
    * Rewrite author base to custom
    *
    * @return void
    */

    function hc_author_base_rewrite() {
        global $wp_rewrite;
        $author_base_db = get_option( 'hc_author_base' );
        if ( !empty( $author_base_db ) ) {
            $wp_rewrite->author_base = $author_base_db;
        }
    }
    add_action( 'init', 'hc_author_base_rewrite' );

    /**
    * Render textinput for Author base
    * Callback for the add_settings_function()
    *
    * @return void
    */

    function hc_author_base_render_field() {
        global $wp_rewrite;
        printf(
            '<input name="hc_author_base" id="hc_author_base" type="text" value="%s" class="regular-text code">',
            esc_attr( $wp_rewrite->author_base )
        );
    }

    /**
    * Add a setting field for Author Base to the "Optional" Section
    * of the Permalinks Page
    *
    * @return void
    */
    function hc_author_base_add_settings_field() {
        add_settings_field(
            'hc_author_base',
            esc_html__( 'Author base' ),
            'hc_author_base_render_field',
            'permalink',
            'optional',
            array( 'label_for' => 'hc_author_base' )
        );
    }

    add_action( 'admin_init', 'hc_author_base_add_settings_field' );

    /**
    * Sanitize and save the given Author Base value to the database
    *
    * @return void
    */

    function hc_author_base_update() {
        $author_base_db = get_option( 'hc_author_base' );

        if ( isset( $_POST['hc_author_base'] ) &&
            isset( $_POST['permalink_structure'] ) &&
            check_admin_referer( 'update-permalink' )
        ) {
            $author_base = sanitize_title( $_POST['hc_author_base'] );

            if ( empty( $author_base ) ) {

                add_settings_error(
                    'hc_author_base',
                    'hc_author_base',
                    esc_html__( 'Invalid Author Base.' ),
                    'error'
                );

            } elseif ( $author_base_db != $author_base ) {
                update_option( 'hc_author_base', $author_base );
            }
        }
    }
    add_action( 'admin_init', 'hc_author_base_update' );


///// COMMENT Messenger
// Yorum Listeleme Özelliği
    function tartisma_comment($comment, $args, $depth) { $GLOBALS['comment'] = $comment; ?>
	<?php 
		// Degerler. 
		$comment_id = $commentID; //$commentID your var
		$comment = get_comment( $comment_id );
		$comment_author_id = $comment->user_id;
		$user_info = get_userdata($comment_author_id);
		$username = $user_info->user_login;
	
		// Yorum -> Yazarın yorumu ise;
		$PostAuthor = false; 
		if($comment->comment_author_email == get_the_author_meta('email')) {$PostAuthor = true;}
	?>
	<div class="argue-message <?php if($PostAuthor) {echo "argue-message-author"; } else {echo "argue-message-commenter";} ?>" id="argue-message-<?php comment_ID() ?>">
		<div class="hc-author_photo">
			<?php if (!$comment_author_id) { ?> 
				<img class="argue-image argue-image-default" src="<?php bloginfo('template_directory'); ?>/images/default-user-image.jpg" alt="varsayılan avatar" style="width: 50px;height: 50px;object-fit: cover;" />
			<?php } else { ?>
				<a href="<?php bloginfo('url'); echo "/yazar/$username"; ?>" title="Profili gör" style="border:none">
				<?php 
				$userAvatar = get_user_meta($comment_author_id,'userAvatar',true);
				if($userAvatar) { ?>
					<img src="<?php echo esc_url( $userAvatar ); ?>" class="argue-image argue-image-default" style="width: 50px; height: 50px; object-fit: cover;" />
				<?php } else { ?>
					<img src="<?php bloginfo('template_directory'); ?>/images/default-user-image.jpg" alt="varsayılan avatar" class="argue-image argue-image-default" style="width: 50px;height: 50px;object-fit: cover;" />
				<?php } ?>
				</a>
			<?php } ?>
		</div>

	<div class="argue-message-wrapper">
		<div class="argue-message-content">
			<?php comment_text() ?>
		</div>
		
        <div class="argue-details">
          <span class="argue-message-localisation font-size-small"><?php printf( _x( '%s önce', '%s = human-readable time difference', 'editorsel'), human_time_diff( get_comment_time( 'U' ), current_time( 'timestamp' ) ) ); ?></span>
          <span class="argue-message-read-status font-size-small"> ∙ <?php comment_author(); ?></span>
          <span class="hovered-show"> ∙ <?php comment_reply_link( array_merge( $args, array( 'reply_text' => esc_html__( 'Cevapla'), 'depth' => $depth, 'max_depth'	 => $args['max_depth'] ) ) ); ?></span>
        </div>
	</div>
		<?php if ($comment->comment_approved == '0') : ?>
		<em class="yorumonay">Yorumunuz onaylandıktan sonra görüntülenecektir.</em>
		<?php endif; ?>
	</div>
    <?php }	
    

