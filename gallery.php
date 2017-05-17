<?php
require_once 'myAutoloader.php';

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
?>

<!DOCTYPE html>
<html lang="fr">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>420px - Accueil</title>

    <link rel="stylesheet" href="css/bulma.css">
    <link rel="stylesheet" href="css/templatemo-style.css">
    <link rel="stylesheet" href="css/custom.css">

</head>
<body>

    <?php
        include "header.php";
        try {
            $prepare = myPDO::getInstance()->getConnection()->prepare('select * from images where userId = :userId');
            if ($prepare->execute(array('userId' => $_SESSION['user']->id))) {
                $html = "";
                $dir = $_SESSION['user']->dir;

                while ($image = $prepare->fetch(PDO::FETCH_OBJ)) {
                    $name = $image->name;
                    $html .= "<div class=\"grid-item\">\n";
                    $html .= "<figure class=\"effect-sadie\">\n";

                    $html .= '<img src="' . $dir . $image->name .
                        "\" alt=\"" . $image->name . "\" class=\"img-fluid tm-img\">\n" .
                        "<figcaption>\n".
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
</body>
</html>