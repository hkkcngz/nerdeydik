<?php get_header();  ?>

<?php if ( is_user_logged_in() ) { ?>
    
    <!-- arama sayfası araçları -->
    <form id="hc_filters" action="#">
    <div class="form-group">
        <label for="hc_number_of_results">İçerik Sınırı</label>
        <select name="hc_number_of_results" id="hc_number_of_results">
        <option><?php echo get_option( 'posts_per_page' ) ?></option><!-- it is from Settings > Reading -->
        <option>5</option>
        <option>10</option>
        <option value="-1">Tümü</option>
        </select>
    </div>
    <div class="form-group">
        <label for="hc_order_by">Sıralama</label>
        <select name="hc_order_by" id="hc_order_by">
        <option value="date-DESC">Tarih ↓</option><!-- I will explode these values by "-" symbol later -->
        <option value="date-ASC">Tarih ↑</option>
        <option value="comment_count-DESC">Yorum Sayısı ↓</option>
        <option value="comment_count-ASC">Yorum Sayısı ↑</option>
        </select>
    </div>
    <div class="form-group">
        <input type="text" name="s" placeholder="ara...">
    </div>
        <!-- required hidden field for admin-ajax.php -->
        <input type="hidden" name="action" value="hcfilter" />
    
        <button>Filtreyi Uygula</button>
    </form>
    
<?php
    echo "<div class='posts articles'>";

    if ( have_posts() ) {
        // Load posts loop.
        while ( have_posts() ) {
          the_post();
                get_template_part( 'parts/content', get_post_type() );
        }
        // Previous/next page navigation.
        if (  $wp_query->max_num_pages > 1 ) {
            echo '<div id="loadmore-home" class="hc_loadmore"><svg class="icon"><use xlink:href="#icon-loadmore" /></svg> Daha Fazla Yükle</div>';
        }
    } else {
        // If no content, include the "No posts found" template.
        get_template_part( 'parts/content', 'none' );
    }

    echo "</div>";
} 
// IF NOT Logged In
else { 
  get_template_part('inc/loginregister/login-register'); 
} ?>

<?php get_footer(); ?>