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
          <a class=\"nav-item\" src=\"img/exit.png\" href=\"?zip=true\">
            <img src=\"img/zip.png\">
          </a>
          <a class=\"nav-item\" src=\"img/exit.png\" href=\"?xml=true\">
            <img src=\"img/rss.png\">
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

if (isset($_GET['zip']))
{
  $zipname = 'zip/'.$_SESSION['user']->login.'.zip';
  $zip = new ZipArchive;
  $zip->open($zipname, ZipArchive::CREATE);
  $dir = 'images/'.$_SESSION['user']->login.'/';
  if ($handle = opendir($dir))
  {
    while (($entry = readdir($handle)) !== false)
      if ($entry != "." && $entry != "..")
        $zip->addFile($dir.$entry, $entry);
    closedir($handle);
  }
  $zip->close();

  header('Content-Type: application/zip');
  header("Content-Disposition: attachment; filename=".$zipname);
  header('Content-Length: ' . filesize($zipname));
  header("Location:" . $zipname);
}

if (isset($_GET['xml']))
{
  $xmlname = 'xml/'.$_SESSION['user']->login.'.xml';
  $xml = new DOMDocument();
  $xml_images = $xml->createElement("Images");
  $dir = 'images/'.$_SESSION['user']->login.'/';
    if ($handle = opendir($dir))
    {
      while (($entry = readdir($handle)) !== false)
        if ($entry != "." && $entry != "..")
        {
          $xml_image = $xml->createElement("Name");
          $xml_image->nodeValue = $entry;
          $xml_images->appendChild( $xml_image );
        }
      closedir($handle);
    }
  $xml->appendChild( $xml_images );
  $xml->save($xmlname);

  header('Content-type: text/xml');
  header("Content-Disposition: attachment; filename=".$xmlname);
}
?>


        </div>
        </div>
    </header>
  </div>