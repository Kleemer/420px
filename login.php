<?php
require_once 'myAutoloader.php';
session_start();

if (isset($_SESSION['user']))
{
    header('Location:home.php');
    exit;
}

if (isset($_POST['login']) && isset($_POST['password']))
{
    $login = $_POST['login'];
    $password = $_POST['password'];
    try
    {
        $prepare = myPDO::getInstance()->getConnection()->prepare('select * from users where login like :search');
        if ($prepare->execute(array('search'=>$login)) && $prepare->rowCount() > 0)
        {
            $user = $prepare->fetch(PDO::FETCH_OBJ);
            if ($user->password == $password)
            {
                $_SESSION['user'] = new User($user->id, $user->login, 'images//' . $user->login . '//');
                header("Location:home.php");
                exit();
            }
        }
        $_SESSION['errorLogin'] = 'Erreur, mauvais login ou mot de passe';
    }
    catch (Exception $e)
    {
        $_SESSION['errorLogin'] = 'Une erreur est survenue, veuillez réessayer ulterieurement.';
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>420px - Connexion</title>
    
    <link rel="stylesheet" href="css/bulma.css">

</head>

<body>

    <?php
        include "header.php";
    ?>

    <div class="hero-body">
        <div class="container has-text-centered">

            <?php
            if (isset($_SESSION['errorLogin']))
            {
                echo "<div style=\"color: #D9534F\">".$_SESSION['errorLogin']."</div>";
                unset($_SESSION['errorLogin']);
            }
            ?>

            <h2 class="title">
                Connexion
            </h2>

            <form method="post" style="width:50%;margin:0 auto;">

                <div class="field">
                        <input type="text" id="login" name="login" class="input" placeholder="Login" required/>
                </div>

                <div class="field">
                    <input type="password" id="password" name="password" class="input" placeholder="Mot de passe" required/>
                </div>

                <div class="field">
                    <button type="submit" class="button">Se connecter</button>
                </div>
            </form>
            </br>
            <a class="button is-primary" href="signup.php">
                S'inscrire
            </a>
        </div>
    </div>
</body>
</html>