<?php
require_once 'pdo.php';
require_once 'vendor/autoload.php';
use Intervention\Image\ImageManagerStatic as Image;
Image::configure(array('driver' => 'gd'));
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>420px - Import</title>
    <!--
    Fluid Gallery Template
    http://www.templatemo.com/tm-500-fluid-gallery
    -->
    <!-- load stylesheets -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600">
    <!-- Google web font "Open Sans" -->
    <link rel="stylesheet" href="Font-Awesome-4.7/css/font-awesome.min.css">
    <!-- Font Awesome -->
    <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Lora:400,700,400italic,700italic" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">

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
                <a class="page-scroll" href="#">Importer</a>
        </div>
    
    <div style="float: none;margin: 0 auto" class="tm-flex tm-contact-page">
        <div class="tm-2-col-textbox-2">
            <?php
            if (isset($_SESSION['errorUpload']))
            {
                echo "<p class=\"tm-text\" style='color:#bf0000'>" .$_SESSION['errorUpload']."</p>";
                unset($_SESSION['errorUpload']);
            }
            ?>
            <h2 class="tm-contact-info">Import d'image</h2>

            <!-- contact form -->
            <form method="post" enctype="multipart/form-data">

                <div class="form-group">
                    <input type="file" id="fileToUpload" name="fileToUpload" class="form-control" required/>
                </div>

                <div class="form-group">
                    <button type="submit" name="upload" class="tm-submit-btn">Importer</button>
                </div>
            </form>
        </div>

    </div>
</header>
</body>
</html>


<?php
if (isset($_SESSION['user']))
{
    if(isset($_POST["upload"]))
    {
        $path = $_FILES['fileToUpload']['name'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $_FILES['fileToUpload']['tmp_name']);
        if (strpos($mimeType, 'image') === false)
        {
            finfo_close($finfo);
            $_SESSION['errorUpload'] = 'Erreur, ce fichier n\'est pas une image.';
            header('Refresh:0');
            exit;
        }
        else
        {
            finfo_close($finfo);
            $name = explode(".", $path);
        $i = 1;
        while (file_exists($_SESSION['dir'].$path))
            $path = $name[0]."(".$i++.").".$name[1];
            $img = Image::make($_FILES['fileToUpload']['tmp_name'])
                ->resize(420, 420)
                ->save($_SESSION['dir'] . $path);
            try {
                $prepare = $connection->prepare('insert into images(name, userId) values(:name,:userId)');
                $prepare->execute(array('name' => $path, 'userId' => $_SESSION['userId']));
            }
            catch (Exception $e)
            {
                $_SESSION['errorUpload'] = 'Une erreur est survenue, veuillez réessayer ulterieurement.';
                header('Refresh:0');
                exit;
            }
        }
    }
}
else
    header("Location:access_refused.php");
