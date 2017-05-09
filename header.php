<div class="hero-head">
    <header class="nav">
      <div class="container">
        <div class="nav-left">
            <a class="nav-item" href="home.php">
                <img src="img/420px-logo.png" alt="Logo">
            </a>
        </div>
        <div class="nav-right nav-menu">
            <form class="nav-item" method="post">
                <input class="input" name="values" type="text" placeholder="R G B">
                <button type="submit" name="search" class="button is-primary">Chercher</button>
            </form>

<?php
if (isset($_SESSION['user']))
    echo
          "
          <a class=\"nav-item\" href=\"home.php\">
            Galeries
          </a>
          <a class=\"nav-item\" href=\"gallery.php\">
            Ma galerie
          </a>
          <a class=\"nav-item\" href=\"upload.php\">
            Importer
          </a>
          <a class=\"nav-item\" src=\"img/exit.png\" href=\"logoff.php\">
            <img src=\"img/exit.png\">
          </a>
          ";
else
    echo "
          <a class=\"nav-item\" href=\"home.php\">
            Galeries
          </a>
          <a class=\"nav-item\" href=\"login.php\">
            Se connecter
          </a>
          <a class=\"nav-item\" href=\"signup.php\">
            S'inscrire
          </a>
          ";

if (isset($_POST['search']))
{
    $_SESSION['values'] = $_POST['values'];
    header("Location:search.php");
    exit;
}
?>


        </div>
        </div>
    </header>
  </div>