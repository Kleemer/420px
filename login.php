<?php
require_once 'myAutoloader.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>420px - Connexion</title>
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
    <div style="float: none;margin: 0 auto" class="tm-flex tm-contact-page">
        <div class="tm-2-col-textbox-2">
            <?php
            if (isset($_SESSION['errorLogin']))
                echo "<p class=\"tm-text\" style='color:#bf0000'>".$_SESSION['errorLogin']."</p>";
            if (isset($_SESSION['signupSuccess']))
            {
                echo "<p class=\"tm-text\" style='color:#1d5622'>" .$_SESSION['signupSuccess']."</p>";
                unset($_SESSION['signupSuccess']);
            }
            ?>
            <h2 class="tm-contact-info">Connexion</h2>

            <!-- contact form -->
            <form method="post">

                <div class="form-group">
                    <input type="text" id="login" name="login" class="form-control" placeholder="Login" required/>
                </div>

                <div class="form-group">
                    <input type="password" id="password" name="password" class="form-control" placeholder="Mot de passe"
                           required/>
                </div>

                <div class="form-group">
                    <button type="submit" class="tm-submit-btn">Connexion</button>
                </div>
            </form>

            <a class="intro-text" href="signup.php">
                S'inscrire
            </a>
        </div>

    </div>
</header>
</body>
</html>

<?php

if (isset($_POST['login']) && isset($_POST['password']))
{
    $login = $_POST['login'];
    $password = $_POST['password'];
    try
    {
    $prepare = myPDO::getInstance()->getConnection()->prepare('select * from users where login like :search');
    if ($prepare->execute(array('search'=>$login))) {
        while ($user = $prepare->fetch(PDO::FETCH_OBJ)) {
            if ($user->password == $password) {
                if (isset($_SESSION['errorLogin']))
                    unset($_SESSION['errorLogin']);
                $_SESSION['user'] = $user->login;
                $_SESSION['userId'] = $user->id;
                $_SESSION['dir'] = 'images//' . $_SESSION['user'] . '//';
                header("Location:home.php");
                exit();
            }
        }
    }
    $_SESSION['errorLogin'] = 'Erreur, mauvais login ou mot de passe';
        header('Refresh:0');
        exit;
    }
    catch (Exception $e)
    {
        $_SESSION['errorLogin'] = 'Une erreur est survenue, veuillez réessayer ulterieurement.';
        header('Refresh:0');
        exit;
    }
}
?>
