<?php

$dns='mysql:host=localhost;dbname=420px_db';
$user='420px_admin';
$password='420px_admin';
$option=array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);
$connection=new PDO($dns, $user, $password, $option);
session_start();

?>