<br> <br>
<!-- Top Menu -->
<ul class="bottom-menu shadow">
    <li>
        <a href="http://localhost/wp">
            <svg class="icon"> <use xlink:href="#icon-home"></use> </svg>
            <span>Anasayfa</span>
        </a>
    </li>
    <li></li>
    <li></li>
    <li></li>
    <li>
        <a href="http://localhost/wp/settings" class="author_photo">
            <svg class="icon"> <use xlink:href="#icon-setting"></use> </svg> 
            <span> Ayarlar</span>
        </a>
    </li>
</ul>
<br> <br>
<ul class="bottom-menu shadow">
  <li class="logo-place">
      <a href="http://localhost/wp/uye/hakki" class="author_photo">
                                          <svg class="icon"> <use xlink:href="#icon-user"></use> </svg> 
                                  <span>Hakkı Cengiz asdsa&nbsp;</span>
      </a>
  </li>
  <li>
      <a href="http://localhost/wp/?s=">
          <svg class="icon"> <use xlink:href="#icon-search"></use> </svg>
          <span>Keşfet</span>
      </a>
  </li>
  <li>
      <a href="http://localhost/wp/add-all">
          <svg class="icon"> <use xlink:href="#icon-plus"></use> </svg>
          <span>İçerik Ekle</span>
      </a>
  </li>
  <li>
      <a href="http://localhost/wp/notifications">
          <svg class="icon"><use xlink:href="#icon-notification"></use></svg>
          <span>Bildirimler </span>
      </a>
  </li>
  <li>
      <a href="http://localhost/wp/inbox">
          <svg class="icon"> <use xlink:href="#icon-messages"></use> </svg>
          <span>Mesajlar</span>
      </a>
  </li>
</ul>

<!-- Breadcrumbs -->

<ol itemscope itemtype="http://schema.org/BreadcrumbList" class="breadcrumbs">
  <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
    <a itemprop="item" href="https://textfancy.com/emoji/">
      <span itemprop="name">Emojis</span>
    </a>
    <meta itemprop="position" content="1" />
  </li> /
  <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
    <a itemprop="item" href="https://textfancy.com/emoji/animals/">
      <span itemprop="name">Animals</span>
    </a>
    <meta itemprop="position" content="2" />
  </li>
</ol>

<!-- Posts Filter -->

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
  <!-- required hidden field for admin-ajax.php -->
  <input type="hidden" name="action" value="hcfilter" />

  <button>Filtreyi Uygula</button>
</form>

<!-- POST Articles-->
<div class="posts articles">
  <article id="post-49"
    class="article-item post-49 post type-post status-publish format-standard hentry category-genel">

    <a class="image" href="#" rel="bookmark" title="6">
      <img class="image fit" src="<?php echo get_template_directory_uri(); ?>/images/default-thumb.jpg" alt="default">
    </a>

    <ul class="actions">
      <li><span class="sl-wrapper"><a
            href="#"
            class="sl-button sl-button-49"
            title="Çayla"><span class="sl-icon"><svg class="icon">
                <use xlink:href="#icon-tea-unliked"></use>
              </svg></span> <span id="sl-loader"></span></a></span></li>
      <li><a href="#respond" rel="bookmark"><svg class="icon">
            <use xlink:href="#icon-comment"></use>
          </svg></a></li>
      <li style="flex: 1; display: flex; justify-content: flex-end;"> <a href="#" class="icon-comments add-to-bookmark"
          rel="bookmark"><i class="fa fa-bookmark-o"></i></a></li>
    </ul>

  </article><!-- #post-49 -->
  <article id="post-47"
    class="article-item post-47 post type-post status-publish format-standard hentry category-genel">

    <a class="image" href="#" rel="bookmark" title="5">
      <img class="image fit" src="<?php echo get_template_directory_uri(); ?>/default-thumb.jpg" alt="5">
    </a>

    <ul class="actions">
      <li><span class="sl-wrapper"><a
            href="#"
            class="sl-button sl-button-47 liked" data-nonce="66f4d2b89d" data-post-id="47" data-iscomment="0"
            title="Çaysız geç"><span class="sl-icon"><svg class="icon">
                <use xlink:href="#icon-tea-liked"></use>
              </svg></span><span class="sl-count">1</span></a></span></li>
      <li><a href="#respond" rel="bookmark"><svg class="icon">
            <use xlink:href="#icon-comment"></use>
          </svg></a></li>
      <li style="flex: 1; display: flex; justify-content: flex-end;"> <a href="#" class="icon-comments add-to-bookmark"
          rel="bookmark"><i class="fa fa-bookmark-o"></i></a></li>
    </ul>

  </article><!-- #post-47 -->
  <article id="post-45"
    class="article-item post-45 post type-post status-publish format-standard hentry category-genel">

    <a class="image" href="#" rel="bookmark" title="4">
      <img class="image fit" src="<?php echo get_template_directory_uri(); ?>/images/default-thumb.jpg" alt="4">
    </a>

    <ul class="actions">
      <li><span class="sl-wrapper"><a
            href="#"
            class="sl-button sl-button-45" data-nonce="66f4d2b89d" data-post-id="45" data-iscomment="0"
            title="Çayla"><span class="sl-icon"><svg class="icon">
                <use xlink:href="#icon-tea-unliked"></use>
              </svg></span> <span id="sl-loader"></span></a></span></li>
      <li><a href="#respond" rel="bookmark"><svg class="icon">
            <use xlink:href="#icon-comment"></use>
          </svg></a></li>
      <li style="flex: 1; display: flex; justify-content: flex-end;"> <a href="#" class="icon-comments add-to-bookmark"
          rel="bookmark"><i class="fa fa-bookmark-o"></i></a></li>
    </ul>

  </article><!-- #post-45 -->
  <div class="hc_loadmore"><svg class="icon">
      <use xlink:href="#icon-loadmore"></use>
    </svg> Daha Fazla Yükle</div>
</div>