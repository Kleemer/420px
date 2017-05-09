<?php
require_once 'myAutoLoader.php';

require_once 'vendor/autoload.php';
use Intervention\Image\ImageManagerStatic as Image;
Image::configure(array('driver' => 'gd'));
use ColorThief\ColorThief;
session_start();

if (!isset($_SESSION['user']))
{
    header("Location:access_refused.php");
    exit;
}

if(isset($_POST["upload"]))
{
    $fileCount = count($_FILES["fileToUpload"]['name']);

    for ($i = 0; $i < $fileCount; $i++)
    {
        $relativePath = $_FILES['fileToUpload']['name'][$i];
        $absolutePath = $_FILES['fileToUpload']['tmp_name'][$i];
        if (Tools::isAnImage($absolutePath) === false)
            $_SESSION['errorUpload'] = 'Erreur, un ou plusieurs fichiers ne sont pas des images.';
        else
        {
            try
            {
                $path = Tools::saveImage($_SESSION['user']->dir, $absolutePath, $relativePath);
                $img = Image::make($absolutePath)->resize(420, 420)->save($_SESSION['user']->dir . $path);
                $dominantColor = ColorThief::getColor($_SESSION['user']->dir . $path);
                $prepare = myPDO::getInstance()->getConnection()->prepare('insert into images(name, userId, red, green, blue) values(:name,:userId, :red, :green, :blue)');
                $prepare->execute(array('name' => $path, 'userId' => $_SESSION['user']->id, 'red' => $dominantColor[0], 'green' => $dominantColor[1], 'blue' => $dominantColor[2]));
            }
            catch (Exception $e)
            {
                $_SESSION['errorUpload'] = 'Une erreur est survenue, veuillez réessayer ulterieurement.';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>420px - Importer une image</title>
    
    <link rel="stylesheet" href="css/bulma.css">
    <link rel="stylesheet" href="css/custom.css">
</head>

<body>
    <?php
        include "header.php";
    ?>
  
     <div class="hero-body">
        <div class="container has-text-centered">
            <?php
            if (isset($_SESSION['errorUpload']))
            {
                echo "<div style=\"color: #D9534F\">".$_SESSION['errorUpload']."</div>";
                unset($_SESSION['errorUpload']);
            }
            ?>

            <h2 class="title">
                Import d'image
            </h2>

            <form method="post" enctype="multipart/form-data">
               <input type="file" id="fileToUpload" name="fileToUpload[]" class="inputfile" data-multiple-caption="{count} files selected" multiple />
                  <label for="fileToUpload">
                      <img src="img/upload.png" width=30 height=22/>
                     <span>Choose a file&hellip;</span>
                  </label>

               <div class="field">
                  <button type="submit" name="upload" class="button is-primary">Importer</button>
               </div>
            </form>
        </div>
    </div>
    <script src="js/custom-file-input.js"></script>

</body>
</html>