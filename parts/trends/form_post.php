<?php
/*
Açıklama: Bu Sayfada Çekilen Bilgiler Post Edilir.
*/

$resimler  = unserialize($_POST['resimler']);
$basliklar = unserialize($_POST['basliklar']);
$linkler   = unserialize($_POST['linkler']);

$singles = array();
foreach ($linkler as $link) {

    $link = trim( $link );
    $content = file_get_contents($link);

    // Yalnızca Yazılar
    $ilk_adim = explode( 'js-article-progress-trigger">', $content );
    $ikinci_adim = explode( '<footer class="detail-footer">', $ilk_adim[1] );
    
    array_push($singles, $ikinci_adim[1]);
}
$singles = array_filter($singles);


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Merhaba</title>
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