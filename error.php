<?php

if (isset($_SESSION['error']))
{
    echo "<div class=\"container has-text-centered\" style=\"color: #D9534F\">".$_SESSION['error']."</div>";
    unset($_SESSION['error']);
}

?>