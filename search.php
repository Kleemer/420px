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

    try
    {
        $values = explode(" ", $_SESSION['values']);
        $red   = $values[0];
        $green = $values[1];
        $blue  = $values[2];
        $threshold = 5;

        $prepare = myPDO::getInstance()->getConnection()->prepare
        ('select * from images join users on images.userId = users.id
        where red between :red - :threshold and :red + :threshold 
        and green between :green - :threshold and :green + :threshold
        and blue between :blue - :threshold and :blue + :threshold');
        if ($prepare->execute(array('red' => $red,
                                    'green' => $green,
                                    'blue' => $blue, 'threshold' => $threshold)))
        {
            $html = "";
            while ($image = $prepare->fetch(PDO::FETCH_OBJ))
            {
                $name = $image->name;
                $html .= "<div class=\"grid-item\">\n";
                $html .= "<figure class=\"effect-sadie\">\n";
                $html .= '<img src="images//' . $image->login . '//' . $image->name .
                    "\" alt=\"" . $image->name . "\" class=\"img-fluid tm-img\">\n" .
                    "<figcaption>\n" .
                    "<h2 class=\"tm-figure-title\">$image->name</h2>" .
                    "</figcaption>\n</figure>\n</div>";
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

    <title>420px - Recherche</title>

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