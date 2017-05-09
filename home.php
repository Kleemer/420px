<?php
    require_once 'myAutoloader.php';
    require_once "vendor/GifCreator/GifCreator.php";
    session_start();

    try
    {
        $prepare = myPDO::getInstance()->getConnection()->prepare('select * from users');
        if ($prepare->execute()) {
            $html = "";
            while ($user = $prepare->fetch(PDO::FETCH_OBJ)) {
                $login = $user->login;
                $prepare2 = myPDO::getInstance()->getConnection()->prepare('select * from images where userId = :userId');
                $prepare2->execute(array('userId' => $user->id));
                $frames = array();
                $durations = array();
                while($image = $prepare2->fetch(PDO::FETCH_OBJ)) {
                    array_push($frames, "images/" . $login . "/" . $image->name);
                    array_push($durations,70);
                }
                if (count($frames) > 0)
                {
                    $gc = new \GifCreator\GifCreator();
                    $gc->create($frames, $durations, 0);
                    $gifBinary = $gc->getGif();
                    file_put_contents('gifs/'.$login.'.gif', $gifBinary);
                    $html .= "<div class=\"grid-item\">\n";
                    $html .= "<figure class=\"effect-sadie\">\n";
                    $html .= '<img src="' . "gifs/" . $login . ".gif".
                        "\" alt=\"" . $login . "\" class=\"img-fluid tm-img\">\n" .
                        "<figcaption>\n" .
                        "<h2 class=\"tm-figure-title\">$user->login</h2>" .
                        "</figcaption>\n</figure>\n</div>";
                }
            }
        }
    }
    catch(Exception $e)
    {
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

</head>

<body>
    <?php
        include "header.php";
        echo $html;
    ?>
</body>
</html>