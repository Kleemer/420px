<?php
    require_once 'myAutoLoader.php';
    session_start();

    if (isset($_POST['signup']))
    {
        $login = $_POST['login'];
        $password = $_POST['password'];
        
        try
        {
            $prepare = myPDO::getInstance()->getConnection()->prepare('insert into users(login, password) values(:login,:password)');
            
            if ($prepare->execute(array('login'=>$login, 'password'=>$password)))
            {
                mkdir('images//' . $login);
                header("Location:login.php");
                exit();
            }
        }
        catch (Exception $e)
        {
            $_SESSION['error'] = 'Ce pseudo est déjà pris.';
            header('Refresh:0');
            exit;
        }
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>420px - Inscription</title>
    
    <link rel="stylesheet" href="css/bulma.css">

</head>

<body>

    <?php
        include "header.php";
    ?>

    <div class="hero-body">
        <div class="container has-text-centered">

            <?php
                include "error.php";
            ?>
            <h2 class="title">
                Inscription
            </h2>

            <form method="post" style="width:50%;margin:0 auto;">

                <div class="field">
                    <input type="text" id="login" name="login" class="input" placeholder="Login" required/>
                </div>

                <div class="field">
                    <input type="password" id="password" name="password" class="input" placeholder="Mot de passe" required/>
                </div>

                <div class="field">
                    <button type="submit" name="signup" class="button">S'incrire</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>