<?php
require_once 'pdo.php';
require_once 'navbar.php';
require 'vendor/autoload.php';
use Intervention\Image\ImageManagerStatic as Image;
Image::configure(array('driver' => 'gd'));
require 'filter.php';
echo generateMenu();
?>


<?php

header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if (isset($_GET['filter'])) {
    $id = $_SESSION['imageId'];
    unset($_SESSION['imageId']);
    $filterId = $_GET['filter'];
    $image = Image::make($_SESSION['dir'] . $_SESSION['image_name']);
    $filter = new filter();
    $image = $filter->apply_filter($filterId, $image);
    header('Location:gallery.php?id='.$id);
}

if (isset($_GET['id'])) {
    $_SESSION['imageId'] = $_GET['id'];
    $id = $_SESSION['imageId'];
    $prepare = $connection->prepare('select * from images where id = :id');
    if ($prepare->execute(array('id'=>$id))) {
        $html = "<div style=\"text-align:center\">\n";
        $image = $prepare->fetch(PDO::FETCH_OBJ);
        $_SESSION['image_name'] = $image->name;
        $filepath = $_SESSION['dir'] . $image->name;
        echo "<div style=\"text-align:center\">\n" .
            '<img src="' . $filepath . " \"/>".
            "</div></br>";
        echo generateFilter($id);
        echo "<div style=\"text-align:center\">\n" .
            "<form method=\"post\">
              <button type=\"submit\"  name=\"delete\">Supprimer</button>
              </form>\n
              </div>";


        if (isset($_POST['delete'])) {
            $prepare = $connection->prepare('delete from images where id = :id');
            if ($prepare->execute(array('id'=>$_GET['id']))) {
                unlink($_SESSION['dir'].$_SESSION['image_name']);
                unset($_SESSION['image_name']);
                header('Location:gallery.php');
            }
        }
    }
    else
        echo "There is no image here with this id.";
}
else
{
    echo "<div style=\"text-align:center\">\n Galerie de : ".$_SESSION['user']."</div></br>\n";

    $prepare = $connection->prepare('select * from images where userId = :userId');
    if ($prepare->execute(array('userId'=>$_SESSION['userId'])))
    {
        $html = "<div style=\"text-align:center\">\n";
        while ($image = $prepare->fetch(PDO::FETCH_OBJ)) {
            $name = $image->name;
            $html .= $name. " </br>\n".
                '<a href=\'?id='.$image->id.'\'> '.
                '<img src="'.$_SESSION['dir'].$image->name.
                "\" alt=\"". $image->name."\"/>".
                '</a>'.
                "</br>\n";
        }
        $html .= "</div>";
        echo $html;
        //echo $html;
    }

}

