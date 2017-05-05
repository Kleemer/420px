<?php
require_once 'myAutoloader.php';
session_start();

header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if (!isset($_SESSION['user']))
{
    header('Location:access_refused.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>420px - Galerie</title>
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

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <script src="js/jquery-1.11.3.min.js"></script>
</head>

<body>

<!-- Content -->
<div class="cd-hero">

    <!-- Navigation -->
    <div class="cd-slider-nav">
        <nav class="navbar">
            <div class="tm-navbar-bg">
                <a class="navbar-brand text-uppercase" href="#"><i class="fa fa-picture-o tm-brand-icon"></i>Galerie de <?= $_SESSION['user']->login ?></a>
                <div id="tmNavbar">
                    <a class="nav-link" href="home.php">Galeries</a>
                    <a class="nav-link" href="gallery.php">Espace perso</a>
                    <a class="nav-link" href="upload.php">Importer</a>
                </div>
            </div>
        </nav>
    </div>

    <ul class="cd-hero-slider">

        <!-- Page 1 Gallery One -->
        <li class="selected">
            <div class="cd-full-width">
                <div class="container-fluid js-tm-page-content" data-page-no="1" data-page-type="gallery">
                    <div class="tm-img-gallery-container">
                        <div class="tm-img-gallery gallery-one" id="div_gallery">
                            <?php
                            try {
                                $prepare = myPDO::getInstance()->getConnection()->prepare('select * from images where userId = :userId');
                                if ($prepare->execute(array('userId' => $_SESSION['user']->id))) {
                                    $html = "";
                                    while ($image = $prepare->fetch(PDO::FETCH_OBJ)) {
                                        $name = $image->name;
                                        $html .= "<div class=\"grid-item\">\n";
                                        $html .= "<figure class=\"effect-sadie\">\n";

                                        $html .= '<img src="' . $_SESSION['user']->dir . $image->name .
                                            "\" alt=\"" . $image->name . "\" class=\"img-fluid tm-img\">\n" .
                                            "<figcaption>\n" .
                                            "<h2 class=\"tm-figure-title\">$image->name</h2>" .
                                            '<a href=\'image.php?id=' . $image->id . '\'> </a>' .
                                            "</figcaption>\n</figure>\n</div>";
                                    }
                                    echo $html;
                                }
                            }
                            catch(Exception $e)
                            {
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </li>

    </ul> <!-- .cd-hero-slider -->


    <footer class="tm-footer">

        <p class="tm-copyright-text">Copyright &copy; <span class="tm-copyright-year">current year</span> Yafata

            | Design: <a href="www.google.com/+templatemo" target="_parent">Templatemo</a></p>

    </footer>

</div> <!-- .cd-hero -->

<!-- load JS files -->

<script src="js/tether.min.js"></script> <!-- Tether (http://tether.io/)  -->
<script src="js/bootstrap.min.js"></script>             <!-- Bootstrap js (v4-alpha.getbootstrap.com/) -->
<script src="js/hero-slider-main.js"></script>          <!-- Hero slider (https://codyhouse.co/gem/hero-slider/) -->
<script src="js/jquery.magnific-popup.min.js"></script> <!-- Magnific popup (http://dimsemenov.com/plugins/magnific-popup/) -->

<script>

    function adjustHeightOfPage(pageNo) {

        var pageContentHeight = 0;

        var pageType = $('div[data-page-no="' + pageNo + '"]').data("page-type");

        if( pageType != undefined && pageType == "gallery") {
            pageContentHeight = $(".cd-hero-slider li:nth-of-type(" + pageNo + ") .tm-img-gallery-container").height();
        }
        else {
            pageContentHeight = $(".cd-hero-slider li:nth-of-type(" + pageNo + ") .js-tm-page-content").height() + 20;
        }

        // Get the page height
        var totalPageHeight = $('.cd-slider-nav').height()
            + pageContentHeight
            + $('.tm-footer').outerHeight();

        // Adjust layout based on page height and window height
        if(totalPageHeight > $(window).height())
        {
            $('.cd-hero-slider').addClass('small-screen');
            $('.cd-hero-slider li:nth-of-type(' + pageNo + ')').css("min-height", totalPageHeight + "px");
        }
        else
        {
            $('.cd-hero-slider').removeClass('small-screen');
            $('.cd-hero-slider li:nth-of-type(' + pageNo + ')').css("min-height", "100%");
        }
    }

    /*
     Everything is loaded including images.
     */
    $(window).load(function(){

        adjustHeightOfPage(1); // Adjust page height

        /* Browser resized
         -----------------------------------------*/
        $( window ).resize(function() {
            var currentPageNo = $(".cd-hero-slider li.selected .js-tm-page-content").data("page-no");
        });

        // Write current year in copyright text.
        $(".tm-copyright-year").text(new Date().getFullYear());

    });
</script>



</body>
</html>