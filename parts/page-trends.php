<?php
/*
Template Name: Google Trends Post Kaydet.
Açıklama: Bu Sayfada Gerekli Bilgiler Çekilir.
*/
function hc_bilgicek( $ilkparametre, $ikinciparametre, $gereksizalan, $tumbosluklarikaldir = false, $bosluksuzilkparametre, $bosluksuzikinciparametre ) {

    // Çekilecek URL Adresleri
    $url = "https://www.thinkwithgoogle.com/intl/tr-tr/icgoruler/"; 
    
    $var1 = "";
    // Tüm Gönderiler
        $content = file_get_contents($url);

        // Yalnızca İçerik
        $first_step = explode( $ilkparametre, $content );
        $second_step = explode( $ikinciparametre, $first_step[1] );
        
        // Belirli Alanı Kaldırma.
        $second_step = str_replace($gereksizalan, '', $second_step);

        $var1 = $second_step[0];

        // var 2
        $bosluksuz = preg_replace('/\s+/', '', $content);
        $first_style = explode( $bosluksuzilkparametre, $bosluksuz );
        $second_style = explode( $bosluksuzikinciparametre, $first_style[1] );
        $var2 = $second_style[0];

    return array($var1, $var2);
}

// Tüm Gönderiler
$result = hc_bilgicek('<div class="card-group card-group--grid" role="list">', '<div class="page-content page-content--light-grey', 'style="background-image: none"', true, 'rel="stylesheet"><style>', '</style><noscript>' );

// Tek Satır Yap
$str = preg_replace("/[\r\n]*/","",$result[0]);
$ftr = preg_replace("/[\r\n]*/","",$result[1]);

// $str = str_replace(array("\r\n", "\n", "\r"), '', $result[0]);

//// Bütün Başlıklar
$tumbasliklar = explode('<h3 class="card__title">', $str);
$basliklar = array();
foreach ( $tumbasliklar as $baslik ) {
    
    // Başlıkları Al
    $baslik = trim( substr($baslik, 0, strpos($baslik, '<div class=')) );
    array_push($basliklar, $baslik);

}
$basliklar = array_filter($basliklar);

//// Bütün Linkler
$tumlinkler = explode('<a href="', $str);
$linkler = array();
foreach ( $tumlinkler as $link ) {
    
    // Linkleri Al
    $link = trim( substr($link, 0, strpos($link, '"')) );
    array_push($linkler, $link);

}
$linkler = array_filter($linkler);

//// Bütün Resimler 1,8,15,22,29,36,43,50
$tumresimler = explode('background-image:url(', $ftr);
$resimler = array();
$process = 0;
foreach ( $tumresimler as $resim ) {
    $process++;
    // Resimleri Al
    $resim = trim( substr($resim, 0, strpos($resim, ')' ) ) );
    if ( $process == 1 || $process == 8 || $process == 15 || $process == 22 || $process == 29 || $process == 36 || $process == 43 || $process == 50 ) {
        array_push($resimler, $resim);
    }
}
$resimler = array_filter($resimler);

//// Bütün Single Yazılar
/*
$singles = array();
foreach ($linkler as $link) {

    $single = file_get_contents($link);

    // Yalnızca Yazılar
    $ilk_adim = explode( '<div class="page-section">', $single );
    $ikinci_adim = explode( '<footer class="detail-footer">', $ilk_adim[1] );
    
    array_push($linkler, $ikinci_adim[1]);
}
*/

?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        ul, ul li {
            margin: 0;
            padding: 0;
            list-style: none;
        }
    </style>

</head>
<body>

    <?php 
        print('<pre>');
        print_r(  $basliklar );
        print('</pre>');


        print('<pre>');
        print_r( $linkler );
        print('</pre>');


        print('<pre>');
        print_r(  $resimler );
        print('</pre>');




    ?>

        <form action="<?php echo get_template_directory_uri(); ?>/parts/trends/form_post.php" method="post">
            <input type='hidden' name='basliklar' value="<?php echo htmlentities(serialize($basliklar)); ?>" />
            <input type='hidden' name='resimler' value="<?php echo htmlentities(serialize($resimler)); ?>" />
            <input type='hidden' name='linkler' value="<?php echo htmlentities(serialize($linkler)); ?>" />
            <button type="submit">Gönder</button>
        </form>

</body>
</html>