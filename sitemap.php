<?php
/**
*Sitemizin link haritasıdır.Bizim sitemzide linkleri google a tanıtıyoruz.
 *
 */
header('Content-type:application/xml; charset="utf8"',true); ?>
<urlset xmlns="http://sitemaps.org/schemas/sitemap/0.9"
         xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
         xsi:schemaLocation="http://sitemaps.org/schemas/sitemap/0.9
         http://wwww.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">

    <?php
    $sunucu="localhost";
    $kullanici="root";
    $password="";
    $database="magexpress";

$site=$_SERVER["HTTP_HOST"].'/php/magexpress'; //alanadını alıyor.
$tarih=date("Y-m-d");

function seo($text)
{
    $find = array('Ç', 'Ş', 'Ğ', 'Ü', 'İ', 'Ö', 'ç', 'ş', 'ğ', 'ü', 'ö', 'ı', '+', '#');
    $replace = array('c', 's', 'g', 'u', 'i', 'o', 'c', 's', 'g', 'u', 'o', 'i', 'plus', 'sharp');
    $text = strtolower(str_replace($find, $replace, $text));
    $text = preg_replace("@[^A-Za-z0-9\-_\.\+]@i", ' ', $text);
    $text = trim(preg_replace('/\s+/', ' ', $text));
    $text = str_replace(' ', '-', $text);
    return $text;
}

$conn=mysqli_connect($sunucu,$kullanici,$password,$database);
mysqli_set_charset($conn,'utf8');
?>

    <!-- Tekli Sayfalar -->

    <url>
        <loc>http://<?php echo $site; ?>/iletisim</loc>
        <lastmod>http://<?php echo $tarih; ?></lastmod>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>

    <url>
        <loc>http://<?php echo $site; ?>/Hakkında</loc>
        <lastmod>http://<?php echo $tarih; ?></lastmod>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>
    <url>
        <loc>http://<?php echo $site; ?>/Hizmetler</loc>
        <lastmod>http://<?php echo $tarih; ?></lastmod>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>
    <!-- Haberler Sitemap -->

    <?php

    $result=mysqli_query($conn,"SELECT * FROM haber");

    while($habercek=mysqli_fetch_assoc($result)){?>
            <url>
                <loc>http://<?php echo $site; ?>/haber-detay/<?php echo seo($habercek["haber_ad"]).'/'. $habercek["haber_id"]; ?></loc>
                <lastmod>http://<?php echo $tarih; ?></lastmod>
                <changefreq>daily</changefreq>
                <priority>1.0</priority>
            </url>
       <?php }?>
 <?php $conn->close(); ?>
