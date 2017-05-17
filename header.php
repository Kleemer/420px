<div class="hero-head">
    <header class="nav">
      <div class="container">
        <div class="nav-left">
            <a class="nav-item" href="home.php">
                <img src="img/420px-logo.png" alt="Logo">
            </a>
        </div>
        <div class="nav-center">
           <form class="nav-item" method="post">
                <input class="input" name="values" type="text" placeholder="R G B">
                <button type="submit" name="search" class="button is-primary">Chercher</button>
            </form>
        </div>
        <div class="nav-right">

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
          <a class=\"nav-item\" href=\"?zip=true\">
            <img src=\"img/zip.png\">
          </a>
          <a class=\"nav-item\" href=\"?xml=true\">
            <img src=\"img/rss.png\">
          </a>
          <a class=\"nav-item\" href=\"logoff.php\">
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

if (isset($_GET['zip']))
  Tools::downloadZip($_SESSION['user']->login);

if (isset($_GET['xml']))
  Tools::downloadRss($_SESSION['user']->login);

?>


        </div>
        </div>
    </header>
  </div>