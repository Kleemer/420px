<?php

    function myAutoLoader($classname)
    {
        include 'classes/'.$classname.'.class.php';
    }

    spl_autoload_register('myAutoLoader');

?>