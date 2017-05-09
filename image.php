<?php
require_once 'myAutoLoader.php';
require_once 'navbar.php';
require_once 'vendor/autoload.php';

use Intervention\Image\ImageManagerStatic as Image;
Image::configure(array('driver' => 'gd'));
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

session_start();

$actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$query = parse_url($actual_link, PHP_URL_QUERY);
$queries = explode("=", $query);

if ($queries[0] === 'id')
    $_SESSION['imageId'] = $queries[1];
try
{
    $prepare = myPDO::getInstance()->getConnection()->prepare('select * from images where id = :id');
    if ($prepare->execute(array('id'=>$_SESSION['imageId'])))
    {
        $image = $prepare->fetch(PDO::FETCH_OBJ);
        $_SESSION['image_name'] = $image->name;
        $filepath = $_SESSION['user']->dir . $image->name;

        $html = "<div style=\"text-align:center\">\n" .
            '<img src="' . $filepath . " \"/>".
            "</div></br>";
        $html.= generateFilter($image->id);
        $html.= "<div style=\"text-align:center\">\n" .
            "<form method=\"post\">
            <button class=\"button is-primary\"type=\"submit\" name=\"delete\">Supprimer</button>
            </form>\n
            </div>";

        if (isset($_POST['delete']))
        {
            $prepare = myPDO::getInstance()->getConnection()->prepare('delete from images where id = :id');
            if ($prepare->execute(array('id'=>$_GET['id'])))
            {
                unlink($_SESSION['user']->dir.$_SESSION['image_name']);
                unset($_SESSION['image_name']);
                header('Location:gallery.php');
                exit;
            }
        }
    }
    else
        $html = "There is no image here with this id.";
}
catch (Exception $e)
{
    $_SESSION['errorUpload'] = 'Une erreur est survenue, veuillez réessayer ulterieurement.';
}

if (isset($_GET['filter']))
{
    $filterId = $_GET['filter'];
    $image = Image::make($_SESSION['user']->dir . $_SESSION['image_name']);
    $image = Filter::apply_filter($filterId, $image);
    $imageid = $_SESSION['imageId'];
    unset($_SESSION['imageId']);
    header('Location:image.php?id='.$imageid);
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>420px - Image</title>
    
    <link rel="stylesheet" href="css/bulma.css">
    <link rel="stylesheet" href="css/custom.css">

</head>
<body>

    <?php
        include "header.php";
        echo $html;
    ?>

</body>
</html>