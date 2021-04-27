</main>

</div> <!-- Screen -->

<!-- Required Scripts -->
<?php if ( is_user_logged_in() ) { ?>

<div id="modal-add-content" class="post-add-contents_fixed">

    <a href="#" class="post-add-item">
        <svg class="icon">
            <use xlink:href="#icon-megaphone"></use>
        </svg>
        <h3>Durum Paylaşımı</h3>
        <p>İçini Dökmek İstersen...</p>
    </a>

    <a href="#" class="post-add-item">
        <svg class="icon">
            <use xlink:href="#icon-text"></use>
        </svg>
        <h3>Standart Post Paylaşımı</h3>
        <p>Normal Yazı Paylaşımı, Hikaye, Deneme, Makale, Şiir...</p>
    </a>
    <a href="#" class="post-add-item">
        <svg class="icon">
            <use xlink:href="#icon-bookmark"></use>
        </svg>
        <h3>Yer İmleri Paylaşımı</h3>
        <p>Hoşunuza giden website bağlantılarını veya notlar..</p>
    </a>
    <a href="#" class="post-add-item">
        <svg class="icon">
            <use xlink:href="#icon-list"></use>
        </svg>
        <h3>Youtube Liste Paylaşımı</h3>
        <p>Youtube Linkleri ile kendi playlistinizi oluşturun.</p>
    </a>

    <a href="#" class="post-add-item">
        <svg class="icon">
            <use xlink:href="#icon-post-standard"></use>
        </svg>
        <h3>Foto İçerik Paylaşımı</h3>
        <p>Öğretici ve Eğitici içerikler..</p>
    </a>

    <a href="#" class="post-add-item">
        <svg class="icon">
            <use xlink:href="#icon-posts"></use>
        </svg>
        <h3>Eğitici İçerik Paylaşımı</h3>
        <p>Öğretici ve Eğitici içerikler..</p>
    </a>

</div>

<script type='text/javascript' src='<?php echo get_template_directory_uri(); ?>/assets/js/jquery.min.js'></script>
<!-- Components Scripts -->
<!-- <script type='text/javascript' src='https://cdn.jsdelivr.net/npm/sweetalert2@9'></script> -->
<!-- <script type='text/javascript' src='< ?php echo get_template_directory_uri(); ?>/assets/js/swiped-events.min.js'></script> -->

<script type='text/javascript' src='<?php echo get_template_directory_uri(); ?>/assets/js/main.js'></script>
<script src='<?php echo get_template_directory_uri(); ?>/assets/js/comps/emoji-picker.js'></script>
<script src='<?php echo get_template_directory_uri(); ?>/assets/js/comps/bgselector.js'></script>
<script src='<?php echo get_template_directory_uri(); ?>/assets/js/geolocation.js'></script>
<?php } else { // Offline User ?>
<script>
    const sign_in_btn = document.querySelector("#sign-in-btn");
    const sign_up_btn = document.querySelector("#sign-up-btn");
    const container = document.querySelector(".container");

    sign_up_btn.addEventListener("click", () => {
        container.classList.add("sign-up-mode");
    });

    sign_in_btn.addEventListener("click", () => {
        container.classList.remove("sign-up-mode");
    });
</script>
<?php } ?>

<?php wp_footer();?>
</body>

</html>