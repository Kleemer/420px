<?php
require_once 'pdo.php';
?>

    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>420px - Inscription</title>
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
                if (isset($_SESSION['errorSignup']))
                    echo "<p class=\"tm-text\" style='color:#bf0000'>".$_SESSION['errorSignup']."</p>";
                ?>
                <h2 class="tm-contact-info">Inscription</h2>

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
                        <button type="submit" class="tm-submit-btn">S'incrire</button>
                    </div>
                </form>
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
    $prepare = $connection->prepare('insert into users(login, password) values(:login,:password)');

    if ($prepare->execute(array('login'=>$login, 'password'=>$password))) {
        if (isset($_SESSION['errorSignup']))
            unset($_SESSION['errorSignup']);
        $_SESSION['signupSuccess'] = 'Vous vous êtes inscrit. Veuillez vous connecter maintenant.';
        mkdir('images//' . $login);
        header("Location:login.php");
        exit();
    }
    }
    catch (Exception $e)
    {
        $_SESSION['errorSignup'] = 'Ce pseudo est déjà pris.';
        header('Refresh:0');
        exit;
    }
}
?>