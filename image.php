<?php
require_once 'myAutoloader.php';
require_once 'vendor/autoload.php';
use Intervention\Image\ImageManagerStatic as Image;
Image::configure(array('driver' => 'gd'));
session_start();

header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

use League\ColorExtractor\Color;
use League\ColorExtractor\ColorExtractor;
use League\ColorExtractor\Palette;

require_once 'navbar.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>420px - Image</title>
    <!--
    Fluid Gallery Template
    http://www.templatemo.com/tm-500-fluid-gallery
    -->
    <!-- load stylesheets -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600">
    <!-- Google web font "Open Sans" -->
    <link rel="stylesheet" href="Font-Awesome-4.7/css/font-awesome.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <!-- Bootstrap style -->
    <link rel="stylesheet" href="css/hero-slider-style.css">
    <!-- Hero slider style (https://codyhouse.co/gem/hero-slider/) -->
    <link rel="stylesheet" href="css/magnific-popup.css">
    <!-- Magnific popup style (http://dimsemenov.com/plugins/magnific-popup/) -->
    <link rel="stylesheet" href="css/templatemo-style.css">
    <link href="css/grayscale.min.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>
<header class="intro">

    <div class="nav navbar-nav">
        <a class="page-scroll" href="home.php">Accueil</a>
        <a class="page-scroll" href="gallery.php">Galerie</a>
        <a class="page-scroll" href="upload.php">Importer</a>
    </div>

    <div style="float: none;margin: 0 auto" class="tm-flex tm-contact-page">
        <div class="tm-2-col-textbox-2">
            <?php
            $actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            $query = parse_url($actual_link, PHP_URL_QUERY);
            $queries = explode("=", $query);
            if ($queries[0] === 'id')
                $_SESSION['imageId'] = $queries[1];
            try {
                $prepare = myPDO::getInstance()->getConnection()->prepare('select * from images where id = :id');
                if ($prepare->execute(array('id'=>$_SESSION['imageId']))) {
                    $image = $prepare->fetch(PDO::FETCH_OBJ);
                    $_SESSION['image_name'] = $image->name;
                    $filepath = $_SESSION['user']->dir . $image->name;

                    $palette = Palette::fromFilename($filepath);
                    $extractor = new ColorExtractor($palette);
                    $dominant = $extractor->extract(1);
                    echo "<div style=\"text-align:center\">\n" .
                        "<h2 class=\"tm-contact-info\">".$image->name."</h2>".
                        "<h1 class=\"tm-contact-info\">
                        Hex dominant: <font color=\"".dechex($dominant[0])."\">".dechex($dominant[0])."</font></h1>".
                        '<img src="' . $filepath . " \"/>".
                        "</div></br>";
                    echo generateFilter($image->id);
                    echo "<div style=\"text-align:center\">\n" .
                        "<form method=\"post\">
                        <button type=\"submit\"  name=\"delete\">Supprimer</button>
                        </form>\n
                        </div>";


                    if (isset($_POST['delete'])) {
                        $prepare = myPDO::getInstance()->getConnection()->prepare('delete from images where id = :id');
                        if ($prepare->execute(array('id'=>$_GET['id']))) {
                            unlink($_SESSION['user']->dir.$_SESSION['image_name']);
                            unset($_SESSION['image_name']);
                            header('Location:gallery.php');
                            exit;
                        }
                    }
                }
                else
                    echo "There is no image here with this id.";
            }
            catch (Exception $e)
            {
                $_SESSION['errorUpload'] = 'Une erreur est survenue, veuillez réessayer ulterieurement.';
                header('Refresh:0');
                exit;
            }
            ?>
        </div>
    </div>
</header>
</body>
</html>

<?php

header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if (isset($_GET['filter'])) {
    $filterId = $_GET['filter'];
    $image = Image::make($_SESSION['dir'] . $_SESSION['image_name']);
    $filter = new Filter();
    $image = $filter->apply_filter($filterId, $image);
    $imageid = $_SESSION['imageId'];
    unset($_SESSION['imageId']);
    header('Location:image.php?id='.$imageid);
    exit;
}