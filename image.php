<?php
require_once 'myAutoLoader.php';
require_once 'navbar.php';
require_once 'vendor/autoload.php';
use ColorThief\ColorThief;
use Intervention\Image\ImageManagerStatic as ImageManagerStatic;

ImageManagerStatic::configure(array('driver' => 'gd'));
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

session_start();

if (!isset($_SESSION['user']))
{
    header('Location:access_refused.php');
    exit;
}

$actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$query = parse_url($actual_link, PHP_URL_QUERY);
$queries = explode("=", $query);

if ($queries[0] === 'id')
    if (!isset($_SESSION['image']) || $_SESSION['image']->id !== $queries[1])
        $_SESSION['image'] = new Image($queries[1], array());

if (isset($_GET['filter']))
{
    $filterId = $_GET['filter'];
    array_push($_SESSION['image']->filters, $_GET['filter']);

    header('Location:image.php?id='.$_SESSION['image']->id);
    exit;
}

if (isset($_POST['undo']))
{
    array_pop($_SESSION['image']->filters);
    if (!isset($_SESSION['image']->filters))
        $_SESSION['image']->filters = array();
}

if (isset($_POST['reset']))
    $_SESSION['image']->filters = array();

try
{
    if (isset($_POST['save']))
    {
        $img = ImageManagerStatic::make($_SESSION['user']->dir . $_SESSION['image']->name);
        Filter::apply_filters($_SESSION['image']->filters, $img);
        $img->save();

        $dominantColor = ColorThief::getColor($_SESSION['user']->dir . $_SESSION['image']->name);
        $prepare = myPDO::getInstance()->getConnection()->prepare('update images set red = :red, green = :green, blue = :blue where name = :name and userId = :userId');
        $prepare->execute(array('red' => $dominantColor[0], 'green' => $dominantColor[1], 'blue' => $dominantColor[2], 'name' => $_SESSION['image']->name, 'userId' => $_SESSION['user']->id));

        $_SESSION['image']->filters = array();
    }

    if (isset($_POST['delete']))
    {
        $prepare = myPDO::getInstance()->getConnection()->prepare('delete from images where id = :id');
        if ($prepare->execute(array('id'=>$_SESSION['image']->id)))
        {
            unlink($_SESSION['user']->dir.$_SESSION['image']->name);
            unset($_SESSION['image']);
            header('Location:gallery.php');
            exit;
        }
    }

    if (!isset($_SESSION['image']->name))
    {
        $prepare = myPDO::getInstance()->getConnection()->prepare('select * from images where id = :id');
        if ($prepare->execute(array('id'=>$_SESSION['image']->id)))
        {
            $image = $prepare->fetch(PDO::FETCH_OBJ);
            $_SESSION['image']->name = $image->name;
        }
    }

    $img = ImageManagerStatic::make($_SESSION['user']->dir . $_SESSION['image']->name);
    Filter::apply_filters($_SESSION['image']->filters, $img);
    $img->encode('png');
    $type = 'png';
    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($img);

    $html = "<div style=\"text-align:center\">\n" .
        '<img src="' . "$base64 \"/>".
        "</div></br>";

    $html.= generateFilter();

    $html.= "<div style=\"text-align:center\">\n" .
        "<form method=\"post\">
        <button class=\"button is-info\"    type=\"submit\" name=\"save\"><i class=\"fa fa-floppy-o\" aria-hidden=\"true\"></i></button>
        <button class=\"button is-primary\" type=\"submit\" name=\"undo\"><i class=\"fa fa-backward\" aria-hidden=\"true\"></i></button>
        <button class=\"button is-primary\" type=\"submit\" name=\"reset\"><i class=\"fa fa-fast-backward\" aria-hidden=\"true\"></i></button>
        </form>\n
        </div></br>";

    $html.= "<div style=\"text-align:center\">\n" .
        "<form method=\"post\">
        <button class=\"button is-danger\" type=\"submit\" name=\"delete\"><i class=\"fa fa-trash\" aria-hidden=\"true\"></i></button>
        </form>\n
        </div>";
}
catch (Exception $e)
{
    $html = "";
    $_SESSION['error'] = 'Une erreur est survenue, veuillez réessayer ulterieurement.';
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
    <link rel="stylesheet" href="css/font-awesome.css">
</head>
<body>

    <?php
        include "header.php";
        include "error.php";
        echo $html;
    ?>

</body>
</html>